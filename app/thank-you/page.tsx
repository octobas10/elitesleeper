import type { Metadata } from "next";
import { ThankYouView } from "@/components/ThankYou";
import { buildMetadata } from "@/lib/seo";

export const metadata: Metadata = buildMetadata({
  title: "Thank you",
  description: "Elite Sleeper confirmation for your order or message.",
  path: "/thank-you/",
  noindex: true,
});

export default async function ThankYouPage({
  searchParams,
}: {
  searchParams: Promise<{ sent?: string | string[]; session_id?: string | string[] }>;
}) {
  const params = await searchParams;
  return (
    <main id="main" className="page thanks-page">
      <div className="wrap">
        <ThankYouView sent={params.sent} sessionId={params.session_id} />
      </div>
    </main>
  );
}
