#!/usr/bin/env python3
"""Generate universal SEO plan Excel template (any website)."""

from openpyxl import Workbook
from openpyxl.styles import Alignment, Font, PatternFill

OUTPUT = "/home/buildco/highereducation_eilte/docs/Universal-SEO-Plan-Template.xlsx"
HEADER_FILL = PatternFill("solid", fgColor="1F4E79")
HEADER_FONT = Font(color="FFFFFF", bold=True)
SUB_FILL = PatternFill("solid", fgColor="D9E2F3")


def style_header(ws):
    for cell in ws[1]:
        cell.fill = HEADER_FILL
        cell.font = HEADER_FONT
        cell.alignment = Alignment(wrap_text=True, vertical="center")


def width(ws, widths):
    from openpyxl.utils import get_column_letter
    for i, w in enumerate(widths, 1):
        ws.column_dimensions[get_column_letter(i)].width = w


def build_kickoff(ws):
    ws.title = "Project Kickoff"
    rows = [
        ("SITE DETAILS", ""),
        ("Site name", ""),
        ("Live URL (canonical)", "https://"),
        ("Primary market / country", ""),
        ("Primary conversion goal", "form / call / purchase / signup"),
        ("Tech stack", "WordPress / Shopify / Next.js / etc."),
        ("SEO tool / plugin", ""),
        ("Analytics", "GA4 / Plausible / etc."),
        ("Sitemap URL", "/sitemap.xml"),
        ("", ""),
        ("SEO GOAL (one sentence)", "When someone in [market] searches [keyword], they land on [URL] and [action]."),
        ("", ""),
        ("AUDIENCE A", ""),
        ("Who", ""),
        ("What they search", ""),
        ("Money page", ""),
        ("", ""),
        ("AUDIENCE B", ""),
        ("Who", ""),
        ("What they search", ""),
        ("Money page", ""),
        ("", ""),
        ("TOP 5 PRIORITY KEYWORDS", ""),
        ("1.", ""),
        ("2.", ""),
        ("3.", ""),
        ("4.", ""),
        ("5.", ""),
        ("", ""),
        ("DATES", ""),
        ("Launch date", ""),
        ("GSC verified (Y/N)", ""),
        ("Sitemap submitted (Y/N)", ""),
        ("Content cadence", "2 posts/month"),
    ]
    for i, (a, b) in enumerate(rows, 1):
        ws.cell(i, 1, a)
        ws.cell(i, 2, b)
        if a.isupper() and a.endswith(")"):
            ws.cell(i, 1).fill = SUB_FILL
            ws.cell(i, 1).font = Font(bold=True)
    width(ws, [28, 70])


def build_phases(ws):
    ws.title = "Phases 1-5"
    ws.append(["Phase", "Timeframe", "Focus", "Key tasks", "Exit criteria", "Status", "Done?"])
    style_header(ws)
    data = [
        ("1", "Week 1-2", "Technical", "HTTPS, speed, sitemap, robots.txt, GSC verify, analytics", "Sitemap Success; no critical crawl errors", "", ""),
        ("2", "Week 2-4", "On-page", "Title, meta, H1, intro, internal links, CTA, schema on Tier 1", "All money pages pass on-page checklist", "", ""),
        ("3", "Month 2-6", "Content", "2-4 posts/month, topic clusters, featured images, GSC per post", "8-20 quality pages; impressions growing", "", ""),
        ("4", "Month 3-12", "Authority", "Directories, partner links, PR, guest posts", "10-20 quality referring domains", "", ""),
        ("5", "Ongoing", "Measure", "GSC weekly, conversions monthly, audit quarterly", "Organic conversions trending up", "", ""),
    ]
    for r in data:
        ws.append(r)
    width(ws, [8, 12, 12, 45, 35, 12, 8])


def build_90day(ws):
    ws.title = "90-Day Plan"
    ws.append(["Week", "Phase", "Task", "Owner", "Status", "Done?", "Notes"])
    style_header(ws)
    tasks = [
        ("1", "1", "Audit HTTPS, mobile, canonical URLs", "", "", "", ""),
        ("1", "1", "Create/fix XML sitemap", "", "", "", ""),
        ("1", "1", "Review robots.txt", "", "", "", ""),
        ("1", "1", "Install analytics", "", "", "", ""),
        ("2", "1", "Verify Google Search Console", "", "", "", ""),
        ("2", "1", "Submit sitemap in GSC", "", "", "", ""),
        ("2", "1", "Run PageSpeed / Core Web Vitals check", "", "", "", ""),
        ("2-3", "2", "Build keyword map (Tier 1-3)", "", "", "", ""),
        ("2-3", "2", "Optimize Tier 1 pages (title, H1, meta, CTA)", "", "", "", ""),
        ("2-3", "2", "Add internal link map", "", "", "", ""),
        ("3-4", "2", "Add schema (Organization, WebPage, Article, FAQ)", "", "", "", ""),
        ("3-4", "2", "Request indexing for money pages in GSC", "", "", "", ""),
        ("4-8", "3", "Publish content on calendar (2-4/month)", "", "", "", ""),
        ("4-8", "3", "Request GSC indexing per new URL", "", "", "", ""),
        ("6-12", "4", "Google Business Profile (if local)", "", "", "", ""),
        ("6-12", "4", "Directory listings + partner backlinks", "", "", "", ""),
        ("Ongoing", "5", "GSC weekly review (15 min)", "", "", "", ""),
        ("Ongoing", "5", "Monthly keyword + conversion review", "", "", "", ""),
    ]
    for t in tasks:
        ws.append(t)
    width(ws, [8, 8, 42, 12, 12, 8, 30])


