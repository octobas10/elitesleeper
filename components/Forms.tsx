"use client";

import { useEffect, useState } from "react";
import { Honeypot } from "./Blocks";
import { QueryNote } from "./QueryNote";
import { site, states } from "@/lib/site";

const bottlePrice = Number(site.price);

export function QuantityField() {
  const [value, setValue] = useState("1");

  useEffect(() => {
    const qty = new URLSearchParams(window.location.search).get("qty");
    if (qty === "1" || qty === "2" || qty === "3") setValue(qty);
  }, []);

  return (
    <label>
      Bottles
      <select name="quantity" required value={value} onChange={(event) => setValue(event.target.value)}>
        {[1, 2, 3].map((count) => (
          <option key={count} value={String(count)}>
            {count} {count === 1 ? "bottle" : "bottles"} · ${(bottlePrice * count).toFixed(2)}
          </option>
        ))}
      </select>
    </label>
  );
}

const errors: Record<string, string> = {
  missing: "Check the required fields and send it again.",
  email: "That email address does not look right.",
  phone: "Add a phone number with at least 10 digits.",
  zip: "Use a 5-digit ZIP code.",
  rate: "Too many notes from this network. Wait a few minutes, or email the desk.",
  save: "We could not store that note. Email support@elitesleeper.com and we will take it from there.",
  payment: "We could not start secure checkout. Try again, or email support@elitesleeper.com.",
  payment_failed: "Payment did not go through. Use a new Pay attempt from this form.",
  declined: "The processor declined that payment. Try another number, or email support@elitesleeper.com.",
  nmi_error: "The processor could not finish that. Try again, or email support@elitesleeper.com.",
  cancelled: "Checkout was cancelled. Your card was not charged. You can try again below.",
  none_found: "We could not match that email and payment ID. Check the thank-you page ID, or email the desk.",
  not_monthly: "That order is not a monthly plan, so there is nothing to cancel.",
  already: "That request was already finished.",
};

export function OrderForm() {
  return (
    <form className="form" method="post" action="/api/checkout/" acceptCharset="UTF-8">
      <input type="hidden" name="form_type" value="order" />
      <Honeypot />
      <QueryNote messages={errors} />
      <fieldset className="plan-set">
        <legend>How do you want the bottle?</legend>
        <label className="plan">
          <input type="radio" name="plan" value="once" defaultChecked />
          <span>
            <strong>One-time order</strong>
            <small>{site.priceLabel} per bottle. Pay securely.</small>
          </span>
        </label>
        <label className="plan">
          <input type="radio" name="plan" value="monthly" />
          <span>
            <strong>A bottle each month</strong>
            <small>
              Same price billed today and each month until you stop it on the{" "}
              <a href="/cancel-plan/">cancel monthly</a> page.
            </small>
          </span>
        </label>
      </fieldset>
      <div className="form-grid">
        <QuantityField />
        <label>
          Reply by
          <select name="contact_pref" defaultValue="email">
            <option value="email">Email</option>
            <option value="phone">Phone call</option>
            <option value="whatsapp">WhatsApp</option>
          </select>
        </label>
        <label>
          First name
          <input name="first_name" required autoComplete="given-name" maxLength={60} />
        </label>
        <label>
          Last name
          <input name="last_name" required autoComplete="family-name" maxLength={60} />
        </label>
        <label>
          Email
          <input name="email" type="email" required autoComplete="email" maxLength={120} />
        </label>
        <label>
          Phone
          <input name="phone" type="tel" required autoComplete="tel" maxLength={24} />
        </label>
        <label className="span-2">
          Street address
          <input name="address" required autoComplete="shipping street-address" maxLength={120} />
        </label>
        <label>
          City
          <input name="city" required autoComplete="shipping address-level2" maxLength={60} />
        </label>
        <label>
          State
          <select name="state" required defaultValue="NY" autoComplete="shipping address-level1">
            {states.map(([code, name]) => (
              <option key={code} value={code}>
                {name}
              </option>
            ))}
          </select>
        </label>
        <label>
          ZIP
          <input name="zip" required inputMode="numeric" autoComplete="shipping postal-code" maxLength={10} />
        </label>
        <label className="span-2">
          Note for the desk
          <textarea name="notes" rows={4} maxLength={1000} placeholder="Gate code, best time to call, or a question." />
        </label>
      </div>
      <label className="check">
        <input type="checkbox" name="sms_ok" value="1" />
        <span>Text me about this order. Message and data rates may apply. Reply STOP to opt out.</span>
      </label>
      <button className="btn btn-wide" type="submit">
        Pay
      </button>
      <p className="fine">
        Next you finish payment. Details go to the processor. Visa, Mastercard, American Express, and Discover. By
        continuing you agree to the <a href="/terms/">terms</a> and <a href="/privacy/">privacy policy</a>.
      </p>
    </form>
  );
}

