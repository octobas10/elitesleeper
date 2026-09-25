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

function handle_robots()
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

function handle_sitemap()
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
        '/terms-of-service/',
        '/do-not-sell/',
        '/removeme/',
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

        case '/terms-of-service':
        case '/terms-of-service/':
            $termsPath = SITE_ROOT . '/data/terms-of-service.txt';
            $termsText = is_readable($termsPath) ? (string) file_get_contents($termsPath) : '';
            render('pages/terms', seo_ctx('terms-of-service', '/terms-of-service/', [
                'terms_text' => $termsText,
            ]));
            return true;

        case '/do-not-sell':
        case '/do-not-sell/':
        case '/ccpa':
        case '/ccpa/':
            render('pages/do_not_sell', seo_ctx('do-not-sell', '/do-not-sell/', [
                'ccpa_sections' => $GLOBALS['ccpa_sections'] ?? CCPA_SECTIONS,
            ]));
            return true;

        case '/removeme':
        case '/removeme/':
            $sent = false;
            $error = '';
            if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
                $name = trim((string) ($_POST['name'] ?? ''));
                $email = trim((string) ($_POST['email'] ?? ''));
                $phone = trim((string) ($_POST['phone'] ?? ''));
                $zip = trim((string) ($_POST['zip'] ?? ''));
                if ($name === '' || $email === '' || $phone === '' || $zip === '') {
                    $error = 'Please complete all fields so we can locate your record.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid email address.';
                } else {
                    $storageDir = Config::get('LEAD_STORAGE_DIR');
                    if ($storageDir === '') {
                        $storageDir = SITE_ROOT . '/data/leads';
                    }
                    if (!is_dir($storageDir)) {
                        @mkdir($storageDir, 0755, true);
                    }
                    $payload = [
                        'type' => 'do_not_sell',
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'zip' => $zip,
                        'created_at' => gmdate('c'),
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                    ];
                    $file = rtrim((string) $storageDir, '/') . '/removeme-' . gmdate('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.json';
                    file_put_contents($file, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    $to = CONTACT['email'] ?? 'support@higherlearningmarketers.com';
                    @mail(
                        (string) $to,
                        'Do Not Sell or Share',
                        "Do Not Sell or Share request\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nZIP: {$zip}\n",
                        'From: ' . $to
                    );
                    $sent = true;
                }
            }
            render('pages/removeme', [
                'page_title' => 'Do Not Sell or Share Request - Higher Learning Marketers',
                'page_description' => 'Submit a Do Not Sell or Share my personal information request.',
                'path' => '/removeme/',
                'removeme_sent' => $sent,
                'removeme_error' => $error,
            ]);
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
