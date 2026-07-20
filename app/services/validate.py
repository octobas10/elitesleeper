"""Lead form validation — mirrors Astro validate-lead.ts."""

from __future__ import annotations

import re
from typing import Any

from app.services.institutions import is_valid_campus, is_valid_program

STUDENT_REQUIRED = (
    "first_name",
    "last_name",
    "email",
    "phone",
    "address",
    "city",
    "state",
    "zip",
    "category",
    "campus",
    "program_of_interest",
    "education_level",
    "desired_degree",
    "grad_year",
)

UNIVERSITY_REQUIRED = (
    "institution_name",
    "first_name",
    "last_name",
    "email",
    "phone",
)

_EMAIL_RE = re.compile(r"^[^\s@]+@[^\s@]+\.[^\s@]+$")
_US_ZIP_RE = re.compile(r"^\d{5}(-\d{4})?$")
_CA_POSTAL_RE = re.compile(r"^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$")
_STATE_RE = re.compile(r"^[A-Za-z]{2}$")
_URL_RE = re.compile(r"^https?://.+", re.IGNORECASE)


def digits_only(v: str) -> str:
    return re.sub(r"\D", "", v or "")


def is_email(v: str) -> bool:
    return bool(_EMAIL_RE.match(v or ""))


def is_valid_name(v: str) -> bool:
    return len((v or "").strip()) >= 2


def is_valid_zip(v: str) -> bool:
    zip_code = (v or "").strip()
    return bool(_US_ZIP_RE.match(zip_code) or _CA_POSTAL_RE.match(zip_code))


def _str(data: dict[str, Any], key: str) -> str:
    val = data.get(key)
    if val is None:
        return ""
    return str(val)


def validate_lead(
    form_type: str,
    data: dict[str, Any],
) -> tuple[bool, dict[str, Any]]:
    """
    Validate a lead submission.

    Returns (ok, errors_dict). On failure, errors_dict includes
    ``error`` (message) and optionally ``fields`` (missing required keys),
    matching the Astro validateLead response shape.
    """
    if form_type not in ("student", "university"):
        return False, {"error": "Invalid form type."}

    required = STUDENT_REQUIRED if form_type == "student" else UNIVERSITY_REQUIRED
    missing: list[str] = []

    for field in required:
        if not _str(data, field).strip():
            missing.append(field)

    if form_type == "student" and not _str(data, "tcpa_consent"):
        missing.append("tcpa_consent")

    if missing:
        return False, {
            "error": "Please fill in all required fields.",
            "fields": missing,
        }

    if not is_email(_str(data, "email")):
        return False, {"error": "Please enter a valid email address."}

    phone = digits_only(_str(data, "phone"))
    if len(phone) != 10:
        return False, {"error": "Please enter a valid 10-digit phone number."}

    state = _str(data, "state")
    if state and not _STATE_RE.match(state):
        return False, {"error": "Please select a valid state or province."}

    zip_code = _str(data, "zip")
    if zip_code and not is_valid_zip(zip_code):
        return False, {"error": "Please enter a valid ZIP or postal code."}

    for name_field in ("first_name", "last_name", "institution_name"):
        value = _str(data, name_field)
        if value and not is_valid_name(value):
            return False, {
                "error": "Please enter a valid name (at least 2 characters).",
            }

    website = _str(data, "website")
    if website and not _URL_RE.match(website):
        return False, {"error": "Please enter a valid website URL."}

    if form_type == "student":
        category = _str(data, "category")
        campus = _str(data, "campus")
        program = _str(data, "program_of_interest")
        if category and campus and not is_valid_campus(category, campus):
            return False, {"error": "Please select a valid campus."}
        if category and campus and program and not is_valid_program(category, campus, program):
            return False, {"error": "Please select a valid program."}

    return True, {}
