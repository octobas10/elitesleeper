<?php

declare(strict_types=1);

final class Config
{
    public static function get(string $key, mixed $default = ''): mixed
    {
        $map = self::all();
        return $map[$key] ?? $default;
    }

    /** @return array<string, mixed> */
    public static function all(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $leadStorage = getenv('LEAD_STORAGE_DIR');
        if ($leadStorage === false || $leadStorage === '') {
            $leadStorage = SITE_ROOT . '/data/leads';
        }

        $adminIps = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) (getenv('ADMIN_ALLOWED_IPS') ?: ''))
        )));

        $cache = [
            'SECRET_KEY' => getenv('SECRET_KEY') ?: 'hlm-dev-change-me',
            'LEAD_STORAGE_DIR' => $leadStorage,
            'ADMIN_PASSWORD' => getenv('ADMIN_PASSWORD') ?: '',
            'ADMIN_ALLOWED_IPS' => $adminIps,
            'ELITE_PROMO_CODE' => getenv('ELITE_PROMO_CODE') ?: '',
            'ELITE_LEAD_MODE' => getenv('ELITE_LEAD_MODE') ?: 'test',
            'ELITE_API_URL' => getenv('ELITE_API_URL') ?: 'https://elitebizpanel.com/edu/postprocess',
            'LEAD_NOTIFY_EMAIL' => getenv('LEAD_NOTIFY_EMAIL') ?: '',
            'LEAD_WEBHOOK_URL' => getenv('LEAD_WEBHOOK_URL') ?: '',
            'LEAD_NOTIFY_FROM' => getenv('LEAD_NOTIFY_FROM') ?: 'noreply@higherlearningmarketers.com',
            'RESEND_API_KEY' => getenv('RESEND_API_KEY') ?: '',
            'SMTP_HOST' => getenv('SMTP_HOST') ?: '',
            'SMTP_PORT' => (int) (getenv('SMTP_PORT') ?: '587'),
            'SMTP_USER' => getenv('SMTP_USER') ?: '',
            'SMTP_PASSWORD' => getenv('SMTP_PASSWORD') ?: '',
            'SMTP_USE_TLS' => (getenv('SMTP_USE_TLS') ?: '1') !== '0',
            'TURNSTILE_SECRET_KEY' => getenv('TURNSTILE_SECRET_KEY') ?: '',
            'PUBLIC_TURNSTILE_SITE_KEY' => getenv('PUBLIC_TURNSTILE_SITE_KEY') ?: '',
            'PUBLIC_ENABLE_TRUSTEDFORM' => (getenv('PUBLIC_ENABLE_TRUSTEDFORM') ?: '0') === '1',
            'PUBLIC_ENABLE_LEADID' => (getenv('PUBLIC_ENABLE_LEADID') ?: '0') === '1',
            'RATE_LIMIT_MAX' => (int) (getenv('RATE_LIMIT_MAX') ?: '5'),
            'RATE_LIMIT_WINDOW_SEC' => (int) (getenv('RATE_LIMIT_WINDOW_SEC') ?: '900'),
            'SITE_URL' => getenv('SITE_URL') ?: 'https://higherlearningmarketers.com',
        ];

        return $cache;
    }
}
