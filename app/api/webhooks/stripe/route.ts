import { NextResponse } from "next/server";
import type Stripe from "stripe";
import { notifyDesk } from "@/lib/payments/notify";
import { getStripe } from "@/lib/payments/stripe";
import { claimStripeEvent, writeJsonRecord } from "@/lib/payments/storage";

export const runtime = "nodejs";

async function handleCheckoutCompleted(session: Stripe.Checkout.Session) {
  const orderId = session.metadata?.order_id || session.client_reference_id || "";
  const record = {
    form_type: "order_paid",
    id: orderId ? `paid-${orderId}` : `paid-${session.id}`,
    order_id: orderId,
    stripe_checkout_session_id: session.id,
    stripe_customer_id: typeof session.customer === "string" ? session.customer : "",
    stripe_payment_intent:
      typeof session.payment_intent === "string" ? session.payment_intent : "",
    stripe_subscription:
      typeof session.subscription === "string" ? session.subscription : "",
    payment_status: session.payment_status,
    mode: session.mode,
    amount_total: session.amount_total,
    currency: session.currency,
    customer_email: session.customer_details?.email || session.customer_email || "",
    customer_name: session.customer_details?.name || "",
    shipping: session.collected_information?.shipping_details ?? null,
    metadata: session.metadata || {},
    paid_at: new Date().toISOString(),
  };

  await writeJsonRecord("paid", record);
  await notifyDesk(`Elite Sleeper payment received ${record.customer_email}`, record);
}

export async function POST(request: Request) {
  const secret = process.env.STRIPE_WEBHOOK_SECRET?.trim();
  if (!secret) {
    return NextResponse.json({ error: "webhook_not_configured" }, { status: 500 });
  }

  const signature = request.headers.get("stripe-signature");
  if (!signature) {
    return NextResponse.json({ error: "missing_signature" }, { status: 400 });
  }

  const rawBody = await request.text();
  let event: Stripe.Event;
  try {
    event = getStripe().webhooks.constructEvent(rawBody, signature, secret);
  } catch (error) {
    console.error("[stripe-webhook] signature failed", error);
    return NextResponse.json({ error: "invalid_signature" }, { status: 400 });
  }

  const claimed = await claimStripeEvent(event.id);
  if (!claimed) {
    return NextResponse.json({ received: true, duplicate: true });
  }

  try {
    switch (event.type) {
      case "checkout.session.completed":
        await handleCheckoutCompleted(event.data.object as Stripe.Checkout.Session);
        break;
      case "checkout.session.async_payment_succeeded":
        await handleCheckoutCompleted(event.data.object as Stripe.Checkout.Session);
        break;
      default:
        break;
    }
  } catch (error) {
    console.error("[stripe-webhook] handler failed", error);
    return NextResponse.json({ error: "handler_failed" }, { status: 500 });
  }

  return NextResponse.json({ received: true });
}
