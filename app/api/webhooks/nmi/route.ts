import { NextResponse } from "next/server";
import { completeNmiCheckout } from "@/lib/payments/nmi";
import { notifyDesk } from "@/lib/payments/notify";
import { readJsonRecord, writeJsonRecord } from "@/lib/payments/storage";
import { siteUrl } from "@/lib/payments/stripe";

export const runtime = "nodejs";

async function tokenFromRequest(request: Request): Promise<string | null> {
  const url = new URL(request.url);
  const fromQuery = url.searchParams.get("token-id") || url.searchParams.get("token_id");
  if (fromQuery) return fromQuery;

  if (request.method === "POST") {
    try {
      const form = await request.formData();
      const fromForm = form.get("token-id") ?? form.get("token_id");
      if (typeof fromForm === "string" && fromForm) return fromForm;
    } catch {
      return null;
    }
  }
  return null;
}

async function finishCheckout(request: Request) {
  const tokenId = await tokenFromRequest(request);

  if (!tokenId) {
    return NextResponse.redirect(new URL("/order/?error=missing_token", request.url), 303);
  }

  try {
    const result = await completeNmiCheckout(tokenId);

    if (result.success) {
      if (result.orderId) {
        const existing = await readJsonRecord(result.orderId);
        if (existing) {
          await writeJsonRecord("order", {
            ...existing,
            payment_status: "paid",
            nmi_transaction_id: result.transactionId,
            nmi_subscription_id: result.subscriptionId,
            paid_at: new Date().toISOString(),
          });
        }
      }

      await notifyDesk(`Elite Sleeper order paid via NMI`, {
        orderId: result.orderId,
        transactionId: result.transactionId,
        subscriptionId: result.subscriptionId,
        stage: "checkout_completed",
      });

      const publicOrigin = siteUrl();
      return NextResponse.redirect(
        `${publicOrigin}/thank-you/?sent=paid&session_id=${encodeURIComponent(String(result.transactionId ?? ""))}`,
        303,
      );
    }

    console.error("[nmi-webhook] payment failed", {
      success: result.success,
      transactionId: result.transactionId,
      resultText: result.resultText,
    });
    const why = encodeURIComponent((result.resultText || "payment_failed").slice(0, 160));
    const text = result.resultText || "";
    const declined = /declin|issuer|invalid|do not honor|insufficient|lost card|stolen|pick up|expired/i.test(
      text,
    );
    const code = declined ? "declined" : "payment_failed";
    return NextResponse.redirect(new URL(`/order/?error=${code}&why=${why}`, request.url), 303);
  } catch (error) {
    console.error("[nmi-webhook] error completing checkout", error);
    return NextResponse.redirect(new URL(`/order/?error=nmi_error`, request.url), 303);
  }
}

export async function GET(request: Request) {
  return finishCheckout(request);
}

export async function POST(request: Request) {
  return finishCheckout(request);
}
