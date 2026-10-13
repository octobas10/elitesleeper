import type { Metadata } from "next";
import { BlogFrame, OutLink } from "@/components/BlogFrame";
import { Crumbs, FactsTable } from "@/components/Blocks";
import { JsonLd } from "@/components/JsonLd";
import { buildMetadata } from "@/lib/seo";
import { fdaLine, site } from "@/lib/site";

const title = "Elite Sleeper Ingredients, From Melatonin to Lemon Balm";
const description =
  "Elite Sleeper ingredients for a 2-capsule serving: melatonin 2 mg, valerian root 150 mg, GABA, L-tryptophan, chamomile, passion flower, and lemon balm.";
const keyword = "Elite Sleeper ingredients";

export const metadata: Metadata = buildMetadata({
  title: "Elite Sleeper Ingredients",
  description,
  path: "/blog/what-is-in-the-bottle/",
  keywords: [keyword, "melatonin", "valerian root", "GABA", "chamomile", "sleep supplement"],
});

const toc = [
  { id: "serving", label: "The 2-capsule serving" },
  { id: "melatonin", label: "Melatonin, 2 mg" },
  { id: "valerian", label: "Valerian root, 150 mg" },
  { id: "gaba-tryptophan", label: "GABA and L-tryptophan" },
  { id: "herbs", label: "Chamomile, passion flower, lemon balm" },
  { id: "shell", label: "The capsule shell" },
  { id: "label-limits", label: "What the label does not say" },
  { id: "order", label: "Order the same bottle" },
];

