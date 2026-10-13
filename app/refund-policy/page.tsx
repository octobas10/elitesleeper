import type { Metadata } from "next";
import { PageIntro } from "@/components/Blocks";
import { RefundForm } from "@/components/Forms";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Refund Policy",
  description:
    "Refund an Elite Sleeper payment with the email and payment ID from your thank-you page, or write support@elitesleeper.com.",
  path: "/refund-policy/",
});

function first(value: string | string[] | undefined): string {
  return Array.isArray(value) ? value[0] ?? "" : value ?? "";
}

export default async function RefundPage({
  searchParams,
}: {
  searchParams: Promise<{ session_id?: string | string[]; reference?: string | string[]; email?: string | string[] }>;
}) {
  const params = await searchParams;
  const reference = first(params.session_id) || first(params.reference);
  const email = first(params.email);
  const cancelHref = reference
    ? `/cancel-plan/?session_id=${encodeURIComponent(reference)}`
    : "/cancel-plan/";

  return (
    <main id="main" className="page lead-page">
      <div className="wrap layout-2">
        <div>
          <PageIntro
            eyebrow="Refunds"
            title="Get this payment back"
            lede="Use the email and payment ID from your thank-you page. The processor voids or refunds the last charge."
            crumb="Refunds"
          />
          <RefundForm reference={reference} email={email} />
        </div>
        <aside className="panel">
          <h2>What this does</h2>
          <ol className="billing-steps">
            <li>Returns the last charge to the same card.</li>
            <li>It can take a few days to show on the statement.</li>
            <li>It does not stop a monthly plan by itself.</li>
          </ol>
          <p>
            Still getting billed each month? <a href={cancelHref}>Stop monthly billing</a>, or email {site.email}.
          </p>
        </aside>
      </div>

      <div className="wrap prose billing-policy">
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
          A monthly bottle is billed today and then once a month on the same calendar day until you cancel. Stop
          future charges on the <a href={cancelHref}>cancel monthly</a> page. If a bottle is already paid and in
          transit, canceling does not pull it off the truck.
        </p>
      </div>
    </main>
  );
}
