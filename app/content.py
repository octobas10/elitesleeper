"""Site content, SEO helpers, and form options — ported from Astro data modules."""

from __future__ import annotations

import json
from pathlib import Path
from typing import Any

_DATA_DIR = Path(__file__).resolve().parent / "data"

# ---------------------------------------------------------------------------
# site.ts
# ---------------------------------------------------------------------------

SITE = {
    "name": "Higher Learning Marketers",
    "tagline": "Education Lead Generation",
    "url": "https://higherlearningmarketers.com",
    "description": (
        "U.S.-focused education lead generation connecting universities "
        "with motivated students."
    ),
    "logo": "/favicon.svg",
    "logoPng": "/favicon/site-icon-512.png",
}

CONTACT = {
    "email": "support@higherlearningmarketers.com",
    "phoneDisplay": "+1 (718) 655-2573",
    "phoneHref": "tel:+17186552573",
    "addressLine1": "138-07 82nd Drive",
    "addressLine2": "Briarwood, NY 11435",
    "addressDisplay": "138-07 82nd Drive, Briarwood, NY 11435",
    "addressHref": (
        "https://maps.google.com/?q=138-07+82nd+Drive,+Briarwood,+NY+11435"
    ),
    "mapEmbedUrl": (
        "https://maps.google.com/maps?q=138-07+82nd+Drive,+Briarwood,+NY+11435"
        "&z=15&output=embed"
    ),
    "hours": "Monday – Friday, 9:00 AM – 6:00 PM ET",
}

NAV = [
    {"label": "About Us", "href": "/about-us/"},
    {"label": "Services", "href": "/services/"},
    {"label": "Affiliations", "href": "/affiliations/"},
    {"label": "Blog", "href": "/blog/"},
    {"label": "Contact Us", "href": "/contact-us/"},
]

MEDIA = {
    "hero_home_poster": (
        "https://images.unsplash.com/photo-1523240795612-9a054b0db644"
        "?w=1920&h=1080&fit=crop"
    ),
    "page_about": (
        "https://images.unsplash.com/photo-1522071820081-009f0129c71c"
        "?w=1920&h=800&fit=crop"
    ),
    "page_services": (
        "https://images.unsplash.com/photo-1551288049-bebda4e38f71"
        "?w=1920&h=800&fit=crop"
    ),
    "page_affiliations": (
        "https://images.unsplash.com/photo-1523050854058-8df87810e8fa"
        "?w=1920&h=800&fit=crop"
    ),
    "page_contact": (
        "https://images.unsplash.com/photo-1497366216548-37526070297c"
        "?w=1920&h=800&fit=crop"
    ),
    "partner_story": (
        "https://images.unsplash.com/photo-1600880292203-757bb62b4baf"
        "?w=800&h=600&fit=crop"
    ),
    "partner_ygo": (
        "https://images.unsplash.com/photo-1529156069898-49953e39b3ac"
        "?w=800&h=600&fit=crop"
    ),
    "team_victor": (
        "https://images.unsplash.com/photo-1560250097-0b93528c311a"
        "?w=600&h=400&fit=crop&crop=face"
    ),
    "team_robert": (
        "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7"
        "?w=600&h=400&fit=crop&crop=face"
    ),
}

# ---------------------------------------------------------------------------
# form-options.ts
# ---------------------------------------------------------------------------

US_STATES = {
    "AL": "Alabama",
    "AK": "Alaska",
    "AZ": "Arizona",
    "AR": "Arkansas",
    "CA": "California",
    "CO": "Colorado",
    "CT": "Connecticut",
    "DE": "Delaware",
    "DC": "District of Columbia",
    "FL": "Florida",
    "GA": "Georgia",
    "HI": "Hawaii",
    "ID": "Idaho",
    "IL": "Illinois",
    "IN": "Indiana",
    "IA": "Iowa",
    "KS": "Kansas",
    "KY": "Kentucky",
    "LA": "Louisiana",
    "ME": "Maine",
    "MD": "Maryland",
    "MA": "Massachusetts",
    "MI": "Michigan",
    "MN": "Minnesota",
    "MS": "Mississippi",
    "MO": "Missouri",
    "MT": "Montana",
    "NE": "Nebraska",
    "NV": "Nevada",
    "NH": "New Hampshire",
    "NJ": "New Jersey",
    "NM": "New Mexico",
    "NY": "New York",
    "NC": "North Carolina",
    "ND": "North Dakota",
    "OH": "Ohio",
    "OK": "Oklahoma",
    "OR": "Oregon",
    "PA": "Pennsylvania",
    "RI": "Rhode Island",
    "SC": "South Carolina",
    "SD": "South Dakota",
    "TN": "Tennessee",
    "TX": "Texas",
    "UT": "Utah",
    "VT": "Vermont",
    "VA": "Virginia",
    "WA": "Washington",
    "WV": "West Virginia",
    "WI": "Wisconsin",
    "WY": "Wyoming",
}

