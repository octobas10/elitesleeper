"""Password-protected leads admin."""

from __future__ import annotations

from functools import wraps

from flask import (
    Blueprint,
    current_app,
    flash,
    redirect,
    render_template,
    request,
    session,
    url_for,
)

from app.services.leads_db import get_lead, get_lead_counts, list_leads

admin_bp = Blueprint("admin", __name__, url_prefix="/admin")


def _client_ip() -> str:
    forwarded = request.headers.get("X-Forwarded-For", "")
    if forwarded:
        return forwarded.split(",")[0].strip()
    return request.remote_addr or "0.0.0.0"


def _ip_allowed() -> bool:
    allowed = current_app.config.get("ADMIN_ALLOWED_IPS") or []
    if not allowed:
        return True
    return _client_ip() in allowed


def admin_required(view):
    @wraps(view)
    def wrapped(*args, **kwargs):
        if not _ip_allowed():
            return "Forbidden", 403
        password = current_app.config.get("ADMIN_PASSWORD") or ""
        if not password:
            return (
                render_template(
                    "admin/login.html",
                    error="Set ADMIN_PASSWORD in the environment to enable admin.",
                    disabled=True,
                ),
                503,
            )
        if not session.get("hlm_admin"):
            return redirect(url_for("admin.login", next=request.path))
        return view(*args, **kwargs)

    return wrapped


@admin_bp.route("/login/", methods=["GET", "POST"])
@admin_bp.route("/login", methods=["GET", "POST"])
def login():
    if not _ip_allowed():
        return "Forbidden", 403

    password = current_app.config.get("ADMIN_PASSWORD") or ""
    if not password:
        return render_template(
            "admin/login.html",
            error="Set ADMIN_PASSWORD in the environment to enable admin.",
            disabled=True,
        )

    error = None
    if request.method == "POST":
        if request.form.get("password") == password:
            session["hlm_admin"] = True
            nxt = request.args.get("next") or url_for("admin.leads")
            return redirect(nxt)
        error = "Incorrect password."

    return render_template("admin/login.html", error=error)


@admin_bp.route("/logout/", methods=["GET", "POST"])
@admin_bp.route("/logout", methods=["GET", "POST"])
def logout():
    session.pop("hlm_admin", None)
    return redirect(url_for("admin.login"))


@admin_bp.get("/")
@admin_bp.get("")
@admin_required
def leads():
    form_type = request.args.get("form_type") or request.args.get("type") or ""
    q = request.args.get("q") or ""
    result = list_leads(
        storage_dir=current_app.config.get("LEAD_STORAGE_DIR"),
        form_type=form_type or None,
        q=q or None,
        limit=200,
    )
    counts = get_lead_counts(storage_dir=current_app.config.get("LEAD_STORAGE_DIR"))
    return render_template(
        "admin/leads.html",
        leads=result.get("leads") or [],
        counts=counts,
        form_type=form_type,
        q=q,
        total=result.get("total", 0),
    )


@admin_bp.get("/leads/<lead_id>/")
@admin_bp.get("/leads/<lead_id>")
@admin_required
def lead_detail(lead_id: str):
    row = get_lead(lead_id, storage_dir=current_app.config.get("LEAD_STORAGE_DIR"))
    if not row:
        flash("Lead not found.", "error")
        return redirect(url_for("admin.leads"))
    return render_template("admin/lead_detail.html", lead=row)
