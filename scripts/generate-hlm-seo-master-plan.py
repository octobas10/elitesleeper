#!/usr/bin/env python3
"""Generate HLM US SEO Master Plan Excel workbook."""

from openpyxl import Workbook
from openpyxl.styles import Alignment, Font, PatternFill, Border, Side
from openpyxl.utils import get_column_letter

OUTPUT = "/home/buildco/highereducation_eilte/HLM-SEO-Master-Plan.xlsx"
DOMAIN = "https://higherlearningmarketers.com"

HEADER_FILL = PatternFill("solid", fgColor="002536")
HEADER_FONT = Font(color="FFFFFF", bold=True)
SUBHEADER_FILL = PatternFill("solid", fgColor="C9A96E")
SUBHEADER_FONT = Font(color="002536", bold=True)
DONE_FILL = PatternFill("solid", fgColor="D4EDDA")
PENDING_FILL = PatternFill("solid", fgColor="FFF3CD")
PARTIAL_FILL = PatternFill("solid", fgColor="D1ECF1")
THIN = Side(style="thin", color="CCCCCC")


def style_header_row(ws, row=1):
    for cell in ws[row]:
        cell.fill = HEADER_FILL
        cell.font = HEADER_FONT
        cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)


def auto_width(ws, max_width=60):
    for col in ws.columns:
        letter = get_column_letter(col[0].column)
        length = max(len(str(c.value or "")) for c in col)
        ws.column_dimensions[letter].width = min(max(length + 2, 10), max_width)


def add_rows(ws, rows, start=2):
    for i, row in enumerate(rows, start=start):
        for j, val in enumerate(row, start=1):
            ws.cell(row=i, column=j, value=val)


def build_overview(ws):
    ws.title = "Overview"
    ws.merge_cells("A1:D1")
    ws["A1"] = "Higher Learning Marketers - US SEO Master Plan"
    ws["A1"].font = Font(size=16, bold=True, color="002536")

    sections = [
        ("SEO GOAL", ""),
        (
            "Primary objective",
            "When someone in the United States searches education lead generation, university student leads, "
            "or program-finding queries, Google should surface HLM commercial pages (Services, Contact), "
            "blog guides (university + student), and trust pages (Home, About).",
        ),
        ("Geographic focus", "95% United States - US English, TCPA, NCES, FAFSA, universities/colleges context"),
        ("Conversion targets", "/services/ (university inquiry) and /contact-us/ (form submission)"),
        ("Technical stack", "WordPress + hlm-seo plugin + Slim SEO + Kadence theme + sitemap.xml (Slim SEO)"),
        ("", ""),
        ("WHO YOU RANK FOR (2 AUDIENCES)", ""),
        (
            "Audience 1 - Universities (B2B)",
            "Enrollment leaders, marketing directors, admissions teams at US universities and colleges",
        ),
        ("What they search", "education lead generation, student lead generation USA, TCPA compliant leads, cost per enrollment, how to buy education leads"),
        ("Money pages", "/services/, /contact-us/, /about-us/, /affiliations/, service detail pages"),
        ("Blog role", "Commercial posts in university-marketing, us-university-leads, hlm-lead-generation clusters"),
        (
            "Audience 2 - Students (B2C)",
            "Working adults, career changers, nursing prospects, traditional students researching programs",
        ),
        ("What they search", "choose nursing program, FAFSA mistakes, apply to college while working, find college program"),
        ("Money pages", "/contact-us/ (advisor inquiry)"),
        ("Blog role", "Informational posts in student-pathways cluster with Contact CTA"),
        ("", ""),
        ("PHASE SUMMARY (90-DAY PLAN)", ""),
        ("Phase 1 - Technical (Week 1-2)", "DONE - Slim SEO, meta, schema, sitemap, US schema. Pending: GSC verify + submit sitemap.xml"),
        ("Phase 2 - On-page (Week 2-4)", "DONE - Keyword H1s, US intros, FAQs, internal links, CTAs on money pages + 6 service details + affiliations + blog CTAs"),
        ("Phase 3 - Content (Month 2-6)", "IN PROGRESS - 16 posts live; 12-post calendar Jul-Dec 2026 planned (2/month)"),
        ("Phase 4 - Authority (Month 3-6)", "NOT STARTED - Directories, partner backlinks, LinkedIn, guest posts (10-20 quality US links)"),
        ("Phase 5 - Measure (ongoing)", "ONGOING - GSC + GA4: impressions, clicks to Services/Contact, US position, organic form submissions"),
        ("", ""),
        ("REALISTIC TIMELINE", ""),
        ("1-2 months", "Indexed; long-tail impressions in GSC"),
        ("3-4 months", "Page 2-3 for niche US terms"),
        ("6-12 months", "Page 1 possible for branded + long-tail; head terms need more content + links"),
    ]

    r = 3
    for label, value in sections:
        ws.cell(row=r, column=1, value=label).font = Font(bold=bool(value and label.isupper() and not label.startswith("Audience")))
        if label in ("SEO GOAL", "WHO YOU RANK FOR (2 AUDIENCES)", "PHASE SUMMARY (90-DAY PLAN)", "REALISTIC TIMELINE"):
            ws.cell(row=r, column=1).fill = SUBHEADER_FILL
            ws.cell(row=r, column=1).font = SUBHEADER_FONT
        ws.cell(row=r, column=2, value=value)
        ws.merge_cells(start_row=r, start_column=2, end_row=r, end_column=4)
        r += 1

    ws.column_dimensions["A"].width = 34
    ws.column_dimensions["B"].width = 90


