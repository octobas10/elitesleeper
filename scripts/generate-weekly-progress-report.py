#!/usr/bin/env python3
"""Generate HLM Elite weekly progress report (Excel).

Content rule: never use em dashes or en dashes. Use a single ASCII hyphen only.
"""

import re
from datetime import date
from pathlib import Path

from openpyxl import Workbook
from openpyxl.styles import Alignment, Border, Font, PatternFill, Side
from openpyxl.utils import get_column_letter

OUTPUT = Path(__file__).resolve().parent.parent / "HLM-Weekly-Progress-Report.xlsx"

# Brand colors
NAVY = "1B2A4A"
BLUE = "2563EB"
LIGHT_BLUE = "EFF6FF"
GREEN = "059669"
AMBER = "D97706"
GRAY = "6B7280"
WHITE = "FFFFFF"
LIGHT_GRAY = "F3F4F6"

thin = Side(style="thin", color="D1D5DB")
border = Border(left=thin, right=thin, top=thin, bottom=thin)

# Em/en dash and other unicode dash variants -> single ASCII hyphen.
_DASH_RE = re.compile(r"[\u2010\u2011\u2012\u2013\u2014\u2015\u2212\uFE58\uFE63\uFF0D]")


def normalize_dashes(text):
    """Replace em/en dashes with a spaced single hyphen for report copy."""
    if text is None or not isinstance(text, str):
        return text
    normalized = _DASH_RE.sub(" - ", text)
    return re.sub(r"\s+-\s+-\s+", " - ", normalized)


