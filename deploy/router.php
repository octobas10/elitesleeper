<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) ? rawurldecode($path) : '/';
$full = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/') . $path;

if (is_file($full)) {
    return false;
}

if (is_dir($full)) {
    $index = rtrim($full, '/') . '/index.html';
    if (is_file($index)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($index);
        return true;
    }
}

http_response_code(404);
$notFound = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/') . '/404.html';
header('Content-Type: text/html; charset=utf-8');
if (is_file($notFound)) {
    readfile($notFound);
} else {
    echo 'Not found';
}
return true;
