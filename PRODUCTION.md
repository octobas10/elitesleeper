# Production on your VPS — replace WordPress with Flask

**Server:** `vish@74.205.70.188`  
**Old WP root:** `/usr/virtual/www.higherlearningmarketers.com`  
**Goal:** Remove WordPress, run the Flask app, keep `higherlearningmarketers.com`.

No Node. Python + gunicorn only.

---

## A. Upload Flask (from your WSL machine)

```bash
ssh vish@74.205.70.188 "mkdir -p /var/www/hlm-flask /var/www/hlm-flask/data/leads"

rsync -avz --exclude '.venv' --exclude '__pycache__' --exclude 'data/leads/*' \
  /home/buildco/highereducation_eilte/flask/ \
  vish@74.205.70.188:/var/www/hlm-flask/
```

---

## B. On the VPS — install & configure

```bash
ssh vish@74.205.70.188
cd /var/www/hlm-flask

python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt

cp .env.example .env
nano .env
```

Set at least:

```env
SECRET_KEY=paste-a-long-random-string-here
SITE_URL=https://higherlearningmarketers.com
ADMIN_PASSWORD=your-strong-admin-password
LEAD_STORAGE_DIR=/var/www/hlm-flask/data/leads

# Optional
ELITE_PROMO_CODE=
ELITE_LEAD_MODE=live
LEAD_NOTIFY_EMAIL=support@higherlearningmarketers.com
```

Generate a secret:

```bash
python3 -c "import secrets; print(secrets.token_hex(32))"
```

---

## C. Run gunicorn with systemd

```bash
sudo tee /etc/systemd/system/hlm-flask.service >/dev/null <<'EOF'
[Unit]
Description=HLM Flask site
After=network.target

[Service]
User=vish
Group=vish
WorkingDirectory=/var/www/hlm-flask
EnvironmentFile=/var/www/hlm-flask/.env
ExecStart=/var/www/hlm-flask/.venv/bin/gunicorn -b 127.0.0.1:5000 --workers 2 --timeout 60 wsgi:app
Restart=always

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload
sudo systemctl enable --now hlm-flask
sudo systemctl status hlm-flask
```

Test locally on the server:

```bash
curl -I http://127.0.0.1:5000/
```

You should see `200`.

---

## D. Point the web server at Flask (not WordPress)

Find what serves the site:

```bash
# Apache?
sudo apache2ctl -S 2>/dev/null | head
# Nginx?
sudo nginx -t 2>/dev/null
ls /etc/nginx/sites-enabled/ 2>/dev/null
# LiteSpeed / other
ps aux | egrep -i 'nginx|apache|litespeed|httpd' | grep -v grep
```

### If Nginx

Proxy the vhost for `higherlearningmarketers.com` to gunicorn:

```nginx
location / {
    proxy_pass http://127.0.0.1:5000;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

Reload: `sudo nginx -t && sudo systemctl reload nginx`

### If Apache

Enable modules and proxy:

```bash
sudo a2enmod proxy proxy_http headers
```

In the vhost for this domain:

```apache
ProxyPreserveHost On
RequestHeader set X-Forwarded-Proto "https"
ProxyPass / http://127.0.0.1:5000/
ProxyPassReverse / http://127.0.0.1:5000/
```

Reload: `sudo systemctl reload apache2`

DNS can stay as-is (same VPS IP). Only the app behind port 80/443 changes.

---

## E. Remove WordPress (after Flask responds 200)

**Do this only after** https://higherlearningmarketers.com shows the Flask site.

```bash
# Backup first (safety)
sudo tar -czf /root/wp-backup-$(date +%F).tar.gz \
  /usr/virtual/www.higherlearningmarketers.com

# Then remove WP (or move it aside)
sudo mv /usr/virtual/www.higherlearningmarketers.com \
        /usr/virtual/www.higherlearningmarketers.com.wp-OLD
```

Optional later: drop the MySQL/MariaDB WordPress database once you no longer need it.

Also remove any WP deploy webhook PHP files if still present:

```bash
rm -f /usr/virtual/www.higherlearningmarketers.com.wp-OLD/hlm-deploy-webhook.php
```

---

## F. Verify

- https://higherlearningmarketers.com/
- https://higherlearningmarketers.com/services/
- https://higherlearningmarketers.com/blog/
- Submit a test lead
- https://higherlearningmarketers.com/admin/ (ADMIN_PASSWORD)

Useful commands:

```bash
sudo systemctl status hlm-flask
sudo journalctl -u hlm-flask -f
```

---

## Updates later

```bash
rsync -avz --exclude '.venv' --exclude 'data/leads' --exclude '.env' \
  /home/buildco/highereducation_eilte/flask/ \
  vish@74.205.70.188:/var/www/hlm-flask/

ssh vish@74.205.70.188 'sudo systemctl restart hlm-flask'
```

---

## Order of operations (safe cutover)

1. Upload Flask to `/var/www/hlm-flask` (WordPress still live)
2. Start gunicorn on `127.0.0.1:5000`
3. Switch Nginx/Apache vhost to proxy → `:5000`
4. Confirm site + forms work
5. Move/delete WordPress docroot
6. Change passwords / clean malware leftovers from the old WP tree