WEEKS = [
    {
        "label": "Week 1 (Past)",
        "dates": "Jun 2 - Jun 8, 2026",
        "phase": "Foundation",
        "status": "Complete",
        "summary": "Project scaffolding, Docker environment, Kadence theme customization, and design reference HTML.",
        "tasks": [
            ("Docker local dev stack (WordPress 8.4, MariaDB, phpMyAdmin)", "Complete", "DevOps", "100%"),
            ("HLM Kadence child theme bootstrap and block architecture (19 Gutenberg blocks)", "Complete", "Theme", "100%"),
            ("Design reference pages (Home, About, Services, Affiliations)", "Complete", "Design", "100%"),
            ("Page patterns and site bootstrap (Home, About, Services, Contact, Blog, Privacy)", "Complete", "Theme", "100%"),
            ("Custom post types: Services catalog with 6 detail pages", "Complete", "Theme", "100%"),
            ("HLM Lead Capture plugin - student and university wizard forms", "Complete", "Plugin", "100%"),
            ("HLM Security plugin - form shield and hardening", "Complete", "Plugin", "100%"),
        ],
        "deliverables": "Local dev environment live on port 10001; 7 core pages; 6 service detail pages; lead forms",
        "blockers": "None",
        "next": "SEO ecosystem and content strategy",
    },
    {
        "label": "Week 2 (Past)",
        "dates": "Jun 9 - Jun 15, 2026",
        "phase": "UX & Content Foundation",
        "status": "Complete",
        "summary": "Lead form UX improvements, block field definitions, patterns, and initial content structure.",
        "tasks": [
            ("Lead capture form redesign - compact wizard with optional fields restored", "Complete", "Plugin", "100%"),
            ("ACF block field definitions for all 19 HLM blocks", "Complete", "Theme", "100%"),
            ("Reusable patterns (CTA banner, stats bar, team profiles, testimonials, services grid)", "Complete", "Theme", "100%"),
            ("Blog template, card layout, and sidebar components", "Complete", "Theme", "100%"),
            ("Gutenberg block build pipeline", "Complete", "Theme", "100%"),
            ("Institution data integration for university lead form", "Complete", "Plugin", "100%"),
            ("Site footer, animations, and design token CSS", "Complete", "Theme", "100%"),
        ],
        "deliverables": "Production-ready theme blocks; optimized lead forms; blog infrastructure",
        "blockers": "Plugins directory permission issue (Docker ownership) - resolved in Week 3",
        "next": "Build theme-agnostic SEO plugin before Slim SEO Pro install",
    },
    {
        "label": "Week 3 (Current)",
        "dates": "Jun 16 - Jun 22, 2026",
        "phase": "SEO Phase 1 & 2",
        "status": "In Progress",
        "summary": "Full SEO ecosystem built, 16+ blog posts published, Phase 2 on-page SEO applied to all money pages and service details.",
        "tasks": [
            ("HLM SEO Ecosystem plugin (theme-agnostic) - meta, schema, sitemap, robots", "Complete", "SEO", "100%"),
            ("Slim SEO compatibility layer and schema integration", "Complete", "SEO", "100%"),
            ("Topic clusters, internal linking, outbound authority links (Backlinko model)", "Complete", "SEO", "100%"),
            ("Lead-gen page structure (SalesFuel-style CTAs, FAQs, sticky CTA)", "Complete", "SEO", "100%"),
            ("16 SEO blog posts with featured images (Tier 1-3 keywords, US-focused)", "Complete", "Content", "100%"),
            ("Phase 2 on-page SEO: Home, Services, Contact, About, Affiliations", "Complete", "SEO", "100%"),
            ("Phase 2 on-page SEO: 6 service detail pages (H1, intro, FAQs, links)", "Complete", "SEO", "100%"),
            ("Leadership team update (Victor Daniel, Robert Intelisano) on About page", "Complete", "Content", "100%"),
            ("Privacy policy refresh (June 2026, TCPA/CCPA compliant)", "Complete", "Legal", "100%"),
            ("Dash normalization across all page content (single hyphen only, no em dashes)", "Complete", "Content", "100%"),
            ("12-post Phase 3 content calendar drafted", "Complete", "Planning", "100%"),
            ("Google Search Console setup guide documented", "In Progress", "SEO", "75%"),
        ],
        "deliverables": "hlm-seo plugin; 16 blog posts; Phase 2 complete on 12 money pages; US SEO plan",
        "blockers": "GSC requires live domain verification (manual step)",
        "next": "GSC setup on production; begin Phase 3 content publishing",
    },
    {
        "label": "Week 4 (Planned)",
        "dates": "Jun 23 - Jun 29, 2026",
        "phase": "Phase 3 Launch & Deployment Prep",
        "status": "Planned",
        "summary": "Google Search Console go-live, first Phase 3 blog posts, hosting deployment guidance, and performance QA.",
        "tasks": [
            ("Google Search Console - property verification on live domain", "Planned", "SEO", "0%"),
            ("Submit sitemap.xml (Slim SEO) and request indexing for 15 money URLs", "Planned", "SEO", "0%"),
            ("Publish Phase 3 blog post #1 (Jul calendar - university audience)", "Planned", "Content", "0%"),
            ("Publish Phase 3 blog post #2 (Jul calendar - student audience)", "Planned", "Content", "0%"),
            ("GitHub to production hosting deployment (SSH/FTP guidance)", "Planned", "DevOps", "0%"),
            ("Cross-browser and mobile QA on all money pages", "Planned", "QA", "0%"),
            ("Core Web Vitals / Lighthouse audit on key pages", "Planned", "Performance", "0%"),
            ("Lead form end-to-end test on production (student + university)", "Planned", "QA", "0%"),
            ("Install and configure Slim SEO Pro (if licensed)", "Planned", "SEO", "0%"),
            ("Performance baseline in GSC (impressions, clicks, coverage)", "Planned", "SEO", "0%"),
        ],
        "deliverables": "Live site indexed in GSC; 2 new blog posts; deployment runbook; QA sign-off",
        "blockers": "Production domain/hosting access needed from client",
        "next": "Ongoing Phase 3 content (2 posts/month through Dec 2026)",
    },
]

PHASES = [
    ("Phase 1", "SEO Foundation", "Complete", "hlm-seo plugin, topic clusters, schema, sitemap, 16 blogs, Slim SEO compat"),
    ("Phase 2", "On-Page SEO", "Complete", "Keyword H1s, US copy, FAQs, internal links, CTAs on all 12 money pages"),
    ("Phase 3", "Content + GSC", "In Progress", "12-post calendar (Jul-Dec 2026); GSC setup pending live domain"),
    ("Phase 4", "Authority & Links", "Planned", "Outbound link building, partner pages, local SEO, review schema"),
]

METRICS = [
    ("Core pages live", "12", "12", "Home, About, Services, Affiliations, Contact, Blog, Privacy + 6 service details"),
    ("Blog posts published", "16", "28", "16 live; 12 planned in Phase 3 calendar"),
    ("Custom Gutenberg blocks", "19", "19", "Hero, CTA, Stats, Team, Services, etc."),
    ("Custom plugins", "3", "3", "hlm-seo, hlm-lead-capture, hlm-security"),
    ("Topic clusters", "4", "4", "Student Pathways, University ROI, HLM Brand, Commercial Intent"),
    ("Phase 2 money pages optimized", "12", "12", "All core + service detail pages"),
    ("Target market", "95% US", "95% US", "US English, TCPA, NCLEX, FAFSA context"),
]


