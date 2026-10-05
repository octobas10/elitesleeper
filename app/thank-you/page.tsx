import type { Metadata } from "next";
import { Photo } from "@/components/Blocks";
import { QueryNote } from "@/components/QueryNote";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "We have your note",
  description: "Elite Sleeper received your form.",
  path: "/thank-you/",
  noindex: true,
});

export default function ThankYouPage() {
  return (
    <main id="main" className="page">
      <div className="wrap prose">
        <p className="eyebrow">Desk</p>
        <h1>We have it.</h1>
        <Photo
          src="/images/stock/linen.jpg"
          alt="A person with a mug, looking out a bright window"
          caption="Watch your email for the confirmation."
        />
        <QueryNote
          messages={{
            order: "Your order request is in. Watch your email for the confirmation before you pay.",
            contact: "Your message is in. We reply by email on business days.",
            optout: "That address is marked for removal from the Elite Sleeper list.",
          }}
        />
        <p>
          If you sent an order, nothing has been charged. We write you to confirm bottles, shipping, and payment. If
          the mailbox stays quiet longer than one business day, email{" "}
          <a href={`mailto:${site.email}`}>{site.email}</a>.
        </p>
        <p>
          <a className="btn" href="/">
            Back to the bottle
          </a>
        </p>
      </div>
    </main>
  );
}
