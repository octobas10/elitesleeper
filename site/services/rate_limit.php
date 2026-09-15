<?php

declare(strict_types=1);

/**
 * Sliding-window rate limiter using a JSON file with flock.
 */
function is_rate_limited(string $namespace, string $key, int $maxHits, int $windowMs): bool
{
    $storageDir = Config::get('LEAD_STORAGE_DIR');
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
    }

    $path = rtrim((string) $storageDir, '/') . '/rate_limit.json';
    $now = (int) (microtime(true) * 1000);
    $bucketKey = $namespace . ':' . $key;

    $fp = fopen($path, 'c+');
    if ($fp === false) {
        return false;
    }

    try {
        if (!flock($fp, LOCK_EX)) {
            return false;
        }

        $raw = stream_get_contents($fp);
        $data = json_decode($raw ?: '{}', true);
        if (!is_array($data)) {
            $data = [];
        }

        $hits = $data[$bucketKey] ?? [];
        if (!is_array($hits)) {
            $hits = [];
        }

        $hits = array_values(array_filter(
            $hits,
            static fn ($t) => is_numeric($t) && ($now - (float) $t) < $windowMs
        ));

        if (count($hits) >= $maxHits) {
            $data[$bucketKey] = $hits;
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($data));
            fflush($fp);
            flock($fp, LOCK_UN);
            return true;
        }

        $hits[] = $now;
        $data[$bucketKey] = $hits;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        fflush($fp);
        flock($fp, LOCK_UN);
        return false;
    } finally {
        fclose($fp);
    }
}
