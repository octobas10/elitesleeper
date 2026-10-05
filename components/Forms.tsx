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
};

export function OrderForm() {
  return (
    <form className="form" method="post" action="/lead.php" acceptCharset="UTF-8">
      <input type="hidden" name="form_type" value="order" />
      <Honeypot />
      <QueryNote messages={errors} />
      <fieldset className="plan-set">
        <legend>How do you want the bottle?</legend>
        <label className="plan">
          <input type="radio" name="plan" value="once" defaultChecked />
          <span>
            <strong>One-time order</strong>
            <small>{site.priceLabel} per bottle. We confirm before any charge.</small>
          </span>
        </label>
        <label className="plan">
          <input type="radio" name="plan" value="monthly" />
          <span>
            <strong>A bottle each month</strong>
            <small>Same price. We email before each refill. Stop whenever you want.</small>
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
        Request my bottle
      </button>
      <p className="fine">
        Sending the form does not charge your card. We email you to confirm bottles, shipping, and payment. By
        sending it you agree to the <a href="/terms/">terms</a> and <a href="/privacy/">privacy policy</a>.
      </p>
    </form>
  );
}

export function ContactForm({ returnTo }: { returnTo?: string }) {
  return (
    <form className="form" method="post" action="/lead.php" acceptCharset="UTF-8">
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
    <form className="form" method="post" action="/lead.php" acceptCharset="UTF-8">
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

export function OptOutForm() {
  return (
    <form className="form" method="post" action="/lead.php" acceptCharset="UTF-8">
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
