<?php

declare(strict_types=1);

const TURNSTILE_VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

function get_turnstile_site_key(): string
{
    return trim((string) (getenv('PUBLIC_TURNSTILE_SITE_KEY') ?: ''));
}

function get_turnstile_secret_key(): string
{
    return trim((string) (getenv('TURNSTILE_SECRET_KEY') ?: ''));
}

function is_turnstile_enabled(): bool
{
    return get_turnstile_site_key() !== '' && get_turnstile_secret_key() !== '';
}

/**
 * @return array{ok: bool, error?: string}
 */
function verify_turnstile_token(string $token, ?string $remoteip = null, ?string $secret = null): array
{
    $secretKey = trim($secret ?? get_turnstile_secret_key());
    if ($secretKey === '') {
        return ['ok' => true];
    }

    if (trim($token) === '') {
        return ['ok' => false, 'error' => 'Please complete the security check.'];
    }

    try {
        $body = [
            'secret' => $secretKey,
            'response' => trim($token),
        ];
        if ($remoteip !== null && $remoteip !== '' && $remoteip !== 'unknown') {
            $body['remoteip'] = $remoteip;
        }

        $ch = curl_init(TURNSTILE_VERIFY_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($body),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            throw new RuntimeException(curl_error($ch) ?: 'curl failed');
        }
        curl_close($ch);

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data['success'])) {
            error_log('[hlm-turnstile] verify failed ' . json_encode($data['error-codes'] ?? []));
            return ['ok' => false, 'error' => 'Security check failed. Please try again.'];
        }
        return ['ok' => true];
    } catch (Throwable $err) {
        error_log('[hlm-turnstile] verify error ' . $err->getMessage());
        return ['ok' => false, 'error' => 'Security check unavailable. Please try again.'];
    }
}
