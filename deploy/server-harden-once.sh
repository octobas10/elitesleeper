#!/usr/bin/env bash
# Run ONCE on the live server after a clean WordPress upload.
# Locks core, protects uploads, blocks PHP execution in uploads.
#
# Usage (SSH):
#   cd /usr/virtual/www.higherlearningmarketers.com
#   bash server-harden-once.sh

set -euo pipefail

WP_ROOT="${WP_ROOT:-$(pwd)}"
cd "$WP_ROOT"

echo "Hardening: $WP_ROOT"

# 1. WordPress core read-only (web user cannot rewrite wp-includes/wp-admin)
echo "Locking wp-includes and wp-admin..."
find wp-includes wp-admin -type f -exec chmod 444 {} \;
find wp-includes wp-admin -type d -exec chmod 555 {} \;

# 2. Block PHP execution in uploads
UPLOADS_HT="wp-content/uploads/.htaccess"
if [ ! -f "$UPLOADS_HT" ] || ! grep -q 'php_flag engine off' "$UPLOADS_HT" 2>/dev/null; then
  cat >> "$UPLOADS_HT" <<'HTACCESS'

# HLM: block PHP in uploads
<Files *.php>
  Require all denied
</Files>
HTACCESS
  echo "Added uploads .htaccess protection"
fi

# 3. Remove common attack entry files
rm -f hlm-deploy-webhook.php phpinfo.php info.php test.php 2>/dev/null || true

# 4. Verify no known malware in core
echo "Scanning core for AdSense injection..."
if grep -rq 'googlesyndication\|ca-pub-3234816981395206' wp-includes wp-admin 2>/dev/null; then
  echo "WARNING: Malware still found in core. Re-upload clean wp-includes/wp-admin from local site/."
  exit 1
fi

echo "Done. Core locked. Re-run deploy script to update core (it must unlock first)."