def style_header(ws, row, cols, fill=NAVY, font_color=WHITE):
    for col in range(1, cols + 1):
        cell = ws.cell(row=row, column=col)
        cell.fill = PatternFill("solid", fgColor=fill)
        cell.font = Font(bold=True, color=font_color, size=11)
        cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
        cell.border = border


def set_col_widths(ws, widths):
    for i, w in enumerate(widths, 1):
        ws.column_dimensions[get_column_letter(i)].width = w


def status_fill(status):
    mapping = {
        "Complete": PatternFill("solid", fgColor="D1FAE5"),
        "In Progress": PatternFill("solid", fgColor="FEF3C7"),
        "Planned": PatternFill("solid", fgColor="E0E7FF"),
        "Blocked": PatternFill("solid", fgColor="FEE2E2"),
    }
    return mapping.get(status, PatternFill("solid", fgColor=LIGHT_GRAY))


def build_summary(wb):
    ws = wb.active
    ws.title = "Executive Summary"
    ws.sheet_view.showGridLines = False

    ws.merge_cells("A1:F1")
    t = ws["A1"]
    t.value = "Higher Learning Marketers (HLM Elite) - Weekly Progress Report"
    t.font = Font(bold=True, size=16, color=NAVY)
    t.alignment = Alignment(horizontal="left", vertical="center")

    ws.merge_cells("A2:F2")
    ws["A2"].value = f"Report Date: {date.today().strftime('%B %d, %Y')}  |  Project: WordPress Marketing Site  |  Status: Phase 2 Complete, Phase 3 Starting"
    ws["A2"].font = Font(size=11, color=GRAY)

    row = 4
    headers = ["Metric", "Current", "Target", "Notes"]
    for col, h in enumerate(headers, 1):
        ws.cell(row=row, column=col, value=h)
    style_header(ws, row, len(headers))
    row += 1

    for metric, current, target, notes in METRICS:
        ws.cell(row=row, column=1, value=metric).border = border
        ws.cell(row=row, column=2, value=current).border = border
        ws.cell(row=row, column=3, value=target).border = border
        c = ws.cell(row=row, column=4, value=notes)
        c.border = border
        c.alignment = Alignment(wrap_text=True)
        row += 1

    row += 1
    ws.cell(row=row, column=1, value="Phase Overview").font = Font(bold=True, size=12, color=NAVY)
    row += 1
    phase_headers = ["Phase", "Name", "Status", "Summary"]
    for col, h in enumerate(phase_headers, 1):
        ws.cell(row=row, column=col, value=h)
    style_header(ws, row, len(phase_headers), fill=BLUE)
    row += 1

    for phase, name, status, summary in PHASES:
        ws.cell(row=row, column=1, value=phase).border = border
        ws.cell(row=row, column=2, value=name).border = border
        sc = ws.cell(row=row, column=3, value=status)
        sc.border = border
        sc.fill = status_fill(status)
        sc.font = Font(bold=True)
        c = ws.cell(row=row, column=4, value=summary)
        c.border = border
        c.alignment = Alignment(wrap_text=True)
        row += 1

    row += 1
    ws.cell(row=row, column=1, value="Key Achievements (Last 2 Weeks)").font = Font(bold=True, size=12, color=NAVY)
    row += 1
    achievements = [
        "Built theme-agnostic HLM SEO Ecosystem plugin with Slim SEO compatibility",
        "Published 16 US-focused SEO blog posts across 4 topic clusters with featured images",
        "Completed Phase 2 on-page SEO on all 12 money pages including 6 service detail pages",
        "Updated leadership team, privacy policy, and normalized content formatting",
        "Delivered 12-post Phase 3 content calendar and GSC setup guide",
    ]
    for a in achievements:
        ws.merge_cells(start_row=row, start_column=1, end_row=row, end_column=4)
        cell = ws.cell(row=row, column=1, value=f"  - {a}")
        cell.alignment = Alignment(wrap_text=True)
        row += 1

    row += 1
    ws.cell(row=row, column=1, value="Priorities (Next 2 Weeks)").font = Font(bold=True, size=12, color=NAVY)
    row += 1
    priorities = [
        "Verify Google Search Console on live production domain",
        "Submit sitemap.xml and request indexing for 15 priority URLs",
        "Publish first 2 Phase 3 blog posts (university + student audiences)",
        "Deploy site to production hosting and run cross-browser QA",
        "Baseline GSC performance metrics for 90-day SEO tracking",
    ]
    for p in priorities:
        ws.merge_cells(start_row=row, start_column=1, end_row=row, end_column=4)
        ws.cell(row=row, column=1, value=f"  - {p}").alignment = Alignment(wrap_text=True)
        row += 1

    set_col_widths(ws, [32, 14, 14, 52])


