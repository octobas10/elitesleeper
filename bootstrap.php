<?php

declare(strict_types=1);

define('SITE_ROOT', __DIR__);
define('REPO_ROOT', SITE_ROOT);
define('TEMPLATES_DIR', SITE_ROOT . '/templates');

require_once SITE_ROOT . '/lib/php74_polyfill.php';

/**
 * Simple PSR-4-like autoload for root-level app folders.
 */
spl_autoload_register(static function (string $class): void {
    $map = [
        'Config' => SITE_ROOT . '/config.php',
    ];
    if (isset($map[$class])) {
        require_once $map[$class];
        return;
    }
    foreach (['lib', 'services', 'routes'] as $dir) {
        $path = SITE_ROOT . '/' . $dir . '/' . strtolower($class) . '.php';
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

/**
 * Load .env from the repo root.
 */
function load_env_file(string $path): void
{
    if (!is_readable($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ($key === '') {
            continue;
        }
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        if (getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

load_env_file(REPO_ROOT . '/.env');
load_env_file(SITE_ROOT . '/.env');

require_once SITE_ROOT . '/config.php';
require_once SITE_ROOT . '/lib/view.php';
require_once SITE_ROOT . '/lib/content.php';
require_once SITE_ROOT . '/lib/links.php';
require_once SITE_ROOT . '/routes/pages.php';
require_once SITE_ROOT . '/routes/api.php';
require_once SITE_ROOT . '/routes/admin.php';

$GLOBALS['site'] = SITE;
$GLOBALS['contact'] = CONTACT;
$GLOBALS['nav'] = NAV;
$GLOBALS['media'] = MEDIA;

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secret = Config::get('SECRET_KEY', 'hlm-dev-change-me');
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    ini_set('session.use_strict_mode', '1');
    session_name('hlm_session');
    session_start();
}

$storageDir = Config::get('LEAD_STORAGE_DIR');
if ($storageDir !== '' && !is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}