export default function BottleArticlePage() {
  const schema = {
    "@context": "https://schema.org",
    "@type": "Article",
    headline: title,
    datePublished: "2026-10-02",
    dateModified: "2026-10-02",
    author: { "@type": "Organization", name: site.name },
    publisher: { "@type": "Organization", name: site.name, url: site.url },
    description,
    keywords: keyword,
    mainEntityOfPage: `${site.url}/blog/what-is-in-the-bottle/`,
  };

  return (
    <BlogFrame returnTo="/blog/what-is-in-the-bottle/" currentSlug="what-is-in-the-bottle" toc={toc}>
      <JsonLd data={schema} />
      <Crumbs
        items={[
          { href: "/", label: "Home" },
          { href: "/blog/", label: "Blog" },
          { label: "Ingredients" },
        ]}
      />
      <p className="meta">October 2, 2026</p>
      <h1>{title}</h1>
      <p>
        Elite Sleeper ingredients are listed for one reason: you should know the dose before you swallow it. The
        bottle holds 60 capsules. The price is {site.priceLabel}. The supplement facts panel is written for a serving
        of 2 capsules, and the bottle contains 30 of those servings. If you take 1 capsule, you get half of each
        amount on this page, and the bottle lasts longer. The short label lives on the{" "}
        <a href="/#ingredients">formula</a>. This note walks through the same numbers with the public sources
        behind each one.
      </p>
      <p>
        Elite Sleeper is a dietary supplement, not a drug. Nothing here says the capsule treats insomnia, sleep apnea,
        or any other condition. The <OutLink href="https://www.fda.gov/food/dietary-supplements">FDA explains that
        difference</OutLink> in plain language: supplements are not approved to diagnose, treat, cure, or prevent
        disease. {fdaLine}
      </p>

      <h2 id="serving">How to read the 2-capsule serving</h2>
      <p>
        Directions say take 1 or 2 capsules before sleep, then go to bed. The facts panel is the 2-capsule column.
        That split trips people up. They read "melatonin 2 mg" and assume one capsule is 2 mg. It is not. Two
        capsules supply the 2 mg. One capsule supplies about 1 mg of melatonin and half of every other amount below.
      </p>
      <p>
        A first bottle is easier to judge at 1 capsule. You can move to the full serving on a later night if you want
        it. The routine around that choice is on{" "}
        <a href="/blog/nighttime-routine/">how to take a sleep capsule</a>. Do not take more than 2 capsules in a
        night, and do not add a separate melatonin product on top. The serving is the serving.
      </p>
      <p>
        At 2 capsules a night, 60 capsules cover 30 nights, about $1.00 a night. At 1 capsule a night, the same bottle
        covers about 60 nights. Pay on secure checkout from the <a href="/order/">order form</a>. Card details never
        touch this site.
      </p>

      <h2 id="melatonin">Melatonin, 2 mg per serving</h2>
      <p>
        Melatonin is the hormone the body already releases in the evening as light drops. The Elite Sleeper serving
        uses 2 mg. We kept that number low on purpose. Higher melatonin doses are a common reason people wake up
        heavy, which fights the "no grogginess in the morning" line on the bottle. The morning side of that choice is
        covered in <a href="/blog/clear-morning/">sleep supplement without morning grogginess</a>.
      </p>
      <p>
        The <OutLink href="https://ods.od.nih.gov/factsheets/Melatonin-HealthProfessional/">NIH Office of Dietary
        Supplements melatonin fact sheet</OutLink> is the reference we point adults to when they want the research
        background, the timing questions, and the interaction notes in one place. It is written for clinicians, and it
        is still the clearest public summary. Read it if you take other medicine. Melatonin can matter for people on
        sedatives, blood pressure drugs, and some other prescriptions. That is a clinician conversation, not a guess
        from a product page.
      </p>
      <p>
        <OutLink href="https://medlineplus.gov/druginfo/natural/940.html">MedlinePlus also keeps a melatonin
        overview</OutLink> in shorter language. Use it if you want the consumer version of the same topic: what people
        take it for, and who should be careful. Neither page is a promise that 2 mg will do the same thing for every
        adult. Bodies differ. The 2 mg figure is the amount in this serving, not a personal prescription.
      </p>

      <h2 id="valerian">Valerian root extract, 150 mg per serving</h2>
      <p>
        Valerian root is an herb people have used for a long time when they want the evening to settle. The serving
        here is 150 mg of valerian root extract, not a cup of tea and not a handful of raw root. Extract on a label
        means the herb has been prepared so a capsule can hold a measured amount. It does not mean the capsule is a
        drug.
      </p>
      <p>
        The <OutLink href="https://ods.od.nih.gov/factsheets/Valerian-HealthProfessional/">NIH valerian fact
        sheet</OutLink> reviews the studies, the doses used in those studies, and the side effects people report,
        including morning drowsiness in some trials that used other doses. That is one more reason our melatonin stays
        at 2 mg and our directions say to start where you can still judge the morning. Valerian can also interact with
        sedatives. If you already take something to sleep, do not stack Elite Sleeper on top of it without asking a
        clinician.
      </p>
      <p>
        <OutLink href="https://www.nccih.nih.gov/health/valerian">NCCIH's valerian page</OutLink> is the shorter public
        summary. Evidence is mixed, and the herb is not a treatment for a sleep disorder. We use it as one part of a
        seven-ingredient capsule, at 150 mg in the 2-capsule serving.
      </p>

      <h2 id="gaba-tryptophan">GABA, 100 mg, and L-tryptophan, 100 mg</h2>
      <h3 id="gaba">GABA, 100 mg</h3>
      <p>
        Gamma-aminobutyric acid, written GABA on most labels, is a compound the nervous system already uses when
        activity quiets down. The serving includes 100 mg. L-tryptophan is an amino acid the body uses when it makes
        serotonin and, from there, melatonin. The serving includes 100 mg of L-tryptophan. These are not herbs. They
        sit on the panel next to the herbs because the formula is a combination, not a single-ingredient melatonin
        pill.
      </p>
      <h3>What the public pages actually say</h3>
      <p>
        The <OutLink href="https://www.nccih.nih.gov/health/safety">NCCIH safety pages</OutLink> are worth reading
        before you assume "more calming compound" means a stronger night. Oral GABA in a capsule is not the same thing
        as the GABA your brain already makes, and public evidence on that oral dose is limited. We still list 100 mg
        because that is what is in the capsule, and you should see the number rather than a vague word like "blend."
      </p>
      <h3 id="tryptophan">L-tryptophan, 100 mg</h3>
      <p>
        L-tryptophan is an amino acid you also get from food. A 100 mg capsule amount is a measured add-on, not a
        meal. People taking antidepressants or other drugs that affect serotonin should not add tryptophan on a whim.
        Ask a clinician. The same rule sits in the <a href="/#faq">questions</a> and on the bottle directions: adults, and
        a conversation first if you are on medicine.
      </p>
      <p>
        Do not confuse this line with 5-HTP. Elite Sleeper uses L-tryptophan at 100 mg. It does not use a separate
        5-HTP ingredient. If another product in your cabinet lists 5-HTP, that is a different bottle. Do not take both
        the same night.
      </p>

      <h2 id="herbs">Chamomile, passion flower, and lemon balm</h2>
      <p>
        Three extracts finish the serving: chamomile 150 mg, passion flower 150 mg, and lemon balm 100 mg. All three
        are plants people have poured as evening tea. In this bottle they are extracts inside a gelatin capsule, so
        the dose is the milligram line, not "a cup." The <a href="/#ingredients">ingredient list</a> uses the same
        figures.
      </p>
      <h3 id="chamomile">Chamomile extract, 150 mg</h3>
      <p>
        Chamomile is the flower in the tea most people already know. The{" "}
        <OutLink href="https://www.nccih.nih.gov/health/chamomile">National Center for Complementary and Integrative
        Health page on chamomile</OutLink> covers the traditional use and the allergy warning. If you are allergic to
        ragweed, chrysanthemums, or related plants, chamomile can be a problem. Skip the bottle and write us if that
        is you. A sleep capsule is not worth a reaction.
      </p>
      <h3 id="passion-flower">Passion flower extract, 150 mg</h3>
      <p>
        Passion flower is a vine traditionally used on nights when the mind is still running. The{" "}
        <OutLink href="https://www.nccih.nih.gov/health/passionflower">NCCIH passionflower page</OutLink> is the short
        public summary. It is not a license to treat anxiety or insomnia with a capsule. It is the reference for what
        the plant is, and for the caution about combining it with sedatives. Our amount is 150 mg of extract per
        2-capsule serving.
      </p>
      <h3 id="lemon-balm">Lemon balm extract, 100 mg</h3>
      <p>
        Lemon balm is a mint-family herb. NCCIH does not give it a standalone fact sheet the way it does for chamomile
        and passion flower. Their <OutLink href="https://www.nccih.nih.gov/health/herbsataglance">Herbs at a Glance
        series</OutLink> is the index for the botanicals they do cover, and the caution is the same: ask before you add
        an herb if you take other medicine. The serving here is 100 mg of extract. The point of listing it separately
        is so you are not buying a proprietary blend that hides the milligrams.
      </p>

      <h2 id="shell">Gelatin and brown rice flour</h2>
      <p>
        The shell is gelatin. Brown rice flour fills out the capsule. That means Elite Sleeper is not a vegetarian or
        vegan capsule. If you need a plant shell, this is the wrong product, and no amount of herbal copy changes the
        gelatin. There is no separate vitamin blend on this label, and there is no melatonin dose hiding past the 2 mg
        already stated.
      </p>
      <p>
        Store the bottle where you will see it at bedtime, away from heat and out of reach of children. The product is
        for adults. A child who finds a sleep capsule is a reason to call Poison Control, not a reason to split a
        dose. The <a href="/#how">directions</a> are adults only, 1 or 2 capsules, then bed.
      </p>

      <h2 id="label-limits">What the label does not say</h2>
      <p>
        You will not find a claim that this bottle cures insomnia or replaces a sleep study. You will find three lines
        we can stand behind as product description: a comprehensive sleep formula, clinically researched ingredients,
        and no grogginess in the morning as the design target. "Clinically researched ingredients" means melatonin,
        valerian, and the other items on this page have public research behind them. It does not mean the FDA approved
        Elite Sleeper as a treatment. The fact sheets linked above are the research trail. The bottle is still a
        supplement.
      </p>
      <p>
        Customer notes are separate from the label. People in New Jersey, Baltimore, and Staten Island wrote us about
        their own nights. Those notes are on the <a href="/#reviews">home page</a>. They are not a clinical trial,
        and we do not turn them into a star rating.
      </p>
      <FactsTable />

      <h2 id="order">Order the bottle with these amounts</h2>
      <p>
        If the milligrams above are what you wanted to see, the bottle on the site is that formula. Sixty capsules,
        {site.priceLabel}, shipped from Forest Hills after we confirm payment. Read the serving as 2 capsules, start
        at 1 if you want a smaller first night, and do not stack it with another sleep product.
      </p>
      <p>
        <a className="btn" href="/order/">
          Order 60 capsules
        </a>
      </p>
    </BlogFrame>
  );
}
