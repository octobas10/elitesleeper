import type { Metadata } from "next";
import { PageIntro, Photo } from "@/components/Blocks";
import { buildMetadata } from "@/lib/seo";
import { site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Privacy Policy",
  description:
    "What Elite Sleeper collects on the order, contact, and opt-out forms, why we keep it, and how to be removed.",
  path: "/privacy/",
});

export default function PrivacyPage() {
  return (
    <main id="main" className="page">
      <div className="wrap prose">
        <PageIntro
          eyebrow="Policy"
          title="Privacy"
          lede="Elite Sleeper collects what we need to ship a bottle, answer a question, or take you off the list. We do not sell that list."
          crumb="Privacy"
        />
        <Photo
          src="/images/stock/desk.jpg"
          alt="A person writing on a laptop"
          caption="We keep what we need to ship a bottle or answer a question."
        />
        <p>
          This policy covers {site.url.replace("https://", "")}. The business address is {site.street}, {site.city},{" "}
          {site.region} {site.postal}. Email {site.email}.
        </p>
        <h2>What we collect</h2>
        <p>
          Order form: name, email, phone, shipping address, bottle count, whether you want a monthly bottle, how you
          want the reply, an optional note, and whether you agreed to texts about that order. Contact form: name,
          email, optional phone, subject, and message. Opt-out form: the email address to remove.
        </p>
        <p>
          The server also stores the time of the form and the IP address, so we can block a burst of junk submissions.
          We do not run advertising cookies on this site.
        </p>
        <h2>Why we keep it</h2>
        <p>
          To confirm and ship the order, take payment, reply to you, and honor an opt-out. If you check the text box,
          we may text you about that order. Message frequency depends on the order. Reply STOP to end those texts.
        </p>
        <p>
          A copy of the form is stored on our server. We may also email that copy to the order desk, or pass it to a
          webhook the desk uses to read new orders. Those tools are for running Elite Sleeper, not for selling your
          name.
        </p>
        <h2>How long</h2>
        <p>
          Order records stay as long as we need them for shipping, refunds, and tax records. An opt-out is kept as a
          suppression note so we do not mail that address again. Email {site.email} if you want a request reviewed.
          We delete what we are not required to keep.
        </p>
        <h2>Age</h2>
        <p>
          The product is for adults 18 and older. We do not knowingly collect information from children. If a child
          sent a form, email us and we will delete it.
        </p>
        <h2>Your choices</h2>
        <p>
          Use the <a href="/opt-out/">opt-out page</a> to leave the mailing list. Email {site.email} to ask what we
          have stored under your address. We do not sell personal information, and we do not share it for
          cross-context advertising.
        </p>
      </div>
    </main>
  );
}
