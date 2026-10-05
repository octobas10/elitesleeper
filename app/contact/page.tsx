import type { Metadata } from "next";
import { PageIntro, ProductShot } from "@/components/Blocks";
import { ContactForm } from "@/components/Forms";
import { Icon } from "@/components/Icons";
import { buildMetadata } from "@/lib/seo";
import { phoneHref, site, whatsappHref } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Contact Elite Sleeper",
  description:
    "Write Elite Sleeper at support@elitesleeper.com or send the contact form. The desk is in Forest Hills, NY.",
  path: "/contact/",
});

export default function ContactPage() {
  const phone = phoneHref();
  const whatsapp = whatsappHref("Hi Elite Sleeper, I have a question.");

  return (
    <main id="main" className="page">
      <div className="wrap">
        <PageIntro
          eyebrow="Desk"
          title="Contact us"
          lede="Questions about an order, the formula, or a bottle that arrived wrong. We read every note."
          crumb="Contact"
        />
        <div className="layout-2">
          <ContactForm />
          <aside className="stack">
            <figure className="media-frame">
              <img src="/images/stock/desk.jpg" alt="A person writing on a laptop" />
            </figure>
            <div className="panel buy-card">
              <ProductShot size="side" />
              <h2>Want the bottle instead?</h2>
              <p>The order form is faster than a question if you already know the dose.</p>
              <a className="btn btn-wide" href="/order/">
                Order · {site.priceLabel}
              </a>
            </div>
            <div className="panel stack">
            <p className="icon-line">
              <Icon name="mail" />
              <a href={`mailto:${site.email}`}>{site.email}</a>
            </p>
            <p className="icon-line">
              <Icon name="pin" />
              <span>
                {site.street}
                <br />
                {site.city}, {site.region} {site.postal}
                <br />
                {site.country}
              </span>
            </p>
            {phone && site.phoneLabel ? (
              <p className="icon-line">
                <Icon name="phone" />
                <a href={phone}>{site.phoneLabel}</a>
              </p>
            ) : null}
            {whatsapp ? (
              <p className="icon-line">
                <Icon name="chat" />
                <a href={whatsapp}>WhatsApp the desk</a>
              </p>
            ) : null}
            <p>
              To leave the list, use <a href="/opt-out/">opt out</a>.
            </p>
            </div>
          </aside>
        </div>
      </div>
    </main>
  );
}
