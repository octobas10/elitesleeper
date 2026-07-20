"""Internal link helpers — ported from Astro lib/internal-links.ts."""

from __future__ import annotations

from functools import lru_cache
from typing import Any

from app import content

PAGE_TITLES: dict[str, str] = {
    "home": "Home",
    "about-us": "About Us",
    "services": "Services",
    "affiliations": "Affiliations",
    "contact-us": "Contact Us",
    "blog": "Blog",
    "privacy-policy": "Privacy Policy",
    "lead-generation": "Lead Generation",
    "brand-awareness": "Brand Awareness",
    "multi-channel-marketing": "Multi-Channel Marketing",
    "analytics-reporting": "Analytics & Reporting",
    "partner-network": "Partner Network",
    "enrollment-funnel": "Enrollment Funnel",
}

FEATURED_IMAGE_KEYS = frozenset({"student", "university", "hlm", "compliance"})


@lru_cache(maxsize=1)
def _seo_links() -> dict[str, Any]:
    return content.SEO_LINKS


def resolve_slug_url(slug: str) -> str:
    if slug.startswith("services/"):
        return f"/{slug}/"
    if slug in PAGE_TITLES or slug in {
        "home",
        "about-us",
        "services",
        "affiliations",
        "contact-us",
        "blog",
        "privacy-policy",
    }:
        return "/" if slug == "home" else f"/{slug}/"
    return f"/blog/{slug}/"


def resolve_link_label(slug: str, anchor: str | None = None) -> str:
    if anchor:
        return anchor
    titles = _seo_links().get("titleBySlug") or {}
    return titles.get(slug) or PAGE_TITLES.get(slug) or slug.replace("-", " ")


def get_blog_cluster_nav(post_slug: str) -> list[dict[str, Any]]:
    titles = _seo_links().get("titleBySlug") or {}
    clusters = _seo_links().get("clusters") or {}
    for cluster in clusters.values():
        posts = cluster.get("posts") or []
        if post_slug not in posts:
            continue
        siblings = [s for s in posts if s != post_slug]
        related = (cluster.get("linksTo") or {}).get(post_slug) or []
        items: list[dict[str, Any]] = [
            {
                "href": cluster.get("pillarUrl") or "/blog/",
                "label": cluster.get("label") or "Blog",
                "featured": True,
            },
            *[
                {
                    "href": f"/blog/{s}/",
                    "label": titles.get(s) or s,
                    "featured": False,
                }
                for s in siblings[:4]
            ],
            *[
                {
                    "href": resolve_slug_url(s),
                    "label": resolve_link_label(s),
                    "featured": False,
                }
                for s in related[:3]
            ],
        ]
        seen: set[str] = set()
        out: list[dict[str, Any]] = []
        for item in items:
            if item["href"] in seen:
                continue
            seen.add(item["href"])
            out.append(item)
        return out
    return []


def get_blog_cluster_label(post_slug: str) -> str:
    clusters = _seo_links().get("clusters") or {}
    for cluster in clusters.values():
        if post_slug in (cluster.get("posts") or []):
            return str(cluster.get("label") or "Blog")
    return "Blog"


def get_related_blog_posts(post_slug: str, limit: int = 3) -> list[dict[str, Any]]:
    clusters = _seo_links().get("clusters") or {}
    for cluster in clusters.values():
        posts = cluster.get("posts") or []
        if post_slug not in posts:
            continue
        siblings = [s for s in posts if s != post_slug]
        found = []
        for s in siblings:
            post = content.get_blog_post(s)
            if post:
                found.append(post)
            if len(found) >= limit:
                break
        if found:
            return found
    return [p for p in content.blog_posts if p.get("slug") != post_slug][:limit]


def featured_image_path(variant: str = "student") -> str:
    key = variant if variant in FEATURED_IMAGE_KEYS else "student"
    return f"images/blog/featured-{key}.svg"
