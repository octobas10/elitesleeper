<?php

declare(strict_types=1);

/**
 * Internal link helpers — port of app/links.py
 */

const PAGE_TITLES = [
    'home' => 'Home',
    'about-us' => 'About Us',
    'services' => 'Services',
    'affiliations' => 'Affiliations',
    'contact-us' => 'Contact Us',
    'blog' => 'Blog',
    'privacy-policy' => 'Privacy Policy',
    'lead-generation' => 'Lead Generation',
    'brand-awareness' => 'Brand Awareness',
    'multi-channel-marketing' => 'Multi-Channel Marketing',
    'analytics-reporting' => 'Analytics & Reporting',
    'partner-network' => 'Partner Network',
    'enrollment-funnel' => 'Enrollment Funnel',
];

const FEATURED_IMAGE_KEYS = ['student', 'university', 'hlm', 'compliance'];

function resolve_slug_url(string $slug): string
{
    if (str_starts_with($slug, 'services/')) {
        return '/' . $slug . '/';
    }
    if (isset(PAGE_TITLES[$slug])) {
        return $slug === 'home' ? '/' : '/' . $slug . '/';
    }
    return '/blog/' . $slug . '/';
}

function resolve_link_label(string $slug, ?string $anchor = null): string
{
    if ($anchor) {
        return $anchor;
    }
    $titles = SEO_LINKS['titleBySlug'] ?? [];
    return $titles[$slug] ?? PAGE_TITLES[$slug] ?? str_replace('-', ' ', $slug);
}

/** @return list<array<string, mixed>> */
function get_blog_cluster_nav(string $postSlug): array
{
    $titles = SEO_LINKS['titleBySlug'] ?? [];
    $clusters = SEO_LINKS['clusters'] ?? [];
    foreach ($clusters as $cluster) {
        $posts = $cluster['posts'] ?? [];
        if (!in_array($postSlug, $posts, true)) {
            continue;
        }
        $siblings = array_values(array_filter($posts, static fn($s) => $s !== $postSlug));
        $related = ($cluster['linksTo'][$postSlug] ?? []) ?: [];
        $items = [
            [
                'href' => $cluster['pillarUrl'] ?? '/blog/',
                'label' => $cluster['label'] ?? 'Blog',
                'featured' => true,
            ],
        ];
        foreach (array_slice($siblings, 0, 4) as $s) {
            $items[] = [
                'href' => '/blog/' . $s . '/',
                'label' => $titles[$s] ?? $s,
                'featured' => false,
            ];
        }
        foreach (array_slice($related, 0, 3) as $s) {
            $items[] = [
                'href' => resolve_slug_url($s),
                'label' => resolve_link_label($s),
                'featured' => false,
            ];
        }
        $seen = [];
        $out = [];
        foreach ($items as $item) {
            if (isset($seen[$item['href']])) {
                continue;
            }
            $seen[$item['href']] = true;
            $out[] = $item;
        }
        return $out;
    }
    return [];
}

function get_blog_cluster_label(string $postSlug): string
{
    $clusters = SEO_LINKS['clusters'] ?? [];
    foreach ($clusters as $cluster) {
        if (in_array($postSlug, $cluster['posts'] ?? [], true)) {
            return (string) ($cluster['label'] ?? 'Blog');
        }
    }
    return 'Blog';
}

/** @return list<array<string, mixed>> */
function get_related_blog_posts(string $postSlug, int $limit = 3): array
{
    $clusters = SEO_LINKS['clusters'] ?? [];
    foreach ($clusters as $cluster) {
        $posts = $cluster['posts'] ?? [];
        if (!in_array($postSlug, $posts, true)) {
            continue;
        }
        $siblings = array_values(array_filter($posts, static fn($s) => $s !== $postSlug));
        $found = [];
        foreach ($siblings as $s) {
            $post = get_blog_post($s);
            if ($post) {
                $found[] = $post;
            }
            if (count($found) >= $limit) {
                break;
            }
        }
        if ($found) {
            return $found;
        }
    }
    $out = [];
    foreach (BLOG_POSTS as $p) {
        if (($p['slug'] ?? '') !== $postSlug) {
            $out[] = $p;
        }
        if (count($out) >= $limit) {
            break;
        }
    }
    return $out;
}

function featured_image_path(string $variant = 'student'): string
{
    $key = in_array($variant, FEATURED_IMAGE_KEYS, true) ? $variant : 'student';
    return 'images/blog/featured-' . $key . '.svg';
}
