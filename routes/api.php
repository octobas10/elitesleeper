<?php

declare(strict_types=1);

require_once SITE_ROOT . '/services/leads_db.php';
require_once SITE_ROOT . '/services/validate.php';
require_once SITE_ROOT . '/services/turnstile.php';
require_once SITE_ROOT . '/services/rate_limit.php';
require_once SITE_ROOT . '/services/elite.php';
require_once SITE_ROOT . '/services/notify.php';

function handle_api(string $path, string $method): bool
{
    if (!str_starts_with($path, '/api')) {
        return false;
    }

    $normalized = rtrim($path, '/') ?: '/';

    if ($method === 'GET' && ($normalized === '/api/public-config' || $normalized === '/api/public-config/')) {
        json_response([
            'turnstileSiteKey' => Config::get('PUBLIC_TURNSTILE_SITE_KEY') ?: '',
            'enableTrustedForm' => (bool) Config::get('PUBLIC_ENABLE_TRUSTEDFORM'),
            'enableLeadId' => (bool) Config::get('PUBLIC_ENABLE_LEADID'),
        ]);
    }

    if ($method === 'POST' && ($normalized === '/api/submit' || $normalized === '/api/submit/')) {
        handle_submit_lead();
    }

    http_response_code(404);
    json_response(['error' => 'Not found.'], 404);
}

function handle_submit_lead()
{
    $ip = client_ip();
    $maxSubmits = (int) Config::get('RATE_LIMIT_MAX', 5);
    $window = (int) Config::get('RATE_LIMIT_WINDOW_SEC', 900) * 1000;

    if (is_rate_limited('lead-submit', $ip, $maxSubmits, $window)) {
        json_response(['error' => 'Too many submissions. Try again later.'], 429);
    }

    $raw = file_get_contents('php://input');
    $body = json_decode($raw ?: '', true);
    if (!is_array($body)) {
        json_response(['error' => 'Invalid JSON.'], 400);
    }

    if (!empty($body['hlm_hp_url'])) {
        json_response(['ok' => true]);
    }

    $secret = Config::get('TURNSTILE_SECRET_KEY') ?: '';
    if ($secret !== '') {
        $token = (string) ($body['cf-turnstile-response'] ?? ($body['turnstile_token'] ?? ''));
        $check = verify_turnstile_token($token, $ip, (string) $secret);
        if (empty($check['ok'])) {
            json_response(['error' => $check['error'] ?? 'Captcha failed.'], 400);
        }
    }

    $skip = ['form_type', 'hlm_hp_url', 'cf-turnstile-response', 'turnstile_token'];
    $formType = (string) ($body['form_type'] ?? '');
    $data = [];
    foreach ($body as $k => $v) {
        if (in_array($k, $skip, true)) {
            continue;
        }
        if (is_array($v)) {
            $data[$k] = implode(',', array_map('strval', $v));
        } else {
            $data[$k] = (string) ($v ?? '');
        }
    }

    [$ok, $errors] = validate_lead($formType, $data);
    if (!$ok) {
        json_response([
            'error' => $errors['error'] ?? 'Validation failed.',
            'fields' => $errors['fields'] ?? null,
        ], 400);
    }

    try {
        $leadId = store_lead(
            $formType,
            array_merge($data, ['ip' => $ip]),
            (string) Config::get('LEAD_STORAGE_DIR')
        );
    } catch (Throwable $err) {
        error_log('[hlm-lead] storage failed ' . $err->getMessage());
        json_response(['error' => 'Could not save your submission. Please try again.'], 500);
    }

    if ($formType === 'student') {
        try {
            post_to_elite(
                array_merge($data, ['form_type' => $formType, 'ip' => $ip]),
                Config::all()
            );
        } catch (Throwable $err) {
            error_log('[hlm-lead] elite failed ' . $err->getMessage());
        }
    }

    try {
        notify_lead($formType, $data, Config::all());
    } catch (Throwable $err) {
        error_log('[hlm-lead] notify failed ' . $err->getMessage());
    }

    json_response([
        'ok' => true,
        'message' => 'Lead submitted successfully.',
        'lead_id' => $leadId,
    ]);
}
