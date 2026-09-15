#!/usr/bin/env python3
"""Generate HLM Advanced Backlinks Plan Excel workbook (PRIVATE - do not commit)."""

from openpyxl import Workbook
from openpyxl.styles import Alignment, Font, PatternFill
from openpyxl.utils import get_column_letter

OUTPUT = "/home/buildco/highereducation_eilte/HLM-Backlinks-Plan-PRIVATE.xlsx"
DOMAIN = "higherlearningmarketers.com"
BASE_URL = f"https://{DOMAIN}"

HEADER_FILL = PatternFill("solid", fgColor="002536")
HEADER_FONT = Font(color="FFFFFF", bold=True)
SUBHEADER_FILL = PatternFill("solid", fgColor="C9A96E")
SUBHEADER_FONT = Font(color="002536", bold=True)
PRIVATE_FILL = PatternFill("solid", fgColor="F8D7DA")
PRIVATE_FONT = Font(color="721C24", bold=True)
DONE_FILL = PatternFill("solid", fgColor="D4EDDA")
PENDING_FILL = PatternFill("solid", fgColor="FFF3CD")
PARTIAL_FILL = PatternFill("solid", fgColor="D1ECF1")
TIER1_FILL = PatternFill("solid", fgColor="E2EFDA")
TIER2_FILL = PatternFill("solid", fgColor="DDEBF7")
TIER3_FILL = PatternFill("solid", fgColor="FCE4D6")


def style_header_row(ws, row=1):
    for cell in ws[row]:
        cell.fill = HEADER_FILL
        cell.font = HEADER_FONT
        cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)


def auto_width(ws, max_width=70):
    for col in ws.columns:
        letter = get_column_letter(col[0].column)
        length = max(len(str(c.value or "")) for c in col)
        ws.column_dimensions[letter].width = min(max(length + 2, 10), max_width)


def add_rows(ws, rows, start=2):
    for i, row in enumerate(rows, start=start):
        for j, val in enumerate(row, start=1):
            ws.cell(row=i, column=j, value=val)


def private_banner(ws, text, merge_to=6):
    ws.merge_cells(start_row=1, start_column=1, end_row=1, end_column=merge_to)
    cell = ws["A1"]
    cell.value = text
    cell.fill = PRIVATE_FILL
    cell.font = PRIVATE_FONT
    cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
    ws.row_dimensions[1].height = 36


