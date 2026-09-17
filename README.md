# Higher Learning Marketers (PHP)

Pure PHP site at the **repo root**. Point the web server document root at this folder (e.g. `htdocs`).

## Setup

```bash
cp .env.example .env
# Set SECRET_KEY and ADMIN_PASSWORD
mkdir -p data/leads
```

PHP extensions: `pdo_sqlite`, `curl`, `mbstring`.

## Local development

```bash
php -S localhost:10001
```

Or with the deploy router:

```bash
php -S localhost:10001 deploy/router.php
```

## Production

Git pull into the document root. Keep `data/leads/` writable. Do not commit `.env`.

## Admin

- `/admin/` and `/admin/login/`
- Requires `ADMIN_PASSWORD` in `.env`
