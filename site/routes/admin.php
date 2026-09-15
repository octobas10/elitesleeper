<?php

declare(strict_types=1);

require_once SITE_ROOT . '/services/leads_db.php';

function admin_ip_allowed(): bool
{
    $allowed = Config::get('ADMIN_ALLOWED_IPS');
    if (!is_array($allowed) || $allowed === []) {
        return true;
    }
    return in_array(client_ip(), $allowed, true);
}

function admin_required(): bool
{
    if (!admin_ip_allowed()) {
        http_response_code(403);
        echo 'Forbidden';
        return false;
    }

    $password = (string) Config::get('ADMIN_PASSWORD');
    if ($password === '') {
        http_response_code(503);
        render_standalone('admin/login', [
            'error' => 'Set ADMIN_PASSWORD in the environment to enable admin.',
            'disabled' => true,
        ]);
        return false;
    }

    if (empty($_SESSION['hlm_admin'])) {
        $next = $_SERVER['REQUEST_URI'] ?? '/admin/';
        redirect('/admin/login/?next=' . rawurlencode($next));
    }

    return true;
}

function handle_admin(string $path, string $method): bool
{
    if (!str_starts_with($path, '/admin')) {
        return false;
    }

    $normalized = rtrim($path, '/') ?: '/';

    if ($normalized === '/admin/login' || $normalized === '/admin/login/') {
        handle_admin_login($method);
        return true;
    }

    if ($normalized === '/admin/logout' || $normalized === '/admin/logout/') {
        unset($_SESSION['hlm_admin']);
        redirect('/admin/login/');
    }

    if (!admin_required()) {
        return true;
    }

    if ($normalized === '/admin' || $normalized === '/admin/') {
        handle_admin_leads();
        return true;
    }

    if (preg_match('#^/admin/leads/([^/]+)/?$#', $normalized, $m)) {
        handle_admin_lead_detail($m[1]);
        return true;
    }

    http_response_code(404);
    echo 'Not found';
    return true;
}

function handle_admin_login(string $method): void
{
    if (!admin_ip_allowed()) {
        http_response_code(403);
        echo 'Forbidden';
        return;
    }

    $password = (string) Config::get('ADMIN_PASSWORD');
    if ($password === '') {
        render_standalone('admin/login', [
            'error' => 'Set ADMIN_PASSWORD in the environment to enable admin.',
            'disabled' => true,
        ]);
        return;
    }

    $error = null;
    if ($method === 'POST') {
        $submitted = $_POST['password'] ?? '';
        if ($submitted === $password) {
            $_SESSION['hlm_admin'] = true;
            $next = $_POST['next'] ?? ($_GET['next'] ?? '/admin/');
            redirect($next);
        }
        $error = 'Incorrect password.';
    }

    render_standalone('admin/login', [
        'error' => $error,
        'next' => $_GET['next'] ?? '/admin/',
        'title' => 'Login',
    ]);
}

function handle_admin_leads(): void
{
    $formType = $_GET['form_type'] ?? ($_GET['type'] ?? '');
    $q = $_GET['q'] ?? '';
    $storage = (string) Config::get('LEAD_STORAGE_DIR');

    $result = list_leads(
        storageDir: $storage,
        formType: $formType !== '' ? $formType : null,
        q: $q !== '' ? $q : null,
        limit: 200
    );
    $counts = get_lead_counts($storage);

    render_standalone('admin/leads', [
        'leads' => $result['leads'] ?? [],
        'counts' => $counts,
        'form_type' => $formType,
        'q' => $q,
        'total' => $result['total'] ?? 0,
    ]);
}

function handle_admin_lead_detail(string $leadId): void
{
    $storage = (string) Config::get('LEAD_STORAGE_DIR');
    $row = get_lead($leadId, $storage);
    if ($row === null) {
        $_SESSION['admin_flash'] = 'Lead not found.';
        redirect('/admin/');
    }

    render_standalone('admin/lead_detail', [
        'lead' => $row,
        'payload_pretty' => json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
}
