# Higher Learning Marketers — Pure PHP

No Node.js. No Python required to run the site.

## Setup

```bash
cp .env.example .env
# Edit .env — set SECRET_KEY and ADMIN_PASSWORD at minimum

# Export content bundle from Flask content.py (one-time / when content changes)
python3 scripts/export_content_for_php.py

# Ensure static assets & JSON are in place
mkdir -p site/data/leads
cp -n app/data/*.json site/data/ 2>/dev/null || true
cp -a app/static/. site/static/
```

## Run (development)

```bash
cd site
php -S localhost:10001 ../deploy/router.php
```

Or:

```bash
cd site
php -S localhost:10001
```

## Production (Apache)

Point the vhost document root at `site/`. `.htaccess` rewrites to `index.php`.

Required PHP extensions: `pdo_sqlite`, `curl`, `mbstring`.

## Admin

Leads admin UI: **/admin/**

- Login: `/admin/login/`
- Requires `ADMIN_PASSWORD` in `.env`

## Flask archive

The previous Flask app remains in this repo (`app/`, `wsgi.py`). A zip snapshot may be under `_archived/`.
