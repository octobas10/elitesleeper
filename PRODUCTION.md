# Production — deploy site/ only

Upload the **`site/`** folder to the VPS. That folder is the entire PHP app.

Do not upload `design-ref-html/`, docs, archives, or other repo-root clutter.

---

## A. Clone / rsync

```bash
# Option 1: rsync only site/
rsync -avz --delete \
  --exclude '.env' \
  --exclude 'data/leads/' \
  ./site/ user@server:/var/www/hlm/

# Option 2: clone full repo, point docroot at site/
git clone https://github.com/octobas10/hlm-ebp.git /var/www/hlm-repo
# DocumentRoot → /var/www/hlm-repo/site
```

```bash
cd /var/www/hlm   # or .../site
cp .env.example .env
nano .env
mkdir -p data/leads
chmod 775 data/leads
```

```env
SECRET_KEY=long-random-string
SITE_URL=https://higherlearningmarketers.com
ADMIN_PASSWORD=strong-password
LEAD_STORAGE_DIR=/var/www/hlm/data/leads
```

---

## B. Apache

```apache
DocumentRoot /var/www/hlm
<Directory /var/www/hlm>
    AllowOverride All
    Require all granted
</Directory>
```

Enable `mod_rewrite`. PHP extensions: `pdo_sqlite`, `curl`, `mbstring`.

---

## C. Check

- https://yoursite.com/
- https://yoursite.com/admin/login/