def build_weekly(wb):
    ws = wb.create_sheet("Weekly Progress")
    ws.sheet_view.showGridLines = False

    headers = [
        "Week",
        "Date Range",
        "Phase",
        "Status",
        "Task / Deliverable",
        "Category",
        "% Complete",
        "Week Summary",
        "Blockers",
        "Next Week Focus",
    ]
    for col, h in enumerate(headers, 1):
        ws.cell(row=1, column=col, value=h)
    style_header(ws, 1, len(headers))

    row = 2
    for week in WEEKS:
        start_row = row
        tasks = week["tasks"]
        for i, (task, status, category, pct) in enumerate(tasks):
            ws.cell(row=row, column=5, value=task).border = border
            ws.cell(row=row, column=5).alignment = Alignment(wrap_text=True, vertical="top")
            sc = ws.cell(row=row, column=6, value=category)
            sc.border = border
            sc.alignment = Alignment(horizontal="center")
            pc = ws.cell(row=row, column=7, value=pct)
            pc.border = border
            pc.alignment = Alignment(horizontal="center")
            st = ws.cell(row=row, column=4, value=status if i == 0 else "")
            if i == 0:
                st.fill = status_fill(week["status"])
                st.font = Font(bold=True)
            st.border = border
            st.alignment = Alignment(horizontal="center", vertical="top")
            row += 1

        end_row = row - 1
        merge_cols = {
            1: week["label"],
            2: week["dates"],
            3: week["phase"],
            8: week["summary"],
            9: week["blockers"],
            10: week["next"],
        }
        for col, val in merge_cols.items():
            if start_row < end_row:
                ws.merge_cells(start_row=start_row, start_column=col, end_row=end_row, end_column=col)
            cell = ws.cell(row=start_row, column=col, value=val)
            cell.border = border
            cell.alignment = Alignment(wrap_text=True, vertical="top")
            if col == 1:
                cell.font = Font(bold=True, color=NAVY)

        for r in range(start_row, end_row + 1):
            for c in range(1, 11):
                if ws.cell(row=r, column=c).border == Border():
                    ws.cell(row=r, column=c).border = border

    ws.freeze_panes = "A2"
    set_col_widths(ws, [14, 16, 18, 12, 48, 12, 10, 36, 24, 28])


def build_content_calendar(wb):
    ws = wb.create_sheet("Phase 3 Content Calendar")
    ws.sheet_view.showGridLines = False

    headers = ["Month", "Post Title", "Audience", "Target Keyword", "Cluster", "Status", "Word Target"]
    for col, h in enumerate(headers, 1):
        ws.cell(row=1, column=col, value=h)
    style_header(ws, 1, len(headers))

    posts = [
        ("Jul 2026", "How to Evaluate Education Lead Vendors", "University (B2B)", "education lead vendors", "Commercial Intent", "Planned", "900-1200"),
        ("Jul 2026", "First-Generation Student Guide to Choosing a College", "Student (B2C)", "first generation college guide", "Student Pathways", "Planned", "900-1200"),
        ("Aug 2026", "Enrollment Marketing Budget Planning for Small Colleges", "University (B2B)", "enrollment marketing budget", "University ROI", "Planned", "900-1200"),
        ("Aug 2026", "Transfer Student Checklist: Credits, Deadlines, and Aid", "Student (B2C)", "transfer student checklist", "Student Pathways", "Planned", "900-1200"),
        ("Sep 2026", "CRM Integration for Education Lead Delivery", "University (B2B)", "education CRM lead integration", "Commercial Intent", "Planned", "900-1200"),
        ("Sep 2026", "Online Degree vs Traditional: Cost and Outcomes", "Student (B2C)", "online degree vs traditional", "Student Pathways", "Planned", "900-1200"),
        ("Oct 2026", "Building an Enrollment Funnel That Converts", "University (B2B)", "enrollment funnel conversion", "University ROI", "Planned", "900-1200"),
        ("Oct 2026", "Scholarship Search Tips for Nursing Students", "Student (B2C)", "nursing scholarship tips", "Student Pathways", "Planned", "900-1200"),
        ("Nov 2026", "Compliance Checklist for Education Lead Generation", "University (B2B)", "education lead compliance", "Commercial Intent", "Planned", "900-1200"),
        ("Nov 2026", "Working Adult's Guide to Accelerated Degree Programs", "Student (B2C)", "accelerated degree programs", "Student Pathways", "Planned", "900-1200"),
        ("Dec 2026", "Year-End Enrollment Marketing Review Template", "University (B2B)", "enrollment marketing review", "University ROI", "Planned", "900-1200"),
        ("Dec 2026", "New Year College Planning Timeline for High School Seniors", "Student (B2C)", "college planning timeline", "Student Pathways", "Planned", "900-1200"),
    ]

    row = 2
    for post in posts:
        for col, val in enumerate(post, 1):
            cell = ws.cell(row=row, column=col, value=val)
            cell.border = border
            cell.alignment = Alignment(wrap_text=True, vertical="top")
            if col == 6:
                cell.fill = status_fill("Planned")
        row += 1

    ws.freeze_panes = "A2"
    set_col_widths(ws, [12, 44, 18, 28, 18, 12, 12])


