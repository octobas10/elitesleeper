<?php

declare(strict_types=1);

/**
 * Form webhook for Elite Sleeper.
 * Stores a JSON lead, then optionally posts to LEAD_WEBHOOK_URL and emails the desk.
 */

function load_env_file(string $file): void
{
    if (!is_file($file)) {
        return;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }
        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos + 1));
        if (
            (str_starts_with($val, '"') && str_ends_with($val, '"'))
            || (str_starts_with($val, "'") && str_ends_with($val, "'"))
        ) {
            $val = substr($val, 1, -1);
        }
        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $val);
            $_ENV[$key] = $val;
        }
    }
}

function field(string $key, int $max, bool $keepBreaks = false): string
{
    $value = isset($_POST[$key]) ? (string) $_POST[$key] : '';
    $value = str_replace("\0", '', $value);
    if ($keepBreaks) {
        $value = str_replace(["\r\n", "\r"], "\n", $value);
    } else {
        $value = preg_replace('/\s+/', ' ', $value) ?? '';
    }
    $value = trim(strip_tags($value));
    if (strlen($value) > $max) {
        $value = substr($value, 0, $max);
    }
    return $value;
}

function safe_return_path(): string
{
    $return = isset($_POST['return_to']) ? (string) $_POST['return_to'] : '';
    $return = trim(str_replace("\0", '', $return));
    if ($return === '/') {
        return '/';
    }
    if (preg_match('#^/(blog(?:/[a-z0-9-]+)?|contact|order|opt-out)/$#', $return) === 1) {
        return $return;
    }
    return '';
}

function fail(string $type, string $code): never
{
    $back = [
        'order' => '/order/',
        'contact' => '/contact/',
        'optout' => '/opt-out/',
    ];
    $path = safe_return_path();
    if ($path === '') {
        $path = $back[$type] ?? '/contact/';
    }
    $hash = ($path === '/' && $type === 'contact') ? '#contact' : '';
    header('Location: ' . $path . '?error=' . rawurlencode($code) . $hash, true, 303);
    exit;
}

function client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return preg_match('/^[0-9a-fA-F:.]+$/', $ip) ? $ip : 'unknown';
}

function rate_limited(string $dir, string $ip): bool
{
    $file = $dir . '/rate_limit.json';
    $now = time();
    $window = 900;
    $max = 8;
    $data = [];
    if (is_file($file)) {
        $decoded = json_decode((string) file_get_contents($file), true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $hits = [];
    if (isset($data[$ip]) && is_array($data[$ip])) {
        foreach ($data[$ip] as $stamp) {
            if (is_int($stamp) && $stamp > $now - $window) {
                $hits[] = $stamp;
            }
        }
    }
    if (count($hits) >= $max) {
        return true;
    }
    $hits[] = $now;
    $data[$ip] = $hits;
    if (count($data) > 400) {
        $data = array_slice($data, -200, null, true);
    }
    file_put_contents($file, json_encode($data), LOCK_EX);
    return false;
}

load_env_file(__DIR__ . '/.env');
load_env_file(dirname(__DIR__) . '/.env');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /contact/', true, 303);
    exit;
}

$host = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? '') ?? '');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    $originHost = strtolower((string) parse_url($origin, PHP_URL_HOST));
    if ($originHost !== '' && $host !== '' && $originHost !== $host) {
        header('Location: /contact/?error=save', true, 303);
        exit;
    }
}

$type = field('form_type', 20);
if (!in_array($type, ['order', 'contact', 'optout'], true)) {
    $type = 'contact';
}

if (field('company_url', 200) !== '') {
    header('Location: /thank-you/', true, 303);
    exit;
}

$storage = getenv('LEAD_STORAGE_DIR') ?: (dirname(__DIR__) . '/data/leads');
if (!is_dir($storage) && !mkdir($storage, 0755, true) && !is_dir($storage)) {
    fail($type, 'save');
}

if (rate_limited($storage, client_ip())) {
    fail($type, 'rate');
}

$email = strtolower(field('email', 120));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail($type, 'email');
}