CA_PROVINCES = {
    "AB": "Alberta",
    "BC": "British Columbia",
    "MB": "Manitoba",
    "NB": "New Brunswick",
    "NL": "Newfoundland and Labrador",
    "NS": "Nova Scotia",
    "NT": "Northwest Territories",
    "NU": "Nunavut",
    "ON": "Ontario",
    "PE": "Prince Edward Island",
    "QC": "Quebec",
    "SK": "Saskatchewan",
    "YT": "Yukon",
}

STATE_OPTIONS = {**US_STATES, **CA_PROVINCES}

GRAD_YEARS = list(range(2026, 2026 - 21, -1))

TCPA_TEXT_STUDENT = (
    "I agree to be contacted by phone, email, and text message regarding "
    "educational opportunities. I understand consent is not required to apply."
)

# ---------------------------------------------------------------------------
# services-catalog.ts
# ---------------------------------------------------------------------------

SERVICE_CATALOG: dict[str, dict[str, Any]] = {
    "lead-generation": {
        "slug": "lead-generation",
        "title": "Lead Generation",
        "headline": "Student Lead Generation for U.S. Universities",
        "icon": "users",
        "excerpt": (
            "TCPA-compliant, consent-based student inquiries delivered in real "
            "time to U.S. university and college admissions teams."
        ),
        "tags": "TCPA Compliant, Real-time, Custom Filtering, Scalable",
        "intro": (
            "Our lead generation programs connect your admissions team with "
            "students who have already expressed interest in programs like yours. "
            "Every inquiry is verified, consent-based, and delivered in real time "
            "so your counselors can respond while intent is highest."
        ),
        "audience": (
            "Ideal for universities and colleges that need predictable inquiry "
            "volume, faster counselor follow-up, and transparent lead quality-not "
            "just raw form fills."
        ),
        "highlights": [
            "Custom audience targeting by program, geography, and degree level",
            "TCPA-compliant consent capture on every lead",
            "Real-time delivery to your CRM or admissions inbox",
            "Quality filters and duplicate suppression",
            "Weekly volume and conversion reporting",
        ],
        "metrics": [
            {"value": "Real-time", "label": "Lead delivery to your CRM"},
            {"value": "TCPA", "label": "Compliant consent on every lead"},
            {"value": "Weekly", "label": "Volume and quality reporting"},
        ],
    },
    "brand-awareness": {
        "slug": "brand-awareness",
        "title": "Brand Awareness",
        "headline": "Education Brand Awareness for U.S. Institutions",
        "icon": "globe",
        "excerpt": (
            "Multi-channel campaigns that keep your U.S. institution visible to "
            "students actively researching degree and certificate options."
        ),
        "tags": "Display Ads, Landing Pages, Retargeting, Segmentation",
        "intro": (
            "Brand awareness campaigns keep your institution visible across the "
            "channels students use when they start exploring schools. We combine "
            "creative, media, and landing experiences so your programs stay top "
            "of mind before students are ready to apply."
        ),
        "audience": (
            "Best for institutions launching new programs, entering new markets, "
            "or rebuilding visibility in competitive categories where name "
            "recognition drives inquiry quality."
        ),
        "highlights": [
            "Audience research and competitive positioning",
            "Display, social, and retargeting creative",
            "Program-specific landing pages",
            "Geo and demographic segmentation",
            "Reach, frequency, and engagement reporting",
        ],
        "metrics": [],
    },
    "multi-channel-marketing": {
        "slug": "multi-channel-marketing",
        "title": "Multi-Channel Marketing",
        "headline": "Multi-Channel Enrollment Marketing for Universities",
        "icon": "megaphone",
        "excerpt": (
            "Integrated enrollment campaigns across search, social, email, and "
            "partner networks for U.S. universities and colleges."
        ),
        "tags": "SEO, Social, Email, PPC",
        "intro": (
            "Students rarely convert on a single touchpoint. Our multi-channel "
            "approach coordinates search, social, email, and partner placements "
            "so each interaction reinforces the next-and moves prospects closer "
            "to enrollment."
        ),
        "audience": (
            "Designed for enrollment teams that want one coordinated plan instead "
            "of disconnected vendors-and need clear attribution across the full "
            "student journey."
        ),
        "highlights": [
            "Cross-channel campaign strategy and calendar",
            "Paid search and social management",
            "Email nurture sequences for inquiry follow-up",
            "Partner network distribution",
            "Unified reporting across channels",
        ],
        "metrics": [],
    },
    "analytics-reporting": {
        "slug": "analytics-reporting",
        "title": "Analytics & Reporting",
        "headline": "Enrollment Marketing Analytics and Reporting",
        "icon": "chart",
        "excerpt": (
            "Real-time dashboards and enrollment reporting so U.S. marketing and "
            "admissions leaders track cost per lead and cost per start."
        ),
        "tags": "Dashboards, Cost Tracking, Attribution, Reviews",
        "intro": (
            "Enrollment marketing only improves when you can see what is working. "
            "We provide dashboards and reporting cadences that tie spend, "
            "inquiries, and downstream enrollment outcomes back to the campaigns "
            "that drove them."
        ),
        "audience": (
            "Perfect for marketing and enrollment leaders who need transparent "
            "ROI, faster budget decisions, and a single source of truth for "
            "campaign performance."
        ),
        "highlights": [
            "Live performance dashboards with source-level drill-down",
            "Cost-per-inquiry and cost-per-enrollment tracking",
            "Multi-touch source and channel attribution",
            "Weekly optimization reviews with your team",
            "Executive-ready summary reports",
        ],
        "metrics": [
            {"value": "Live", "label": "Performance dashboards"},
            {"value": "CPI / CPE", "label": "Cost tracking built in"},
            {"value": "Weekly", "label": "Optimization reviews"},
        ],
    },
    "partner-network": {
        "slug": "partner-network",
        "title": "Partner Network",
        "headline": "Education Partner Network for Student Reach",
        "icon": "network",
        "excerpt": (
            "Access to 560+ education-focused websites and platforms that reach "
            "U.S. students at every stage of their decision journey."
        ),
        "tags": "560+ Sites, U.S. Focus, Targeting, Exclusive",
        "intro": (
            "Our partner network extends your reach beyond owned channels. We "
            "place your programs on education-focused sites where students "
            "compare schools, explore career paths, and request information from "
            "trusted sources."
        ),
        "audience": (
            "Ideal when you need scale quickly, want incremental volume without "
            "increasing internal workload, or are expanding into new student "
            "segments and geographies."
        ),
        "highlights": [
            "Placement across 560+ education properties",
            "Program-level targeting and exclusivity options",
            "International reach in 164 countries",
            "Compliance review for every partner placement",
            "Network-level performance reporting",
        ],
        "metrics": [
            {"value": "560+", "label": "Education partner sites"},
            {"value": "164", "label": "Countries reached"},
            {"value": "100%", "label": "Compliance-reviewed placements"},
        ],
    },
    "enrollment-funnel": {
        "slug": "enrollment-funnel",
        "title": "Enrollment Funnel",
        "headline": "Enrollment Funnel Optimization for Universities",
        "icon": "target",
        "excerpt": (
            "End-to-end funnel optimization from first impression to application "
            "submitted for U.S. enrollment teams."
        ),
        "tags": "Landing Pages, A/B Testing, Forms, CRO",
        "intro": (
            "Every step between first click and submitted application affects "
            "enrollment. We audit and optimize landing pages, forms, messaging, "
            "and follow-up sequences so more interested students complete the "
            "actions that matter."
        ),
        "audience": (
            "Best for teams generating traffic but struggling to convert "
            "inquiries into applications-or anyone preparing for a peak "
            "enrollment season who needs higher conversion without increasing "
            "media spend."
        ),
        "highlights": [
            "Funnel audit across ads, landing pages, and forms",
            "A/B testing on headlines, CTAs, and form fields",
            "Mobile-first landing page improvements",
            "Conversion rate optimization sprints",
            "Before/after performance benchmarks",
        ],
        "metrics": [],
    },
}