export function ContactForm({ returnTo }: { returnTo?: string }) {
  return (
    <form className="form" method="post" action="/api/leads/" acceptCharset="UTF-8">
      <input type="hidden" name="form_type" value="contact" />
      {returnTo ? <input type="hidden" name="return_to" value={returnTo} /> : null}
      <Honeypot />
      <QueryNote messages={errors} />
      <div className="form-grid">
        <label>
          First name
          <input name="first_name" required autoComplete="given-name" maxLength={60} />
        </label>
        <label>
          Last name
          <input name="last_name" required autoComplete="family-name" maxLength={60} />
        </label>
        <label>
          Email
          <input name="email" type="email" required autoComplete="email" maxLength={120} />
        </label>
        <label>
          Phone
          <input name="phone" type="tel" autoComplete="tel" maxLength={24} />
        </label>
        <label className="span-2">
          Subject
          <input name="subject" required maxLength={120} />
        </label>
        <label className="span-2">
          Message
          <textarea name="message" required rows={6} maxLength={1000} />
        </label>
      </div>
      <button className="btn btn-wide" type="submit">
        Send my question
      </button>
    </form>
  );
}

export function SidebarContactForm({ returnTo }: { returnTo: string }) {
  return (
    <form className="form" method="post" action="/api/leads/" acceptCharset="UTF-8">
      <input type="hidden" name="form_type" value="contact" />
      <input type="hidden" name="return_to" value={returnTo} />
      <Honeypot />
      <QueryNote messages={errors} />
      <div className="form-grid">
        <label>
          First name
          <input name="first_name" required autoComplete="given-name" maxLength={60} />
        </label>
        <label>
          Last name
          <input name="last_name" required autoComplete="family-name" maxLength={60} />
        </label>
        <label>
          Email
          <input name="email" type="email" required autoComplete="email" maxLength={120} />
        </label>
        <label>
          Subject
          <input name="subject" required maxLength={120} defaultValue="Question about Elite Sleeper" />
        </label>
        <label>
          Message
          <textarea name="message" required rows={4} maxLength={1000} />
        </label>
      </div>
      <button className="btn" type="submit">
        Send the note
      </button>
    </form>
  );
}

function billingLink(path: string, reference: string) {
  if (!reference) return path;
  return `${path}?session_id=${encodeURIComponent(reference)}`;
}

function PaymentReferenceField({ defaultValue = "" }: { defaultValue?: string }) {
  const [value, setValue] = useState(defaultValue);

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const fromUrl = params.get("session_id") || params.get("reference") || "";
    if (fromUrl) setValue(fromUrl);
  }, []);

  return (
    <label>
      Payment ID
      <input
        name="reference"
        required
        maxLength={80}
        placeholder="From the thank-you page"
        value={value}
        onChange={(event) => setValue(event.target.value)}
        autoComplete="off"
      />
      <small className="field-hint">The number on your confirmation page after you paid.</small>
    </label>
  );
}

export function CancelPlanForm({
  reference = "",
  email = "",
}: {
  reference?: string;
  email?: string;
}) {
  return (
    <form className="form" method="post" action="/api/billing/" acceptCharset="UTF-8">
      <input type="hidden" name="intent" value="cancel" />
      <Honeypot />
      <QueryNote messages={errors} />
      <label>
        Order email
        <input
          name="email"
          type="email"
          required
          autoComplete="email"
          maxLength={120}
          defaultValue={email}
        />
      </label>
      <PaymentReferenceField defaultValue={reference} />
      <button className="btn btn-wide" type="submit">
        Stop monthly billing
      </button>
      <p className="fine">
        This stops future monthly charges. A bottle already paid still ships. To get the last charge back, use the{" "}
        <a href={billingLink("/refund-policy/", reference)}>refund form</a>.
      </p>
    </form>
  );
}

export function RefundForm({
  reference = "",
  email = "",
}: {
  reference?: string;
  email?: string;
}) {
  return (
    <form className="form" method="post" action="/api/billing/" acceptCharset="UTF-8">
      <input type="hidden" name="intent" value="refund" />
      <Honeypot />
      <QueryNote messages={errors} />
      <label>
        Order email
        <input
          name="email"
          type="email"
          required
          autoComplete="email"
          maxLength={120}
          defaultValue={email}
        />
      </label>
      <PaymentReferenceField defaultValue={reference} />
      <button className="btn btn-wide" type="submit">
        Refund this payment
      </button>
      <p className="fine">
        This returns the last charge. It does not stop a monthly plan. To stop future bottles, use{" "}
        <a href={billingLink("/cancel-plan/", reference)}>cancel monthly</a>.
      </p>
    </form>
  );
}

export function OptOutForm() {
  return (
    <form className="form" method="post" action="/api/leads/" acceptCharset="UTF-8">
      <input type="hidden" name="form_type" value="optout" />
      <Honeypot />
      <QueryNote messages={errors} />
      <label>
        Email to remove
        <input name="email" type="email" required autoComplete="email" maxLength={120} />
      </label>
      <button className="btn" type="submit">
        Remove this email
      </button>
      <p className="fine">
        We take the address off the Elite Sleeper list. A bottle that is already paid and in transit still ships.
        Write {site.email} if you need that order stopped.
      </p>
    </form>
  );
}
