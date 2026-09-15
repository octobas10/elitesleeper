<?php

declare(strict_types=1);

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return '/static/' . ltrim($path, '/');
}

/** @param mixed $data */
function json_attr($data): string
{
    return htmlspecialchars(
        json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}',
        ENT_QUOTES,
        'UTF-8'
    );
}

/** @param array<string, mixed> $vars */
function partial(string $name, array $vars = []): void
{
    extract(view_globals());
    extract($vars, EXTR_SKIP);
    include TEMPLATES_DIR . '/' . $name . '.php';
}

/** @return array<string, mixed> */
function view_globals(): array
{
    return [
        'site' => $GLOBALS['site'] ?? [],
        'contact' => $GLOBALS['contact'] ?? [],
        'nav' => $GLOBALS['nav'] ?? [],
        'media' => $GLOBALS['media'] ?? [],
        'current_year' => (int) date('Y'),
    ];
}

/**
 * Render a page template inside the base layout.
 *
 * @param array<string, mixed> $vars
 */
function render(string $template, array $vars = []): void
{
    extract(view_globals());
    extract($vars, EXTR_SKIP);

    $content = '';
    $extra_scripts = '';
    $extra_head = '';

    ob_start();
    include TEMPLATES_DIR . '/' . $template . '.php';
    $buffer = ob_get_clean();
    if ($buffer === false) {
        $buffer = '';
    }

    if (isset($page_content)) {
        $content = (string) $page_content;
        unset($page_content);
    } else {
        $content = $buffer;
    }

    if (isset($block_scripts)) {
        $extra_scripts = (string) $block_scripts;
        unset($block_scripts);
    } elseif (isset($page_scripts)) {
        $extra_scripts = (string) $page_scripts;
        unset($page_scripts);
    }

    if (isset($block_head)) {
        $extra_head = (string) $block_head;
        unset($block_head);
    } elseif (isset($page_head)) {
        $extra_head = (string) $page_head;
        unset($page_head);
    }

    $head = $extra_head;
    $scripts = $extra_scripts;

    include TEMPLATES_DIR . '/layouts/base.php';
}

/**
 * Render a standalone template (admin pages).
 *
 * @param array<string, mixed> $vars
 */
function render_standalone(string $template, array $vars = []): void
{
    extract(view_globals());
    extract($vars, EXTR_SKIP);
    include TEMPLATES_DIR . '/' . $template . '.php';
}

function client_ip(): string
{
    $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    if ($forwarded !== '') {
        return trim(explode(',', $forwarded)[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function redirect(string $url, int $code = 302): never
{
    header('Location: ' . $url, true, $code);
    exit;
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
