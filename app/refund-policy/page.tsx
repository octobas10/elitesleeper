import type { Metadata } from "next";
import { PageIntro, Photo } from "@/components/Blocks";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Refund Policy",
  description:
    "How Elite Sleeper handles damaged bottles, unopened returns, and monthly refill requests. Email support@elitesleeper.com.",
  path: "/refund-policy/",
});

export default function RefundPage() {
  return (
    <main id="main" className="page">
      <div className="wrap prose">
        <PageIntro
          eyebrow="Policy"
          title="Refunds"
          lede="Write us within 30 days of the delivery date if the order is wrong or you want to send an unopened bottle back."
          crumb="Refunds"
        />
        <Photo
          src="/images/stock/morning-bed.jpg"
          alt="A person stretching after waking up"
          caption="Write us if the order arrives wrong or you want an unopened bottle back."
        />
        <h2>Our mistake</h2>
        <p>
          If we ship the wrong item, or the bottle arrives damaged, email {site.email}. We replace it or refund the
          product price. You do not pay return shipping when the error is ours.
        </p>
        <h2>Unopened bottles</h2>
        <p>
          You can send an unopened bottle back within 30 days of delivery. Email first so we have the order name.
          You pay the return postage. When we receive the unopened bottle, we refund the product price. Shipping you
          already paid to receive it is not refunded.
        </p>
        <h2>Opened bottles</h2>
        <p>
          We cannot resell an opened supplement, so we do not refund the product price on an opened bottle. We still
          fix a shipping error or a damaged bottle, opened or not.
        </p>
        <h2>Monthly bottles</h2>
        <p>
          A monthly bottle is a request, not a silent charge. We email you before a refill is billed. To stop, email{" "}
          {site.email} before that confirmation. If a bottle is already paid and in transit, the opt-out form does
          not pull it off the truck.
        </p>
      </div>
    </main>
  );
}
