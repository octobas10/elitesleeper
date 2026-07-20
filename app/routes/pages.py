"""Public page routes."""

from __future__ import annotations

from flask import Blueprint, abort, current_app, render_template

from app import content
from app.services.institutions import _load as load_institution_data
from app.services.institutions import get_institutions

pages_bp = Blueprint("pages", __name__)


def _form_context() -> dict:
    return {
        "institutions": get_institutions(),
        "state_options": content.STATE_OPTIONS,
        "grad_years": content.GRAD_YEARS,
        "tcpa_text": content.TCPA_TEXT_STUDENT,
        "institution_data": load_institution_data(),
        "turnstile_site_key": current_app.config.get("PUBLIC_TURNSTILE_SITE_KEY") or "",
    }


def _seo_ctx(slug: str, path: str, **extra) -> dict:
    seo = content.get_page_seo(slug)
    return {
        "page_title": seo.get("title"),
        "page_description": seo.get("description"),
        "seo_slug": slug,
        "path": path,
        "faqs": content.get_page_faqs(slug),
        "page_intro": content.get_page_intro(slug),
        **extra,
    }


@pages_bp.get("/")
def home():
    ctx = _seo_ctx(
        "home",
        "/",
        transparent_header=True,
        body_class="hlm-landing-page home",
        stats=content.default_stats,
        institutions_list=content.institutions,
        programs=content.programs,
        process_steps=content.home_process_steps,
        testimonials=content.testimonials,
        blog_posts=content.blog_posts[:3],
        seo_intro=content.get_page_intro("home"),
        **_form_context(),
    )
    return render_template("pages/home.html", **ctx)


@pages_bp.get("/about-us/")
def about():
    ctx = _seo_ctx(
        "about-us",
        "/about-us/",
        about_values=content.about_values,
        timeline=content.timeline,
        leadership=content.leadership,
        hero_image=content.media["page_about"],
    )
    return render_template("pages/about.html", **ctx)


@pages_bp.get("/services/")
def services():
    ctx = _seo_ctx(
        "services",
        "/services/",
        service_cards=content.service_cards,
        process_steps=content.services_process_steps,
        network_stats=content.network_stats,
        hero_image=content.media["page_services"],
    )
    return render_template("pages/services.html", **ctx)


@pages_bp.get("/services/<slug>/")
def service_detail(slug: str):
    service = content.get_service(slug)
    if not service:
        abort(404)
    ctx = _seo_ctx(
        slug,
        f"/services/{slug}/",
        service=service,
        service_cards=content.service_cards,
        hero_image=content.media["page_services"],
    )
    return render_template("pages/service_detail.html", **ctx)


@pages_bp.get("/affiliations/")
def affiliations():
    ctx = _seo_ctx(
        "affiliations",
        "/affiliations/",
        partners=content.partners,
        affiliation_benefits=content.affiliation_benefits,
        hero_image=content.media["page_affiliations"],
    )
    return render_template("pages/affiliations.html", **ctx)


@pages_bp.get("/contact-us/")
def contact_page():
    ctx = _seo_ctx(
        "contact-us",
        "/contact-us/",
        hero_image=content.media["page_contact"],
        **_form_context(),
    )
    return render_template("pages/contact.html", **ctx)


@pages_bp.get("/blog/")
def blog_index():
    ctx = _seo_ctx(
        "blog",
        "/blog/",
        blog_posts=content.blog_posts,
    )
    return render_template("pages/blog_index.html", **ctx)


@pages_bp.get("/blog/<slug>/")
def blog_post(slug: str):
    from app import links as link_helpers

    post = content.get_blog_post(slug)
    if not post:
        abort(404)

    intent = post.get("intent") or "informational"
    featured_variant = post.get("featuredImage") or "student"
    ctx = _seo_ctx(
        "blog",
        f"/blog/{slug}/",
        page_title=post.get("title"),
        page_description=post.get("excerpt") or post.get("description"),
        post=post,
        og_type="article",
        body_class="hlm-single-post",
        cluster_label=link_helpers.get_blog_cluster_label(slug),
        topics=link_helpers.get_blog_cluster_nav(slug),
        related_posts=link_helpers.get_related_blog_posts(slug, 3),
        featured_image=link_helpers.featured_image_path(featured_variant),
        featured_image_alt=post.get("title"),
        cta_primary_url="/services/" if intent == "commercial" else "/contact-us/#form",
        cta_primary_text="Explore Services" if intent == "commercial" else "Join Now",
    )
    return render_template("pages/blog_post.html", **ctx)


@pages_bp.get("/privacy-policy/")
def privacy():
    ctx = _seo_ctx(
        "privacy-policy",
        "/privacy-policy/",
        privacy_sections=content.privacy_sections,
    )
    return render_template("pages/privacy.html", **ctx)


@pages_bp.get("/thank-you/")
def thank_you():
    return render_template(
        "pages/thank_you.html",
        page_title="Thank You",
        page_description="We received your submission.",
        path="/thank-you/",
        noindex=True,
    )


@pages_bp.app_errorhandler(404)
def not_found(_err):
    return (
        render_template(
            "pages/404.html",
            page_title="Page Not Found",
            page_description="The page you requested could not be found.",
            path="/404",
            noindex=True,
        ),
        404,
    )


@pages_bp.get("/robots.txt")
def robots():
    from flask import Response

    body = (
        "User-agent: *\n"
        "Allow: /\n"
        "Disallow: /admin/\n"
        f"Sitemap: {current_app.config.get('SITE_URL', content.SITE['url'])}/sitemap.xml\n"
    )
    return Response(body, mimetype="text/plain")


@pages_bp.get("/sitemap.xml")
def sitemap():
    from flask import Response

    base = current_app.config.get("SITE_URL", content.SITE["url"]).rstrip("/")
    urls = [
        "/",
        "/about-us/",
        "/services/",
        "/affiliations/",
        "/contact-us/",
        "/blog/",
        "/privacy-policy/",
    ]
    for slug in content.SERVICE_CATALOG:
        urls.append(f"/services/{slug}/")
    for post in content.blog_posts:
        urls.append(f"/blog/{post['slug']}/")

    items = "\n".join(f"  <url><loc>{base}{u}</loc></url>" for u in urls)
    xml = (
        '<?xml version="1.0" encoding="UTF-8"?>\n'
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n'
        f"{items}\n"
        "</urlset>\n"
    )
    return Response(xml, mimetype="application/xml")
