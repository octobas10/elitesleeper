import {
  type Plan,
  type Quantity,
  isPlan,
  isQuantity,
  lineTotalCents,
  PRODUCT,
} from "./catalog";

export type OrderInput = {
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  address: string;
  city: string;
  state: string;
  zip: string;
  quantity: Quantity;
  plan: Plan;
  contactPref: "email" | "phone" | "whatsapp";
  smsOk: boolean;
  notes: string;
  /** Honeypot — must be empty */
  companyUrl: string;
};

export type OrderValidationError =
  | "missing"
  | "email"
  | "phone"
  | "zip"
  | "bot";

function clean(value: FormDataEntryValue | null, max: number, keepBreaks = false): string {
  let text = typeof value === "string" ? value : "";
  text = text.replace(/\0/g, "");
  if (keepBreaks) {
    text = text.replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  } else {
    text = text.replace(/\s+/g, " ");
  }
  text = text.trim().replace(/<[^>]*>/g, "");
  if (text.length > max) text = text.slice(0, max);
  return text;
}

export function parseOrderForm(form: FormData):
  | { ok: true; order: OrderInput }
  | { ok: false; error: OrderValidationError } {
  const companyUrl = clean(form.get("company_url"), 200);
  if (companyUrl !== "") {
    return { ok: false, error: "bot" };
  }

  const firstName = clean(form.get("first_name"), 60);
  const lastName = clean(form.get("last_name"), 60);
  const email = clean(form.get("email"), 120).toLowerCase();
  const phone = clean(form.get("phone"), 24);
  const address = clean(form.get("address"), 120);
  const city = clean(form.get("city"), 60);
  const state = clean(form.get("state"), 2).toUpperCase();
  const zip = clean(form.get("zip"), 10);
  const quantityRaw = clean(form.get("quantity"), 2);
  const planRaw = clean(form.get("plan"), 12);
  const prefRaw = clean(form.get("contact_pref"), 12);
  const notes = clean(form.get("notes"), 1000, true);
  const smsOk = clean(form.get("sms_ok"), 1) === "1";

  if (!firstName || !lastName || !address || !city || !state) {
    return { ok: false, error: "missing" };
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    return { ok: false, error: "email" };
  }
  const digits = phone.replace(/\D+/g, "");
  if (digits.length < 10) {
    return { ok: false, error: "phone" };
  }
  if (!/^\d{5}(-\d{4})?$/.test(zip)) {
    return { ok: false, error: "zip" };
  }
  if (!isQuantity(quantityRaw) || !isPlan(planRaw)) {
    return { ok: false, error: "missing" };
  }
  if (!/^[A-Z]{2}$/.test(state)) {
    return { ok: false, error: "missing" };
  }

  const contactPref =
    prefRaw === "phone" || prefRaw === "whatsapp" || prefRaw === "email" ? prefRaw : "email";

  return {
    ok: true,
    order: {
      firstName,
      lastName,
      email,
      phone,
      address,
      city,
      state,
      zip,
      quantity: Number(quantityRaw) as Quantity,
      plan: planRaw,
      contactPref,
      smsOk,
      notes,
      companyUrl: "",
    },
  };
}

export function orderSnapshot(order: OrderInput) {
  const totalCents = lineTotalCents(order.quantity);
  return {
    form_type: "order" as const,
    product_id: PRODUCT.id,
    product_name: PRODUCT.name,
    currency: PRODUCT.currency,
    unit_amount_cents: PRODUCT.unitAmountCents,
    quantity: order.quantity,
    plan: order.plan,
    amount_cents: totalCents,
    product_total: (totalCents / 100).toFixed(2),
    first_name: order.firstName,
    last_name: order.lastName,
    email: order.email,
    phone: order.phone,
    address: order.address,
    city: order.city,
    state: order.state,
    zip: order.zip,
    contact_pref: order.contactPref,
    sms_ok: order.smsOk ? "yes" : "no",
    notes: order.notes,
    payment_status: "pending_checkout",
    submitted_at: new Date().toISOString(),
  };
}
