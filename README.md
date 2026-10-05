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

`php/lead.php` writes each form to `data/leads/` outside the web root. On the server that folder is `/var/www/data/leads`. Set `LEAD_NOTIFY_EMAIL` in `.env` if the desk should also get a copy by email.

## Deploy

The GitHub action builds the site and rsyncs `out/` to the document root.

## Desk numbers

Phone and WhatsApp buttons stay hidden until you set `phoneE164`, `phoneLabel`, and `whatsappE164` in `lib/site.ts`. The order form already asks the customer for a phone or WhatsApp reply.
