# Production on your VPS — pure PHP

**Goal:** Serve Higher Learning Marketers from this repo root (where `index.php` lives).

No Python. No Gunicorn. No Flask. PHP + Apache (or Nginx + php-fpm) only.

---

## A. Upload / clone

```bash
# Example path on the VPS
sudo mkdir -p /var/www/hlm
sudo chown "$USER":"$USER" /var/www/hlm

git clone https://github.com/octobas10/hlm-ebp.git /var/www/hlm
cd /var/www/hlm
cp .env.example .env
nano .env
mkdir -p data/leads
chmod 775 data/leads
```

Set at least:

```env
SECRET_KEY=paste-a-long-random-string-here
SITE_URL=https://higherlearningmarketers.com
ADMIN_PASSWORD=your-strong-admin-password
LEAD_STORAGE_DIR=/var/www/hlm/data/leads
```

---

## B. Apache vhost

```apache
<VirtualHost *:80>
    ServerName higherlearningmarketers.com
    DocumentRoot /var/www/hlm

    <Directory /var/www/hlm>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/hlm-error.log
    CustomLog ${APACHE_LOG_DIR}/hlm-access.log combined
</VirtualHost>
```

Enable `mod_rewrite`. Terminate TLS with Certbot as usual.

Forward real client IP if behind a proxy — `ADMIN_ALLOWED_IPS` uses `X-Forwarded-For` when present.

---

## C. Permissions

```bash
sudo chown -R www-data:www-data /var/www/hlm/data/leads
# or keep deploy user owner and add www-data to the group
```

---

## D. Smoke check

- https://higherlearningmarketers.com/
- https://higherlearningmarketers.com/admin/login/
- POST `/api/submit/` from the contact form

Admin requires `ADMIN_PASSWORD` in `.env`.
