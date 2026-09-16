# Higher Learning Marketers (PHP)

Live site code is in `site/`. Point the web server document root at that folder.

## Setup

```bash
cd site
cp .env.example .env
# Set SECRET_KEY and ADMIN_PASSWORD
mkdir -p data/leads
```

PHP extensions: `pdo_sqlite`, `curl`, `mbstring`.

## Local development

From the repo root:

```bash
php -S localhost:10001 deploy/router.php
```

Or:

```bash
cd site && php -S localhost:10001
```

## Production

Upload only `site/`. Set DocumentRoot to that directory. Keep `data/leads/` writable. Do not commit `.env`.

## Admin

- `/admin/` and `/admin/login/`
- Requires `ADMIN_PASSWORD` in `site/.env`
