<?php

declare(strict_types=1);

require_once SITE_ROOT . '/services/institutions.php';

/** @return array<string, mixed> */
function form_context(): array
{
    return [
        'institutions' => get_institutions(),
        'state_options' => STATE_OPTIONS,
        'grad_years' => GRAD_YEARS,
        'tcpa_text' => TCPA_TEXT_STUDENT,
        'institution_data' => load_institution_data(),
        'turnstile_site_key' => Config::get('PUBLIC_TURNSTILE_SITE_KEY') ?: '',
    ];
}

/** @param array<string, mixed> $extra */
function seo_ctx(string $slug, string $path, array $extra = []): array
{
    $seo = get_page_seo($slug);
    return array_merge([
        'page_title' => $seo['title'] ?? null,
        'page_description' => $seo['description'] ?? null,
        'seo_slug' => $slug,
        'path' => $path,
        'faqs' => get_page_faqs($slug),
        'page_intro' => get_page_intro($slug),
    ], $extra);
}

function handle_robots(): never
{
    $siteUrl = Config::get('SITE_URL', SITE['url']);
    $body = "User-agent: *\n"
        . "Allow: /\n"
        . "Disallow: /admin/\n"
        . "Sitemap: {$siteUrl}/sitemap.xml\n";
    header('Content-Type: text/plain; charset=utf-8');
    echo $body;
    exit;
}

function handle_sitemap(): never
{
    $base = rtrim((string) Config::get('SITE_URL', SITE['url']), '/');
    $urls = [
        '/',
        '/about-us/',
        '/services/',
        '/affiliations/',
        '/contact-us/',
        '/blog/',
        '/privacy-policy/',
    ];
    foreach (SERVICE_CATALOG as $slug => $_service) {
        $urls[] = '/services/' . $slug . '/';
    }
    foreach (BLOG_POSTS as $post) {
        $urls[] = '/blog/' . $post['slug'] . '/';
    }

    $items = implode("\n", array_map(static fn ($u) => "  <url><loc>{$base}{$u}</loc></url>", $urls));
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
        . $items . "\n"
        . "</urlset>\n";

    header('Content-Type: application/xml; charset=utf-8');
    echo $xml;
    exit;
}

function render_not_found(): void
{
    http_response_code(404);
    render('pages/404', [
        'page_title' => 'Page Not Found',
        'page_description' => 'The page you requested could not be found.',
        'path' => '/404',
        'noindex' => true,
    ]);
}

function handle_pages(string $path): bool
{
    if ($path === '/robots.txt') {
        handle_robots();
    }
    if ($path === '/sitemap.xml') {
        handle_sitemap();
    }

    switch ($path) {
        case '/':
        case '':
            render('pages/home', seo_ctx('home', '/', array_merge([
                'transparent_header' => true,
                'body_class' => 'hlm-landing-page home',
                'stats' => $GLOBALS['default_stats'],
                'institutions_list' => $GLOBALS['institutions'],
                'programs' => $GLOBALS['programs'],
                'process_steps' => $GLOBALS['home_process_steps'],
                'testimonials' => $GLOBALS['testimonials'],
                'blog_posts' => array_slice(BLOG_POSTS, 0, 3),
                'seo_intro' => get_page_intro('home'),
            ], form_context())));
            return true;

        case '/about-us':
        case '/about-us/':
            render('pages/about', seo_ctx('about-us', '/about-us/', [
                'about_values' => $GLOBALS['about_values'],
                'timeline' => $GLOBALS['timeline'],
                'leadership' => $GLOBALS['leadership'],
                'hero_image' => MEDIA['page_about'],
            ]));
            return true;

        case '/services':
        case '/services/':
            render('pages/services', seo_ctx('services', '/services/', [
                'service_cards' => $GLOBALS['service_cards'],
                'process_steps' => $GLOBALS['services_process_steps'],
                'network_stats' => $GLOBALS['network_stats'],
                'hero_image' => MEDIA['page_services'],
            ]));
            return true;

        case '/affiliations':
        case '/affiliations/':
            render('pages/affiliations', seo_ctx('affiliations', '/affiliations/', [
                'partners' => $GLOBALS['partners'],
                'affiliation_benefits' => $GLOBALS['affiliation_benefits'],
                'hero_image' => MEDIA['page_affiliations'],
            ]));
            return true;

        case '/contact-us':
        case '/contact-us/':
            render('pages/contact', seo_ctx('contact-us', '/contact-us/', array_merge([
                'hero_image' => MEDIA['page_contact'],
            ], form_context())));
            return true;

        case '/blog':
        case '/blog/':
            render('pages/blog_index', seo_ctx('blog', '/blog/', [
                'blog_posts' => BLOG_POSTS,
            ]));
            return true;

        case '/privacy-policy':
        case '/privacy-policy/':
            render('pages/privacy', seo_ctx('privacy-policy', '/privacy-policy/', [
                'privacy_sections' => $GLOBALS['privacy_sections'],
            ]));
            return true;

        case '/thank-you':
        case '/thank-you/':
            render('pages/thank_you', [
                'page_title' => 'Thank You',
                'page_description' => 'We received your submission.',
                'path' => '/thank-you/',
                'noindex' => true,
                'form_type' => $_GET['type'] ?? '',
            ]);
            return true;
    }

    if (preg_match('#^/services/([a-z0-9-]+)/?$#', $path, $m)) {
        $slug = $m[1];
        $service = get_service($slug);
        if ($service === null) {
            render_not_found();
            return true;
        }
        render('pages/service_detail', seo_ctx($slug, '/services/' . $slug . '/', [
            'service' => $service,
            'service_cards' => $GLOBALS['service_cards'],
            'hero_image' => MEDIA['page_services'],
        ]));
        return true;
    }

    if (preg_match('#^/blog/([a-z0-9-]+)/?$#', $path, $m)) {
        $slug = $m[1];
        $post = get_blog_post($slug);
        if ($post === null) {
            render_not_found();
            return true;
        }
        $intent = $post['intent'] ?? 'informational';
        $featuredVariant = $post['featuredImage'] ?? 'student';
        render('pages/blog_post', seo_ctx('blog', '/blog/' . $slug . '/', [
            'page_title' => $post['title'] ?? null,
            'page_description' => $post['excerpt'] ?? ($post['description'] ?? null),
            'post' => $post,
            'og_type' => 'article',
            'body_class' => 'hlm-single-post',
            'cluster_label' => get_blog_cluster_label($slug),
            'topics' => get_blog_cluster_nav($slug),
            'related_posts' => get_related_blog_posts($slug, 3),
            'featured_image' => featured_image_path((string) $featuredVariant),
            'featured_image_alt' => $post['title'] ?? '',
            'cta_primary_url' => $intent === 'commercial' ? '/services/' : '/contact-us/#form',
            'cta_primary_text' => $intent === 'commercial' ? 'Explore Services' : 'Join Now',
        ]));
        return true;
    }

    return false;
}
