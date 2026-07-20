"""Lead notification via webhook / Resend / SMTP — mirrors Astro notify-lead.ts."""

from __future__ import annotations

import logging
import smtplib
from datetime import datetime, timezone
from email.message import EmailMessage
from typing import Any, Mapping

import requests

logger = logging.getLogger(__name__)

DEFAULT_FROM = "HLM Leads <leads@higherlearningmarketers.com>"


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


def format_lead_body(form_type: str, data: dict[str, Any]) -> str:
    lines = [
        f"{k}: {v}"
        for k, v in data.items()
        if not str(k).startswith("hlm_")
    ]
    return (
        f"New {form_type} lead from Higher Learning Marketers\n\n"
        + "\n".join(lines)
    )


def notify_lead(
    form_type: str,
    data: dict[str, Any],
    config: Any = None,
) -> None:
    """
    Notify operators of a new lead.

    Order matches Astro:
    1. Optional webhook POST (always attempted when URL set)
    2. Resend email when API key + notify email are set (returns after success)
    3. Otherwise SMTP when host + notify email are set
    """
    webhook = _cfg_get(config, "LEAD_WEBHOOK_URL").strip()
    if webhook:
        payload = {
            "form_type": form_type,
            "submitted_at": datetime.now(timezone.utc).isoformat().replace("+00:00", "Z"),
            **data,
        }
        requests.post(
            webhook,
            json=payload,
            headers={"Content-Type": "application/json"},
            timeout=20,
        )

    resend_key = _cfg_get(config, "RESEND_API_KEY").strip()
    to_addr = _cfg_get(config, "LEAD_NOTIFY_EMAIL").strip()
    from_addr = (
        _cfg_get(config, "LEAD_NOTIFY_FROM").strip()
        or DEFAULT_FROM
    )

    subject = (
        f"New {form_type} lead — "
        f"{data.get('email') or data.get('institution_name') or 'HLM'}"
    )
    text = format_lead_body(form_type, data)

    if resend_key and to_addr:
        res = requests.post(
            "https://api.resend.com/emails",
            headers={
                "Authorization": f"Bearer {resend_key}",
                "Content-Type": "application/json",
            },
            json={
                "from": from_addr,
                "to": [to_addr],
                "subject": subject,
                "text": text,
            },
            timeout=20,
        )
        if not res.ok:
            raise RuntimeError(f"Resend failed: {res.status_code}")
        return

    host = _cfg_get(config, "SMTP_HOST").strip()
    if host and to_addr:
        port = int(_cfg_get(config, "SMTP_PORT", "587") or "587")
        user = _cfg_get(config, "SMTP_USER").strip()
        password = (
            _cfg_get(config, "SMTP_PASSWORD").strip()
            or _cfg_get(config, "SMTP_PASS").strip()
        )
        use_tls = _cfg_get(config, "SMTP_USE_TLS", "1") not in ("0", "false", "False")
        # Astro used SMTP_SECURE === 'true' for SSL; map USE_TLS / SMTP_SECURE
        secure = _cfg_get(config, "SMTP_SECURE", "").lower() == "true"
        smtp_from = _cfg_get(config, "SMTP_FROM").strip() or from_addr

        msg = EmailMessage()
        msg["From"] = smtp_from
        msg["To"] = to_addr
        msg["Subject"] = subject
        msg.set_content(text)

        if secure:
            with smtplib.SMTP_SSL(host, port, timeout=30) as smtp:
                if user:
                    smtp.login(user, password)
                smtp.send_message(msg)
        else:
            with smtplib.SMTP(host, port, timeout=30) as smtp:
                if use_tls:
                    smtp.starttls()
                if user:
                    smtp.login(user, password)
                smtp.send_message(msg)