SERVICE_SLUGS = list(SERVICE_CATALOG.keys())

# ---------------------------------------------------------------------------
# content.ts
# ---------------------------------------------------------------------------

_BLOG_DATES = [
    "2026-06-18",
    "2026-06-16",
    "2026-06-14",
    "2026-06-12",
    "2026-06-10",
    "2026-06-08",
    "2026-06-06",
    "2026-06-04",
    "2026-06-02",
    "2026-05-30",
    "2026-05-28",
    "2026-05-26",
    "2026-05-24",
    "2026-05-22",
    "2026-05-20",
    "2026-05-18",
]


def _load_json(name: str) -> Any:
    path = _DATA_DIR / name
    with path.open(encoding="utf-8") as fh:
        return json.load(fh)


_blog_posts_raw: list[dict[str, Any]] = _load_json("blog-posts.json")
BLOG_POSTS: list[dict[str, Any]] = [
    {**post, "date": _BLOG_DATES[i] if i < len(_BLOG_DATES) else "2026-06-01"}
    for i, post in enumerate(_blog_posts_raw)
]

SEO_DATA: dict[str, Any] = _load_json("seo.json")
SEO_LINKS: dict[str, Any] = _load_json("seo-links.json")

DEFAULT_STATS = [
    {"number": 164, "prefix": "", "suffix": "+", "label": "Countries Reached"},
    {"number": 560, "prefix": "", "suffix": "+", "label": "Partner Websites"},
    {"number": 10, "prefix": "", "suffix": "M+", "label": "Leads Generated"},
    {"number": 98, "prefix": "", "suffix": "%", "label": "Client Satisfaction"},
]

