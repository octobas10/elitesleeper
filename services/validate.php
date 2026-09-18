<?php

declare(strict_types=1);

require_once __DIR__ . '/institutions.php';

const STUDENT_REQUIRED = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'address',
    'city',
    'state',
    'zip',
    'category',
    'campus',
    'program_of_interest',
    'education_level',
    'desired_degree',
    'grad_year',
];

const UNIVERSITY_REQUIRED = [
    'institution_name',
    'first_name',
    'last_name',
    'email',
    'phone',
];

function digits_only(string $v): string
{
    return preg_replace('/\D/', '', $v) ?? '';
}

function is_email(string $v): bool
{
    return (bool) preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $v);
}

function is_valid_name(string $v): bool
{
    return strlen(trim($v)) >= 2;
}

function is_valid_zip(string $v): bool
{
    // US ZIP only: 12345 or 12345-6789
    $zipCode = trim($v);
    return (bool) preg_match('/^\d{5}(-\d{4})?$/', $zipCode);
}

/** @param array<string, mixed> $data */
function str_field(array $data, string $key): string
{
    if (!isset($data[$key]) || $data[$key] === null) {
        return '';
    }
    return (string) $data[$key];
}

/**
 * @param array<string, mixed> $data
 * @return array{0: bool, 1: array<string, mixed>}
 */
function validate_lead(string $formType, array $data): array
{
    if (!in_array($formType, ['student', 'university'], true)) {
        return [false, ['error' => 'Invalid form type.']];
    }

    $required = $formType === 'student' ? STUDENT_REQUIRED : UNIVERSITY_REQUIRED;
    $missing = [];

    foreach ($required as $field) {
        if (trim(str_field($data, $field)) === '') {
            $missing[] = $field;
        }
    }

    if ($formType === 'student' && trim(str_field($data, 'tcpa_consent')) === '') {
        $missing[] = 'tcpa_consent';
    }

    if ($missing !== []) {
        return [false, ['error' => 'Please fill in all required fields.', 'fields' => $missing]];
    }

    if (!is_email(str_field($data, 'email'))) {
        return [false, ['error' => 'Please enter a valid email address.']];
    }

    $phone = digits_only(str_field($data, 'phone'));
    if (strlen($phone) !== 10) {
        return [false, ['error' => 'Please enter a valid 10-digit phone number.']];
    }

    $state = str_field($data, 'state');
    if ($state !== '' && !preg_match('/^[A-Za-z]{2}$/', $state)) {
        return [false, ['error' => 'Please select a valid state or province.']];
    }

    $zipCode = str_field($data, 'zip');
    if ($zipCode !== '' && !is_valid_zip($zipCode)) {
        return [false, ['error' => 'Please enter a valid 5-digit U.S. ZIP code.']];
    }

    foreach (['first_name', 'last_name', 'institution_name'] as $nameField) {
        $value = str_field($data, $nameField);
        if ($value !== '' && !is_valid_name($value)) {
            return [false, ['error' => 'Please enter a valid name (at least 2 characters).']];
        }
    }

    $website = str_field($data, 'website');
    if ($website !== '' && !preg_match('/^https?:\/\/.+/i', $website)) {
        return [false, ['error' => 'Please enter a valid website URL.']];
    }

    if ($formType === 'student') {
        $category = str_field($data, 'category');
        $campus = str_field($data, 'campus');
        $program = str_field($data, 'program_of_interest');
        if ($category !== '' && $campus !== '' && !is_valid_campus($category, $campus)) {
            return [false, ['error' => 'Please select a valid campus.']];
        }
        if ($category !== '' && $campus !== '' && $program !== '' && !is_valid_program($category, $campus, $program)) {
            return [false, ['error' => 'Please select a valid program.']];
        }
    }

    return [true, []];
}
