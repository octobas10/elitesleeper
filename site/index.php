<?php

declare(strict_types=1);

/**
 * Front controller — pure PHP HLM site.
 */

require __DIR__ . '/bootstrap.php';
require_once SITE_ROOT . '/routes/pages.php';
require_once SITE_ROOT . '/routes/api.php';
require_once SITE_ROOT . '/routes/admin.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?: '/';
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if (str_starts_with($path, '/static/')) {
    $root = realpath(SITE_ROOT . '/static');
    $file = realpath(SITE_ROOT . $path);
    $rootPrefix = $root === false ? '' : str_replace('\\', '/', $root) . '/';
    $resolved = $file === false ? '' : str_replace('\\', '/', $file);

    if (
        $root === false
        || $file === false
        || !str_starts_with($resolved, $rootPrefix)
        || !is_file($file)
    ) {
        http_response_code(404);
        echo 'Not found';
        exit;
    }

    $mime = mime_content_type($file) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . (string) filesize($file));
    readfile($file);
    exit;
}

if (str_starts_with($path, '/api')) {
    handle_api($path, $method);
    exit;
}

if (str_starts_with($path, '/admin')) {
    handle_admin($path, $method);
    exit;
}

if (!handle_pages($path)) {
    render_not_found();
}