def build_90_day(ws):
    ws.title = "90-Day Plan"
    headers = [
        "Week",
        "Phase",
        "Task",
        "Owner",
        "Status",
        "Done?",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("1", "1", "Activate Slim SEO + hlm-seo plugin", "Dev", "Complete", "Y", "Live on site"),
        ("1", "1", "Meta titles/descriptions per page (registry)", "Dev", "Complete", "Y", "hlm-seo-bridge registry"),
        ("1", "1", "JSON-LD Organization, Service, FAQ, WebPage", "Dev", "Complete", "Y", ""),
        ("1", "1", "Sitemap live at /sitemap.xml (Slim SEO)", "Dev", "Complete", "Y", "NOT wp-sitemap.xml"),
        ("1", "1", "US schema areaServed + NY address", "Dev", "Complete", "Y", ""),
        ("1-2", "1", "PageSpeed / mobile check on money pages", "Marketing", "Pending", "N", "Run PageSpeed Insights on live domain"),
        ("2", "1", "Google Search Console - verify property", "Marketing", "Pending", "N", "URL prefix on live domain"),
        ("2", "1", "GSC - submit sitemap.xml only", "Marketing", "Pending", "N", "One sitemap; avoid duplicates"),
        ("2", "1", "GSC - request indexing for money pages", "Marketing", "Pending", "N", "Home, Services, Contact, About, Affiliations, 6 service details, Blog"),
        ("2", "1", "Google Business Profile (Briarwood NY)", "Marketing", "Optional", "N", "Local trust signal"),
        ("2-3", "2", "Keyword H1s on all money pages", "Dev", "Complete", "Y", "Home, Services, Contact, About, Affiliations"),
        ("2-3", "2", "US keyword intro blocks after heroes", "Dev", "Complete", "Y", "Includes service detail pages"),
        ("2-3", "2", "Internal links 3-5+ per money page", "Dev", "Complete", "Y", "Link map in hlm-seo-bridge"),
        ("2-3", "2", "FAQs + FAQ schema on money pages", "Dev", "Complete", "Y", "Home 4, Services 6, Contact 5, About 4, Affiliations 5, each service 4"),
        ("2-3", "2", "Blog cluster CTAs (Services vs Contact)", "Dev", "Complete", "Y", "Commercial -> Services; student -> Contact"),
        ("2-3", "2", "Service detail pages Phase 2 structure", "Dev", "Complete", "Y", "6 child pages under /services/"),
        ("3-4", "2", "Expand Tier 2 blogs to 800-1200 words", "Dev", "Complete", "Y", "16 posts published"),
        ("3-4", "2", "Tier 3 US commercial cluster (6 posts)", "Dev", "Complete", "Y", "us-university-leads cluster"),
        ("3-4", "2", "Featured images on all posts", "Dev", "Complete", "Y", "SVG variants attached"),
        ("4-8", "3", "Publish 2 US blogs per month", "Content", "In Progress", "N", "Jul-Dec 2026 calendar (12 posts)"),
        ("4-8", "3", "Request GSC indexing per new post", "Marketing", "Pending", "N", "Once per publish"),
        ("6-12", "4", "Education marketing directory listings", "Marketing", "Not Started", "N", "Target 5-10 listings"),
        ("6-12", "4", "Partner/affiliation backlinks to HLM", "Partnerships", "Not Started", "N", "YGO + university partners"),
        ("6-12", "4", "LinkedIn + entity mentions", "Marketing", "Not Started", "N", ""),
        ("6-12", "4", "Guest posts on higher ed blogs", "Marketing", "Not Started", "N", "Goal: 10-20 quality US links"),
        ("Ongoing", "5", "GSC weekly review (US filter, 28 days)", "Marketing", "Ongoing", "N", "15 min/week"),
        ("Ongoing", "5", "Track clicks to /services/ and /contact-us/", "Marketing", "Ongoing", "N", "Primary KPI"),
        ("Ongoing", "5", "GA4 link + organic form attribution", "Marketing", "Pending", "N", "Associate GSC + GA4"),
        ("Ongoing", "5", "Monthly keyword review - double down on winners", "Marketing", "Ongoing", "N", ""),
    ]
    add_rows(ws, rows)

    for row in ws.iter_rows(min_row=2, max_row=ws.max_row):
        status = str(row[4].value or "")
        if status == "Complete":
            row[4].fill = DONE_FILL
            row[5].value = "Y"
        elif status in ("Pending", "Not Started"):
            row[4].fill = PENDING_FILL
        elif status in ("In Progress", "Ongoing"):
            row[4].fill = PARTIAL_FILL

    auto_width(ws)


