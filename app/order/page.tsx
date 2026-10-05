import type { Metadata } from "next";
import { PageIntro, ProductShot } from "@/components/Blocks";
import { Icon } from "@/components/Icons";
import { OrderForm } from "@/components/Forms";
import { JsonLd } from "@/components/JsonLd";
import { buildMetadata } from "@/lib/seo";
import { phoneHref, site, whatsappHref } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Order Elite Sleeper",
  description:
    "Order Elite Sleeper for $29.95 a bottle. 60 capsules. We email you to confirm shipping and payment. Visa, Mastercard, American Express, and Discover.",
  path: "/order/",
});

const productSchema = {
  "@context": "https://schema.org",
  "@type": "Product",
  name: "Elite Sleeper",
  image: `${site.url}/images/bottle.png`,
  brand: { "@type": "Brand", name: site.name },
  offers: {
    "@type": "Offer",
    url: `${site.url}/order/`,
    priceCurrency: "USD",
    price: site.price,
    availability: "https://schema.org/InStock",
  },
};

export default function OrderPage() {
  const phone = phoneHref();
  const whatsapp = whatsappHref("I want to order Elite Sleeper, 60 capsules.");

  return (
    <main id="main" className="page lead-page">
      <JsonLd data={productSchema} />
      <div className="wrap">
        <PageIntro
          eyebrow="Order desk"
          title="Request your bottle"
          lede="One bottle is $29.95 for 60 capsules. The form does not charge your card. We email you to confirm the count, the shipping cost, and payment."
          crumb="Order"
        />
        <div className="layout-2">
          <OrderForm />
          <aside className="stack">
            <div className="offer">
              <ProductShot size="card" />
              <p className="offer-kicker">60 capsules</p>
              <p className="offer-price">{site.priceLabel}</p>
              <p>About $1.00 a night at 2 capsules.</p>
              <ul>
                <li>Comprehensive sleep formula</li>
                <li>2 mg melatonin in the full serving</li>
                <li>Ships after we confirm payment</li>
              </ul>
            </div>
            <div className="panel">
              <h2>What happens next</h2>
              <p className="icon-line">
                <Icon name="mail" />
                <span>We reply by email on business days. If you picked phone or WhatsApp, we use your number.</span>
              </p>
              <p className="icon-line">
                <Icon name="card" />
                <span>Pay when we confirm. Visa, Mastercard, American Express, and Discover.</span>
              </p>
              <p className="icon-line">
                <Icon name="truck" />
                <span>
                  We ship to U.S. street addresses from {site.city}, {site.region}.
                </span>
              </p>
              <figure className="media-frame media-frame-short">
                <img src="/images/stock/morning-bed.jpg" alt="A person stretching after waking up" />
              </figure>
              <p>
                <a href={`mailto:${site.email}?subject=Elite%20Sleeper%20order`}>Email {site.email}</a>
              </p>
              {phone && site.phoneLabel ? (
                <p>
                  <a href={phone}>Call {site.phoneLabel}</a>
                </p>
              ) : null}
              {whatsapp ? (
                <p>
                  <a href={whatsapp}>Order on WhatsApp</a>
                </p>
              ) : (
                <p className="fine">
                  Choose WhatsApp on the form and leave your mobile number. We reply there.
                </p>
              )}
            </div>
          </aside>
        </div>
      </div>
    </main>
  );
}
