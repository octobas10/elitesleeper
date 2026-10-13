import type { Metadata } from "next";
import { PageIntro } from "@/components/Blocks";
import { CancelPlanForm } from "@/components/Forms";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Cancel monthly Elite Sleeper",
  description: "Stop monthly Elite Sleeper billing. A bottle already paid still ships.",
  path: "/cancel-plan/",
});

function first(value: string | string[] | undefined): string {
  return Array.isArray(value) ? value[0] ?? "" : value ?? "";
}

export default async function CancelPlanPage({
  searchParams,
}: {
  searchParams: Promise<{ session_id?: string | string[]; reference?: string | string[]; email?: string | string[] }>;
}) {
  const params = await searchParams;
  const reference = first(params.session_id) || first(params.reference);
  const email = first(params.email);
  const refundHref = reference
    ? `/refund-policy/?session_id=${encodeURIComponent(reference)}`
    : "/refund-policy/";

  return (
    <main id="main" className="page lead-page">
      <div className="wrap layout-2">
        <div>
          <PageIntro
            eyebrow="Monthly plan"
            title="Stop monthly billing"
            lede="Use the email and payment ID from your thank-you page. Future months will not be charged."
            crumb="Cancel monthly"
          />
          <CancelPlanForm reference={reference} email={email} />
        </div>
        <aside className="panel">
          <h2>What this does</h2>
          <ol className="billing-steps">
            <li>Stops the next monthly charge.</li>
            <li>Does not reverse the payment you already made.</li>
            <li>A bottle already paid still ships from {site.city}.</li>
          </ol>
          <p>
            Need that last charge back? Use the <a href={refundHref}>refund form</a> or email {site.email}.
          </p>
        </aside>
      </div>
    </main>
  );
}
