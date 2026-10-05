import type { Metadata } from "next";
import { BlogFrame, OutLink } from "@/components/BlogFrame";
import { Crumbs, Photo } from "@/components/Blocks";
import { JsonLd } from "@/components/JsonLd";
import { buildMetadata } from "@/lib/seo";
import { fdaLine, site } from "@/lib/site";

const title = "How to Take a Sleep Capsule Before Bed";
const description =
  "How to take a sleep capsule: keep one bedtime, dim the last hour, then take 1 or 2 Elite Sleeper capsules and go to bed. No second dose.";
const keyword = "how to take a sleep capsule";

export const metadata: Metadata = buildMetadata({
  title,
  description,
  path: "/blog/nighttime-routine/",
  keywords: [keyword, "Elite Sleeper", "sleep supplement", "bedtime routine", "melatonin capsule"],
});

const toc = [
  { id: "one-bedtime", label: "Keep one bedtime" },
  { id: "light-and-caffeine", label: "Light and caffeine" },
  { id: "take-the-capsule", label: "Take the capsule, then stop" },
  { id: "still-awake", label: "If you are still awake" },
  { id: "who-should-wait", label: "Who should wait" },
  { id: "order", label: "Order the bottle" },
];

export default function NightRoutinePage() {
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
    mainEntityOfPage: `${site.url}/blog/nighttime-routine/`,
  };

  return (
    <BlogFrame returnTo="/blog/nighttime-routine/" currentSlug="nighttime-routine" toc={toc}>
      <JsonLd data={schema} />
      <Crumbs
        items={[
          { href: "/", label: "Home" },
          { href: "/blog/", label: "Blog" },
          { label: "How to take it" },
        ]}
      />
      <p className="meta">October 2, 2026</p>
      <h1>{title}</h1>
      <Photo
        src="/images/stock/morning-bed.jpg"
        alt="A person stretching after waking up"
        caption="One bedtime, then the capsule, then lights out."
      />
      <p>
        Learning how to take a sleep capsule is mostly about the hour before it, not about adding a second product
        when the first one has not had a fair night. Elite Sleeper is a 60-capsule sleep supplement. The directions
        are short: take 1 or 2 capsules, then go to bed. Do not drive. Do not stay up to finish one more task. This
        note is the routine that makes those directions work. The label serving and the dose chart sit in the{" "}
        <a href="/#how">how it works</a> section, and the bottle is {site.priceLabel} on the{" "}
        <a href="/order/">order form</a>.
      </p>
      <p>
        The <OutLink href="https://www.cdc.gov/sleep/about/index.html">CDC sleep pages</OutLink> treat a steady night
        as a health habit, not as a trick. Adults who sleep short nights, or who move their bedtime around by hours,
        feel it the next day. A capsule cannot repair a week of 1 a.m. lights-out followed by a 6 a.m. alarm. If that
        is the pattern, fix the clock first, then judge the bottle.
      </p>

      <h2 id="one-bedtime">Keep one bedtime for the whole week</h2>
      <p>
        Pick a time you can hit on Sunday the same way you hit it on Tuesday. Write it down. A bedtime that slides
        from 10 p.m. to 1 a.m. and back again is a common reason people say a sleep supplement "did nothing." The
        body is still on yesterday's clock. Elite Sleeper does not reset a schedule. It is a capsule for the night
        you already decided to end.
      </p>
      <h3>What a late weekend does</h3>
      <p>
        Two late nights can make Monday feel like jet lag even if you never left town. If you want a clean test of
        the bottle, hold the same bedtime for seven nights before you decide the formula failed. Use the same wake
        time too, within about an hour. Sleeping until noon on Saturday and then expecting a capsule to knock you
        out at 10 p.m. on Sunday is a setup for a restless night.
      </p>
      <h3>Use a wind-down alarm, not only a morning alarm</h3>
      <p>
        Set a second alarm 45 to 60 minutes before bed. That alarm means the work stops. Close the laptop, leave the
        kitchen, and put the bottle where you will see it when you get into bed. People miss the dose because the
        bottle is in another room and the bed already won. The point of the alarm is to start the last hour, not to
        scare you awake.
      </p>
      <p>
        If your work shift changes every few days, do not pretend you have a normal bedtime. Take the capsule only
        on nights when you can give yourself a full night in bed afterward. A capsule before a short nap, or before
        you still have to drive, is the wrong use. The <a href="/#faq">questions</a> repeat that rule because it is the
        one people skip.
      </p>

      <h2 id="light-and-caffeine">Cut the light and the late caffeine</h2>
      <p>
        The <OutLink href="https://www.sleepfoundation.org/sleep-hygiene">Sleep Foundation sleep hygiene guide</OutLink>{" "}
        is blunt about the last hour: dim the room, and do not treat the bed as an office. Bright overhead lights and
        a phone held a foot from your face keep the evening feeling like daytime. You do not need a perfect dark
        house. You need the last hour to be quieter and dimmer than the hour before it.
      </p>
      <h3>Screens</h3>
      <p>
        If you need a screen, drop the brightness and set a stopping point before you take the capsule. A show you
        start after the capsule is how people end up awake at 1 a.m. with an empty glass and a half-watched episode.
        Charge the phone outside the bedroom if you can. If it has to stay in the room, put it face down and out of
        reach so you are not negotiating with it after the dose.
      </p>
      <h3>Coffee, tea, soda, and late meals</h3>
      <p>
        Caffeine after lunch is a frequent reason the night feels immune to a capsule. Coffee, strong tea, many
        sodas, and some headache powders all count. If you drink caffeine late, move the last cup earlier for a week
        before you judge Elite Sleeper. A heavy meal right at bedtime can do the same job in a different way: you are
        full, warm, and still digesting, so the night is uncomfortable even if you are tired. Finish dinner early
        enough that you are not lying down on a large plate of food.
      </p>
      <p>
        Alcohol is not a partner for this capsule. Do not use a drink to "help" the dose along. The{" "}
        <a href="/blog/clear-morning/">note on morning grogginess</a> covers why stacking sedating habits is how
        people wake up heavy. One change at a time. If you want to know whether the bottle fits you, take it on a
        night without alcohol.
      </p>

      <h2 id="take-the-capsule">Take the capsule, then stop for the night</h2>
      <p>
        When the wind-down hour is actually over, take Elite Sleeper with water. The label serving is 2 capsules.
        Directions allow 1 or 2. If this is your first bottle, start with 1 capsule so you can see the morning. You
        can move to 2 on a later night if you want the full serving. A full serving is what the{" "}
        <a href="/#ingredients">ingredient amounts</a> describe: valerian root extract 150 mg, GABA 100 mg, melatonin
        2 mg, L-tryptophan 100 mg, chamomile extract 150 mg, passion flower extract 150 mg, and lemon balm extract
        100 mg.
      </p>
      <h3>Where and when</h3>
      <p>
        Swallow the capsule in the bedroom, or on the way there, not at the kitchen table while you are still
        packing lunch for tomorrow. Then lights out. The National Heart, Lung, and Blood Institute's page on{" "}
        <OutLink href="https://www.nhlbi.nih.gov/health/sleep-deprivation">sleep deprivation</OutLink> is a reminder
        that short nights add up. The capsule is for a night you are willing to finish. It is not a way to stay
        sharp for another hour of email.
      </p>
      <h3>Do not drive, and do not "just finish this"</h3>
      <p>
        After you take it, do not drive, do not cook on a hot stove, and do not decide you will lie down "in a
        minute" after one more chore. Melatonin and the herbs are in the capsule so the evening can end. Using that
        hour to keep working is how people end up neither rested nor clear. If you still have something that must be
        done, do it before the capsule, not after.
      </p>
      <p>
        Keep water by the bed if you want it. You do not need a special drink. You do not need a second sleep
        product. Elite Sleeper is already a combination of melatonin, valerian root, GABA, and four herbs. Adding
        another melatonin gummy on top is not the plan, and it is a direct way to fight the{" "}
        <a href="/blog/clear-morning/">clear morning</a> the label is written for.
      </p>

      <h2 id="still-awake">If you are still awake</h2>
      <p>
        Give the dark room a little time. If you are wide awake well later, get up, keep the lights low, and do
        something dull until you are sleepy again. A paper book you have already read, or a quiet chair in a dim
        room, is enough. Do not turn the overhead lights back on and do not open a bright screen "for five minutes."
      </p>
      <p>
        Do not take another round of capsules. The serving is already set for the night. More is not a stronger
        routine. It is a different dose than the one you can judge in the morning. If this happens several nights in
        a row, look at the bedtime, the caffeine, and the screen first. Then write the desk from the form on this
        page, or read the <a href="/#faq">questions</a> about dose. If your nights have been bad for a long time, or
        someone has told you that you stop breathing in your sleep, a supplement is not the fix. Talk to a clinician.
      </p>
      <p>
        A bad night is also not a reason to change five things at once. Hold the bedtime, hold the 1-capsule test,
        and change the late coffee or the late screen, not all of them plus the dose on the same evening. You cannot
        tell what helped if everything moved.
      </p>

      <h2 id="who-should-wait">Who should wait before the first capsule</h2>
      <p>
        Elite Sleeper is for adults 18 and older. If you are pregnant, nursing, or already taking medicine, talk to
        your clinician before you start. Melatonin, valerian, and GABA can interact with some drugs. The{" "}
        <OutLink href="https://www.fda.gov/food/dietary-supplements">FDA's dietary supplement pages</OutLink> are the
        right place to remember what a supplement is: it is not a drug, and it is not approved to treat insomnia,
        sleep apnea, or any other condition.
      </p>
      <p>
        {fdaLine} Read the <a href="/#how">directions</a> the same night you open the bottle. If you want the
        longer ingredient walk-through before you buy, use{" "}
        <a href="/blog/what-is-in-the-bottle/">what is in the bottle</a>. Customer notes from people who already use
        it are on the <a href="/#reviews">home page</a>.
      </p>

      <h2 id="order">Order the bottle when the routine is real</h2>
      <p>
        A sleep capsule works best on a night you were already willing to end. Set the bedtime, dim the last hour,
        take 1 or 2 Elite Sleeper capsules, and stop. One bottle is {site.priceLabel} for 60 capsules, about $1.00 a
        night at the 2-capsule serving. The order form does not charge your card. We email you to confirm the count,
        the shipping cost, and payment, then ship from Forest Hills, NY.
      </p>
      <p>
        <a className="btn" href="/order/">
          Order Elite Sleeper
        </a>
      </p>
    </BlogFrame>
  );
}
