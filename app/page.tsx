import type { Metadata } from "next";
import { FactsTable, FaqList } from "@/components/Blocks";
import { ContactForm } from "@/components/Forms";
import { Icon } from "@/components/Icons";
import { JsonLd } from "@/components/JsonLd";
import { buildMetadata } from "@/lib/seo";
import { faqs, fdaLine, ingredients, otherIngredients, posts, reviews, site } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Elite Sleeper | Sleep Capsules for a Clear Morning",
  description:
    "Elite Sleeper is a 60-capsule sleep supplement with melatonin, valerian, GABA, and calming herbs. $29.95 a bottle. Wake without the heavy morning.",
  path: "/",
  absolute: true,
});

const productSchema = {
  "@context": "https://schema.org",
  "@type": "Product",
  name: "Elite Sleeper",
  image: `${site.url}/images/bottle.png`,
  description:
    "Dietary supplement. 60 capsules with melatonin, valerian root extract, GABA, L-tryptophan, chamomile, passion flower, and lemon balm.",
  brand: { "@type": "Brand", name: site.name },
  sku: "ES-60",
  offers: {
    "@type": "Offer",
    url: `${site.url}/order/`,
    priceCurrency: "USD",
    price: site.price,
    availability: "https://schema.org/InStock",
    areaServed: "US",
  },
};

const faqSchema = {
  "@context": "https://schema.org",
  "@type": "FAQPage",
  mainEntity: faqs.map((item) => ({
    "@type": "Question",
    name: item.question,
    acceptedAnswer: { "@type": "Answer", text: item.answer },
  })),
};

const tiles = [
  {
    href: "#ingredients",
    kicker: "The label",
    title: "Herbs. Quiet. Bedtime.",
    text: "Seven ingredients on the panel, including melatonin at 2 mg.",
    image: "/images/stock/chamomile.jpg",
    alt: "Hands holding a warm cup of tea",
    cta: "See the formula",
  },
  {
    href: "#ritual",
    kicker: "The ritual",
    title: "One bedtime. Then lights out.",
    text: "1 or 2 capsules with water. The evening is over after that.",
    image: "/images/stock/night-bed.jpg",
    alt: "A person sleeping under a light comforter",
    cta: "How to take it",
  },
  {
    href: "#reviews",
    kicker: "The notes",
    title: "What people wrote us.",
    text: "New Jersey, Baltimore, and Staten Island. We did not write the notes.",
    image: "/images/stock/moon.jpg",
    alt: "A person resting with eyes closed",
    cta: "Read the notes",
  },
  {
    href: "/order/",
    kicker: "The morning",
    title: "A day you can use.",
    text: "The bottle is built so the next morning stays the point.",
    image: "/images/stock/linen.jpg",
    alt: "A person with a mug, looking out a bright window",
    cta: "Order the bottle",
    action: true,
  },
];

