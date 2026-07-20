<?php
/**
 * Deploy webhook — upload to WordPress web root once.
 * Protect with token file outside public access if possible.
 *
 * Server files needed:
 *   ~/hlm-deploy/token          — same value as GitHub secret DEPLOY_WEBHOOK_TOKEN
 *   ~/hlm-deploy/server-deploy.sh — copy from repo deploy/server-deploy.sh
 */

header('Content-Type: text/plain; charset=utf-8');

$tokenFile = getenv('HOME') . '/hlm-deploy/token';
$script    = getenv('HOME') . '/hlm-deploy/server-deploy.sh';

$provided = $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '';
$expected = is_readable($tokenFile) ? trim((string) file_get_contents($tokenFile)) : '';

if ($expected === '' || !hash_equals($expected, $provided)) {
    http_response_code(403);
    exit("Forbidden\n");
}

if (!is_executable($script)) {
    http_response_code(500);
    exit("Deploy script missing or not executable: $script\n");
}

$output = [];
$code   = 0;
exec('bash ' . escapeshellarg($script) . ' 2>&1', $output, $code);
echo implode("\n", $output) . "\n";
exit($code === 0 ? 0 : 1);
