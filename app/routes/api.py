"""API routes — lead submit and public config."""

from __future__ import annotations

import logging

from flask import Blueprint, current_app, jsonify, request

from app.services.elite import post_to_elite
from app.services.leads_db import store_lead
from app.services.notify import notify_lead
from app.services.rate_limit import is_rate_limited
from app.services.turnstile import verify_turnstile_token
from app.services.validate import validate_lead

logger = logging.getLogger(__name__)

api_bp = Blueprint("api", __name__, url_prefix="/api")


def _client_ip() -> str:
    forwarded = request.headers.get("X-Forwarded-For", "")
    if forwarded:
        return forwarded.split(",")[0].strip()
    return request.remote_addr or "0.0.0.0"


@api_bp.post("/submit/")
@api_bp.post("/submit")
def submit_lead():
    ip = _client_ip()
    max_submits = current_app.config.get("RATE_LIMIT_MAX", 5)
    window = current_app.config.get("RATE_LIMIT_WINDOW_SEC", 900) * 1000

    if is_rate_limited("lead-submit", ip, max_submits, window):
        return jsonify({"error": "Too many submissions. Try again later."}), 429

    body = request.get_json(silent=True)
    if not isinstance(body, dict):
        return jsonify({"error": "Invalid JSON."}), 400

    if body.get("hlm_hp_url"):
        return jsonify({"ok": True})

    secret = current_app.config.get("TURNSTILE_SECRET_KEY") or ""
    if secret:
        token = str(body.get("cf-turnstile-response") or body.get("turnstile_token") or "")
        check = verify_turnstile_token(token, ip, secret=secret)
        if not check.get("ok"):
            return jsonify({"error": check.get("error", "Captcha failed.")}), 400

    skip = {"form_type", "hlm_hp_url", "cf-turnstile-response", "turnstile_token"}
    form_type = str(body.get("form_type") or "")
    data = {
        k: (
            ",".join(str(x) for x in v)
            if isinstance(v, list)
            else str(v if v is not None else "")
        )
        for k, v in body.items()
        if k not in skip
    }

    ok, errors = validate_lead(form_type, data)
    if not ok:
        return (
            jsonify(
                {
                    "error": errors.get("error", "Validation failed."),
                    "fields": errors.get("fields"),
                }
            ),
            400,
        )

    try:
        lead_id = store_lead(
            form_type,
            {**data, "ip": ip},
            storage_dir=current_app.config.get("LEAD_STORAGE_DIR"),
        )
    except Exception:
        logger.exception("[hlm-lead] storage failed")
        return jsonify({"error": "Could not save your submission. Please try again."}), 500

    if form_type == "student":
        try:
            post_to_elite(
                {**data, "form_type": form_type, "ip": ip},
                current_app.config,
            )
        except Exception:
            logger.exception("[hlm-lead] elite failed")

    try:
        notify_lead(form_type, data, current_app.config)
    except Exception:
        logger.exception("[hlm-lead] notify failed")

    return jsonify(
        {
            "ok": True,
            "message": "Lead submitted successfully.",
            "lead_id": lead_id,
        }
    )


@api_bp.get("/public-config/")
@api_bp.get("/public-config")
def public_config():
    return jsonify(
        {
            "turnstileSiteKey": current_app.config.get("PUBLIC_TURNSTILE_SITE_KEY") or "",
            "enableTrustedForm": bool(current_app.config.get("PUBLIC_ENABLE_TRUSTEDFORM")),
            "enableLeadId": bool(current_app.config.get("PUBLIC_ENABLE_LEADID")),
        }
    )