NETWORK_STATS = [
    {
        "number": 40,
        "prefix": "",
        "suffix": "%",
        "label": "Avg. Increase in Qualified Leads",
    },
    {
        "number": 35,
        "prefix": "",
        "suffix": "%",
        "label": "Lower Cost Per Enrollment",
    },
    {
        "number": 24,
        "prefix": "",
        "suffix": "h",
        "label": "Average Lead Response Time",
    },
    {
        "number": 10,
        "prefix": "",
        "suffix": "M+",
        "label": "Students Connected",
    },
]

INSTITUTIONS = [
    {
        "badge": "Featured Partner",
        "title": "Berkeley College",
        "description": (
            "Career-focused education with campuses in New Jersey and New York, "
            "plus a robust online program offering degrees in business, health, "
            "and justice studies."
        ),
        "imageUrl": (
            "https://images.unsplash.com/photo-1562774053-701939374585"
            "?w=800&h=450&fit=crop"
        ),
        "meta": ["4 Campuses + Online", "90+ Years"],
    },
    {
        "badge": "Technology Focus",
        "title": "ECPI University",
        "description": (
            "Accelerated programs in technology, health science, nursing, and "
            "business with 15+ campus locations and online options across the US."
        ),
        "imageUrl": (
            "https://images.unsplash.com/photo-1517245386807-bb43f82c33c4"
            "?w=800&h=450&fit=crop"
        ),
        "meta": ["15+ Campuses", "1966 Founded"],
    },
    {
        "badge": "Health & Business",
        "title": "South University",
        "description": (
            "Comprehensive programs in nursing, business, criminal justice, and "
            "healthcare with campus and online options for working professionals."
        ),
        "imageUrl": (
            "https://images.unsplash.com/photo-1576091160399-112ba8d25d1d"
            "?w=800&h=450&fit=crop"
        ),
        "meta": ["7 Locations", "1899 Founded"],
    },
]

PROGRAMS = [
    "Nursing (RN, BSN, MSN)",
    "Cybersecurity",
    "Business Administration",
    "Criminal Justice",
    "Healthcare Management",
    "Software Development",
    "Marketing Communications",
    "Information Technology",
]

