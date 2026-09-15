#!/usr/bin/env python3
"""Export Flask content.py data as JSON for the PHP site."""
from __future__ import annotations

import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
sys.path.insert(0, str(ROOT))

from app import content  # noqa: E402

out = {
    "SITE": content.SITE,
    "CONTACT": content.CONTACT,
    "NAV": content.NAV,
    "MEDIA": content.MEDIA,
    "US_STATES": content.US_STATES,
    "CA_PROVINCES": content.CA_PROVINCES,
    "STATE_OPTIONS": content.STATE_OPTIONS,
    "GRAD_YEARS": content.GRAD_YEARS,
    "TCPA_TEXT_STUDENT": content.TCPA_TEXT_STUDENT,
    "SERVICE_CATALOG": content.SERVICE_CATALOG,
    "SERVICE_SLUGS": content.SERVICE_SLUGS,
    "BLOG_POSTS": content.BLOG_POSTS,
    "SEO_DATA": content.SEO_DATA,
    "SEO_LINKS": content.SEO_LINKS,
    "DEFAULT_STATS": content.DEFAULT_STATS,
    "NETWORK_STATS": content.NETWORK_STATS,
    "INSTITUTIONS": content.INSTITUTIONS,
    "PROGRAMS": content.PROGRAMS,
    "HOME_PROCESS_STEPS": content.HOME_PROCESS_STEPS,
    "SERVICES_PROCESS_STEPS": content.SERVICES_PROCESS_STEPS,
    "TESTIMONIALS": content.TESTIMONIALS,
    "SERVICE_CARDS": content.SERVICE_CARDS,
    "ABOUT_VALUES": content.ABOUT_VALUES,
    "TIMELINE": content.TIMELINE,
    "LEADERSHIP": content.LEADERSHIP,
    "PARTNERS": content.PARTNERS,
    "AFFILIATION_BENEFITS": content.AFFILIATION_BENEFITS,
    "PRIVACY_SECTIONS": content.PRIVACY_SECTIONS,
}

dest = ROOT / "site" / "data" / "content-bundle.json"
dest.parent.mkdir(parents=True, exist_ok=True)
dest.write_text(json.dumps(out, ensure_ascii=False, indent=2), encoding="utf-8")
print(f"Wrote {dest} ({dest.stat().st_size} bytes)")