export default function HomePage() {
  return (
    <main id="main" className="home">
      <JsonLd data={productSchema} />
      <JsonLd data={faqSchema} />

      <section className="home-hero-band" id="bottle">
        <div className="home-hero">
        <div className="home-hero-copy">
          <p className="home-kicker">Dietary supplement · 60 capsules</p>
          <h1>Morning is calling.</h1>
          <p className="home-lede">
            Elite Sleeper is a bedtime capsule for adults who want the night to end and the morning to stay usable.
            Melatonin stays at 2 mg, with valerian, GABA, and four evening herbs.
          </p>
          <p className="home-price">
            {site.priceLabel} <span>about $1 a night</span>
          </p>
          <div className="hero-actions">
            <a className="btn home-btn-light" href="/order/">
              Order the bottle
            </a>
          </div>
          <p className="home-trust">
            The form does not charge your card. We confirm by email, then ship from {site.city}, {site.region}.
          </p>
          <p className="home-fine">{fdaLine}</p>
        </div>
        <div className="home-hero-visual">
          <img
            src="/images/bottle-clear.png"
            alt="Elite Sleeper bottle, dietary supplement, 60 capsules"
            width={505}
            height={971}
          />
        </div>
        </div>
      </section>

      <section className="home-statement">
        <div className="wrap home-statement-grid">
          <div>
            <h2>
              Kept at 2 mg,
              <br />
              so the morning is the point.
            </h2>
            <p>
              Elite Sleeper is not a treatment for a sleep disorder. It is a dietary supplement with a modest melatonin
              dose, for adults who still want the next day.
            </p>
            <a className="btn" href="/order/">
              Order 60 capsules
            </a>
          </div>
          <figure className="home-orb">
            <img src="/images/stock/linen.jpg" alt="A person with a mug in morning light" />
          </figure>
        </div>
      </section>

      <section className="home-mosaic-wrap" id="how">
        <div className="wrap">
          <div className="home-head">
            <p className="home-kicker">The night</p>
            <h2>How the evening should end</h2>
          </div>
          <div className="home-mosaic">
            {tiles.map((tile) => (
              <a className={`home-tile${tile.action ? " home-tile-action" : ""}`} href={tile.href} key={tile.title}>
                <div className="home-tile-copy">
                  <p>{tile.kicker}</p>
                  <h3>{tile.title}</h3>
                  <span>{tile.text}</span>
                  <strong>{tile.cta}</strong>
                </div>
                <img src={tile.image} alt={tile.alt} />
              </a>
            ))}
          </div>
          <ol className="home-steps" id="ritual">
            <li>
              <span>1</span>
              <div>
                <h3>Set the bottle by the bed</h3>
                <p>Adults only. If you take medicine, ask your clinician before the first night.</p>
              </div>
            </li>
            <li>
              <span>2</span>
              <div>
                <h3>Take 1 or 2 capsules</h3>
                <p>The label serving is 2. Start with 1 if you want to see how the morning feels.</p>
              </div>
            </li>
            <li>
              <span>3</span>
              <div>
                <h3>Stop for the night</h3>
                <p>Do not drive or keep working. The capsule is for sleep, not for one more task.</p>
              </div>
            </li>
          </ol>
          <p className="home-warn">
            Adults 18 and older. If you are pregnant, nursing, or taking medicine, talk to your clinician first. Do not
            mix it with alcohol or with another sleep product. It is a dietary supplement, not a treatment for
            insomnia, sleep apnea, or any other condition.
          </p>
        </div>
      </section>

      <section className="home-reviews" id="reviews">
        <div className="wrap">
          <div className="home-head">
            <p className="home-kicker">Customers</p>
            <h2>What people wrote us</h2>
            <p>Notes from New Jersey, Baltimore, and Staten Island. We did not write them, and we do not turn them into a star score.</p>
          </div>
          <div className="home-quotes">
            {reviews.map((review) => (
              <figure className="home-quote" key={review.name}>
                <blockquote>
                  <p>{review.quote}</p>
                </blockquote>
                <figcaption>
                  <strong>{review.name}</strong>
                  <span>{review.detail}</span>
                </figcaption>
              </figure>
            ))}
          </div>
        </div>
      </section>

      <section className="home-formula" id="ingredients">
        <div className="wrap home-formula-grid">
          <div>
            <div className="home-head">
              <p className="home-kicker">The label</p>
              <h2>What is in the bottle</h2>
              <p>
                Seven ingredients in a 2-capsule serving, plus {otherIngredients.join(" and ").toLowerCase()}. The
                capsule is not vegetarian. A longer walk-through is in{" "}
                <a href="/blog/what-is-in-the-bottle/">what&apos;s in the bottle</a>.
              </p>
            </div>
            <ul className="home-ings">
              {ingredients.map((item) => (
                <li key={item.name} className={item.name === "Melatonin" ? "is-lead" : undefined}>
                  <div>
                    <strong>{item.name}</strong>
                    {item.name === "Melatonin" ? <em>Kept low</em> : null}
                    <p>{item.note}</p>
                  </div>
                  <span>{item.amount}</span>
                </li>
              ))}
            </ul>
          </div>
          <aside className="home-formula-side">
            <div className="home-buy">
              <p className="home-kicker">60 capsules · 30 servings</p>
              <p className="home-buy-price">{site.priceLabel}</p>
              <p>About $1.00 a night at the 2-capsule serving. The form does not charge your card.</p>
              <a className="btn home-btn-light" href="/order/">
                Order the bottle
              </a>
              <p className="home-fine">One capsule is half of each amount on the label.</p>
            </div>
            <FactsTable />
          </aside>
        </div>
      </section>

      <section className="home-journal" id="blog">
        <div className="wrap">
          <div className="home-head">
            <p className="home-kicker">Before you order</p>
            <h2>Three notes worth the read</h2>
          </div>
          <div className="home-posts">
            {posts.map((post) => (
              <a className="home-post" href={`/blog/${post.slug}/`} key={post.slug}>
                <img src={post.image} alt={post.imageAlt} />
                <div>
                  <p>{post.label}</p>
                  <h3>{post.title}</h3>
                  <span>Read the note</span>
                </div>
              </a>
            ))}
          </div>
        </div>
      </section>

      <section className="home-faq" id="faq">
        <div className="wrap home-faq-grid">
          <div>
            <p className="home-kicker">Questions</p>
            <h2>Before you send the form</h2>
            <p>Shipping, the morning, and who should skip the bottle.</p>
            <a className="btn" href="/order/">
              Order 60 capsules
            </a>
          </div>
          <FaqList items={faqs} />
        </div>
      </section>

      <section className="home-desk" id="contact">
        <div className="wrap">
          <div className="home-head">
            <p className="home-kicker">Desk</p>
            <h2>A question before the order</h2>
            <p>Write us about a bottle, the formula, or an order that arrived wrong. We read every note.</p>
          </div>
          <div className="home-desk-grid">
            <ContactForm returnTo="/" />
            <aside className="home-desk-side">
              <p className="icon-line">
                <Icon name="mail" />
                <a href={`mailto:${site.email}`}>{site.email}</a>
              </p>
              <p className="icon-line">
                <Icon name="pin" />
                <span>
                  {site.street}
                  <br />
                  {site.city}, {site.region} {site.postal}
                  <br />
                  {site.country}
                </span>
              </p>
              <p>
                To leave the list, use <a href="/opt-out/">opt out</a>.
              </p>
            </aside>
          </div>
        </div>
      </section>

      <section className="home-close">
        <div className="wrap">
          <p className="home-word">Elite Sleeper</p>
          <div className="hero-actions">
            <a className="btn home-btn-light" href="/order/">
              Order the bottle
            </a>
            <a className="btn home-btn-ghost" href="#contact">
              Ask a question
            </a>
          </div>
        </div>
      </section>
    </main>
  );
}
