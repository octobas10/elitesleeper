import type { Metadata } from "next";
import { redirect } from "next/navigation";
import { PageIntro } from "@/components/Blocks";
import { NmiPayForm } from "@/components/NmiPayForm";
import { isAllowedNmiFormUrl } from "@/lib/payments/nmi";
import { readJsonRecord } from "@/lib/payments/storage";
import { buildMetadata } from "@/lib/seo";

export const metadata: Metadata = buildMetadata({
  title: "Pay for Elite Sleeper",
  description: "Finish your Elite Sleeper order.",
  path: "/order/pay/",
});

export default async function PayPage({
  searchParams,
}: {
  searchParams: Promise<{ order?: string }>;
}) {
  const { order: orderId } = await searchParams;
  if (!orderId) {
    redirect("/order/?error=payment");
  }

  const record = await readJsonRecord(orderId);
  const formUrl = typeof record?.nmi_form_url === "string" ? record.nmi_form_url : "";
  if (!record || !isAllowedNmiFormUrl(formUrl)) {
    redirect("/order/?error=payment");
  }

  const total =
    typeof record.product_total === "string" ? record.product_total : siteFallbackTotal(record);
  const monthly = record.plan === "monthly";

  return (
    <main id="main" className="page lead-page">
      <div className="wrap">
        <PageIntro
          eyebrow="Secure checkout"
          title="Pay"
          lede={
            monthly
              ? `$${total} today, then the same amount each month until you cancel on this site.`
              : `Total due $${total}.`
          }
          crumb="Pay"
        />
        <NmiPayForm formUrl={formUrl} total={total} />
      </div>
    </main>
  );
}

function siteFallbackTotal(record: Record<string, unknown>): string {
  if (typeof record.amount_cents === "number") {
    return (record.amount_cents / 100).toFixed(2);
  }
  return "29.95";
}
