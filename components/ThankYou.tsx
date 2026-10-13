import { ProductShot } from "@/components/Blocks";
import { site } from "@/lib/site";

type Kind = "paid" | "order" | "contact" | "optout" | "cancelled" | "refunded" | "default";

type Copy = {
  eyebrow: string;
  title: string;
  lede: string;
  steps?: string[];
  primary: { href: string; label: string };
  secondary?: { href: string; label: string };
  showBottle?: boolean;
};

const copy: Record<Exclude<Kind, "paid">, Copy> = {
  order: {
    eyebrow: "Checkout",
    title: "Payment was not taken",
    lede: "Nothing was charged. You can go back to the order page and try again.",
    primary: { href: "/order/", label: "Return to order" },
    secondary: { href: "/", label: "Back home" },
    showBottle: true,
  },
  contact: {
    eyebrow: "Desk",
    title: "We have your note",
    lede: "We reply by email on business days.",
    primary: { href: "/", label: "Back home" },
    secondary: { href: `mailto:${site.email}`, label: `Email ${site.email}` },
  },
  optout: {
    eyebrow: "List",
    title: "You're off the email list",
    lede: "We will not send Elite Sleeper mail to that address. A bottle already paid still ships.",
    primary: { href: "/", label: "Back home" },
    secondary: { href: "/cancel-plan/", label: "Cancel monthly billing" },
  },
  cancelled: {
    eyebrow: "Monthly plan",
    title: "Monthly billing is stopped",
    lede: "No further monthly charges. A bottle already paid still ships.",
    primary: { href: "/", label: "Back home" },
    secondary: { href: "/refund-policy/", label: "Refund the last payment" },
  },
  refunded: {
    eyebrow: "Refund",
    title: "Refund is in",
    lede: "The processor voided or refunded the charge. It can take a few days to show on the card.",
    primary: { href: "/", label: "Back home" },
    secondary: { href: `mailto:${site.email}`, label: `Email ${site.email}` },
  },
  default: {
    eyebrow: site.name,
    title: "You're all set",
    lede: `If you expected a confirmation and do not see one, write ${site.email}.`,
    primary: { href: "/", label: "Back home" },
    secondary: { href: "/order/", label: "Order a bottle" },
  },
};

function resolveKind(sent: string | null | undefined): Kind {
  if (
    sent === "paid" ||
    sent === "order" ||
    sent === "contact" ||
    sent === "optout" ||
    sent === "cancelled" ||
    sent === "refunded"
  ) {
    return sent;
  }
  return "default";
}

function firstParam(value: string | string[] | undefined): string {
  if (Array.isArray(value)) return value[0] ?? "";
  return value ?? "";
}

export function ThankYouView({
  sent,
  sessionId,
}: {
  sent?: string | string[];
  sessionId?: string | string[];
}) {
  const kind = resolveKind(firstParam(sent) || null);
  const paymentId = firstParam(sessionId);

  if (kind === "paid") {
    const refundHref = paymentId
      ? `/refund-policy/?session_id=${encodeURIComponent(paymentId)}`
      : "/refund-policy/";
    const cancelHref = paymentId
      ? `/cancel-plan/?session_id=${encodeURIComponent(paymentId)}`
      : "/cancel-plan/";

    return (
      <div className="thanks">
        <div className="thanks-copy">
          <p className="eyebrow">Order confirmed</p>
          <h1>We have your payment</h1>
          <p className="thanks-lede">
            Elite Sleeper will ship to the address on the order, from {site.city}, {site.region}.
          </p>

          {paymentId ? (
            <div className="thanks-receipt">
              <p className="thanks-receipt-label">Payment ID</p>
              <p className="thanks-receipt-id">{paymentId}</p>
              <p className="thanks-receipt-hint">Save this. You need it to refund or stop monthly billing.</p>
            </div>
          ) : null}

          <ol className="thanks-steps">
            <li>We pack the bottle after the payment is on file.</li>
            <li>A shipping note goes to the email used at checkout.</li>
            <li>Need the desk? {site.email}</li>
          </ol>

          <div className="thanks-actions">
            <a className="btn" href="/">
              Back home
            </a>
            <a className="btn btn-ghost" href={refundHref}>
              Refund this payment
            </a>
            <a className="btn btn-ghost" href={cancelHref}>
              Stop monthly billing
            </a>
          </div>
        </div>
        <aside className="thanks-visual">
          <ProductShot size="side" />
          <p className="thanks-visual-note">
            {site.capsules} capsules · {site.priceLabel}
          </p>
        </aside>
      </div>
    );
  }

  const content = copy[kind];

  return (
    <div className="thanks">
      <div className="thanks-copy">
        <p className="eyebrow">{content.eyebrow}</p>
        <h1>{content.title}</h1>
        <p className="thanks-lede">{content.lede}</p>

        {content.steps ? (
          <ol className="thanks-steps">
            {content.steps.map((step) => (
              <li key={step}>{step}</li>
            ))}
          </ol>
        ) : null}

        <div className="thanks-actions">
          <a className="btn" href={content.primary.href}>
            {content.primary.label}
          </a>
          {content.secondary ? (
            <a className="btn btn-ghost" href={content.secondary.href}>
              {content.secondary.label}
            </a>
          ) : null}
        </div>
      </div>

      {content.showBottle ? (
        <aside className="thanks-visual">
          <ProductShot size="side" />
          <p className="thanks-visual-note">
            {site.capsules} capsules · {site.priceLabel}
          </p>
        </aside>
      ) : (
        <aside className="thanks-visual thanks-visual-photo">
          <figure className="photo">
            <img src="/images/stock/linen.jpg" alt="A person with a mug, looking out a bright window" />
          </figure>
        </aside>
      )}
    </div>
  );
}
