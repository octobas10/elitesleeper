"""Higher Learning Marketers — Flask application factory."""

from __future__ import annotations

import os
from pathlib import Path

from dotenv import load_dotenv
from flask import Flask

from app.config import Config
from app.routes.admin import admin_bp
from app.routes.api import api_bp
from app.routes.pages import pages_bp


def create_app(config_class: type[Config] = Config) -> Flask:
    load_dotenv()

    app = Flask(
        __name__,
        static_folder="static",
        template_folder="templates",
    )
    app.config.from_object(config_class)

    Path(app.config["LEAD_STORAGE_DIR"]).mkdir(parents=True, exist_ok=True)

    app.register_blueprint(pages_bp)
    app.register_blueprint(api_bp)
    app.register_blueprint(admin_bp)

    @app.context_processor
    def inject_globals():
        from app.content import contact, media, nav, site

        return {
            "site": site,
            "contact": contact,
            "nav": nav,
            "media": media,
        }

    return app
