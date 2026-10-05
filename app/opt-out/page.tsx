import type { Metadata } from "next";
import { PageIntro, Photo } from "@/components/Blocks";
import { OptOutForm } from "@/components/Forms";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Remove My Email",
  description:
    "Take your email off the Elite Sleeper list. We keep a suppression note so the address is not added again.",
  path: "/opt-out/",
});

export default function OptOutPage() {
  return (
    <main id="main" className="page">
      <div className="wrap layout-2">
        <div>
          <PageIntro
            eyebrow="List"
            title="Opt out of Elite Sleeper email"
            lede="Add the address you want removed. We take it off the mailing list and keep a record so we do not add it back."
            crumb="Opt out"
          />
          <OptOutForm />
        </div>
        <aside className="stack">
          <Photo
            src="/images/stock/desk.jpg"
            alt="A person writing on a laptop"
            caption="Send the address you want taken off the list."
          />
          <div className="panel">
          <h2>What this does not do</h2>
          <p>
            It does not cancel a bottle that is already paid and with the carrier. Email{" "}
            <a href={`mailto:${site.email}`}>{site.email}</a> with the order name if you need that stopped.
          </p>
          <p>It also does not delete a shipping record we have to keep for a past order. It stops new mail.</p>
          </div>
        </aside>
      </div>
    </main>
  );
}
