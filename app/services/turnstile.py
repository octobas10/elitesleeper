"""Cloudflare Turnstile verification — mirrors Astro turnstile.ts."""

from __future__ import annotations

import logging
import os
from typing import Any

import requests

logger = logging.getLogger(__name__)

TURNSTILE_VERIFY_URL = "https://challenges.cloudflare.com/turnstile/v0/siteverify"


def get_turnstile_site_key() -> str:
    return (os.getenv("PUBLIC_TURNSTILE_SITE_KEY") or "").strip()


def get_turnstile_secret_key() -> str:
    return (os.getenv("TURNSTILE_SECRET_KEY") or "").strip()


def is_turnstile_enabled() -> bool:
    return bool(get_turnstile_site_key() and get_turnstile_secret_key())


def verify_turnstile_token(
    token: str,
    remoteip: str | None = None,
    *,
    secret: str | None = None,
) -> dict[str, Any]:
    """
    Verify a Turnstile token.

    Returns ``{"ok": True}`` or ``{"ok": False, "error": "..."}``.
    If no secret is configured, verification is skipped (ok=True).
    """
    secret_key = (secret if secret is not None else get_turnstile_secret_key()).strip()
    if not secret_key:
        return {"ok": True}

    if not (token or "").strip():
        return {"ok": False, "error": "Please complete the security check."}

    try:
        body: dict[str, str] = {
            "secret": secret_key,
            "response": token.strip(),
        }
        if remoteip and remoteip != "unknown":
            body["remoteip"] = remoteip

        res = requests.post(
            TURNSTILE_VERIFY_URL,
            data=body,
            headers={"Content-Type": "application/x-www-form-urlencoded"},
            timeout=15,
        )
        data = res.json()
        if not data.get("success"):
            logger.warning("[hlm-turnstile] verify failed %s", data.get("error-codes"))
            return {"ok": False, "error": "Security check failed. Please try again."}
        return {"ok": True}
    except Exception as err:  # noqa: BLE001 — match Astro catch-all
        logger.error("[hlm-turnstile] verify error %s", err)
        return {"ok": False, "error": "Security check unavailable. Please try again."}
