# Higher Learning Marketers — Pure PHP

The deployable app lives in **`site/`**. Point the VPS document root at `site/` only — ignore design refs and other root clutter.

## Setup

```bash
cd site
cp .env.example .env
# Set SECRET_KEY and ADMIN_PASSWORD
mkdir -p data/leads
```

Required PHP extensions: `pdo_sqlite`, `curl`, `mbstring`.

## Run (development)

From the **repo root**:

```bash
php -S localhost:10001 deploy/router.php
```

Or from `site/`:

```bash
cd site && php -S localhost:10001
```

## Production (VPS)

Upload / rsync **only `site/`** to the server. DocumentRoot should point at the deployed `site/` folder.

```apache
# If you clone full repo on server:
DocumentRoot /var/www/hlm-repo/site
# If you rsync only ./site/ into /var/www/hlm:
# DocumentRoot /var/www/hlm
```

Keep `data/leads/` writable. Do not commit `.env`.

## Admin

- `/admin/` — requires `ADMIN_PASSWORD` in `site/.env`