def build_phases(ws):
    ws.title = "Phases 1-5"
    headers = ["Phase", "Timeframe", "Focus", "Key deliverables", "Status", "Checklist"]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        (
            "Phase 1",
            "Week 1-2",
            "Technical foundation",
            "Slim SEO, hlm-seo, meta registry, JSON-LD, sitemap.xml, US schema, mobile speed",
            "Mostly Done",
            "[x] Plugin stack [x] Page meta [x] Schema [x] Sitemap [ ] GSC verify [ ] GSC sitemap submit [ ] PageSpeed audit",
        ),
        (
            "Phase 2",
            "Week 2-4",
            "On-page SEO",
            "Keyword H1s, US intro copy, internal links, FAQs, CTAs on all money pages + service details + blog",
            "Done",
            "[x] Money page H1s [x] SEO intros [x] Internal links [x] FAQs [x] Blog CTAs [x] Affiliations [x] Service details [x] Dash normalization",
        ),
        (
            "Phase 3",
            "Month 2-6",
            "Content publishing",
            "2 posts/month US-focused; 800-1200 words; cluster wiring; featured images; GSC per post",
            "In Progress",
            "[x] 16 posts live [ ] 12 calendar posts (Jul-Dec 2026) [ ] GSC indexing workflow [ ] Monthly publish cadence",
        ),
        (
            "Phase 4",
            "Month 3-6",
            "Off-site authority",
            "Directories, partner backlinks, LinkedIn, guest posts - 10-20 quality US education links",
            "Not Started",
            "[ ] Directory listings [ ] Affiliation backlinks [ ] LinkedIn presence [ ] Guest posts [ ] Press mentions",
        ),
        (
            "Phase 5",
            "Ongoing",
            "Measurement + optimization",
            "GSC impressions/clicks, US rankings, Services/Contact traffic, organic form submissions, monthly keyword review",
            "Ongoing",
            "[ ] GSC weekly [ ] GA4 linked [ ] US performance filter [ ] CPL/CPE tracking [ ] Content refresh from GSC data",
        ),
    ]
    add_rows(ws, rows)
    auto_width(ws, 70)