def build_gsc_checklist(wb):
    ws = wb.create_sheet("GSC Setup Checklist")
    ws.sheet_view.showGridLines = False

    ws.merge_cells("A1:C1")
    ws["A1"].value = "Google Search Console - Go-Live Checklist"
    ws["A1"].font = Font(bold=True, size=14, color=NAVY)

    headers = ["Step", "Action", "Status"]
    row = 3
    for col, h in enumerate(headers, 1):
        ws.cell(row=row, column=col, value=h)
    style_header(ws, row, len(headers))
    row += 1

    steps = [
        ("1", "Add property in GSC (URL prefix: https://higherlearningmarketers.com/)", "Pending"),
        ("2", "Verify ownership (DNS TXT record or HTML file upload)", "Pending"),
        ("3", "Submit sitemap: sitemap.xml only (Slim SEO - do NOT submit wp-sitemap.xml)", "Pending"),
        ("4", "Request indexing for Home (/)", "Pending"),
        ("4", "Request indexing for /services/", "Pending"),
        ("4", "Request indexing for /contact-us/", "Pending"),
        ("4", "Request indexing for /about-us/", "Pending"),
        ("4", "Request indexing for /affiliations/", "Pending"),
        ("4", "Request indexing for /blog/", "Pending"),
        ("4", "Request indexing for /services/lead-generation/", "Pending"),
        ("4", "Request indexing for /services/brand-awareness/", "Pending"),
        ("4", "Request indexing for /services/multi-channel-marketing/", "Pending"),
        ("4", "Request indexing for /services/analytics-reporting/", "Pending"),
        ("4", "Request indexing for /services/partner-network/", "Pending"),
        ("4", "Request indexing for /services/enrollment-funnel/", "Pending"),
        ("4", "Request indexing for top blog: /student-lead-generation-universities-usa/", "Pending"),
        ("4", "Request indexing for top blog: /how-hlm-helps-universities-fill-programs/", "Pending"),
        ("5", "Confirm Coverage report shows indexed pages (allow 3-7 days)", "Pending"),
        ("6", "Set up email alerts for coverage and manual actions", "Pending"),
    ]

    for step, action, status in steps:
        ws.cell(row=row, column=1, value=step).border = border
        ws.cell(row=row, column=1).alignment = Alignment(horizontal="center")
        c = ws.cell(row=row, column=2, value=action)
        c.border = border
        c.alignment = Alignment(wrap_text=True)
        sc = ws.cell(row=row, column=3, value=status)
        sc.border = border
        sc.fill = status_fill("Planned")
        sc.alignment = Alignment(horizontal="center")
        row += 1

    set_col_widths(ws, [8, 64, 14])


def normalize_workbook_dashes(wb):
    """Normalize every string cell before save."""
    for ws in wb.worksheets:
        for row in ws.iter_rows():
            for cell in row:
                if isinstance(cell.value, str):
                    cell.value = normalize_dashes(cell.value)


def scan_workbook_for_bad_dashes(wb):
    """Fail fast if any em/en dash slipped into generated output."""
    bad = []
    for ws in wb.worksheets:
        for row in ws.iter_rows():
            for cell in row:
                if isinstance(cell.value, str) and _DASH_RE.search(cell.value):
                    bad.append(f"{ws.title}!{cell.coordinate}: {cell.value[:80]}")
    if bad:
        raise ValueError("Em/en dash found in report output:\n" + "\n".join(bad))


def main():
    wb = Workbook()
    build_summary(wb)
    build_weekly(wb)
    build_content_calendar(wb)
    build_gsc_checklist(wb)
    normalize_workbook_dashes(wb)
    scan_workbook_for_bad_dashes(wb)
    wb.save(OUTPUT)
    print(f"Report saved to: {OUTPUT}")


if __name__ == "__main__":
    main()
