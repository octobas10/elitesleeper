import { NextResponse } from "next/server";
import { PRODUCT } from "@/lib/payments/catalog";
import { notifyDesk } from "@/lib/payments/notify";
import { orderSnapshot, parseOrderForm } from "@/lib/payments/orders";
import { clientIp, isRateLimited } from "@/lib/payments/rate-limit";
import { writeJsonRecord } from "@/lib/payments/storage";

export const runtime = "nodejs";

function originAllowed(request: Request): boolean {
  const origin = request.headers.get("origin");
  if (!origin) return true;
  try {
    const host = request.headers.get("host")?.replace(/:\d+$/, "").toLowerCase() ?? "";
    const originHost = new URL(origin).hostname.toLowerCase();
    return !host || originHost === host;
  } catch {
    return false;
  }
}

function redirectOrderError(request: Request, code: string) {
  return NextResponse.redirect(
    new URL(`/order/?error=${encodeURIComponent(code)}`, request.url),
    303,
  );
}

export async function POST(request: Request) {
  if (!originAllowed(request)) {
    return redirectOrderError(request, "save");
  }

  const ip = clientIp(request.headers);
  if (await isRateLimited(ip, "checkout")) {
    return redirectOrderError(request, "rate");
  }

  let form: FormData;
  try {
    form = await request.formData();
  } catch {
    return redirectOrderError(request, "missing");
  }

  const parsed = parseOrderForm(form);
  if (!parsed.ok) {
    if (parsed.error === "bot") {
      return NextResponse.redirect(new URL("/thank-you/?sent=order", request.url), 303);
    }
    return redirectOrderError(request, parsed.error);
  }

  const snapshot = {
    ...orderSnapshot(parsed.order),
    ip,
  };

  const { id: orderId } = await writeJsonRecord("order", snapshot);

  let sessionUrl: string | null = null;
  try {
    const { createNmiCheckout } = await import("@/lib/payments/nmi");
    sessionUrl = await createNmiCheckout(
      orderId,
      PRODUCT.unitAmountCents * parsed.order.quantity,
      parsed.order,
      ip,
    );

    await writeJsonRecord("order", {
      ...snapshot,
      id: orderId,
      nmi_form_url: sessionUrl,
      nmi_checkout: "three_step",
      payment_status: "checkout_created",
    });
  } catch (error) {
    console.error("[checkout] NMI session failed", error);
    await writeJsonRecord("order", {
      ...snapshot,
      id: orderId,
      payment_status: "checkout_failed",
      error: error instanceof Error ? error.message : "nmi_error",
    });
    return redirectOrderError(request, "payment");
  }

  void notifyDesk(`Elite Sleeper checkout started ${parsed.order.email}`, {
    ...snapshot,
    id: orderId,
    stage: "checkout_created",
  });

  if (!sessionUrl) {
    return redirectOrderError(request, "payment");
  }

  return NextResponse.redirect(new URL(`/order/pay/?order=${encodeURIComponent(orderId)}`, request.url), 303);
}

export async function GET(request: Request) {
  return NextResponse.redirect(new URL("/order/", request.url), 303);
}