def build_overview(ws):
    ws.title = "Overview"
    private_banner(
        ws,
        "PRIVATE / INTERNAL ONLY — Do not share, publish, or commit this workbook. Google Dorks sheet is for your prospecting only.",
        4,
    )

    sections = [
        ("BACKLINKS PLAN — HIGHER LEARNING MARKETERS", ""),
        ("Primary KPI", "Referring domains (unique websites linking to HLM) — NOT total backlink count"),
        ("Secondary KPI", "Authority Score trend in Semrush + quality of linking domains (.edu, industry, partners)"),
        ("Geographic focus", "United States — higher education, enrollment marketing, student leads"),
        ("Framework", "Backlinko (quality editorial links) + Semrush (referring domains, Backlink Audit)"),
        ("", ""),
        ("METRICS CHEAT SHEET (Semrush)", ""),
        (
            "Backlinks (total)",
            "Every individual hyperlink to your site. One domain linking from 5 pages = 5 backlinks.",
        ),
        (
            "Referring domains",
            "Unique websites linking to you (each domain counted once). THIS is your main goal number.",
        ),
        (
            "Example",
            "forbes.com links from 3 articles → 3 backlinks, 1 referring domain. Track referring domains.",
        ),
        ("Authority Score", "Semrush 0-100 quality score for domains linking to you. Target AS 30+ where possible."),
        ("", ""),
        ("YEAR 1 TARGETS", ""),
        ("Months 1-3", "0-5 referring domains (partners, directories, GBP)"),
        ("Months 3-6", "10-20 referring domains"),
        ("Months 6-12", "30-50 referring domains"),
        ("Backlinks total", "No hard target — will exceed referring domains naturally"),
        ("", ""),
        ("LINK ALLOCATION (% of external links)", ""),
        ("Blog posts (guides, compliance)", "50% — natural editorial links"),
        ("/services/ + service detail pages", "30% — commercial authority"),
        ("Home, About, Affiliations", "20% — brand trust"),
        ("", ""),
        ("ANCHOR TEXT MIX (Backlinko-safe)", ""),
        ("Branded 40%", "Higher Learning Marketers, HLM"),
        ("Partial match 30%", "education lead generation company, enrollment marketing partner"),
        ("Naked URL 20%", f"{DOMAIN}"),
        ("Exact match 10% max", "education lead generation (use sparingly)"),
        ("", ""),
        ("SHEET GUIDE", ""),
        ("KPI Targets", "Semrush baseline + monthly referring-domain goals"),
        ("Monthly Calendar", "Jul-Dec 2026 execution plan"),
        ("Partner Links", "YGO + university partner backlink tracker"),
        ("Directories", "Quality directory listing tracker"),
        ("Guest Posts", "Outreach and publication tracker"),
        ("Outreach Log", "All link-building emails and follow-ups"),
        ("Linkable Assets", "HLM pages ranked by link-earning potential"),
        ("Semrush Monthly Log", "Record metrics every month"),
        ("Google Dorks", "PRIVATE prospecting queries — internal use only"),
    ]

    r = 3
    for label, value in sections:
        cell = ws.cell(row=r, column=1, value=label)
        if label.isupper() and value:
            cell.font = Font(bold=True)
        if label in (
            "BACKLINKS PLAN — HIGHER LEARNING MARKETERS",
            "METRICS CHEAT SHEET (Semrush)",
            "YEAR 1 TARGETS",
            "LINK ALLOCATION (% of external links)",
            "ANCHOR TEXT MIX (Backlinko-safe)",
            "SHEET GUIDE",
        ):
            cell.fill = SUBHEADER_FILL
            cell.font = SUBHEADER_FONT
        ws.cell(row=r, column=2, value=value)
        ws.merge_cells(start_row=r, start_column=2, end_row=r, end_column=4)
        r += 1

    ws.column_dimensions["A"].width = 38
    ws.column_dimensions["B"].width = 95


def build_kpi_targets(ws):
    ws.title = "KPI Targets"
    headers = [
        "Period",
        "Referring domains goal",
        "New RD/month (avg)",
        "Backlinks (info only)",
        "HLM Authority Score target",
        "Avg linking domain AS",
        "Focus",
        "Status",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("Baseline (record in Semrush)", "—", "—", "—", "—", "—", "Run Backlink Analytics on live domain", "Pending", "Day 1 snapshot"),
        ("Month 1 (Jul 2026)", "3-5", "3-5", "Don't track", "10+", "25+", "Partners + directories + GBP", "Not Started", ""),
        ("Month 2 (Aug 2026)", "5-8", "2-3", "Don't track", "12+", "25+", "First guest post + affiliations", "Not Started", ""),
        ("Month 3 (Sep 2026)", "8-12", "2-4", "Don't track", "15+", "30+", "Resource outreach (TCPA post)", "Not Started", ""),
        ("Month 4 (Oct 2026)", "10-15", "2-3", "Don't track", "18+", "30+", "Guest post + Clutch/listing", "Not Started", ""),
        ("Month 5 (Nov 2026)", "13-18", "2-3", "Don't track", "20+", "30+", "Partner co-marketing", "Not Started", ""),
        ("Month 6 (Dec 2026)", "15-25", "2-3", "Don't track", "22+", "30+", "Review + refresh linkable assets", "Not Started", ""),
        ("Month 12 (Jun 2027)", "30-50", "3-5", "Don't track", "30+", "35+", "Sustained guest posts + PR", "Not Started", ""),
    ]
    add_rows(ws, rows)

    note_row = ws.max_row + 2
    ws.cell(row=note_row, column=1, value="Semrush path:").font = Font(bold=True)
    ws.cell(row=note_row, column=2, value="Backlink Analytics > Overview > Referring Domains + Authority Score")
    ws.merge_cells(start_row=note_row, start_column=2, end_row=note_row, end_column=9)

    auto_width(ws)


