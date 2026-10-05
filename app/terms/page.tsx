import type { Metadata } from "next";
import { PageIntro, Photo } from "@/components/Blocks";
import { buildMetadata } from "@/lib/seo";
import { fdaLine, site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Terms of Use",
  description:
    "Terms for using EliteSleeper.com and for ordering the 60-capsule dietary supplement from Forest Hills, NY.",
  path: "/terms/",
});

export default function TermsPage() {
  return (
    <main id="main" className="page">
      <div className="wrap prose">
        <PageIntro
          eyebrow="Policy"
          title="Terms of use"
          lede="These terms cover the site and the way an order is confirmed. The effective date is October 2, 2026."
          crumb="Terms"
        />
        <Photo
          src="/images/stock/desk.jpg"
          alt="A person writing on a laptop"
          caption="These terms are the written agreement for the site and an order."
        />
        <h2>The site</h2>
        <p>
          EliteSleeper.com is run from {site.street}, {site.city}, {site.region} {site.postal}. You may read the
          pages and send a form. You may not break the site, scrape it in a way that knocks it over, or send a form
          with someone else's details.
        </p>
        <h2>Orders</h2>
        <p>
          The order form is a request. A sale starts when we email you to confirm the bottle count, shipping, and
          price, and you pay. The price on the site is {site.priceLabel} per bottle until we change the page. A
          confirmed order stays at the price in that email. We can refuse an order, including one we cannot ship.
        </p>
        <p>
          You are responsible for a shipping address we can deliver to. See <a href="/shipping/">shipping</a> and the{" "}
          <a href="/refund-policy/">refund policy</a>.
        </p>
        <h2>The product</h2>
        <p>
          Elite Sleeper is a dietary supplement for adults 18 and older. {fdaLine} It is not a drug and it does not
          replace advice from your clinician. You are responsible for reading the directions and the ingredient list
          before you take a capsule.
        </p>
        <h2>Liability</h2>
        <p>
          The site is provided as it is available. To the extent the law allows, Elite Sleeper is not liable for
          indirect or consequential damages from use of the site. Product questions and refunds follow the policies
          linked above. Some states do not allow certain limits, so those limits apply only as far as the law lets
          them.
        </p>
        <h2>Law</h2>
        <p>
          These terms follow the laws of the State of New York, aside from conflict-of-law rules. If a line in these
          terms cannot be enforced, the rest still stands.
        </p>
      </div>
    </main>
  );
}
