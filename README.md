# Elite Sleeper

Next.js site with Stripe Checkout. Runs as a Node process behind nginx (PM2).

## Local

```bash
npm install
cp .env.example .env
npm run dev
```

Open http://localhost:3000. Add Stripe test keys to `.env` before checkout will work.

## Env

Do not commit `.env`.

- `STRIPE_SECRET_KEY`: secret key (`sk_test_` or `sk_live_`)
- `STRIPE_WEBHOOK_SECRET`: from `stripe listen` or the Stripe Dashboard
- `NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY`: optional
- `SITE_URL`: public origin for Checkout success and cancel URLs

Optional desk alerts: `LEAD_NOTIFY_EMAIL`, `LEAD_NOTIFY_FROM`, `RESEND_API_KEY`, `LEAD_WEBHOOK_URL`.

Production webhook: `https://elitesleeper.com/api/webhooks/stripe/`

Events: `checkout.session.completed`, `checkout.session.async_payment_succeeded`

```bash
stripe listen --forward-to localhost:3000/api/webhooks/stripe/
```

## Payments

The order form posts to `/api/checkout/`, which opens Stripe Checkout. Amounts come from `lib/payments/catalog.ts`. Card data stays with Stripe.

Contact and opt-out forms post to `/api/leads/`.

## Deploy

On the server, point nginx at `127.0.0.1:3000` (`deploy/nginx-next.conf.example`), put live keys in `.env`, then:

```bash
npm ci && npm run build
pm2 start deploy/ecosystem.config.cjs && pm2 save
```

Pushes to `main` rsync the app, run `npm ci` and `npm run build`, then `pm2 reload elitesleeper`.

Phone and WhatsApp buttons stay hidden until `phoneE164`, `phoneLabel`, and `whatsappE164` are set in `lib/site.ts`.
