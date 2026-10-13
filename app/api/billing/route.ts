import { NextResponse } from "next/server";
import { cancelNmiSubscription, refundNmiTransaction } from "@/lib/payments/nmi";
import { notifyDesk } from "@/lib/payments/notify";
import { clientIp, isRateLimited } from "@/lib/payments/rate-limit";
import { listOrderRecords, writeJsonRecord } from "@/lib/payments/storage";

export const runtime = "nodejs";

function clean(value: FormDataEntryValue | null, max: number): string {
  let text = typeof value === "string" ? value : "";
  text = text.replace(/\0/g, "").replace(/\s+/g, " ").trim().replace(/<[^>]*>/g, "");
  if (text.length > max) text = text.slice(0, max);
  return text;
}

function fail(request: Request, path: string, code: string) {
  return NextResponse.redirect(
    new URL(`${path}?error=${encodeURIComponent(code)}`, request.url),
    303,
  );
}

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

function emailOf(record: Record<string, unknown>): string {
  return String(record.email ?? "").trim().toLowerCase();
}

export async function POST(request: Request) {
  if (!originAllowed(request)) {
    return fail(request, "/refund-policy/", "save");
  }

  const ip = clientIp(request.headers);
  if (await isRateLimited(ip, "billing")) {
    return fail(request, "/refund-policy/", "rate");
  }

  let form: FormData;
  try {
    form = await request.formData();
  } catch {
    return fail(request, "/refund-policy/", "missing");
  }

  if (clean(form.get("company_url"), 200) !== "") {
    return NextResponse.redirect(new URL("/thank-you/", request.url), 303);
  }

  const intent = clean(form.get("intent"), 20);
  const path = intent === "cancel" ? "/cancel-plan/" : "/refund-policy/";
  const email = clean(form.get("email"), 120).toLowerCase();
  const reference = clean(form.get("reference"), 80);

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    return fail(request, path, "email");
  }
  if (!reference) {
    return fail(request, path, "missing");
  }

  const orders = (await listOrderRecords())
    .filter((record) => emailOf(record) === email)
    .sort((a, b) => String(b.submitted_at ?? "").localeCompare(String(a.submitted_at ?? "")));
  let match =
    orders.find((record) => String(record.nmi_transaction_id ?? "") === reference) ||
    orders.find((record) => String(record.id ?? "") === reference) ||
    orders.find((record) => String(record.nmi_subscription_id ?? "") === reference) ||
    null;

  if (!match && /^\d{8,}$/.test(reference) && orders[0]) {
    match = {
      ...orders[0],
      nmi_transaction_id: String(orders[0].nmi_transaction_id ?? reference),
      nmi_subscription_id: String(orders[0].nmi_subscription_id ?? ""),
    };
  }

  if (!match) {
    return fail(request, path, "none_found");
  }

  if (intent === "cancel") {
    const subscriptionId = String(match.nmi_subscription_id ?? "");
    if (!subscriptionId) {
      return fail(request, path, "not_monthly");
    }
    if (match.payment_status === "cancelled") {
      return fail(request, path, "already");
    }

    const result = await cancelNmiSubscription(subscriptionId);
    if (!result.ok) {
      console.error("[billing] cancel failed", result);
      return fail(request, path, "nmi_error");
    }

    await writeJsonRecord("order", {
      ...match,
      payment_status: "cancelled",
      cancelled_at: new Date().toISOString(),
    });
    void notifyDesk(`Elite Sleeper monthly cancelled ${email}`, {
      ...match,
      stage: "subscription_cancelled",
    });
    return NextResponse.redirect(new URL("/thank-you/?sent=cancelled", request.url), 303);
  }

  if (intent === "refund") {
    const transactionId = String(match.nmi_transaction_id ?? "");
    if (!transactionId) {
      return fail(request, path, "none_found");
    }
    if (match.payment_status === "refunded" || match.payment_status === "voided") {
      return fail(request, path, "already");
    }

    const result = await refundNmiTransaction(transactionId);
    if (!result.ok) {
      console.error("[billing] refund failed", result);
      return fail(request, path, "nmi_error");
    }

    await writeJsonRecord("order", {
      ...match,
      payment_status: result.method === "void" ? "voided" : "refunded",
      refunded_at: new Date().toISOString(),
      nmi_refund_method: result.method,
    });
    void notifyDesk(`Elite Sleeper ${result.method} ${email}`, {
      ...match,
      stage: result.method,
    });
    return NextResponse.redirect(new URL("/thank-you/?sent=refunded", request.url), 303);
  }

  return fail(request, path, "missing");
}
