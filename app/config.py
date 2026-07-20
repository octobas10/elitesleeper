"""Application configuration from environment."""

from __future__ import annotations

import os
from pathlib import Path

BASE_DIR = Path(__file__).resolve().parent.parent
DATA_DIR = Path(__file__).resolve().parent / "data"


class Config:
    SECRET_KEY = os.getenv("SECRET_KEY", "hlm-dev-change-me")
    LEAD_STORAGE_DIR = os.getenv(
        "LEAD_STORAGE_DIR",
        str(BASE_DIR / "data" / "leads"),
    )
    ADMIN_PASSWORD = os.getenv("ADMIN_PASSWORD", "")
    ADMIN_ALLOWED_IPS = [
        ip.strip()
        for ip in os.getenv("ADMIN_ALLOWED_IPS", "").split(",")
        if ip.strip()
    ]

    ELITE_PROMO_CODE = os.getenv("ELITE_PROMO_CODE", "")
    ELITE_LEAD_MODE = os.getenv("ELITE_LEAD_MODE", "test")
    ELITE_API_URL = os.getenv(
        "ELITE_API_URL",
        "https://elitebizpanel.com/edu/postprocess",
    )

    LEAD_NOTIFY_EMAIL = os.getenv("LEAD_NOTIFY_EMAIL", "")
    LEAD_WEBHOOK_URL = os.getenv("LEAD_WEBHOOK_URL", "")
    LEAD_NOTIFY_FROM = os.getenv(
        "LEAD_NOTIFY_FROM",
        "noreply@higherlearningmarketers.com",
    )
    RESEND_API_KEY = os.getenv("RESEND_API_KEY", "")

    SMTP_HOST = os.getenv("SMTP_HOST", "")
    SMTP_PORT = int(os.getenv("SMTP_PORT", "587"))
    SMTP_USER = os.getenv("SMTP_USER", "")
    SMTP_PASSWORD = os.getenv("SMTP_PASSWORD", "")
    SMTP_USE_TLS = os.getenv("SMTP_USE_TLS", "1") != "0"

    TURNSTILE_SECRET_KEY = os.getenv("TURNSTILE_SECRET_KEY", "")
    PUBLIC_TURNSTILE_SITE_KEY = os.getenv("PUBLIC_TURNSTILE_SITE_KEY", "")
    PUBLIC_ENABLE_TRUSTEDFORM = os.getenv("PUBLIC_ENABLE_TRUSTEDFORM", "0") == "1"
    PUBLIC_ENABLE_LEADID = os.getenv("PUBLIC_ENABLE_LEADID", "0") == "1"

    RATE_LIMIT_MAX = int(os.getenv("RATE_LIMIT_MAX", "5"))
    RATE_LIMIT_WINDOW_SEC = int(os.getenv("RATE_LIMIT_WINDOW_SEC", "900"))

    SITE_URL = os.getenv("SITE_URL", "https://higherlearningmarketers.com")