def build_monthly_calendar(ws):
    ws.title = "Monthly Calendar"
    headers = [
        "Month",
        "Week",
        "Tier",
        "Task",
        "Tactic",
        "Target HLM page",
        "Owner",
        "RD goal",
        "Status",
        "Done?",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("Jul 2026", "1", "1", "Semrush baseline snapshot", "Measurement", "All", "Marketing", "—", "Pending", "N", "Record RD, AS, top linked pages"),
        ("Jul 2026", "1", "1", "Google Business Profile live + website link", "Local entity", "/contact-us/", "Marketing", "+1", "Pending", "N", "Briarwood NY"),
        ("Jul 2026", "1", "1", "Outreach: YGO + top 3 partners for partner-page link", "Partner link", "/affiliations/", "Partnerships", "+2", "Pending", "N", "Logo + description on their site"),
        ("Jul 2026", "2", "1", "Submit to 3 quality education marketing directories", "Directory", "/services/", "Marketing", "+2", "Pending", "N", "See Directories sheet"),
        ("Jul 2026", "3", "2", "Identify 15 guest-post targets (use Google Dorks)", "Research", "—", "Marketing", "—", "Pending", "N", "PRIVATE dorks sheet"),
        ("Jul 2026", "4", "2", "Resource outreach: TCPA post to 10 .edu marketing blogs", "Resource link", "/tcpa-compliant-education-leads/", "Marketing", "+1", "Pending", "N", ""),
        ("Aug 2026", "1", "2", "Publish guest post #1", "Guest post", "/how-to-buy-education-leads/", "Marketing", "+1", "Pending", "N", ""),
        ("Aug 2026", "2", "1", "Follow up partner link requests (batch 2)", "Partner link", "/about-us/", "Partnerships", "+2", "Pending", "N", ""),
        ("Aug 2026", "3", "2", "Broken-link outreach on 5 higher-ed resource pages", "Broken link", "/5-fafsa-mistakes-to-avoid/", "Marketing", "+1", "Pending", "N", "Use dork: resource pages"),
        ("Aug 2026", "4", "3", "LinkedIn company page + 3 team profile links", "Entity", f"{BASE_URL}/", "Marketing", "+1", "Pending", "N", ""),
        ("Sep 2026", "1", "2", "Guest post #2 published", "Guest post", "/student-lead-generation-universities-usa/", "Marketing", "+1", "Pending", "N", ""),
        ("Sep 2026", "2", "2", "Outreach: cost-per-enrollment post to enrollment blogs", "Resource link", "/cost-per-enrollment-vs-cost-per-lead/", "Marketing", "+2", "Pending", "N", ""),
        ("Sep 2026", "3", "1", "Clutch / GoodFirms profile (if relevant)", "Directory", "/about-us/", "Marketing", "+1", "Pending", "N", ""),
        ("Sep 2026", "4", "3", "HARO: respond to 4 higher-ed journalist queries", "Digital PR", "/services/", "Marketing", "+1", "Pending", "N", "Optional"),
        ("Oct 2026", "1", "2", "Guest post #3 published", "Guest post", "/tcpa-compliant-education-leads/", "Marketing", "+1", "Pending", "N", ""),
        ("Oct 2026", "2", "2", "Unlinked brand mention reclamation (5 mentions)", "Mention reclaim", "/about-us/", "Marketing", "+1", "Pending", "N", "Google Alerts for HLM"),
        ("Oct 2026", "3", "1", "Partner co-marketing page with 1 university", "Partner link", "/services/lead-generation/", "Partnerships", "+1", "Pending", "N", ""),
        ("Oct 2026", "4", "—", "Monthly Semrush review + log metrics", "Measurement", "—", "Marketing", "—", "Pending", "N", "Semrush Monthly Log sheet"),
        ("Nov 2026", "1", "2", "Guest post #4 published", "Guest post", "/enrollment-marketing-roi-for-universities/", "Marketing", "+1", "Pending", "N", ""),
        ("Nov 2026", "2", "2", "Nursing cluster outreach (3 nursing program blogs)", "Resource link", "/nursing-program-leads-for-colleges/", "Marketing", "+2", "Pending", "N", ""),
        ("Nov 2026", "3", "1", "Affiliation page reciprocal links audit", "Partner link", "/affiliations/", "Partnerships", "+1", "Pending", "N", ""),
        ("Nov 2026", "4", "3", "Refresh top 3 linkable assets (add stats, update year)", "Content", "Blog", "Content", "—", "Pending", "N", "Helps earn more links"),
        ("Dec 2026", "1", "2", "Guest post #5 published", "Guest post", "/services/lead-generation/", "Marketing", "+1", "Pending", "N", ""),
        ("Dec 2026", "2", "2", "Year-end outreach: 10 uncontacted prospects from Dorks", "Outreach", "Mixed", "Marketing", "+2", "Pending", "N", ""),
        ("Dec 2026", "3", "—", "Backlink Audit in Semrush (toxic check)", "Measurement", "—", "Marketing", "—", "Pending", "N", "Disavow only if truly toxic"),
        ("Dec 2026", "4", "—", "6-month review: RD count vs goal (15-25)", "Measurement", "—", "Marketing", "—", "Pending", "N", ""),
    ]
    add_rows(ws, rows)

    for row in ws.iter_rows(min_row=2, max_row=ws.max_row):
        tier = str(row[2].value or "")
        if tier == "1":
            row[2].fill = TIER1_FILL
        elif tier == "2":
            row[2].fill = TIER2_FILL
        elif tier == "3":
            row[2].fill = TIER3_FILL

    auto_width(ws, 55)


