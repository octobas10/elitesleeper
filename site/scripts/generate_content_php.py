#!/usr/bin/env python3
"""Generate site/lib/content.php from app/content.py."""

from __future__ import annotations

import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
sys.path.insert(0, str(ROOT))

from app import content  # noqa: E402


def php_export(value, indent: int = 0) -> str:
    sp = "    " * indent
    if isinstance(value, dict):
        if not value:
            return "[]"
        lines = ["["]
        for k, v in value.items():
            key = json.dumps(str(k), ensure_ascii=False)
            lines.append(f"{sp}    {key} => {php_export(v, indent + 1)},")
        lines.append(f"{sp}]")
        return "\n".join(lines)
    if isinstance(value, list):
        if not value:
            return "[]"
        lines = ["["]
        for item in value:
            lines.append(f"{sp}    {php_export(item, indent + 1)},")
        lines.append(f"{sp}]")
        return "\n".join(lines)
    if isinstance(value, bool):
        return "true" if value else "false"
    if value is None:
        return "null"
    if isinstance(value, (int, float)):
        return str(value)
    return json.dumps(str(value), ensure_ascii=False)


def main() -> None:
    out = ROOT / "site" / "lib" / "content.php"
    names = [
        "SITE",
        "CONTACT",
        "NAV",
        "MEDIA",
        "US_STATES",
        "CA_PROVINCES",
        "STATE_OPTIONS",
        "GRAD_YEARS",
        "TCPA_TEXT_STUDENT",
        "SERVICE_CATALOG",
        "SERVICE_SLUGS",
        "BLOG_POSTS",
        "SEO_DATA",
        "SEO_LINKS",
        "DEFAULT_STATS",
        "NETWORK_STATS",
        "INSTITUTIONS",
        "PROGRAMS",
        "HOME_PROCESS_STEPS",
        "SERVICES_PROCESS_STEPS",
        "TESTIMONIALS",
        "SERVICE_CARDS",
        "ABOUT_VALUES",
        "TIMELINE",
        "LEADERSHIP",
        "PARTNERS",
        "AFFILIATION_BENEFITS",
        "PRIVACY_SECTIONS",
    ]
    aliases = {
        "SITE": "site",
        "CONTACT": "contact",
        "NAV": "nav",
        "MEDIA": "media",
        "BLOG_POSTS": "blog_posts",
        "DEFAULT_STATS": "default_stats",
        "NETWORK_STATS": "network_stats",
        "INSTITUTIONS": "institutions",
        "PROGRAMS": "programs",
        "HOME_PROCESS_STEPS": "home_process_steps",
        "SERVICES_PROCESS_STEPS": "services_process_steps",
        "TESTIMONIALS": "testimonials",
        "SERVICE_CARDS": "service_cards",
        "ABOUT_VALUES": "about_values",
        "TIMELINE": "timeline",
        "LEADERSHIP": "leadership",
        "PARTNERS": "partners",
        "AFFILIATION_BENEFITS": "affiliation_benefits",
        "PRIVACY_SECTIONS": "privacy_sections",
        "SERVICE_CATALOG": "service_catalog",
        "STATE_OPTIONS": "state_options",
        "GRAD_YEARS": "grad_years",
        "TCPA_TEXT_STUDENT": "tcpa_text_student",
    }

    parts = [
        "<?php",
        "",
        "declare(strict_types=1);",
        "",
        "/** Auto-generated from app/content.py — do not edit by hand. */",
        "",
    ]

    for name in names:
        val = getattr(content, name)
        parts.append(f"const {name} = {php_export(val)};")
        parts.append("")

    for const, alias in aliases.items():
        parts.append(f"${alias} = {const};")
        parts.append(f"$GLOBALS['{alias}'] = ${alias};")
        parts.append("")

    parts.extend(
        [
            "function get_page_seo(string $slug): array",
            "{",
            "    $pages = SEO_DATA['pages'] ?? [];",
            "    return $pages[$slug] ?? [",
            "        'title' => SITE['name'],",
            "        'description' => SITE['description'],",
            "    ];",
            "}",
            "",
            "/** @return list<array<string, string>> */",
            "function get_page_faqs(string $slug): array",
            "{",
            "    $faqs = SEO_DATA['faqs'] ?? [];",
            "    return array_values($faqs[$slug] ?? []);",
            "}",
            "",
            "function get_page_intro(string $slug): string",
            "{",
            "    $intros = SEO_DATA['intros'] ?? [];",
            "    return (string) ($intros[$slug] ?? '');",
            "}",
            "",
            "/** @return array<string, mixed>|null */",
            "function get_blog_post(string $slug): ?array",
            "{",
            "    foreach (BLOG_POSTS as $post) {",
            "        if (($post['slug'] ?? '') === $slug) {",
            "            return $post;",
            "        }",
            "    }",
            "    return null;",
            "}",
            "",
            "/** @return array<string, mixed>|null */",
            "function get_service(string $slug): ?array",
            "{",
            "    return SERVICE_CATALOG[$slug] ?? null;",
            "}",
            "",
        ]
    )

    out.write_text("\n".join(parts), encoding="utf-8")
    print(f"Wrote {out}")


if __name__ == "__main__":
    main()