def build_keyword_map(ws):
    ws.title = "Keyword Map US"
    headers = [
        "Tier",
        "Type",
        "URL path",
        "Page/Post title",
        "Focus keyword",
        "H1 (live)",
        "Audience",
        "Intent",
        "Priority",
        "Status",
    ]
    ws.append(headers)
    style_header_row(ws)

    tier1 = [
        ("1", "Money page", "/", "Home", "education lead generation", "Connect Universities With Motivated U.S. Students", "Both", "Commercial", "Highest", "Live"),
        ("1", "Money page", "/services/", "Services", "education lead generation services", "Education Lead Generation Services", "University", "Commercial", "Highest", "Live"),
        ("1", "Money page", "/contact-us/", "Contact", "contact education lead generation", "Contact an Education Lead Generation Company", "Both", "Conversion", "Highest", "Live"),
        ("1", "Money page", "/about-us/", "About", "education marketing company", "Education Marketing Company for U.S. Institutions", "University", "Trust", "High", "Live"),
        ("1", "Money page", "/affiliations/", "Affiliations", "education partnerships", "Education Partnerships and Strategic Affiliations", "University", "Trust", "High", "Live"),
        ("1", "Service detail", "/services/lead-generation/", "Lead Generation", "student lead generation", "Student Lead Generation for U.S. Universities", "University", "Commercial", "High", "Live"),
        ("1", "Service detail", "/services/brand-awareness/", "Brand Awareness", "education brand awareness", "Education Brand Awareness for U.S. Institutions", "University", "Commercial", "High", "Live"),
        ("1", "Service detail", "/services/multi-channel-marketing/", "Multi-Channel Marketing", "multi-channel enrollment marketing", "Multi-Channel Enrollment Marketing for Universities", "University", "Commercial", "High", "Live"),
        ("1", "Service detail", "/services/analytics-reporting/", "Analytics & Reporting", "enrollment marketing analytics", "Enrollment Marketing Analytics and Reporting", "University", "Commercial", "Medium", "Live"),
        ("1", "Service detail", "/services/partner-network/", "Partner Network", "education partner network", "Education Partner Network for Student Reach", "University", "Commercial", "Medium", "Live"),
        ("1", "Service detail", "/services/enrollment-funnel/", "Enrollment Funnel", "enrollment funnel optimization", "Enrollment Funnel Optimization for Universities", "University", "Commercial", "Medium", "Live"),
        ("1", "Hub", "/blog/", "Blog", "higher education blog", "Higher Education Insights", "Both", "Informational", "High", "Live"),
    ]

    tier2 = [
        ("2", "Blog", "/how-to-choose-the-right-nursing-program/", "How to Choose the Right Nursing Program", "choose nursing program", "Post title H1", "Student", "Informational", "High", "Live"),
        ("2", "Blog", "/berkeley-college-application-tips-for-working-adults/", "Berkeley College Application Tips for Working Adults", "berkeley college application", "Post title H1", "Student", "Informational", "Medium", "Live"),
        ("2", "Blog", "/online-vs-campus-nursing-programs/", "Online vs Campus Nursing Programs", "online vs campus nursing program", "Post title H1", "Student", "Informational", "Medium", "Live"),
        ("2", "Blog", "/5-fafsa-mistakes-to-avoid/", "5 FAFSA Mistakes to Avoid", "FAFSA mistakes", "Post title H1", "Student", "Informational", "High", "Live"),
        ("2", "Blog", "/what-universities-should-know-about-lead-quality/", "What Universities Should Know About Lead Quality", "education lead quality", "Post title H1", "University", "Commercial", "High", "Live"),
        ("2", "Blog", "/enrollment-marketing-roi-for-universities/", "Enrollment Marketing ROI", "enrollment marketing ROI", "Post title H1", "University", "Commercial", "High", "Live"),
        ("2", "Blog", "/how-hlm-helps-universities-fill-programs/", "How HLM Helps Universities Fill Programs", "education lead generation for universities", "Post title H1", "University", "Commercial", "Highest", "Live"),
        ("2", "Blog", "/how-students-find-the-right-program-with-hlm/", "How Students Find the Right Program", "find the right college program", "Post title H1", "Student", "Informational", "High", "Live"),
        ("2", "Blog", "/premium-education-lead-generation-hlm/", "Premium Education Lead Generation", "premium education lead generation", "Post title H1", "University", "Commercial", "High", "Live"),
        ("2", "Blog", "/consent-based-education-leads-hlm/", "Consent-Based Education Leads", "consent-based education leads", "Post title H1", "University", "Commercial", "High", "Live"),
    ]

    tier3 = [
        ("3", "Blog", "/student-lead-generation-universities-usa/", "Student Lead Generation for Universities in the USA", "student lead generation for universities USA", "Post title H1", "University", "Commercial", "Highest", "Live"),
        ("3", "Blog", "/nursing-program-leads-for-colleges/", "Nursing Program Leads for Colleges", "nursing program leads for colleges", "Post title H1", "University", "Commercial", "High", "Live"),
        ("3", "Blog", "/tcpa-compliant-education-leads/", "TCPA Compliant Education Leads", "TCPA compliant education leads", "Post title H1", "University", "Commercial", "High", "Live"),
        ("3", "Blog", "/adult-learner-enrollment-marketing/", "Adult Learner Enrollment Marketing", "adult learner enrollment marketing", "Post title H1", "University", "Commercial", "High", "Live"),
        ("3", "Blog", "/cost-per-enrollment-vs-cost-per-lead/", "Cost Per Enrollment vs Cost Per Lead", "cost per enrollment vs cost per lead", "Post title H1", "University", "Commercial", "High", "Live"),
        ("3", "Blog", "/how-to-buy-education-leads/", "How to Buy Education Leads", "how to buy education leads", "Post title H1", "University", "Commercial", "Highest", "Live"),
    ]

    long_tail = [
        ("Long-tail", "Target phrase", "", "consent based education leads USA", "", "", "University", "Commercial", "Win first", "Content exists"),
        ("Long-tail", "Target phrase", "", "university nursing program lead generation", "", "", "University", "Commercial", "Win first", "Content exists"),
        ("Long-tail", "Target phrase", "", "premium education lead generation services", "", "", "University", "Commercial", "Win first", "Content exists"),
        ("Long-tail", "Target phrase", "", "find nursing program USA", "", "", "Student", "Informational", "Win first", "Content exists"),
    ]

    add_rows(ws, tier1 + tier2 + tier3 + long_tail)

    for row in ws.iter_rows(min_row=2, max_row=ws.max_row):
        if str(row[9].value) == "Live":
            row[9].fill = DONE_FILL

    auto_width(ws)


