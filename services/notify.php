<?php

declare(strict_types=1);

const NOTIFY_DEFAULT_FROM = 'HLM Leads <leads@higherlearningmarketers.com>';

/** @param array<string, mixed>|null $config */
function notify_cfg_get(?array $config, string $key, string $default = ''): string
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

/** @param array<string, mixed> $data */
function format_lead_body(string $formType, array $data): string
{
    $lines = [];
    foreach ($data as $k => $v) {
        if (str_starts_with((string) $k, 'hlm_')) {
            continue;
        }
        $lines[] = $k . ': ' . $v;
    }
    return "New {$formType} lead from Higher Learning Marketers\n\n" . implode("\n", $lines);
}

/**
 * @param array<string, mixed> $data
 * @param array<string, mixed>|null $config
 */
function notify_lead(string $formType, array $data, ?array $config = null): void
{
    $webhook = trim(notify_cfg_get($config, 'LEAD_WEBHOOK_URL'));
    if ($webhook !== '') {
        $payload = array_merge(
            [
                'form_type' => $formType,
                'submitted_at' => gmdate('Y-m-d\TH:i:s\Z'),
            ],
            $data
        );
        $ch = curl_init($webhook);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    $resendKey = trim(notify_cfg_get($config, 'RESEND_API_KEY'));
    $toAddr = trim(notify_cfg_get($config, 'LEAD_NOTIFY_EMAIL'));
    $fromAddr = trim(notify_cfg_get($config, 'LEAD_NOTIFY_FROM')) ?: NOTIFY_DEFAULT_FROM;

    $subject = 'New ' . $formType . ' lead — ' . ($data['email'] ?? ($data['institution_name'] ?? 'HLM'));
    $text = format_lead_body($formType, $data);

    if ($resendKey !== '' && $toAddr !== '') {
        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'from' => $fromAddr,
                'to' => [$toAddr],
                'subject' => $subject,
                'text' => $text,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $resendKey,
                'Content-Type: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
        ]);
        $response = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status < 200 || $status >= 300) {
            throw new RuntimeException('Resend failed: ' . $status);
        }
        return;
    }

    $host = trim(notify_cfg_get($config, 'SMTP_HOST'));
    if ($host !== '' && $toAddr !== '') {
        $port = (int) notify_cfg_get($config, 'SMTP_PORT', '587');
        $user = trim(notify_cfg_get($config, 'SMTP_USER'));
        $password = trim(notify_cfg_get($config, 'SMTP_PASSWORD'))
            ?: trim(notify_cfg_get($config, 'SMTP_PASS'));
        $useTls = notify_cfg_get($config, 'SMTP_USE_TLS', '1') !== '0'
            && strtolower(notify_cfg_get($config, 'SMTP_USE_TLS', '1')) !== 'false';
        $secure = strtolower(notify_cfg_get($config, 'SMTP_SECURE')) === 'true';
        $smtpFrom = trim(notify_cfg_get($config, 'SMTP_FROM')) ?: $fromAddr;

        $headers = [
            'From: ' . $smtpFrom,
            'To: ' . $toAddr,
            'Subject: ' . $subject,
            'Content-Type: text/plain; charset=UTF-8',
        ];
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $text;

        if ($secure) {
            $transport = stream_socket_client('ssl://' . $host . ':' . $port, $errno, $errstr, 30);
        } else {
            $transport = stream_socket_client('tcp://' . $host . ':' . $port, $errno, $errstr, 30);
        }
        if ($transport === false) {
            throw new RuntimeException('SMTP connect failed: ' . $errstr);
        }

        $read = static function () use ($transport): string {
            $data = '';
            while ($line = fgets($transport)) {
                $data .= $line;
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            return $data;
        };

        $write = static function (string $cmd) use ($transport, $read): void {
            fwrite($transport, $cmd . "\r\n");
            $read();
        };

        $read();
        $write('EHLO localhost');
        if (!$secure && $useTls) {
            $write('STARTTLS');
            stream_socket_enable_crypto($transport, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $write('EHLO localhost');
        }
        if ($user !== '') {
            $write('AUTH LOGIN');
            $write(base64_encode($user));
            $write(base64_encode($password));
        }
        $write('MAIL FROM:<' . preg_replace('/^.*<([^>]+)>.*$/', '$1', $smtpFrom) . '>');
        $write('RCPT TO:<' . $toAddr . '>');
        $write('DATA');
        fwrite($transport, $message . "\r\n.\r\n");
        $read();
        $write('QUIT');
        fclose($transport);
    }
}