HOME_PROCESS_STEPS = [
    {
        "number": "01",
        "title": "Submit Your Info",
        "description": (
            "Fill out our simple form with your details, preferences, and goals. "
            "Takes less than 2 minutes."
        ),
        "ctaText": "Start the Form",
        "ctaUrl": "#form",
    },
    {
        "number": "02",
        "title": "Get Matched",
        "description": (
            "Our system connects you with the best-fit programs or partnership "
            "opportunities based on your profile."
        ),
        "ctaText": "Browse Programs",
        "ctaUrl": "#programs",
    },
    {
        "number": "03",
        "title": "Start Your Journey",
        "description": (
            "Connect directly with admissions teams or our partnership "
            "specialists to move forward."
        ),
        "ctaText": "Get Started",
        "ctaUrl": "#form",
    },
]

SERVICES_PROCESS_STEPS = [
    {
        "number": "01",
        "title": "Discovery & Strategy",
        "description": (
            "We analyze your programs, target demographics, and enrollment goals "
            "to build a custom campaign strategy."
        ),
        "ctaText": "Talk to Our Team",
        "ctaUrl": "/contact-us/#form",
    },
    {
        "number": "02",
        "title": "Campaign Launch",
        "description": (
            "Multi-channel campaigns go live across our partner network, search, "
            "social, and display platforms."
        ),
    },
    {
        "number": "03",
        "title": "Lead Delivery",
        "description": (
            "Qualified leads flow directly to your CRM or admissions team in "
            "real-time, fully verified and consented."
        ),
    },
    {
        "number": "04",
        "title": "Optimize & Scale",
        "description": (
            "Weekly performance reviews and continuous optimization to improve "
            "quality and increase volume."
        ),
        "ctaText": "Request a Quote",
        "ctaUrl": "/contact-us/#form",
    },
]

TESTIMONIALS = [
    {
        "quote": (
            "Higher Learning Marketers transformed our enrollment numbers. We "
            "saw a 40% increase in qualified leads within the first quarter."
        ),
        "author": "Michael Torres",
        "meta": "Director of Admissions, ECPI University",
        "photoUrl": (
            "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e"
            "?w=96&h=96&fit=crop&crop=face"
        ),
    },
    {
        "quote": (
            "The quality of leads is exceptional. Every student inquiry is "
            "pre-qualified and genuinely interested in our programs."
        ),
        "author": "Sarah Chen",
        "meta": "VP Marketing, South University",
        "photoUrl": (
            "https://images.unsplash.com/photo-1580489944761-15a19d654956"
            "?w=96&h=96&fit=crop&crop=face"
        ),
    },
    {
        "quote": (
            "As a student, I found the perfect nursing program through Higher "
            "Learning Marketers. The process was smooth and I had options from "
            "multiple top schools."
        ),
        "author": "Amanda Rodriguez",
        "meta": "BSN Student, Berkeley College",
        "photoUrl": (
            "https://images.unsplash.com/photo-1534528741775-53994a69daeb"
            "?w=96&h=96&fit=crop&crop=face"
        ),
    },
]

SERVICE_CARDS = [
    {
        "title": "Lead Generation",
        "description": (
            "High-intent, pre-qualified student inquiries delivered directly to "
            "your admissions team. Every lead is verified, consented, and matched "
            "to your programs."
        ),
        "tags": "TCPA Compliant, Real-time, Custom Filtering, Scalable",
        "link": "/services/lead-generation/",
    },
    {
        "title": "Brand Awareness",
        "description": (
            "Multi-channel campaigns that put your institution in front of "
            "students actively researching degree and certificate options in the U.S."
        ),
        "tags": "Display Ads, Landing Pages, Retargeting, Segmentation",
        "link": "/services/brand-awareness/",
    },
    {
        "title": "Multi-Channel Marketing",
        "description": (
            "Integrated campaigns across search, social, email, and partner "
            "networks to maximize reach and enrollment conversions for U.S. "
            "institutions."
        ),
        "tags": "SEO, Social, Email, PPC",
        "link": "/services/multi-channel-marketing/",
    },
    {
        "title": "Analytics & Reporting",
        "description": (
            "Real-time dashboards and detailed reporting so enrollment leaders "
            "track cost per lead, cost per start, and campaign ROI."
        ),
        "tags": "Dashboards, Cost Tracking, Attribution, Reviews",
        "link": "/services/analytics-reporting/",
    },
    {
        "title": "Partner Network",
        "description": (
            "Education-focused websites and platforms that reach students at "
            "every stage of their decision journey across the United States."
        ),
        "tags": "560+ Sites, U.S. Focus, Targeting, Exclusive",
        "link": "/services/partner-network/",
    },
    {
        "title": "Enrollment Funnel",
        "description": (
            "End-to-end funnel optimization from first impression to application "
            "submitted. We optimize every touchpoint for conversion."
        ),
        "tags": "Landing Pages, A/B Testing, Forms, CRO",
        "link": "/services/enrollment-funnel/",
    },
]