def build_partner_links(ws):
    ws.title = "Partner Links"
    headers = [
        "Partner",
        "Partner website",
        "HLM page to link",
        "Their page (where link should live)",
        "Link type",
        "Anchor text suggestion",
        "Contact",
        "Outreach date",
        "Status",
        "Live URL (their page)",
        "RD gained?",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("YGO", "", "/affiliations/", "Partners / Vendors page", "Partner logo + link", "Higher Learning Marketers", "", "", "Not Started", "", "N", "Priority #1"),
        ("University partner 1", "", "/affiliations/", "Partner ecosystem page", "Partner listing", "HLM — education lead generation", "", "", "Not Started", "", "N", ""),
        ("University partner 2", "", "/services/", "Enrollment resources", "Resource mention", "education lead generation services", "", "", "Not Started", "", "N", ""),
        ("University partner 3", "", "/about-us/", "About partners", "Logo link", "Higher Learning Marketers", "", "", "Not Started", "", "N", ""),
        ("Co-marketing partner", "", "/services/lead-generation/", "Powered by / vendor page", "Co-marketing", "student lead generation partner", "", "", "Not Started", "", "N", ""),
        ("Affiliate / referral partner", "", "/contact-us/", "Referral partners", "Referral link", f"{DOMAIN}", "", "", "Not Started", "", "N", ""),
    ]
    add_rows(ws, rows)
    auto_width(ws)


def build_directories(ws):
    ws.title = "Directories"
    headers = [
        "Directory",
        "URL",
        "Category",
        "HLM page to list",
        "Cost",
        "DA/AS (check Semrush)",
        "Quality tier",
        "Submitted",
        "Approved",
        "Live listing URL",
        "RD gained?",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("Google Business Profile", "https://business.google.com", "Local entity", "/contact-us/", "Free", "High", "Tier 1", "", "", "", "N", "Briarwood NY address"),
        ("Clutch", "https://clutch.co", "B2B services", "/about-us/", "Free/Paid", "High", "Tier 1", "", "", "", "N", "Education marketing category"),
        ("GoodFirms", "https://www.goodfirms.co", "B2B services", "/services/", "Free", "Medium", "Tier 2", "", "", "", "N", ""),
        ("UPCEA (if member)", "https://www.upcea.edu", "Higher ed association", "/about-us/", "Membership", "High", "Tier 1", "", "", "", "N", "Association listing"),
        ("Education marketing niche dir", "", "Education", "/services/", "Free", "Medium", "Tier 2", "", "", "", "N", "Research via dorks"),
        ("NY local business directory", "", "Local", "/contact-us/", "Free", "Low-Med", "Tier 3", "", "", "", "N", "Local trust signal only"),
    ]
    add_rows(ws, rows)
    auto_width(ws)


