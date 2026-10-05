import type { Metadata } from "next";
import { CtaBand, PageIntro } from "@/components/Blocks";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Shipping",
  description:
    "Elite Sleeper ships inside the United States from Forest Hills, NY after payment is confirmed by email.",
  path: "/shipping/",
});

export default function ShippingPage() {
  return (
    <main id="main" className="page">
      <div className="wrap prose">
        <PageIntro
          eyebrow="Policy"
          title="Shipping"
          lede="We pack Elite Sleeper in Forest Hills and send it to street addresses in the United States."
          crumb="Shipping"
        />
        <figure className="media-frame media-frame-banner">
          <img src="/images/stock/morning-bed.jpg" alt="A person stretching after waking up" />
        </figure>
        <h2>When it leaves</h2>
        <p>
          The order form is a request. We email you with the bottle count, the shipping cost, and how to pay. The
          bottle ships after that payment clears. We do not charge the card when you send the form.
        </p>
        <h2>Where it goes</h2>
        <p>
          U.S. street addresses. If you only have a P.O. box, email {site.email} before you order and we will say if
          the carrier can take it. We do not promise delivery outside the United States. Ask first if that is what
          you need.
        </p>
        <h2>How long</h2>
        <p>
          Once the box leaves {site.city}, transit is usually a few business days. The confirmation email names the
          carrier. Weather, the carrier, and a wrong address can slow it down. We are not a freight company, so the
          tracking note from the carrier is the record of where the box is.
        </p>
        <h2>If the box arrives damaged</h2>
        <p>
          Email {site.email} within 7 days of delivery. Include the name on the order and what showed up. We will
          replace the bottle or refund the product price. See the <a href="/refund-policy/">refund policy</a> for
          opened bottles and returns you send back.
        </p>
      </div>
      <CtaBand
        title="Ready for a bottle?"
        text="Send the order form. We confirm shipping and payment by email before anything ships."
      >
        <a className="btn" href="/order/">
          Order the bottle
        </a>
      </CtaBand>
    </main>
  );
}
