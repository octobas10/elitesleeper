import type { Metadata } from "next";
import { PageIntro, PromoShot } from "@/components/Blocks";
import { Icon } from "@/components/Icons";
import { OrderForm } from "@/components/Forms";
import { JsonLd } from "@/components/JsonLd";
import { buildMetadata } from "@/lib/seo";
import { phoneHref, site, whatsappHref } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Order Elite Sleeper",
  description:
    "Order Elite Sleeper for $29.95 a bottle. 60 capsules. Pay securely with secure checkout. Visa, Mastercard, American Express, and Discover.",
  path: "/order/",
});

const productSchema = {
  "@context": "https://schema.org",
  "@type": "Product",
  name: "Elite Sleeper",
  image: `${site.url}/images/product-promo.jpg`,
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
          title="Order your bottle"
          lede="One bottle is $29.95 for 60 capsules. Fill in shipping details, then pay on the processor’s checkout page."
          crumb="Order"
        />
        <div className="layout-2">
          <OrderForm />
          <aside className="stack">
            <div className="offer offer-promo">
              <PromoShot />
              <p>About $1.00 a night at 2 capsules.</p>
              <ul>
                <li>Comprehensive sleep formula</li>
                <li>2 mg melatonin in the full serving</li>
                <li>Ships after payment confirms</li>
              </ul>
            </div>
            <div className="panel">
              <h2>What happens next</h2>
              <p className="icon-line">
                <Icon name="card" />
                <span>Pay on secure checkout. Visa, Mastercard, American Express, and Discover.</span>
              </p>
              <p className="icon-line">
                <Icon name="mail" />
                <span>We email a receipt and shipping update. Phone or WhatsApp if you asked for that reply.</span>
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
