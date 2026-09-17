<?php

declare(strict_types=1);

/** @return array<string, mixed> */
function load_institution_data(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $path = SITE_ROOT . '/data/institution-data.json';
    $raw = file_get_contents($path);
    $cache = json_decode($raw ?: '{}', true) ?: [];
    return $cache;
}

/** @return array<string, string> */
function get_institutions(): array
{
    $data = load_institution_data();
    return $data['institutions'] ?? [];
}

/** @return array<string, array<string, string>> */
function get_campus_data(): array
{
    $data = load_institution_data();
    return $data['campus'] ?? [];
}

/** @return array<string, array<string, array<string, string>>> */
function get_program_data(): array
{
    $data = load_institution_data();
    return $data['programs'] ?? [];
}

function is_valid_campus(string $category, string $campus): bool
{
    $data = load_institution_data();
    return isset(($data['campus'] ?? [])[$category][$campus]);
}

function is_valid_program(string $category, string $campus, string $program): bool
{
    $data = load_institution_data();
    return isset(($data['programs'] ?? [])[$category][$campus][$program]);
}
