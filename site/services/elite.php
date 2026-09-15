<?php

declare(strict_types=1);

const DEFAULT_ELITE_URL = 'https://elitebizpanel.com/edu/postprocess';
const DEFAULT_TCPA = 'I agree to be contacted by phone, email, and text message regarding educational opportunities.';

/** @param array<string, mixed>|null $config */
function cfg_get(?array $config, string $key, string $default = ''): string
{
    if ($config === null) {
        return $default;
    }
    $val = $config[$key] ?? $default;
    if ($val === null) {
        return $default;
    }
    return (string) $val;
}

/**
 * @param array<string, mixed> $leadData
 * @param array<string, mixed>|null $config
 * @return array<string, mixed>|null
 */
function post_to_elite(array $leadData, ?array $config = null): ?array
{
    $promo = trim(cfg_get($config, 'ELITE_PROMO_CODE'));
    if ($promo === '') {
        return null;
    }

    $mode = trim(cfg_get($config, 'ELITE_LEAD_MODE', 'test')) ?: 'test';
    $apiUrl = trim(cfg_get($config, 'ELITE_API_URL', DEFAULT_ELITE_URL)) ?: DEFAULT_ELITE_URL;

    $data = [];
    foreach ($leadData as $k => $v) {
        $data[$k] = $v === null ? '' : (string) $v;
    }

    $formType = $data['form_type'] ?? 'student';
    $phone = digits_only($data['phone'] ?? '');
    $ip = $data['ip'] ?? ($data['ipaddress'] ?? '');
    $tcpaText = $data['tcpa_text'] ?? DEFAULT_TCPA;
    $tcpaText = substr($tcpaText, 0, 200);
    $now = gmdate('Y-m-d H:i:s');

    $body = [
        'lead_mode' => $mode,
        'promo_code' => $promo,
        'sub_id' => 'hlm-flask-' . $formType,
        'datetime_stamp' => $now,
        'first_name' => $data['first_name'] ?? '',
        'last_name' => $data['last_name'] ?? '',
        'email' => $data['email'] ?? '',
        'phone' => $phone,
        'mobile' => $phone,
        'address' => $data['address'] ?? '',
        'city' => $data['city'] ?? '',
        'state' => strtoupper(substr($data['state'] ?? '', 0, 2)),
        'zip' => $data['zip'] ?? '',
        'education_level' => $data['education_level'] ?? '',
        'desired_degree' => $data['desired_degree'] ?? '',
        'program_of_interest' => $data['program_of_interest'] ?? '',
        'campus' => $data['campus'] ?? '',
        'grad_year' => $data['grad_year'] ?? '',
        'ipaddress' => $ip,
        'url' => $data['page_url'] ?? ($data['url'] ?? ''),
        'tcpa_text' => $tcpaText,
        'trustedformcerturl' => $data['trustedformcerturl'] ?? '',
        'universal_leadid' => $data['universal_leadid'] ?? '',
    ];

    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($body),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
    ]);
    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($response === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($err ?: 'Elite request failed');
    }
    curl_close($ch);

    if ($status < 200 || $status >= 300) {
        throw new RuntimeException('Elite HTTP ' . $status);
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        return ['ok' => true, 'status_code' => $status, 'text' => $response];
    }
    return $decoded;
}