def build_keywords(ws):
    ws.title = "Keyword Map"
    ws.append(["Tier", "URL", "Page title", "Primary keyword", "Secondary keywords", "Audience", "Intent", "Priority", "Status", "Notes"])
    style_header(ws)
    for _ in range(25):
        ws.append(["", "", "", "", "", "", "", "", "", ""])
    width(ws, [8, 28, 30, 28, 25, 14, 14, 10, 10, 25])


def build_pages(ws):
    ws.title = "Page Checklist"
    ws.append([
        "URL", "Page type", "Tier", "Title tag", "Meta desc", "One H1", "Intro 100 words",
        "Internal links 3+", "Images alt", "CTA", "Schema", "In sitemap", "GSC indexed", "Request indexing", "Notes",
    ])
    style_header(ws)
    for _ in range(20):
        ws.append([""] * 15)
    width(ws, [30, 12, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 20])


def build_content(ws):
    ws.title = "Content Calendar"
    ws.append([
        "Publish date", "Status", "Audience", "Title", "URL slug", "Primary keyword", "Cluster/pillar",
        "Target words", "Written", "Published", "In sitemap", "GSC request indexing", "Internal links added", "Notes",
    ])
    style_header(ws)
    for _ in range(24):
        ws.append([""] * 14)
    width(ws, [12, 10, 12, 35, 25, 25, 18, 10, 8, 8, 8, 8, 8, 25])


def build_gsc(ws):
    ws.title = "GSC Setup"
    ws.append(["Step", "Action", "Detail", "Status", "Done?", "Notes"])
    style_header(ws)
    steps = [
        ("1", "Add GSC property", "URL prefix or Domain", "", "", ""),
        ("2", "Verify ownership", "DNS TXT / HTML meta / file", "", "", ""),
        ("3", "Submit ONE sitemap", "e.g. sitemap.xml - not duplicates", "", "", ""),
        ("4", "Request indexing", "Home + money pages + hub", "", "", "URL Inspection once per URL"),
        ("4", "Request indexing", "Each new blog when published", "", "", ""),
        ("5", "Check Pages report", "Fix not indexed URLs", "", "", ""),
        ("6", "Link GA4", "GSC Settings > Associations", "", "", ""),
        ("7", "Weekly review", "Performance 28 days; filter country", "", "", ""),
    ]
    for s in steps:
        ws.append(s)
    width(ws, [8, 22, 40, 12, 8, 35])


def build_onpage(ws):
    ws.title = "On-Page Reference"
    ws.append(["Element", "Rule", "Example"])
    style_header(ws)
    refs = [
        ("Title tag", "50-60 chars; keyword front; brand end", "Plumbing Repair Chicago | ABC Plumbing"),
        ("Meta description", "150-160 chars; benefit + CTA", "24/7 emergency plumbing in Chicago. Licensed, insured. Call now."),
        ("H1", "One per page; matches intent", "Emergency Plumbing Services in Chicago"),
        ("First 100 words", "Topic + location + value", "Clear who you help and what you do"),
        ("Internal links", "3-5 per page to related + money pages", "Service links, blog links, contact"),
        ("Images", "Descriptive filename + alt text", "emergency-plumber-chicago.jpg"),
        ("URL", "Short, lowercase, hyphens", "/services/emergency-plumbing/"),
        ("Schema", "Organization, WebPage, Article, FAQ, Product, LocalBusiness", "JSON-LD in head"),
    ]
    for r in refs:
        ws.append(r)
    width(ws, [18, 45, 45])


def main():
    wb = Workbook()
    build_kickoff(wb.active)
    build_phases(wb.create_sheet())
    build_90day(wb.create_sheet())
    build_keywords(wb.create_sheet())
    build_pages(wb.create_sheet())
    build_content(wb.create_sheet())
    build_gsc(wb.create_sheet())
    build_onpage(wb.create_sheet())
    wb.save(OUTPUT)
    print(f"Created {OUTPUT}")


if __name__ == "__main__":
    main()
