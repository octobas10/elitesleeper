# Server recovery after malware re-injection

If core files get hacked again right after you replace them, a **backdoor or stolen FTP/SSH access** is still active. Fix in this order.

## Phase 1: Stop the bleeding (do this FIRST)

1. **Put site in maintenance mode** (or ask host to suspend the site briefly).

2. **Change ALL passwords before uploading clean files:**
   - Hosting control panel
   - FTP / SFTP / SSH
   - Database user password (update hosting + `wp-config.php`)
   - Every WordPress admin user
   - GitHub token if it was on the server

3. **Delete public entry points attackers use:**
   ```bash
   rm -f /usr/virtual/www.higherlearningmarketers.com/hlm-deploy-webhook.php
   rm -f /usr/virtual/www.higherlearningmarketers.com/phpinfo.php
   rm -f /usr/virtual/www.higherlearningmarketers.com/*.php.bak
   ```

4. **Disable compromised FTP accounts** in hosting panel. Create a **new SFTP user** with a new password.

## Phase 2: Find the backdoor (SSH on server)

```bash
cd /usr/virtual/www.higherlearningmarketers.com
bash server-find-malware.sh
```

Upload `deploy/server-find-malware.sh` to the server first, or paste the commands from it.

**Delete anything suspicious** it finds (unknown PHP in uploads, mu-plugins, extra root PHP files).

Run in phpMyAdmin:

```sql
SELECT ID, user_login, user_email FROM wp_users;
DELETE FROM wp_users WHERE user_login = 'UNKNOWN_USER';

SELECT option_name FROM wp_options
WHERE option_value LIKE '%googlesyndication%' OR option_value LIKE '%ca-pub-%';
```

## Phase 3: Replace with KNOWN CLEAN files only

Upload from your **local `site/`** (verified clean). Do **not** copy anything from the hacked server back to your machine.

| Upload (overwrite) | Do NOT touch yet |
|---|---|
| `wp-admin/` full folder | `wp-config.php` (edit manually) |
| `wp-includes/` full folder | |
| Root `index.php`, `wp-*.php` | |

For **wp-content**, upload only from local:

- `wp-content/themes/hlm-theme-kadence/`
- `wp-content/plugins/hlm-*`
- Other plugins you trust from local repo

**Do not** re-upload entire hacked `wp-content` from server.

## Phase 4: Harden wp-config.php (production)

Add these lines **before** `require_once ABSPATH . 'wp-settings.php';`:

```php
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );
define( 'WP_DEBUG', false );
```

Remove `WP_DEBUG_DISPLAY` on live.

## Phase 5: Lock core files (prevents web-based re-write)

Run on server **after** clean upload:

```bash
cd /usr/virtual/www.higherlearningmarketers.com
find wp-includes wp-admin -type f -exec chmod 444 {} \;
find wp-includes wp-admin -type d -exec chmod 555 {} \;
```

WordPress core updates will need to unlock temporarily, but attackers using the web user cannot patch core easily.

## Phase 6: Verify

```bash
grep -r 'googlesyndication\|ca-pub-3234816981395206' wp-includes wp-admin
# Must return nothing
```

Wait 30 minutes. Run grep again. If injection returns, **FTP/SSH is still compromised** or backdoor remains in wp-content.

## Phase 7: Ongoing

- Install **Wordfence** or keep **hlm-security** active
- Enable hosting **WAF** if available
- Only use SFTP with new credentials
- Do not reinstall nulled/pirated plugins
- Keep `hlm-deploy-webhook.php` deleted until you rotate the deploy token

## Most likely cause

| Symptom | Cause |
|---|---|
| Re-infected within minutes | Stolen FTP/SSH password |
| Re-infected on page load | PHP backdoor in wp-content |
| Only core files | Cron or external bot with file access |

**Changing passwords BEFORE re-upload is the step most people skip.** That is usually why it comes back.
