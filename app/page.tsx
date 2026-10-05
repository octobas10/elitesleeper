import type { Metadata } from "next";
import { FactsTable, FaqList, ProductShot } from "@/components/Blocks";
import { ContactForm } from "@/components/Forms";
import { ReviewSlider } from "@/components/ReviewSlider";
import { Icon } from "@/components/Icons";
import { JsonLd } from "@/components/JsonLd";
import { buildMetadata } from "@/lib/seo";
import { faqs, fdaLine, ingredients, otherIngredients, posts, site } from "@/lib/site";

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

export default function HomePage() {
  return (
    <main id="main">
      <JsonLd data={productSchema} />
      <JsonLd data={faqSchema} />

      <section className="hero">
        <div className="wrap hero-grid">
          <div className="hero-copy">
            <p className="eyebrow">Dietary supplement · 60 capsules</p>
            <h1>
              Sleep the night. <em>Wake up clear.</em>
            </h1>
            <p>
              Elite Sleeper is a nighttime capsule: melatonin, valerian root, GABA, and four herbs people have used
              to wind down. One bottle is {site.priceLabel}. Take it, go to bed, and get up without the heavy morning.
            </p>
            <p className="price-line">
              {site.priceLabel} <span>about $1.00 a night</span>
            </p>
            <div className="hero-actions">
              <a className="btn" href="/order/">
                Order the bottle
              </a>
              <a className="btn btn-ghost" href="#ingredients">
                See the formula
              </a>
            </div>
            <ul className="tick-list">
              <li>
                <Icon name="moon" />
                <span>1 or 2 capsules, then lights out. Do not drive after you take it.</span>
              </li>
              <li>
                <Icon name="sun" />
                <span>2 mg of melatonin, so the morning is the point of the formula.</span>
              </li>
              <li>
                <Icon name="truck" />
                <span>Ships from Forest Hills, NY after we confirm payment by email.</span>
              </li>
            </ul>
            <p className="fine">{fdaLine}</p>
          </div>
          <ProductShot />
        </div>
      </section>

      <section className="proof" aria-label="Bottle at a glance">
        <div className="wrap proof-row">
          <div>
            <strong>60</strong>
            <span>capsules in the bottle</span>
          </div>
          <div>
            <strong>30</strong>
            <span>nights at 2 capsules</span>
          </div>
          <div>
            <strong>$1</strong>
            <span>about a night</span>
          </div>
          <div>
            <strong>2 mg</strong>
            <span>melatonin per serving</span>
          </div>
        </div>
      </section>

      <section className="band band-paper" id="bottle">
        <div className="wrap media-split">
          <figure className="media-frame">
            <img src="/images/stock/night-bed.jpg" alt="A person sleeping under a light comforter" />
          </figure>
          <div>
            <p className="eyebrow">The bottle</p>
            <h2>A straightforward capsule at bedtime</h2>
            <p>
              Prescription sleep drugs are one path. They can also leave people foggy the next day, which is why a
              lot of adults look for something simpler. Elite Sleeper is not a treatment for a sleep disorder. It is
              a dietary supplement for adults who want one capsule when the night should end.
            </p>
            <p>
              The <a href="#reviews">notes</a> on this page are from customers who wrote us.
            </p>
            <a className="btn" href="/order/">
              Order 60 capsules
            </a>
          </div>
        </div>
      </section>

      <section className="band">
        <div className="wrap">
          <div className="section-head">
            <p className="eyebrow">Why this bottle</p>
            <h2>Built for the night, and for the morning after</h2>
          </div>
          <div className="card-grid">
            <article className="card">
              <Icon name="capsule" />
              <h3>One formula, seven ingredients</h3>
              <p>Valerian, GABA, melatonin, L-tryptophan, chamomile, passion flower, and lemon balm.</p>
            </article>
            <article className="card">
              <Icon name="sun" />
              <h3>A morning you can use</h3>
              <p>Melatonin stays at 2 mg in the 2-capsule serving. The bottle is meant to leave the next day clear.</p>
            </article>
            <article className="card">
              <Icon name="bed" />
              <h3>Then the evening is over</h3>
              <p>Take 1 or 2 capsules and go to bed. Do not drive, and do not stay up to finish one more task.</p>
            </article>
          </div>
        </div>
      </section>

      <section className="band" id="how">
        <div className="wrap media-split media-split-reverse">
          <div>
            <p className="eyebrow">Directions</p>
            <h2>How to take it</h2>
            <p>Adults only. Swallow 1 or 2 capsules with water, then the evening is over.</p>
            <ol className="steps steps-list">
              <li className="step">
                <p className="step-no">1</p>
                <h3>Set the bottle by the bed</h3>
                <p>If you take medicine, ask your clinician before the first night.</p>
              </li>
              <li className="step">
                <p className="step-no">2</p>
                <h3>Take 1 or 2 capsules</h3>
                <p>The label serving is 2. Start with 1 if you want to see how the morning feels.</p>
              </li>
              <li className="step">
                <p className="step-no">3</p>
                <h3>Stop for the night</h3>
                <p>Do not drive or keep working. The capsule is for sleep, not for one more task.</p>
              </li>
            </ol>
            <p className="warn">
              Adults 18 and older. If you are pregnant, nursing, or taking medicine, talk to your clinician first. Do
              not mix it with alcohol or with another sleep product. It is a dietary supplement, not a treatment for
              insomnia, sleep apnea, or any other condition.
            </p>
          </div>
          <figure className="media-frame">
            <img src="/images/stock/morning-bed.jpg" alt="A person stretching after waking up" />
          </figure>
        </div>
      </section>

      <section className="band band-paper" id="ingredients">
        <div className="wrap">
          <div className="section-head">
            <p className="eyebrow">Product details</p>
            <h2>What is in the bottle</h2>
            <p>
              The supplement facts panel is the label: seven ingredients for a 2-capsule serving, plus{" "}
              {otherIngredients.join(" and ").toLowerCase()}. The capsule is not vegetarian. A longer walk-through is
              in <a href="/blog/what-is-in-the-bottle/">what&apos;s in the bottle</a>.
            </p>
          </div>
          <div className="ingredient-grid">
            {ingredients.map((item) => (
              <article className="card" key={item.name}>
                <p className="eyebrow">{item.amount}</p>
                <h3>{item.name}</h3>
                <p>{item.note}</p>
              </article>
            ))}
          </div>
          <p className="fine section-gap">
            Other ingredients: {otherIngredients.join(", ").toLowerCase()}. The shell is gelatin, so this is not a
            vegetarian or vegan capsule. If you take 1 capsule, you get half of each amount on the label.
          </p>
          <div className="split split-top">
            <FactsTable />
            <div className="panel buy-note">
              <p className="eyebrow">60 capsules · 30 servings</p>
              <p className="offer-price">{site.priceLabel}</p>
              <p>About $1.00 a night at the 2-capsule serving. The form does not charge your card.</p>
              <a className="btn btn-wide" href="/order/">
                Order the bottle
              </a>
            </div>
          </div>
        </div>
      </section>

      <section className="band" id="reviews">
        <div className="wrap">
          <div className="review-layout">
            <div className="section-head">
              <p className="eyebrow">Customers</p>
              <h2>What people wrote us</h2>
              <p>
                Notes from New Jersey, Baltimore, and Staten Island. We did not write them, and we do not turn them
                into a star score.
              </p>
            </div>
            <ReviewSlider />
          </div>
        </div>
      </section>

      <section className="band band-paper" id="blog">
        <div className="wrap">
          <div className="section-head">
            <p className="eyebrow">From the desk</p>
            <h2>Read this before you order</h2>
            <p>Three notes. Each one opens on its own page if you want the longer version.</p>
          </div>
          <div className="post-grid">
            {posts.map((post) => (
              <a className="post-card" href={`/blog/${post.slug}/`} key={post.slug}>
                <img src={post.image} alt={post.imageAlt} />
                <h3>{post.title}</h3>
                <p>{post.description}</p>
                <span>Read the note</span>
              </a>
            ))}
          </div>
        </div>
      </section>

      <section className="band" id="faq">
        <div className="wrap split">
          <div>
            <p className="eyebrow">Questions</p>
            <h2>Before you send the form</h2>
            <p>
              Shipping, the morning, and who should skip the bottle. A question that is not here goes to the{" "}
              <a href="#contact">desk</a>.
            </p>
            <a className="btn" href="/order/">
              Order 60 capsules
            </a>
          </div>
          <FaqList items={faqs} />
        </div>
      </section>

      <section className="band band-paper" id="contact">
        <div className="wrap">
          <div className="section-head">
            <p className="eyebrow">Desk</p>
            <h2>Contact us</h2>
            <p>Questions about an order, the formula, or a bottle that arrived wrong. We read every note.</p>
          </div>
          <div className="layout-2">
            <ContactForm returnTo="/" />
            <aside className="stack">
              <div className="panel stack">
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
              </div>
              <div className="panel buy-card">
                <p className="eyebrow">Already know the dose?</p>
                <p className="offer-price">{site.priceLabel}</p>
                <p>The order form is faster than a question. Nothing is charged when you send it.</p>
                <a className="btn btn-wide" href="/order/">
                  Order the bottle
                </a>
              </div>
            </aside>
          </div>
        </div>
      </section>

      <section className="cta-band">
        <div className="wrap cta-inner">
          <div>
            <h2>One bottle. {site.priceLabel}. A clear morning.</h2>
            <p>
              Send the order form. We email you to confirm the count, shipping, and payment, then ship from{" "}
              {site.city}, {site.region}. Nothing is charged when you send the form.
            </p>
          </div>
          <div className="cta-actions">
            <a className="btn" href="/order/">
              Order the bottle
            </a>
            <a className="btn btn-ghost" href="#contact">
              Ask a question
            </a>
          </div>
        </div>
      </section>
    </main>
  );
}