$lead = [
    'form_type' => $type,
    'email' => $email,
    'submitted_at' => gmdate('c'),
    'ip' => client_ip(),
];

if ($type === 'order') {
    $first = field('first_name', 60);
    $last = field('last_name', 60);
    $phone = field('phone', 24);
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    $address = field('address', 120);
    $city = field('city', 60);
    $state = strtoupper(field('state', 2));
    $zip = field('zip', 10);
    $quantity = field('quantity', 2);
    $plan = field('plan', 12);
    $pref = field('contact_pref', 12);
    $notes = field('notes', 1000, true);
    $sms = field('sms_ok', 1) === '1' ? 'yes' : 'no';

    if ($first === '' || $last === '' || $address === '' || $city === '' || $state === '') {
        fail($type, 'missing');
    }
    if (strlen($digits) < 10) {
        fail($type, 'phone');
    }
    if (!preg_match('/^\d{5}(-\d{4})?$/', $zip)) {
        fail($type, 'zip');
    }
    if (!in_array($quantity, ['1', '2', '3'], true) || !in_array($plan, ['once', 'monthly'], true)) {
        fail($type, 'missing');
    }
    if (!in_array($pref, ['email', 'phone', 'whatsapp'], true)) {
        $pref = 'email';
    }
    if (!preg_match('/^[A-Z]{2}$/', $state)) {
        fail($type, 'missing');
    }

    $prices = ['1' => '29.95', '2' => '59.90', '3' => '89.85'];
    $lead += [
        'first_name' => $first,
        'last_name' => $last,
        'phone' => $phone,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'quantity' => $quantity,
        'plan' => $plan,
        'product_total' => $prices[$quantity],
        'contact_pref' => $pref,
        'sms_ok' => $sms,
        'notes' => $notes,
    ];
}

if ($type === 'contact') {
    $first = field('first_name', 60);
    $last = field('last_name', 60);
    $subject = field('subject', 120);
    $message = field('message', 1000, true);
    $phone = field('phone', 24);
    if ($first === '' || $last === '' || $subject === '' || $message === '') {
        fail($type, 'missing');
    }
    $lead += [
        'first_name' => $first,
        'last_name' => $last,
        'phone' => $phone,
        'subject' => $subject,
        'message' => $message,
    ];
}

if ($type === 'optout') {
    $lead['request'] = 'remove email from Elite Sleeper list';
}

$id = gmdate('Ymd-His') . '-' . bin2hex(random_bytes(4));
$lead['id'] = $id;
$stored = file_put_contents(
    $storage . '/' . $id . '.json',
    json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n",
    LOCK_EX
);

$webhook = trim((string) getenv('LEAD_WEBHOOK_URL'));
$hooked = false;
if ($webhook !== '' && preg_match('#^https://#', $webhook)) {
    $body = json_encode($lead, JSON_UNESCAPED_SLASHES);
    if (function_exists('curl_init')) {
        $ch = curl_init($webhook);
        if ($ch !== false) {
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 8,
            ]);
            curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            $hooked = $code >= 200 && $code < 300;
        }
    }
}

$to = trim((string) getenv('LEAD_NOTIFY_EMAIL'));
$from = trim((string) getenv('LEAD_NOTIFY_FROM'));
if ($from === '') {
    $from = 'Elite Sleeper <support@elitesleeper.com>';
}
$from = str_replace(["\r", "\n"], '', $from);
$mailed = false;
if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
    $lines = ['New Elite Sleeper ' . $type . ' form', ''];
    foreach ($lead as $key => $value) {
        if ($key === 'ip') {
            continue;
        }
        $lines[] = $key . ': ' . $value;
    }
    $subject = str_replace(["\r", "\n"], '', 'Elite Sleeper ' . $type . ' ' . $email);
    $headers = 'From: ' . $from . "\r\n" . 'Content-Type: text/plain; charset=UTF-8';
    $mailed = mail($to, $subject, implode("\n", $lines), $headers);
}

if ($stored === false && !$hooked && !$mailed) {
    fail($type, 'save');
}

header('Location: /thank-you/?sent=' . rawurlencode($type), true, 303);
exit;
