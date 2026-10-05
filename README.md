# Elite Sleeper

Next.js site, exported to static HTML, with a PHP webhook for the order, contact, and opt-out forms.

## Local

```bash
npm install
npm run build
npm run preview
```

Open http://127.0.0.1:10001

`npm run dev` previews the pages. Forms post to `/lead.php`, so use the preview server above when you want to send a test order.

## Leads

`php/lead.php` writes JSON to `data/leads/` outside the web root when the built site lives in `out/`. Set `LEAD_NOTIFY_EMAIL` and `LEAD_WEBHOOK_URL` in `.env` if the desk should also get a copy.

## Deploy

The GitHub action builds the site and rsyncs `out/` to the document root. Old `index.php?mode=` URLs 301 to the new pages.

## Desk numbers

Phone and WhatsApp buttons stay hidden until you set `phoneE164`, `phoneLabel`, and `whatsappE164` in `lib/site.ts`. The order form already asks the customer for a phone or WhatsApp reply.