def build_pages(ws):
    ws.title = "Active Pages"
    headers = [
        "Page",
        "URL",
        "Active?",
        "Phase 2 H1",
        "SEO intro",
        "Internal links",
        "FAQs",
        "CTA",
        "Meta title set",
        "GSC indexed",
        "Request indexing",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    pages = [
        ("Home", f"{DOMAIN}/", "Yes", "Y", "Y", "5", "4", "Y", "Y", "", "", "Tier 1 hub"),
        ("Services", f"{DOMAIN}/services/", "Yes", "Y", "Y", "8", "6", "Y", "Y", "", "", "Tier 1 pillar"),
        ("Contact", f"{DOMAIN}/contact-us/", "Yes", "Y", "Y", "2", "5", "Y", "Y", "", "", "Could add more internal links"),
        ("About", f"{DOMAIN}/about-us/", "Yes", "Y", "Y", "3", "4", "Y", "Y", "", "", "Team updated Jun 2026"),
        ("Affiliations", f"{DOMAIN}/affiliations/", "Yes", "Y", "Y", "6", "5", "Y", "Y", "", "", "Phase 2 complete"),
        ("Blog", f"{DOMAIN}/blog/", "Yes", "Y", "N/A", "5", "0", "Y", "Y", "", "", "Archive hub"),
        ("Privacy Policy", f"{DOMAIN}/privacy-policy/", "Yes", "Y", "N/A", "0", "0", "N/A", "Y", "", "", "Legal - updated Jun 2026"),
        ("Lead Generation", f"{DOMAIN}/services/lead-generation/", "Yes", "Y", "Y", "6", "4", "Y", "Y", "", "", "Service detail"),
        ("Brand Awareness", f"{DOMAIN}/services/brand-awareness/", "Yes", "Y", "Y", "6", "4", "Y", "Y", "", "", "Service detail"),
        ("Multi-Channel Marketing", f"{DOMAIN}/services/multi-channel-marketing/", "Yes", "Y", "Y", "6", "4", "Y", "Y", "", "", "Service detail"),
        ("Analytics & Reporting", f"{DOMAIN}/services/analytics-reporting/", "Yes", "Y", "Y", "6", "4", "Y", "Y", "", "", "Service detail"),
        ("Partner Network", f"{DOMAIN}/services/partner-network/", "Yes", "Y", "Y", "6", "4", "Y", "Y", "", "", "Service detail"),
        ("Enrollment Funnel", f"{DOMAIN}/services/enrollment-funnel/", "Yes", "Y", "Y", "6", "4", "Y", "Y", "", "", "Service detail"),
    ]

    add_rows(ws, pages)

    checklist_note = "GSC columns: fill after live domain verification"
    ws.cell(row=ws.max_row + 2, column=1, value=checklist_note)

    for row in ws.iter_rows(min_row=2, max_row=14):
        row[2].fill = DONE_FILL

    auto_width(ws)


def build_blog_published(ws):
    ws.title = "Blog Posts Live"
    headers = [
        "Tier",
        "Publish status",
        "Title",
        "Slug",
        "Full URL",
        "Cluster",
        "Audience",
        "Focus keyword",
        "~Words",
        "Featured image",
        "800+ words",
        "Internal links",
        "Outbound .gov/.edu",
        "Cluster CTA",
        "GSC indexed",
        "Request indexing",
    ]
    ws.append(headers)
    style_header_row(ws)

    posts = [
        ("2", "Live", "How to Choose the Right Nursing Program", "how-to-choose-the-right-nursing-program", "student-pathways", "Student", "choose nursing program", "900", "student", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "Berkeley College Application Tips for Working Adults", "berkeley-college-application-tips-for-working-adults", "student-pathways", "Student", "berkeley college application", "900", "student", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "Online vs Campus Nursing Programs", "online-vs-campus-nursing-programs", "student-pathways", "Student", "online vs campus nursing program", "900", "student", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "5 FAFSA Mistakes to Avoid", "5-fafsa-mistakes-to-avoid", "student-pathways", "Student", "FAFSA mistakes", "897", "student", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "What Universities Should Know About Lead Quality", "what-universities-should-know-about-lead-quality", "university-marketing", "University", "education lead quality", "950", "university", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "Enrollment Marketing ROI for Universities", "enrollment-marketing-roi-for-universities", "university-marketing", "University", "enrollment marketing ROI", "950", "university", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "How HLM Helps Universities Fill Programs", "how-hlm-helps-universities-fill-programs", "hlm-lead-generation", "University", "education lead generation for universities", "1000", "hlm", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "How Students Find the Right Program With HLM", "how-students-find-the-right-program-with-hlm", "hlm-lead-generation", "Student", "find the right college program", "950", "student", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "Premium Education Lead Generation", "premium-education-lead-generation-hlm", "hlm-lead-generation", "University", "premium education lead generation", "950", "hlm", "Y", "Y", "Y", "Y", "", ""),
        ("2", "Live", "Consent-Based Education Leads", "consent-based-education-leads-hlm", "hlm-lead-generation", "University", "consent-based education leads", "950", "compliance", "Y", "Y", "Y", "Y", "", ""),
        ("3", "Live", "Student Lead Generation for Universities in the USA", "student-lead-generation-universities-usa", "us-university-leads", "University", "student lead generation for universities USA", "854", "university", "Y", "Y", "Y", "Y", "", ""),
        ("3", "Live", "Nursing Program Leads for Colleges", "nursing-program-leads-for-colleges", "us-university-leads", "University", "nursing program leads for colleges", "867", "university", "Y", "Y", "Y", "Y", "", ""),
        ("3", "Live", "TCPA Compliant Education Leads", "tcpa-compliant-education-leads", "us-university-leads", "University", "TCPA compliant education leads", "842", "compliance", "Y", "Y", "Y", "Y", "", ""),
        ("3", "Live", "Adult Learner Enrollment Marketing", "adult-learner-enrollment-marketing", "us-university-leads", "University", "adult learner enrollment marketing", "850", "university", "Y", "Y", "Y", "Y", "", ""),
        ("3", "Live", "Cost Per Enrollment vs Cost Per Lead", "cost-per-enrollment-vs-cost-per-lead", "us-university-leads", "University", "cost per enrollment vs cost per lead", "900", "university", "Y", "Y", "Y", "Y", "", ""),
        ("3", "Live", "How to Buy Education Leads", "how-to-buy-education-leads", "us-university-leads", "University", "how to buy education leads", "950", "university", "Y", "Y", "Y", "Y", "", ""),
    ]

    rows = []
    for p in posts:
        tier, status, title, slug, cluster, aud, kw, words, img, w, il, ob, cta, gsc, req = p
        rows.append((tier, status, title, slug, f"{DOMAIN}/{slug}/", cluster, aud, kw, words, img, w, il, ob, cta, gsc, req))

    add_rows(ws, rows)

    for row in ws.iter_rows(min_row=2, max_row=ws.max_row):
        row[1].fill = DONE_FILL
        if row[10].value == "Y":
            row[10].fill = DONE_FILL

    auto_width(ws)


def build_content_calendar(ws):
    ws.title = "Content Calendar"
    headers = [
        "Month",
        "Publish date",
        "Status",
        "Audience",
        "Title",
        "Slug",
        "Focus keyword",
        "Cluster",
        "Target words",
        "Featured image",
        "Internal links planned",
        "Outbound source",
        "Written",
        "Published",
        "GSC request indexing",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    calendar = [
        ("Jul 2026", "Jul 7", "Planned", "Student", "Best Nursing Programs in the U.S. for Working Adults", "best-nursing-programs-us-working-adults", "best nursing programs for working adults", "student-pathways", "800-1200", "student", "how-to-choose-the-right-nursing-program, online-vs-campus-nursing-programs, /contact-us/", "BLS Nursing", "", "", "", ""),
        ("Jul 2026", "Jul 21", "Planned", "University", "Speed to Contact: Why Education Leads Go Cold in 24 Hours", "speed-to-contact-education-leads", "speed to contact education leads", "us-university-leads", "800-1200", "university", "what-universities-should-know-about-lead-quality, how-to-buy-education-leads, /services/", "McKinsey Higher Ed", "", "", "", ""),
        ("Aug 2026", "Aug 4", "Planned", "Student", "How to Apply to College While Working Full Time", "how-to-apply-college-working-full-time", "apply to college while working full time", "student-pathways", "800-1200", "student", "berkeley-college-application-tips-for-working-adults, 5-fafsa-mistakes-to-avoid, /contact-us/", "NCES Adult", "", "", "", ""),
        ("Aug 2026", "Aug 18", "Planned", "University", "Exclusive vs Shared Education Leads: What Universities Should Buy", "exclusive-vs-shared-education-leads", "exclusive education leads", "us-university-leads", "800-1200", "compliance", "consent-based-education-leads-hlm, tcpa-compliant-education-leads, /services/", "FCC TCPA", "", "", "", ""),
        ("Sep 2026", "Sep 1", "Planned", "Student", "RN vs BSN in the U.S.: Which Nursing Path Is Right for You?", "rn-vs-bsn-which-path-us", "RN vs BSN USA", "student-pathways", "800-1200", "student", "how-to-choose-the-right-nursing-program, best-nursing-programs-us-working-adults, /contact-us/", "NCSBN", "", "", "", ""),
        ("Sep 2026", "Sep 15", "Planned", "University", "TCPA Rules for University Outbound Calling (2026 Guide)", "tcpa-rules-university-outbound-calling", "TCPA university outbound calling", "us-university-leads", "800-1200", "compliance", "tcpa-compliant-education-leads, consent-based-education-leads-hlm, /privacy-policy/", "FCC TCPA", "", "", "", ""),
        ("Oct 2026", "Oct 6", "Planned", "Student", "Merit Scholarships and Grants for Adult Learners in the U.S.", "merit-scholarships-adult-learners-usa", "merit scholarships adult learners", "student-pathways", "800-1200", "student", "5-fafsa-mistakes-to-avoid, how-to-apply-college-working-full-time, /contact-us/", "StudentAid.gov", "", "", "", ""),
        ("Oct 2026", "Oct 20", "Planned", "University", "Google Ads for University Enrollment: A Starter Guide", "google-ads-university-enrollment", "google ads university enrollment", "university-marketing", "800-1200", "university", "enrollment-marketing-roi-for-universities, adult-learner-enrollment-marketing, /services/", "McKinsey Higher Ed", "", "", "", ""),
        ("Nov 2026", "Nov 3", "Planned", "Student", "Accelerated Nursing Programs in the U.S.: What to Know Before You Enroll", "accelerated-nursing-programs-us-guide", "accelerated nursing programs USA", "student-pathways", "800-1200", "student", "rn-vs-bsn-which-path-us, online-vs-campus-nursing-programs, /contact-us/", "BLS Nursing", "", "", "", ""),
        ("Nov 2026", "Nov 17", "Planned", "University", "CRM Lead Routing for University Admissions Teams", "crm-lead-routing-university-admissions", "university admissions CRM lead routing", "university-marketing", "800-1200", "university", "speed-to-contact-education-leads, student-lead-generation-universities-usa, /services/", "Ed.gov", "", "", "", ""),
        ("Dec 2026", "Dec 1", "Planned", "Student", "College Transfer Credits for Adult Learners: A U.S. Guide", "college-transfer-credits-adult-learners", "college transfer credits adult learners", "student-pathways", "800-1200", "student", "berkeley-college-application-tips-for-working-adults, how-students-find-the-right-program-with-hlm, /contact-us/", "NCES Adult", "", "", "", ""),
        ("Dec 2026", "Dec 15", "Planned", "University", "In-House Enrollment Marketing vs Agency: What U.S. Colleges Should Know", "in-house-vs-agency-enrollment-marketing", "enrollment marketing agency vs in-house", "university-marketing", "800-1200", "university", "how-hlm-helps-universities-fill-programs, premium-education-lead-generation-hlm, /contact-us/", "McKinsey Higher Ed", "", "", "", ""),
    ]

    add_rows(ws, calendar)

    for row in ws.iter_rows(min_row=2, max_row=ws.max_row):
        row[2].fill = PENDING_FILL

    auto_width(ws, 55)


def build_gsc(ws):
    ws.title = "GSC Checklist"
    headers = ["Step", "Action", "URL / detail", "Status", "Done?", "Notes"]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("1", "Add GSC property (URL prefix)", DOMAIN, "Pending", "N", "Use live domain only - not localhost"),
        ("2", "Verify ownership (DNS TXT or HTML meta via Slim SEO)", "", "Pending", "N", ""),
        ("3", "Submit sitemap", "sitemap.xml", "Pending", "N", "Slim SEO only - do NOT submit wp-sitemap.xml"),
        ("4", "Request indexing - Home", f"{DOMAIN}/", "Pending", "N", "URL Inspection > Request indexing"),
        ("4", "Request indexing - Services", f"{DOMAIN}/services/", "Pending", "N", ""),
        ("4", "Request indexing - Contact", f"{DOMAIN}/contact-us/", "Pending", "N", ""),
        ("4", "Request indexing - About", f"{DOMAIN}/about-us/", "Pending", "N", ""),
        ("4", "Request indexing - Affiliations", f"{DOMAIN}/affiliations/", "Pending", "N", ""),
        ("4", "Request indexing - Blog", f"{DOMAIN}/blog/", "Pending", "N", ""),
        ("4", "Request indexing - Lead Generation service", f"{DOMAIN}/services/lead-generation/", "Pending", "N", ""),
        ("4", "Request indexing - Brand Awareness service", f"{DOMAIN}/services/brand-awareness/", "Pending", "N", ""),
        ("4", "Request indexing - Multi-Channel service", f"{DOMAIN}/services/multi-channel-marketing/", "Pending", "N", ""),
        ("4", "Request indexing - Analytics service", f"{DOMAIN}/services/analytics-reporting/", "Pending", "N", ""),
        ("4", "Request indexing - Partner Network service", f"{DOMAIN}/services/partner-network/", "Pending", "N", ""),
        ("4", "Request indexing - Enrollment Funnel service", f"{DOMAIN}/services/enrollment-funnel/", "Pending", "N", ""),
        ("4", "Request indexing - Top blog 1", f"{DOMAIN}/how-hlm-helps-universities-fill-programs/", "Pending", "N", ""),
        ("4", "Request indexing - Top blog 2", f"{DOMAIN}/student-lead-generation-universities-usa/", "Pending", "N", ""),
        ("4", "Request indexing - Top blog 3", f"{DOMAIN}/premium-education-lead-generation-hlm/", "Pending", "N", ""),
        ("5", "Filter Performance by United States", "GSC > Performance > Countries", "Pending", "N", "Primary market tracking"),
        ("6", "Link GA4 property", "GSC Settings > Associations", "Pending", "N", ""),
        ("7", "Weekly review (15 min)", "Impressions, clicks, indexed pages", "Ongoing", "N", "Every Monday recommended"),
    ]
    add_rows(ws, rows)
    auto_width(ws, 65)


def main():
    wb = Workbook()
    build_overview(wb.active)
    build_90_day(wb.create_sheet())
    build_phases(wb.create_sheet())
    build_keyword_map(wb.create_sheet())
    build_pages(wb.create_sheet())
    build_blog_published(wb.create_sheet())
    build_content_calendar(wb.create_sheet())
    build_gsc(wb.create_sheet())

    wb.save(OUTPUT)
    print(f"Created {OUTPUT}")


if __name__ == "__main__":
    main()
