"""Institution / campus / program lookup — mirrors Astro institution.ts."""

from __future__ import annotations

import json
from functools import lru_cache
from pathlib import Path
from typing import Any

_DATA_PATH = Path(__file__).resolve().parent.parent / "data" / "institution-data.json"


@lru_cache(maxsize=1)
def _load() -> dict[str, Any]:
    with _DATA_PATH.open(encoding="utf-8") as fh:
        return json.load(fh)


def get_institutions() -> dict[str, str]:
    return dict(_load().get("institutions") or {})


def get_campus_data() -> dict[str, dict[str, str]]:
    return dict(_load().get("campus") or {})


def get_program_data() -> dict[str, dict[str, dict[str, str]]]:
    return dict(_load().get("programs") or {})


def is_valid_campus(category: str, campus: str) -> bool:
    return bool((_load().get("campus") or {}).get(category, {}).get(campus))


def is_valid_program(category: str, campus: str, program: str) -> bool:
    return bool(
        (_load().get("programs") or {})
        .get(category, {})
        .get(campus, {})
        .get(program)
    )
