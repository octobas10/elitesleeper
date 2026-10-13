import "server-only";

import Stripe from "stripe";

let stripe: Stripe | null = null;

export function getStripe(): Stripe {
  const key = process.env.STRIPE_SECRET_KEY?.trim();
  if (!key) {
    throw new Error("STRIPE_SECRET_KEY is not configured");
  }
  if (!stripe) {
    stripe = new Stripe(key, {
      apiVersion: "2026-09-30.endive",
      typescript: true,
      appInfo: {
        name: "Elite Sleeper",
        url: process.env.SITE_URL || "https://elitesleeper.com",
      },
    });
  }
  return stripe;
}

export function siteUrl(): string {
  const raw = (process.env.SITE_URL || "https://elitesleeper.com").replace(/\/$/, "");
  return raw;
}
