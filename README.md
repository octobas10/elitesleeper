# Elite Sleeper

Next.js App Router site with Stripe Checkout. Runs as a Node process behind nginx (PM2).

## Local

```bash
npm install
cp .env.example .env   # add Stripe test keys
npm run dev
```

Open http://localhost:3000

## Stripe keys

Put these in `.env` (never commit them):

- `STRIPE_SECRET_KEY` — secret key (`sk_test_…` / `sk_live_…`)
- `STRIPE_WEBHOOK_SECRET` — from Stripe CLI (`stripe listen`) or Dashboard webhook
- `NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY` — optional; Checkout does not need it on the client yet
- `SITE_URL` — public origin used for Checkout success/cancel URLs

Optional desk alerts: `LEAD_NOTIFY_EMAIL`, `LEAD_NOTIFY_FROM`, `RESEND_API_KEY`, `LEAD_WEBHOOK_URL`.

Webhook URL (production): `https://elitesleeper.com/api/webhooks/stripe/`  
Events: `checkout.session.completed`, `checkout.session.async_payment_succeeded`

Local webhook forwarding:

```bash
stripe listen --forward-to localhost:3000/api/webhooks/stripe/
```

## Payments

Order form → `POST /api/checkout/` → Stripe Checkout → thank-you page.  
Amounts are calculated server-side from `lib/payments/catalog.ts`. Card data never hits this server.

Contact and opt-out forms → `POST /api/leads/`.

## Deploy (VPS + PM2)

One-time on the server:

1. Point nginx at `127.0.0.1:3000` (see `deploy/nginx-next.conf.example`)
2. Place `.env` in the app directory with live Stripe keys
3. `npm ci && npm run build`
4. `pm2 start deploy/ecosystem.config.cjs && pm2 save`

GitHub Actions on `main` rsyncs the app, runs `npm ci` / `npm run build`, and `pm2 reload elitesleeper`.

## Desk numbers

Phone and WhatsApp buttons stay hidden until you set `phoneE164`, `phoneLabel`, and `whatsappE164` in `lib/site.ts`.