def build_guest_posts(ws):
    ws.title = "Guest Posts"
    headers = [
        "Target site",
        "Site URL",
        "Niche relevance",
        "Domain AS (Semrush)",
        "Contact / editor",
        "Pitch topic",
        "HLM link target",
        "Anchor text",
        "Pitch sent",
        "Response",
        "Draft sent",
        "Published",
        "Live article URL",
        "RD gained?",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    rows = [
        ("Enrollment marketing blog", "", "University enrollment", "", "", "Speed to contact for education leads", "/how-to-buy-education-leads/", "Higher Learning Marketers", "", "", "", "", "", "N", "Jul-Aug target"),
        ("Higher ed compliance blog", "", "TCPA / compliance", "", "", "TCPA rules for university outbound", "/tcpa-compliant-education-leads/", "TCPA compliant education leads guide", "", "", "", "", "", "N", ""),
        ("Adult learner education blog", "", "Adult learners", "", "", "Adult learner enrollment marketing ROI", "/adult-learner-enrollment-marketing/", "education lead generation", "", "", "", "", "", "N", ""),
        ("Nursing education blog", "", "Nursing programs", "", "", "Nursing program lead generation for colleges", "/nursing-program-leads-for-colleges/", "HLM", "", "", "", "", "", "N", ""),
        ("University marketing publication", "", "Higher ed marketing", "", "", "Cost per enrollment vs cost per lead", "/cost-per-enrollment-vs-cost-per-lead/", "Higher Learning Marketers", "", "", "", "", "", "N", ""),
        ("Student pathway blog", "", "Student recruitment", "", "", "How students find the right program", "/how-students-find-the-right-program-with-hlm/", f"{DOMAIN}", "", "", "", "", "", "N", ""),
    ]
    add_rows(ws, rows)
    auto_width(ws)


def build_outreach_log(ws):
    ws.title = "Outreach Log"
    headers = [
        "Date",
        "Prospect site",
        "Prospect URL",
        "Tactic",
        "HLM page pitched",
        "Contact name",
        "Email",
        "Subject line",
        "Follow-up 1",
        "Follow-up 2",
        "Response",
        "Outcome",
        "Link live?",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    # Empty template rows for user to fill
    for _ in range(25):
        ws.append([""] * len(headers))

    auto_width(ws, 45)


def build_linkable_assets(ws):
    ws.title = "Linkable Assets"
    headers = [
        "Priority",
        "HLM page",
        "Full URL",
        "Link magnet reason",
        "Best outreach angle",
        "Target linker type",
        "Current RD (Semrush)",
        "Outreach status",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    pages = [
        ("1", "TCPA Compliant Education Leads", f"{BASE_URL}/tcpa-compliant-education-leads/", "Compliance resource — universities need this", "Resource for your TCPA/compliance readers", ".edu marketing, compliance blogs", "", "Not Started", ""),
        ("2", "How to Buy Education Leads", f"{BASE_URL}/how-to-buy-education-leads/", "B2B buying guide", "Comprehensive guide for enrollment teams", "Enrollment directors, agencies", "", "Not Started", ""),
        ("3", "5 FAFSA Mistakes to Avoid", f"{BASE_URL}/5-fafsa-mistakes-to-avoid/", "Student resource — high shareability", "Helpful resource for adult learners", "Student advisors, .edu financial aid", "", "Not Started", ""),
        ("4", "Cost Per Enrollment vs Cost Per Lead", f"{BASE_URL}/cost-per-enrollment-vs-cost-per-lead/", "Data/comparison content", "ROI framework for enrollment leaders", "University marketing blogs", "", "Not Started", ""),
        ("5", "Student Lead Generation USA", f"{BASE_URL}/student-lead-generation-universities-usa/", "Commercial pillar", "US-focused enrollment lead gen overview", "Higher ed marketing publications", "", "Not Started", ""),
        ("6", "Nursing Program Leads", f"{BASE_URL}/nursing-program-leads-for-colleges/", "Vertical niche", "Nursing enrollment resource", "Nursing program blogs, colleges", "", "Not Started", ""),
        ("7", "Consent-Based Education Leads", f"{BASE_URL}/consent-based-education-leads-hlm/", "Compliance angle", "Consent-first lead gen explainer", "Compliance + enrollment blogs", "", "Not Started", ""),
        ("8", "Services (pillar)", f"{BASE_URL}/services/", "Money page — use sparingly", "Partner/vendor reference only", "Partners, directories", "", "Not Started", "Don't over-outreach to this URL"),
        ("9", "Affiliations", f"{BASE_URL}/affiliations/", "Trust page", "Partnership verification", "University partner pages", "", "Not Started", ""),
        ("10", "How to Choose Nursing Program", f"{BASE_URL}/how-to-choose-the-right-nursing-program/", "Student guide", "Resource for prospective nursing students", "Nursing schools, advisors", "", "Not Started", ""),
    ]
    add_rows(ws, pages)
    auto_width(ws)


def build_semrush_log(ws):
    ws.title = "Semrush Monthly Log"
    headers = [
        "Month",
        "Referring domains",
        "New RD (month)",
        "Lost RD (month)",
        "Total backlinks (info)",
        "HLM Authority Score",
        "Top linked page 1",
        "Top linked page 2",
        "Top linked page 3",
        "Toxic links flagged",
        "Action taken",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws)

    months = [
        "Jul 2026", "Aug 2026", "Sep 2026", "Oct 2026", "Nov 2026", "Dec 2026",
        "Jan 2027", "Feb 2027", "Mar 2027", "Apr 2027", "May 2027", "Jun 2027",
    ]
    for m in months:
        ws.append([m] + [""] * (len(headers) - 1))

    note_row = ws.max_row + 2
    ws.cell(row=note_row, column=1, value="How to fill:").font = Font(bold=True)
    ws.cell(
        row=note_row,
        column=2,
        value="Semrush > Backlink Analytics > higherlearningmarketers.com > Overview. Log Referring Domains + Authority Score monthly.",
    )
    ws.merge_cells(start_row=note_row, start_column=2, end_row=note_row, end_column=12)

    auto_width(ws)


def build_google_dorks(ws):
    ws.title = "Google Dorks"
    private_banner(
        ws,
        "PRIVATE — Google Dorks for internal prospecting only. Do not publish, share, or automate bulk scraping. Use ethically for manual outreach.",
        8,
    )

    headers = [
        "Category",
        "Dork query",
        "What it finds",
        "Best for HLM",
        "HLM page to pitch",
        "Used?",
        "Prospects found",
        "Notes",
    ]
    ws.append(headers)
    style_header_row(ws, row=2)
    ws.freeze_panes = "A3"

    dorks = [
        # Guest posts
        (
            "Guest post",
            '"education marketing" "write for us"',
            "Blogs accepting education marketing guest posts",
            "Guest post pitches",
            "/how-to-buy-education-leads/",
            "",
            "",
            "",
        ),
        (
            "Guest post",
            '"enrollment marketing" "guest post"',
            "Enrollment-focused publications",
            "Guest post",
            "/student-lead-generation-universities-usa/",
            "",
            "",
            "",
        ),
        (
            "Guest post",
            '"higher education" "contribute" blog',
            "Higher ed blogs open to contributors",
            "Guest post",
            "/enrollment-marketing-roi-for-universities/",
            "",
            "",
            "",
        ),
        (
            "Guest post",
            'site:.edu "write for us" marketing',
            ".edu marketing blogs with contributor pages",
            "High-authority guest posts",
            "/tcpa-compliant-education-leads/",
            "",
            "",
            "",
        ),
        (
            "Guest post",
            '"university enrollment" "submit a guest post"',
            "Enrollment niche blogs",
            "Guest post",
            "/adult-learner-enrollment-marketing/",
            "",
            "",
            "",
        ),
        # Resource pages
        (
            "Resource page",
            '"higher education" "useful resources" enrollment',
            "Resource roundup pages in enrollment niche",
            "Suggest HLM guide as resource",
            "/how-to-buy-education-leads/",
            "",
            "",
            "",
        ),
        (
            "Resource page",
            'site:.edu "student resources" marketing',
            ".edu student/marketing resource lists",
            "Suggest FAFSA or student pathway post",
            "/5-fafsa-mistakes-to-avoid/",
            "",
            "",
            "",
        ),
        (
            "Resource page",
            '"enrollment" "helpful links" university',
            "Universities linking to external enrollment tools",
            "Resource inclusion",
            "/cost-per-enrollment-vs-cost-per-lead/",
            "",
            "",
            "",
        ),
        (
            "Resource page",
            '"adult learners" "resources" site:.edu',
            ".edu adult learner resource hubs",
            "Student content outreach",
            "/how-to-apply-college-working-full-time/",
            "",
            "",
            "Use when post is live",
        ),
        # Link roundups / lists
        (
            "Roundup",
            '"best education marketing" companies',
            "Listicles ranking education marketing firms",
            "Inclusion outreach",
            "/about-us/",
            "",
            "",
            "",
        ),
        (
            "Roundup",
            '"lead generation" "higher education" tools',
            "Tool/vendor roundup articles",
            "Vendor listing pitch",
            "/services/",
            "",
            "",
            "",
        ),
        (
            "Roundup",
            '"enrollment marketing" agencies -site:clutch.co',
            "Agency roundup posts (exclude Clutch)",
            "Get listed in roundups",
            "/services/",
            "",
            "",
            "",
        ),
        # Broken links
        (
            "Broken link",
            'site:.edu "enrollment marketing" "resources" 404',
            ".edu pages that may have broken outbound links",
            "Broken link replacement",
            "/student-lead-generation-universities-usa/",
            "",
            "",
            "Verify with Check My Links extension",
        ),
        (
            "Broken link",
            '"higher education leads" "page not found"',
            "Articles linking to dead lead-gen resources",
            "Broken link outreach",
            "/how-to-buy-education-leads/",
            "",
            "",
            "",
        ),
        # Unlinked mentions
        (
            "Unlinked mention",
            '"Higher Learning Marketers" -site:higherlearningmarketers.com',
            "Web mentions without a link",
            "Reclaim brand mentions",
            f"{BASE_URL}/",
            "",
            "",
            "Set Google Alert too",
        ),
        (
            "Unlinked mention",
            '"education lead generation" "contact" -inurl:contact',
            "Pages discussing topic without vendor link",
            "Soft pitch",
            "/services/",
            "",
            "",
            "",
        ),
        # Directories
        (
            "Directory",
            '"education marketing" directory inurl:submit',
            "Directories accepting education marketing listings",
            "Directory submissions",
            "/services/",
            "",
            "",
            "Quality-check before submitting",
        ),
        (
            "Directory",
            '"higher education" vendor directory',
            "Higher ed vendor directories",
            "B2B listing",
            "/about-us/",
            "",
            "",
            "",
        ),
        # Competitor / gap research
        (
            "Competitor RD",
            'link:educationdynamics.com -site:educationdynamics.com',
            "Sites linking to a competitor",
            "Same-site outreach prospects",
            "Match to linkable asset",
            "",
            "",
            "Swap competitor domain as needed",
        ),
        (
            "Competitor RD",
            'link:sparkroom.com -site:sparkroom.com',
            "Sparkroom backlink sources",
            "Prospect list",
            "/services/lead-generation/",
            "",
            "",
            "",
        ),
        (
            "Competitor RD",
            '"education lead generation" -site:higherlearningmarketers.com -site:educationdynamics.com',
            "Pages mentioning niche but not linking to you or top competitor",
            "Gap outreach",
            "Best matching blog post",
            "",
            "",
            "",
        ),
        # Niche vertical — nursing
        (
            "Nursing niche",
            '"nursing program" "resources for students" site:.edu',
            ".edu nursing student resources",
            "Nursing content outreach",
            "/how-to-choose-the-right-nursing-program/",
            "",
            "",
            "",
        ),
        (
            "Nursing niche",
            '"nursing enrollment" marketing blog',
            "Nursing enrollment marketing blogs",
            "Vertical guest posts",
            "/nursing-program-leads-for-colleges/",
            "",
            "",
            "",
        ),
        # TCPA / compliance
        (
            "Compliance",
            '"TCPA" "higher education" marketing',
            "TCPA articles in higher ed context",
            "Suggest compliance guide",
            "/tcpa-compliant-education-leads/",
            "",
            "",
            "",
        ),
        (
            "Compliance",
            '"consent-based leads" education',
            "Consent-based lead gen discussions",
            "Resource pitch",
            "/consent-based-education-leads-hlm/",
            "",
            "",
            "",
        ),
        # Partnerships
        (
            "Partnership",
            'site:.edu "our partners" "education"',
            ".edu partner ecosystem pages",
            "Partner link placement",
            "/affiliations/",
            "",
            "",
            "",
        ),
        (
            "Partnership",
            '"university partners" "lead generation" OR "enrollment"',
            "Universities listing enrollment vendors",
            "Partner page inclusion",
            "/affiliations/",
            "",
            "",
            "",
        ),
        # PR / news
        (
            "Digital PR",
            '"higher education enrollment" "study" 2025 OR 2026',
            "Recent studies journalists cite",
            "HARO / journalist outreach",
            "/services/",
            "",
            "",
            "Offer expert quote + link",
        ),
        (
            "Digital PR",
            'site:prnewswire.com "enrollment" university',
            "Enrollment press releases",
            "Find journalists covering niche",
            "/about-us/",
            "",
            "",
            "",
        ),
        # PDF / syllabus (advanced)
        (
            "Advanced",
            'filetype:pdf "enrollment marketing" syllabus',
            "Course syllabi linking to resources",
            "Academic resource inclusion",
            "/enrollment-marketing-roi-for-universities/",
            "",
            "",
            "Long-shot but high quality",
        ),
        (
            "Advanced",
            'inurl:resources "higher education" "marketing" -site:.gov',
            "Marketing resource hubs",
            "Resource page outreach",
            "Best matching asset",
            "",
            "",
            "",
        ),
    ]
    add_rows(ws, dorks, start=3)

    for row in ws.iter_rows(min_row=3, max_row=ws.max_row):
        cat = str(row[0].value or "")
        if cat == "Guest post":
            row[0].fill = TIER2_FILL
        elif cat in ("Resource page", "Roundup"):
            row[0].fill = TIER1_FILL
        elif cat in ("Competitor RD", "Advanced"):
            row[0].fill = TIER3_FILL

    note_row = ws.max_row + 2
    ws.cell(row=note_row, column=1, value="Usage tips:").font = Font(bold=True)
    tips = (
        "1) Run 3-5 dorks per week manually in Google. 2) Log prospects in Outreach Log. "
        "3) Prioritize .edu + industry AS 30+. 4) Never spam — one personalized email per site. "
        "5) Mark 'Used?' column to avoid duplicate searches."
    )
    ws.cell(row=note_row, column=2, value=tips)
    ws.merge_cells(start_row=note_row, start_column=2, end_row=note_row, end_column=8)

    auto_width(ws, 60)


def main():
    wb = Workbook()
    build_overview(wb.active)
    build_kpi_targets(wb.create_sheet())
    build_monthly_calendar(wb.create_sheet())
    build_partner_links(wb.create_sheet())
    build_directories(wb.create_sheet())
    build_guest_posts(wb.create_sheet())
    build_outreach_log(wb.create_sheet())
    build_linkable_assets(wb.create_sheet())
    build_semrush_log(wb.create_sheet())
    build_google_dorks(wb.create_sheet())

    wb.save(OUTPUT)
    print(f"Created {OUTPUT}")


if __name__ == "__main__":
    main()
