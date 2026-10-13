import { site } from "@/lib/site";

/** Server-side catalog. Clients never set amounts. */
export const PRODUCT = {
  id: "elite-sleeper-bottle",
  name: "Elite Sleeper",
  description: `${site.capsules} capsules · melatonin 2 mg full serving`,
  currency: "usd" as const,
  /** Unit price in cents */
  unitAmountCents: Math.round(Number(site.price) * 100),
} as const;

export const ALLOWED_QUANTITIES = [1, 2, 3] as const;
export type Quantity = (typeof ALLOWED_QUANTITIES)[number];

export const ALLOWED_PLANS = ["once", "monthly"] as const;
export type Plan = (typeof ALLOWED_PLANS)[number];

export function isQuantity(value: unknown): value is Quantity {
  return ALLOWED_QUANTITIES.includes(Number(value) as Quantity);
}

export function isPlan(value: unknown): value is Plan {
  return typeof value === "string" && (ALLOWED_PLANS as readonly string[]).includes(value);
}

export function lineTotalCents(quantity: Quantity): number {
  return PRODUCT.unitAmountCents * quantity;
}
