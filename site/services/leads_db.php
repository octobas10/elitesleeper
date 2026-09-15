<?php

declare(strict_types=1);

const LEADS_SCHEMA_SQL = <<<'SQL'
CREATE TABLE IF NOT EXISTS leads (
    id TEXT PRIMARY KEY,
    form_type TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'saved',
    first_name TEXT NOT NULL DEFAULT '',
    last_name TEXT NOT NULL DEFAULT '',
    email TEXT NOT NULL DEFAULT '',
    phone TEXT NOT NULL DEFAULT '',
    city TEXT NOT NULL DEFAULT '',
    state TEXT NOT NULL DEFAULT '',
    zip TEXT NOT NULL DEFAULT '',
    category TEXT NOT NULL DEFAULT '',
    campus TEXT NOT NULL DEFAULT '',
    program_of_interest TEXT NOT NULL DEFAULT '',
    institution_name TEXT NOT NULL DEFAULT '',
    payload TEXT NOT NULL,
    created_at TEXT NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_leads_email ON leads(email);
CREATE INDEX IF NOT EXISTS idx_leads_created_at ON leads(created_at);
CREATE INDEX IF NOT EXISTS idx_leads_form_type ON leads(form_type);
SQL;

const LEADS_INSERT_SQL = <<<'SQL'
INSERT INTO leads (
    id, form_type, status, first_name, last_name, email, phone,
    city, state, zip, category, campus, program_of_interest,
    institution_name, payload, created_at
) VALUES (
    :id, :form_type, 'saved', :first_name, :last_name, :email, :phone,
    :city, :state, :zip, :category, :campus, :program_of_interest,
    :institution_name, :payload, :created_at
)
SQL;

const LEADS_SELECT_COLS = <<<'SQL'
    id, form_type, status, first_name, last_name, email, phone,
    city, state, zip, category, campus, program_of_interest,
    institution_name, payload, created_at
SQL;

function get_leads_dir(?string $storageDir = null): string
{
    if ($storageDir !== null && $storageDir !== '') {
        return $storageDir;
    }
    $env = getenv('LEAD_STORAGE_DIR');
    if ($env !== false && $env !== '') {
        return $env;
    }
    return dirname(__DIR__, 2) . '/data/leads';
}

function leads_db_path(?string $storageDir = null): string
{
    return get_leads_dir($storageDir) . '/leads.db';
}

function leads_connect(?string $storageDir = null, bool $readonly = false): ?PDO
{
    $path = leads_db_path($storageDir);
    if ($readonly && !is_file($path)) {
        return null;
    }
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    if ($readonly) {
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } else {
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

/** @param array<string, mixed> $record */
function store_in_sqlite(string $formType, array $record, string $directory): void
{
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    $pdo = new PDO('sqlite:' . $directory . '/leads.db', null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec(LEADS_SCHEMA_SQL);
    $stmt = $pdo->prepare(LEADS_INSERT_SQL);
    $stmt->execute([
        ':id' => $record['id'],
        ':form_type' => $formType,
        ':first_name' => $record['first_name'] ?? '',
        ':last_name' => $record['last_name'] ?? '',
        ':email' => $record['email'] ?? '',
        ':phone' => $record['phone'] ?? '',
        ':city' => $record['city'] ?? '',
        ':state' => $record['state'] ?? '',
        ':zip' => $record['zip'] ?? '',
        ':category' => $record['category'] ?? '',
        ':campus' => $record['campus'] ?? '',
        ':program_of_interest' => $record['program_of_interest'] ?? '',
        ':institution_name' => $record['institution_name'] ?? '',
        ':payload' => json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ':created_at' => $record['submitted_at'],
    ]);
}

/** @param array<string, mixed> $record */
function store_as_json_files(array $record, string $directory): void
{
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    $payload = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    file_put_contents($directory . '/' . $record['id'] . '.json', $payload);
    file_put_contents($directory . '/leads.jsonl', $payload . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * @param array<string, mixed> $data
 */
function store_lead(string $formType, array $data, ?string $storageDir = null): string
{
    $leadId = (string) (int) (microtime(true) * 1000) . '-' . $formType;
    $record = [
        'id' => $leadId,
        'form_type' => $formType,
        'submitted_at' => gmdate('Y-m-d\TH:i:s\Z'),
    ];
    foreach ($data as $k => $v) {
        $record[$k] = $v === null ? '' : (string) $v;
    }

    $directory = get_leads_dir($storageDir);
    $saved = false;

    try {
        store_in_sqlite($formType, $record, $directory);
        $saved = true;
    } catch (Throwable $err) {
        error_log('[hlm-lead] sqlite storage failed ' . $err->getMessage());
    }

    try {
        store_as_json_files($record, $directory);
        $saved = true;
    } catch (Throwable $err) {
        error_log('[hlm-lead] json backup failed ' . $err->getMessage());
    }

    if (!$saved) {
        throw new RuntimeException('Lead storage unavailable');
    }

    return $leadId;
}

/** @return array{leads: list<array<string, mixed>>, total: int, limit: int, offset: int} */
function list_leads(
    ?string $storageDir = null,
    ?string $formType = null,
    ?string $q = null,
    int $limit = 50,
    int $offset = 0
): array {
    $limit = min(max($limit, 1), 200);
    $offset = max($offset, 0);
    $formType = trim((string) $formType);
    $q = trim((string) $q);

    $empty = ['leads' => [], 'total' => 0, 'limit' => $limit, 'offset' => $offset];
    $pdo = leads_connect($storageDir, true);
    if ($pdo === null) {
        return $empty;
    }

    $where = [];
    $params = [];

    if (in_array($formType, ['student', 'university'], true)) {
        $where[] = 'form_type = :form_type';
        $params[':form_type'] = $formType;
    }

    if ($q !== '') {
        $where[] = '(email LIKE :q OR first_name LIKE :q OR last_name LIKE :q OR phone LIKE :q OR id LIKE :q OR institution_name LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }

    $whereSql = $where !== [] ? 'WHERE ' . implode(' AND ', $where) : '';

    $countStmt = $pdo->prepare("SELECT COUNT(*) AS count FROM leads $whereSql");
    $countStmt->execute($params);
    $total = (int) ($countStmt->fetch()['count'] ?? 0);

    $sql = 'SELECT ' . LEADS_SELECT_COLS . " FROM leads $whereSql ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $leads = $stmt->fetchAll();

    return ['leads' => $leads, 'total' => $total, 'limit' => $limit, 'offset' => $offset];
}

/** @return array{total: int, student: int, university: int} */
function get_lead_counts(?string $storageDir = null): array
{
    $pdo = leads_connect($storageDir, true);
    if ($pdo === null) {
        return ['total' => 0, 'student' => 0, 'university' => 0];
    }

    $rows = $pdo->query('SELECT form_type, COUNT(*) AS count FROM leads GROUP BY form_type')->fetchAll();
    $byType = [];
    foreach ($rows as $row) {
        $byType[$row['form_type']] = (int) $row['count'];
    }
    $student = $byType['student'] ?? 0;
    $university = $byType['university'] ?? 0;

    return ['total' => $student + $university, 'student' => $student, 'university' => $university];
}

/** @return array<string, mixed>|null */
function get_lead(string $leadId, ?string $storageDir = null): ?array
{
    $pdo = leads_connect($storageDir, true);
    if ($pdo === null) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT ' . LEADS_SELECT_COLS . ' FROM leads WHERE id = :id');
    $stmt->execute([':id' => $leadId]);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}
