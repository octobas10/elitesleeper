"""SQLite lead storage — mirrors Astro lead-storage.ts."""

from __future__ import annotations

import json
import os
import sqlite3
import time
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

SCHEMA_SQL = """
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
"""

INSERT_SQL = """
INSERT INTO leads (
    id, form_type, status, first_name, last_name, email, phone,
    city, state, zip, category, campus, program_of_interest,
    institution_name, payload, created_at
) VALUES (
    ?, ?, 'saved', ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?,
    ?, ?, ?
)
"""

SELECT_COLS = """
    id, form_type, status, first_name, last_name, email, phone,
    city, state, zip, category, campus, program_of_interest,
    institution_name, payload, created_at
"""


def get_leads_dir(storage_dir: str | None = None) -> Path:
    if storage_dir:
        return Path(storage_dir)
    env = os.getenv("LEAD_STORAGE_DIR")
    if env:
        return Path(env)
    # flask/data/leads relative to this package's grandparent (flask/)
    return Path(__file__).resolve().parent.parent.parent / "data" / "leads"


def _db_path(storage_dir: str | None = None) -> Path:
    return get_leads_dir(storage_dir) / "leads.db"


def _connect(storage_dir: str | None = None, *, readonly: bool = False) -> sqlite3.Connection | None:
    path = _db_path(storage_dir)
    if readonly and not path.exists():
        return None
    path.parent.mkdir(parents=True, exist_ok=True)
    uri = f"file:{path.as_posix()}?mode=ro" if readonly else path.as_posix()
    if readonly:
        conn = sqlite3.connect(uri, uri=True)
    else:
        conn = sqlite3.connect(path)
    conn.row_factory = sqlite3.Row
    return conn


def _row_to_dict(row: sqlite3.Row | None) -> dict[str, Any] | None:
    if row is None:
        return None
    return {k: row[k] for k in row.keys()}


def _store_in_sqlite(form_type: str, record: dict[str, Any], directory: Path) -> None:
    directory.mkdir(parents=True, exist_ok=True)
    conn = sqlite3.connect(directory / "leads.db")
    try:
        conn.executescript(SCHEMA_SQL)
        conn.execute(
            INSERT_SQL,
            (
                record["id"],
                form_type,
                record.get("first_name") or "",
                record.get("last_name") or "",
                record.get("email") or "",
                record.get("phone") or "",
                record.get("city") or "",
                record.get("state") or "",
                record.get("zip") or "",
                record.get("category") or "",
                record.get("campus") or "",
                record.get("program_of_interest") or "",
                record.get("institution_name") or "",
                json.dumps(record),
                record["submitted_at"],
            ),
        )
        conn.commit()
    finally:
        conn.close()


def _store_as_json_files(record: dict[str, Any], directory: Path) -> None:
    directory.mkdir(parents=True, exist_ok=True)
    payload = json.dumps(record)
    (directory / f"{record['id']}.json").write_text(payload, encoding="utf-8")
    with (directory / "leads.jsonl").open("a", encoding="utf-8") as fh:
        fh.write(payload + "\n")


def store_lead(
    form_type: str,
    data: dict[str, Any],
    *,
    storage_dir: str | None = None,
) -> str:
    """
    Persist a lead to SQLite (primary) and JSON/JSONL backup.

    Returns the lead id. Raises RuntimeError if both backends fail.
    """
    lead_id = f"{int(time.time() * 1000)}-{form_type}"
    record: dict[str, Any] = {
        "id": lead_id,
        "form_type": form_type,
        "submitted_at": datetime.now(timezone.utc).isoformat().replace("+00:00", "Z"),
        **{k: ("" if v is None else str(v)) for k, v in data.items()},
    }

    directory = get_leads_dir(storage_dir)
    saved = False

    try:
        _store_in_sqlite(form_type, record, directory)
        saved = True
    except Exception as err:  # noqa: BLE001
        print(f"[hlm-lead] sqlite storage failed {err}")

    try:
        _store_as_json_files(record, directory)
        saved = True
    except Exception as err:  # noqa: BLE001
        print(f"[hlm-lead] json backup failed {err}")

    if not saved:
        raise RuntimeError("Lead storage unavailable")

    return lead_id


def list_leads(
    *,
    form_type: str | None = None,
    q: str | None = None,
    limit: int = 50,
    offset: int = 0,
    storage_dir: str | None = None,
) -> dict[str, Any]:
    limit = min(max(limit, 1), 200)
    offset = max(offset, 0)
    form_type = (form_type or "").strip()
    q = (q or "").strip()

    empty = {"leads": [], "total": 0, "limit": limit, "offset": offset}
    conn = _connect(storage_dir, readonly=True)
    if conn is None:
        return empty

    try:
        where: list[str] = []
        params: list[Any] = []

        if form_type in ("student", "university"):
            where.append("form_type = ?")
            params.append(form_type)

        if q:
            like = f"%{q}%"
            where.append(
                "(email LIKE ? OR first_name LIKE ? OR last_name LIKE ? "
                "OR phone LIKE ? OR id LIKE ? OR institution_name LIKE ?)"
            )
            params.extend([like, like, like, like, like, like])

        where_sql = f"WHERE {' AND '.join(where)}" if where else ""

        total_row = conn.execute(
            f"SELECT COUNT(*) AS count FROM leads {where_sql}",
            params,
        ).fetchone()
        total = int(total_row["count"] if total_row else 0)

        rows = conn.execute(
            f"""
            SELECT {SELECT_COLS}
            FROM leads
            {where_sql}
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
            """,
            [*params, limit, offset],
        ).fetchall()

        leads = [_row_to_dict(r) for r in rows]
        return {"leads": leads, "total": total, "limit": limit, "offset": offset}
    finally:
        conn.close()


def get_lead_counts(*, storage_dir: str | None = None) -> dict[str, int]:
    conn = _connect(storage_dir, readonly=True)
    if conn is None:
        return {"total": 0, "student": 0, "university": 0}

    try:
        rows = conn.execute(
            """
            SELECT form_type, COUNT(*) AS count
            FROM leads
            GROUP BY form_type
            """
        ).fetchall()
        by_type = {r["form_type"]: int(r["count"]) for r in rows}
        student = by_type.get("student", 0)
        university = by_type.get("university", 0)
        return {"total": student + university, "student": student, "university": university}
    finally:
        conn.close()


def get_lead(lead_id: str, *, storage_dir: str | None = None) -> dict[str, Any] | None:
    conn = _connect(storage_dir, readonly=True)
    if conn is None:
        return None

    try:
        row = conn.execute(
            f"""
            SELECT {SELECT_COLS}
            FROM leads
            WHERE id = ?
            """,
            (lead_id,),
        ).fetchone()
        return _row_to_dict(row)
    finally:
        conn.close()
