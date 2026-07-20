# Permanent security plan — higherlearningmarketers.com

One-time setup + habits. Stops file injection, DB floods, and re-infection.

---

## The 5 layers (do all of them)

| Layer | What it stops | Where you set it up |
|-------|---------------|---------------------|
| 1. Cloudflare | Bots, floods, most attacks before they hit server | cloudflare.com (free) |
| 2. Locked WordPress core | Hackers rewriting wp-includes | Server SSH (once) |
| 3. Hardened wp-config | Admin file editor, plugin installs from wp-admin | Server FileZilla |
| 4. Git-only deploy | Stolen FTP re-infecting files | Your PC + GitHub |
| 5. Monitoring | Catch problems early | Server cron + Wordfence |

---

## LAYER 1: Cloudflare (do this first — biggest impact)

**Where:** https://dash.cloudflare.com (free account)

1. Add site `higherlearningmarketers.com`
2. Change nameservers at GoDaddy to Cloudflare nameservers (Cloudflare shows you which)
3. SSL/TLS → **Full (strict)**
4. Security → **Bot Fight Mode** → ON
5. Security → WAF → enable managed rules (free tier)
6. Security → Settings → Security Level → **High**
7. Speed → Caching → standard (optional)

**Optional rate limit (free):** Security → WAF → Create rule  
If URI contains `wp-login.php` → Block or Challenge → more than 5 requests per minute.

This fixes most "too many connections" bot floods before they reach MySQL.

---

## LAYER 2: Lock server files (one time after clean upload)

**Where:** SSH on live server

```bash
ssh vish@74.205.70.188
cd /usr/virtual/www.higherlearningmarketers.com
bash server-harden-once.sh
```

Or upload `deploy/server-harden-once.sh` from your PC first:

```bash
scp ~/highereducation_eilte/deploy/server-harden-once.sh vish@74.205.70.188:~/
```

**What it does:**
- Makes `wp-includes/` and `wp-admin/` read-only
- Blocks PHP in `uploads/`
- Deletes `hlm-deploy-webhook.php`, `phpinfo.php`

**When you deploy core updates:** temporarily unlock, upload, run script again.

---

## LAYER 3: Production wp-config.php (live server only)

**Where:** FileZilla → `/usr/virtual/www.higherlearningmarketers.com/wp-config.php`

Use `site/wp-config.production.example.php` as template. Required lines:

```php
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );

define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );

define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );
```

**Redis:** Only add `WP_REDIS_*` if Redis is actually running on the server.  
If not, **disable** `redis-cache` plugin on live (rename folder to `redis-cache-OFF`).  
Misconfigured Redis causes extra DB load.

**Never** copy local `wp-config.php` (Docker `127.0.0.1:3307` will break live).

---

## LAYER 4: Deploy only from Git (stop using FileZilla for daily edits)

**Where:** Your PC (WSL)

| Do | Don't |
|----|-------|
| Edit files in `~/highereducation_eilte/site/` | Edit files directly on server |
| `git commit` + `bash deploy/push-site-to-github.sh` | Upload random files via FTP |
| Deploy theme/plugins via SSH rsync from Mac runner | Leave `hlm-deploy-webhook.php` public |

**Delete permanently from server:** `hlm-deploy-webhook.php`

**SFTP:** One dedicated deploy user, strong password, rotate every 90 days.

---

## LAYER 5: WordPress hardening (live site)

**Where:** Live wp-admin + FileZilla

### Plugins — keep active on live:
- `hlm-security` (login guard, rate limits, headers)
- `hlm-lead-capture`, `hlm-seo`

### Plugins — disable on live unless needed:
- `query-monitor` (dev only — remove from production)
- `redis-cache` (only if Redis server exists)

### Install on live (one time):
- **Wordfence Security** (free) → scan + file integrity alerts
- Enable **2FA** for all admin users (Wordfence or WordPress 2FA plugin)

### wp-admin checklist:
- Settings → Permalinks → Save (after any deploy)
- Users → only people you trust as Administrator
- UpdraftPlus → backup to Google Drive/Dropbox weekly

---

## LAYER 6: Database "too many connections" (permanent)

**Where:** Cloudflare (layer 1) + hosting panel

1. **Cloudflare** reduces bot traffic (main fix)
2. Ask hosting to **increase max_connections** for your DB (or upgrade plan)
3. Disable unused plugins that hit DB every request
4. Do not run load tests + live site on same shared DB

---

## LAYER 7: Weekly monitor (15 minutes)

**Where:** SSH on server — add to cron or run manually Mondays

```bash
cd /usr/virtual/www.higherlearningmarketers.com
grep -r 'googlesyndication\|ca-pub-\|eval(base64' wp-includes wp-admin wp-content/themes wp-content/plugins/hlm-* 2>/dev/null && echo "ALERT: possible malware" || echo "OK"
```

Wordfence email alerts → your inbox.

---

## Recovery if hacked again

1. Cloudflare → **Under Attack Mode** (temporary)
2. Hosting → restart MySQL
3. Change ALL passwords (hosting, SFTP, DB, WP admin)
4. Re-upload clean `wp-includes`, `wp-admin` from local `site/`
5. Run `server-harden-once.sh` again
6. Wordfence full scan

---

## If attacks continue on shared hosting

**Where:** GoDaddy / hosting sales

Upgrade to:
- **Managed WordPress** or **VPS** with isolated resources
- Dedicated IP + Cloudflare

Shared hosting with low MySQL limits will always struggle under bot floods.

---

## Order to implement today

```
1. Cloudflare + nameservers          (30 min, cloudflare.com + GoDaddy DNS)
2. Clean upload core from local site (FileZilla)
3. Fix wp-config.php on server       (FileZilla)
4. bash server-harden-once.sh        (SSH)
5. Install Wordfence + 2FA             (live wp-admin)
6. Delete hlm-deploy-webhook.php      (FileZilla)
7. Disable query-monitor on live       (rename plugin folder)
8. UpdraftPlus backup to cloud         (wp-admin)
```

After this, normal workflow is: edit local → git push → deploy script. No FTP editing.