ABOUT_VALUES = [
    {
        "title": "Integrity First",
        "description": (
            "We are TCPA and GDPR compliant. Every lead is verified, consented, "
            "and delivered with full transparency."
        ),
    },
    {
        "title": "Quality Leads",
        "description": (
            "We do not chase volume. Every student inquiry is pre-qualified and "
            "genuinely interested in advancing their education."
        ),
    },
    {
        "title": "Partnership",
        "description": (
            "We work alongside universities as an extension of their admissions "
            "team, not just another vendor."
        ),
    },
    {
        "title": "Innovation",
        "description": (
            "From AI-driven matching to real-time analytics, we constantly evolve "
            "our technology to stay ahead."
        ),
    },
]

TIMELINE = [
    {
        "year": "2014",
        "title": "The Beginning",
        "description": (
            "Higher Learning Marketers is founded in New York with a focus on "
            "connecting local students with trade schools and community colleges."
        ),
    },
    {
        "year": "2016",
        "title": "First Major Partnership",
        "description": (
            "Signs exclusive lead generation agreement with Berkeley College, "
            "establishing the foundation for long-term institutional partnerships."
        ),
    },
    {
        "year": "2018",
        "title": "Going National",
        "description": (
            "Expands partner network to include ECPI University, South "
            "University, and Abilene Christian University. Reaches 1 million "
            "leads generated."
        ),
    },
    {
        "year": "2020",
        "title": "International Expansion",
        "description": (
            "Partners with CDI College in Canada. Platform now serves students "
            "across North America with 50+ partner websites."
        ),
    },
    {
        "year": "2023",
        "title": "10 Million Leads",
        "description": (
            "Crosses the 10 million lead milestone. Launches advanced AI matching "
            "engine and real-time analytics dashboard for partners."
        ),
    },
    {
        "year": "2026",
        "title": "Today & Beyond",
        "description": (
            "Operating in 164 countries with 560+ partner websites. Continuously "
            "innovating to connect the next generation of students with their "
            "ideal programs."
        ),
    },
]

LEADERSHIP = [
    {
        "name": "Victor Daniel",
        "role": "President",
        "bio": (
            "Victor leads HLM's education data and lead generation strategy, "
            "drawing on decades of performance marketing experience across 560+ "
            "partner websites in 164 countries. A Stony Brook University graduate, "
            "he focuses on connecting accredited institutions with motivated "
            "prospective students across the United States."
        ),
        "photoUrl": MEDIA["team_victor"],
    },
    {
        "name": "Robert Intelisano",
        "role": "Director",
        "bio": (
            "Robert shapes enrollment campaigns and partner outreach for "
            "college-bound audiences, with a background in marketing from Lehigh "
            "University. He is especially focused on helping students understand "
            "financial aid pathways while helping partner institutions improve "
            "enrollment outcomes."
        ),
        "photoUrl": MEDIA["team_robert"],
    },
]

PARTNERS = [
    {
        "initials": "YGO",
        "title": "Youth Guidance Organization",
        "description": (
            "Community-based youth mentorship and education guidance programs "
            "across the United States."
        ),
        "meta": "Strategic Partner",
    },
    {
        "initials": "BC",
        "title": "Berkeley College",
        "description": (
            "Featured institutional partner with direct application integration "
            "and dedicated campaign support."
        ),
        "meta": "Featured Partner",
    },
    {
        "initials": "EC",
        "title": "ECPI University",
        "description": (
            "Technology and health sciences focused university with accelerated "
            "degree programs."
        ),
        "meta": "University Partner",
    },
    {
        "initials": "SU",
        "title": "South University",
        "description": (
            "Comprehensive health, business, and legal studies programs with "
            "campus and online options."
        ),
        "meta": "University Partner",
    },
    {
        "initials": "AC",
        "title": "Abilene Christian University",
        "description": (
            "Faith-based online education programs with strong emphasis on "
            "leadership and service."
        ),
        "meta": "University Partner",
    },
    {
        "initials": "CD",
        "title": "CDI College",
        "description": (
            "Canadian career college with campuses across British Columbia, "
            "Alberta, Ontario, and Manitoba."
        ),
        "meta": "Canadian Partner",
    },
]

