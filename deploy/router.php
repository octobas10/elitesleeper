<?php
/**
 * Local dev only.
 *
 * Usage (from repo root):
 *   php -S localhost:10001 deploy/router.php
 */

$root = dirname(__DIR__) . '/site';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = $root . $path;

if ($path !== '/' && is_file($file)) {
	return false;
}

chdir($root);
require $root . '/index.php';
