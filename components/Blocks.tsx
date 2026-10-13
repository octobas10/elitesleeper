import type { ReactNode } from "react";
import { JsonLd } from "./JsonLd";
import { site } from "@/lib/site";

export function Crumbs({ items }: { items: { href?: string; label: string }[] }) {
  const data = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((item, index) => ({
      "@type": "ListItem",
      position: index + 1,
      name: item.label,
      ...(item.href ? { item: new URL(item.href, site.url).toString() } : {}),
    })),
  };

  return (
    <>
      <nav className="crumbs" aria-label="Breadcrumb">
        {items.map((item, index) => (
          <span key={item.label}>
            {index > 0 ? <span className="crumb-sep"> / </span> : null}
            {item.href ? <a href={item.href}>{item.label}</a> : <span>{item.label}</span>}
          </span>
        ))}
      </nav>
      <JsonLd data={data} />
    </>
  );
}

export function Photo({
  src,
  alt,
  caption,
  focus = "center",
  wide = false,
}: {
  src: string;
  alt: string;
  caption?: string;
  focus?: "center" | "bottom";
  wide?: boolean;
}) {
  return (
    <figure className={wide ? "photo photo-wide" : "photo"}>
      <img src={src} alt={alt} style={{ objectPosition: focus === "bottom" ? "center 82%" : "center" }} />
      {caption ? <figcaption>{caption}</figcaption> : null}
    </figure>
  );
}

export const heroBanner = {
  src: "/images/hero-banner.jpg",
  width: 1024,
  height: 576,
  alt: "Elite Sleeper, 60 capsules, $29.95. A calmer nighttime routine with valerian, chamomile, passion flower, lemon balm, GABA, melatonin, and L-tryptophan.",
};

export const productPromo = {
  src: "/images/product-promo.jpg",
  width: 1024,
  height: 1024,
  alt: "Elite Sleeper dietary supplement, 60 capsules. Bottle with valerian, chamomile, passion flower, lemon balm, GABA, melatonin, and L-tryptophan.",
};

export function ProductShot({ size = "hero" }: { size?: "hero" | "card" | "side" }) {
  return (
    <div className={`product-shot product-shot-${size}`}>
      <img
        src="/images/bottle-clear.png"
        alt="Elite Sleeper bottle, dietary supplement, 60 capsules"
        width={505}
        height={971}
      />
    </div>
  );
}

export function PromoShot({ linked = false }: { linked?: boolean }) {
  const image = (
    <img src={productPromo.src} alt={productPromo.alt} width={productPromo.width} height={productPromo.height} />
  );
  if (linked) {
    return (
      <a className="promo-shot" href="/order/">
        {image}
      </a>
    );
  }
  return <div className="promo-shot">{image}</div>;
}

export function PageIntro({
  eyebrow,
  title,
  lede,
  crumb,
}: {
  eyebrow?: string;
  title: string;
  lede: string;
  crumb: string;
}) {
  return (
    <header className="page-intro">
      <Crumbs items={[{ href: "/", label: "Home" }, { label: crumb }]} />
      {eyebrow ? <p className="eyebrow">{eyebrow}</p> : null}
      <h1>{title}</h1>
      <p className="lede">{lede}</p>
    </header>
  );
}

export function FactsTable() {
  const rows = [
    ["Valerian root extract", "150 mg"],
    ["Gamma-aminobutyric acid (as GABA)", "100 mg"],
    ["Melatonin", "2 mg"],
    ["L-tryptophan", "100 mg"],
    ["Chamomile extract", "150 mg"],
    ["Passion flower extract", "150 mg"],
    ["Lemon balm extract", "100 mg"],
  ];

  return (
    <div className="facts">
      <p className="facts-title">Supplement Facts</p>
      <p className="facts-serving">
        Serving size 2 capsules
        <br />
        Servings per container 30
      </p>
      <table>
        <caption className="sr-only">Amount per serving. Daily value is not established.</caption>
        <thead>
          <tr>
            <th scope="col">Amount per serving</th>
            <th scope="col">% Daily value</th>
          </tr>
        </thead>
        <tbody>
          {rows.map(([name, amount]) => (
            <tr key={name}>
              <th scope="row">
                {name} <span>{amount}</span>
              </th>
              <td>*</td>
            </tr>
          ))}
        </tbody>
      </table>
      <p className="facts-note">* Daily value not established.</p>
      <p className="facts-note">Other ingredients: gelatin, brown rice flour.</p>
    </div>
  );
}

export function FaqList({ items }: { items: { question: string; answer: string }[] }) {
  return (
    <div className="faq-list">
      {items.map((item) => (
        <details key={item.question} className="faq-item">
          <summary>{item.question}</summary>
          <p>{item.answer}</p>
        </details>
      ))}
    </div>
  );
}

export function CtaBand({
  title,
  text,
  children,
}: {
  title: string;
  text: string;
  children?: ReactNode;
}) {
  return (
    <section className="cta-band">
      <div className="wrap cta-inner">
        <div>
          <h2>{title}</h2>
          <p>{text}</p>
        </div>
        <div className="cta-actions">{children}</div>
      </div>
    </section>
  );
}

export function Honeypot() {
  return (
    <p className="hp">
      <label htmlFor="company_url">Leave this blank</label>
      <input id="company_url" name="company_url" type="text" tabIndex={-1} autoComplete="off" />
    </p>
  );
}