AFFILIATION_BENEFITS = [
    {
        "title": "Verified Lead Quality",
        "description": (
            "Every student inquiry is pre-qualified, consented, and matched to "
            "programs that fit their goals."
        ),
    },
    {
        "title": "Global Reach",
        "description": (
            "Access our network of 560+ partner websites reaching students in "
            "164 countries."
        ),
    },
    {
        "title": "Technology Integration",
        "description": (
            "Seamless API connections, real-time dashboards, and CRM integrations "
            "that fit your workflow."
        ),
    },
    {
        "title": "Dedicated Support",
        "description": (
            "A dedicated partnership manager, weekly strategy calls, and "
            "responsive support team invested in your success."
        ),
    },
]

PRIVACY_SECTIONS = [
    {
        "title": "Scope of This Policy",
        "content": (
            'This Privacy Policy describes how Higher Learning Marketers ("HLM," '
            '"we," "us," or "our") collects, uses, shares, and protects personal '
            "information when you visit higherlearningmarketers.com, submit an "
            "inquiry form, communicate with us, or otherwise use our services. We "
            "may update this policy from time to time. Material changes will be "
            'posted on this page with a revised "Last updated" date. We encourage '
            "you to review this policy regularly."
        ),
    },
    {
        "title": "Information We Collect",
        "content": (
            "We collect information you provide directly, such as your name, "
            "email address, telephone number, mailing address, date of birth, "
            "age, education history, program interests, and any other details you "
            "submit through our forms or correspondence. We also collect "
            "information automatically when you use our site, including IP "
            "address, browser type, device identifiers, referring and exit pages, "
            "operating system, timestamps, clickstream data, and cookie-related "
            "identifiers. Some information is collected through cookies, pixels, "
            "and similar technologies as described below."
        ),
    },
    {
        "title": "How We Use Your Information",
        "content": (
            "We use personal information to provide our education marketing and "
            "lead generation services; match prospective students with accredited "
            "institutions and programs; respond to inquiries; communicate about "
            "programs, services, and offers you may request or that relate to "
            "your stated interests; improve our website and campaigns; analyze "
            "performance and audience trends; prevent fraud; comply with law; and "
            "enforce our agreements. We may contact you by email, telephone, text "
            "message (SMS/MMS), or other channels permitted by law and your "
            "consent preferences."
        ),
    },
    {
        "title": "How We Share Information",
        "content": (
            "We share personal information with accredited universities, colleges, "
            "and education program providers when you request information about "
            "their programs or otherwise consent to be contacted. We also share "
            "information with service providers that help us operate our website, "
            "deliver leads, host data, analyze traffic, and support marketing - "
            "under contractual confidentiality and security obligations. We may "
            "share or sell information to third parties engaged in education "
            "marketing, advertising, publishing, or related industries where "
            "permitted by law and consistent with the consent you provide at the "
            "point of collection. We may disclose information if required by law, "
            "to protect rights and safety, or in connection with a business "
            "transaction such as a merger or acquisition."
        ),
    },
    {
        "title": "TCPA Consent and Telemarketing",
        "content": (
            "By submitting your information through our forms or otherwise using "
            "our services, you acknowledge that your submission may constitute an "
            "inquiry or application for purposes of federal and state "
            "telemarketing laws, including the Telephone Consumer Protection Act "
            "(TCPA). You expressly consent to be contacted by HLM, our partner "
            "institutions, and authorized third parties by telephone (including "
            "autodialed, prerecorded, or artificial voice calls), text message, "
            "email, or other means at the number and address you provide - even "
            "if your number is listed on a state or federal Do-Not-Call registry. "
            "Consent is not a condition of purchase. You may revoke consent or "
            'opt out of marketing communications as described in "Your Rights and '
            'Opt-Out Choices."'
        ),
    },
    {
        "title": "Cookies and Tracking Technologies",
        "content": (
            "We use cookies, web beacons, pixels, and similar technologies to "
            "remember preferences, measure site usage, deliver relevant content, "
            "and evaluate advertising effectiveness. Third-party advertising and "
            "analytics partners may also use tracking technologies on our site. "
            'You can manage cookies through your browser settings. Because '
            'industry standards for "Do Not Track" browser signals are not '
            "uniform, our site may not respond to all such signals in the same "
            "way across browsers and devices."
        ),
    },
    {
        "title": "Your Rights and Opt-Out Choices",
        "content": (
            "Depending on where you live, you may have rights to access, correct, "
            "delete, or obtain a copy of personal information we maintain about "
            "you, and to opt out of certain sharing for cross-context behavioral "
            "advertising. To opt out of marketing emails, use the unsubscribe "
            "link in our messages. To opt out of telemarketing or revise consent "
            "preferences, contact us using the information below. We may retain "
            "certain information as required by law, for fraud prevention, or in "
            "backup systems for a limited period after processing your request."
        ),
    },
    {
        "title": "California Privacy Rights",
        "content": (
            "California residents may request information about categories of "
            "personal information we have disclosed to third parties for those "
            "parties' direct marketing purposes, subject to California Civil Code "
            "Section 1798.83. California Consumer Privacy Act (CCPA) / California "
            "Privacy Rights Act (CPRA) residents may also have rights to know, "
            "delete, correct, and opt out of the sale or sharing of personal "
            "information. To submit a request, email "
            "support@higherlearningmarketers.com with the subject line "
            '"California Privacy Request." We will verify requests as required by '
            "law. We do not knowingly sell personal information of consumers we "
            "know are under 16 without appropriate consent."
        ),
    },
    {
        "title": "Data Security and Retention",
        "content": (
            "We implement reasonable administrative, technical, and organizational "
            "safeguards designed to protect personal information. No method of "
            "transmission or storage is completely secure. We retain information "
            "for as long as needed to provide services, meet legal obligations, "
            "resolve disputes, and enforce agreements, then delete or de-identify "
            "it where practicable."
        ),
    },
    {
        "title": "Children's Privacy",
        "content": (
            "Our services are directed to prospective college and career students "
            "and institutional partners, not to children under 13. We do not "
            "knowingly collect personal information from children under 13. If "
            "you believe we have collected such information, contact us and we "
            "will take steps to delete it."
        ),
    },
]

