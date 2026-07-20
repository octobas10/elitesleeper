"""Sliding-window in-memory IP rate limiter — mirrors Astro rate-limit.ts."""

from __future__ import annotations

import time
from collections import defaultdict

# namespace -> key -> list of hit timestamps (ms)
_buckets: dict[str, dict[str, list[float]]] = defaultdict(dict)


def is_rate_limited(
    namespace: str,
    key: str,
    max_hits: int,
    window_ms: int,
) -> bool:
    """
    Sliding-window rate limiter (in-memory, per process).

    Returns True when the key should be blocked.
    """
    now = time.time() * 1000
    bucket = _buckets[namespace]
    hits = [t for t in bucket.get(key, []) if now - t < window_ms]

    if len(hits) >= max_hits:
        bucket[key] = hits
        return True

    hits.append(now)
    bucket[key] = hits
    return False
