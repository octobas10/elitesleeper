"""Elite Biz Panel lead posting — mirrors Astro submit.ts postToElite."""

from __future__ import annotations

import re
from datetime import datetime, timezone
from typing import Any, Mapping

import requests

DEFAULT_ELITE_URL = "https://elitebizpanel.com/edu/postprocess"
DEFAULT_TCPA = (
    "I agree to be contacted by phone, email, and text message "
    "regarding educational opportunities."
)


def _cfg_get(config: Any, key: str, default: str = "") -> str:
    if config is None:
        return default
    if isinstance(config, Mapping):
        val = config.get(key, default)
    else:
        val = getattr(config, key, default)
    if val is None:
        return default
    return str(val)


def digits_only(v: str) -> str:
    return re.sub(r"\D", "", v or "")


def post_to_elite(lead_data: dict[str, Any], config: Any = None) -> dict[str, Any] | None:
    """
    POST a student lead to Elite Biz Panel.

    Skips (returns None) when ``ELITE_PROMO_CODE`` is empty.
    Raises ``requests.HTTPError`` / ``RuntimeError`` on HTTP failure.
    """
    promo = _cfg_get(config, "ELITE_PROMO_CODE").strip()
    if not promo:
        return None

    mode = _cfg_get(config, "ELITE_LEAD_MODE", "test").strip() or "test"
    api_url = _cfg_get(config, "ELITE_API_URL", DEFAULT_ELITE_URL).strip() or DEFAULT_ELITE_URL

    data = {k: ("" if v is None else str(v)) for k, v in lead_data.items()}
    form_type = data.get("form_type") or "student"
    phone = digits_only(data.get("phone", ""))
    ip = data.get("ip") or data.get("ipaddress") or ""

    tcpa_text = data.get("tcpa_text") or DEFAULT_TCPA
    tcpa_text = tcpa_text[:200]

    now = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M:%S")

    body = {
        "lead_mode": mode,
        "promo_code": promo,
        "sub_id": f"hlm-flask-{form_type}",
        "datetime_stamp": now,
        "first_name": data.get("first_name", ""),
        "last_name": data.get("last_name", ""),
        "email": data.get("email", ""),
        "phone": phone,
        "mobile": phone,
        "address": data.get("address", ""),
        "city": data.get("city", ""),
        "state": (data.get("state") or "").upper()[:2],
        "zip": data.get("zip", ""),
        "education_level": data.get("education_level", ""),
        "desired_degree": data.get("desired_degree", ""),
        "program_of_interest": data.get("program_of_interest", ""),
        "campus": data.get("campus", ""),
        "grad_year": data.get("grad_year", ""),
        "ipaddress": ip,
        "url": data.get("page_url") or data.get("url") or "",
        "tcpa_text": tcpa_text,
        "trustedformcerturl": data.get("trustedformcerturl", ""),
        "universal_leadid": data.get("universal_leadid", ""),
    }

    res = requests.post(
        api_url,
        data=body,
        headers={"Content-Type": "application/x-www-form-urlencoded"},
        timeout=30,
    )
    if not res.ok:
        raise RuntimeError(f"Elite HTTP {res.status_code}")

    try:
        return res.json()
    except ValueError:
        return {"ok": True, "status_code": res.status_code, "text": res.text}