# Snake_case aliases for templates / imports
site = SITE
contact = CONTACT
nav = NAV
media = MEDIA
blog_posts = BLOG_POSTS
default_stats = DEFAULT_STATS
network_stats = NETWORK_STATS
institutions = INSTITUTIONS
programs = PROGRAMS
home_process_steps = HOME_PROCESS_STEPS
services_process_steps = SERVICES_PROCESS_STEPS
testimonials = TESTIMONIALS
service_cards = SERVICE_CARDS
about_values = ABOUT_VALUES
timeline = TIMELINE
leadership = LEADERSHIP
partners = PARTNERS
affiliation_benefits = AFFILIATION_BENEFITS
privacy_sections = PRIVACY_SECTIONS
service_catalog = SERVICE_CATALOG
state_options = STATE_OPTIONS
grad_years = GRAD_YEARS
tcpa_text_student = TCPA_TEXT_STUDENT


# ---------------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------------

def get_page_seo(slug: str) -> dict[str, str]:
    pages = SEO_DATA.get("pages") or {}
    return pages.get(slug) or {
        "title": SITE["name"],
        "description": SITE["description"],
    }


def get_page_faqs(slug: str) -> list[dict[str, str]]:
    faqs = SEO_DATA.get("faqs") or {}
    return list(faqs.get(slug) or [])


def get_page_intro(slug: str) -> str:
    intros = SEO_DATA.get("intros") or {}
    return str(intros.get(slug) or "")


def get_blog_post(slug: str) -> dict[str, Any] | None:
    for post in BLOG_POSTS:
        if post.get("slug") == slug:
            return post
    return None


def get_service(slug: str) -> dict[str, Any] | None:
    return SERVICE_CATALOG.get(slug)
