# Higher Learning Marketers — Pure PHP

Apache/Nginx document root = **this repository root**. No Node. No Python. No Flask.

## Setup

```bash
cp .env.example .env
# Set at least SECRET_KEY and ADMIN_PASSWORD
mkdir -p data/leads
```

Required PHP extensions: `pdo_sqlite`, `curl`, `mbstring`.

## Run (development)

From the repo root:

```bash
php -S localhost:10001 deploy/router.php
```

Or:

```bash
php -S localhost:10001
```

## Production (VPS)

Point the vhost `DocumentRoot` at the clone directory (where `index.php` and `.htaccess` live).

```apache
DocumentRoot /var/www/hlm
<Directory /var/www/hlm>
    AllowOverride All
    Require all granted
</Directory>
```

`.htaccess` rewrites non-file requests to `index.php`.

Keep `data/leads/` writable by the web user. Do not commit `.env`.

## Admin

- UI: `/admin/`
- Login: `/admin/login/`
- Requires `ADMIN_PASSWORD` in `.env`
