#!/usr/bin/env bash
# Export local WordPress DB with production URLs (does NOT change your local database).
#
# Usage:
#   bash deploy/export-production-sql.sh
#
# Output:
#   deploy/hlm-production.sql  (ready to import on live server)

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

FROM_URL="${FROM_URL:-http://localhost:10001}"
TO_URL="${TO_URL:-https://higherlearningmarketers.com}"
OUT="${OUT:-deploy/hlm-production.sql}"
WP_CLI="${WP_CLI:-/tmp/wp-cli.phar}"

if [ ! -f "$WP_CLI" ]; then
  echo "Downloading WP-CLI..."
  curl -sL -o "$WP_CLI" https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
fi

echo "Exporting SQL: ${FROM_URL} -> ${TO_URL}"
echo "Output: ${OUT}"
echo "(Local database is NOT modified.)"
echo ""

php "$WP_CLI" search-replace "$FROM_URL" "$TO_URL" \
  --all-tables \
  --export="$OUT" \
  --path=site \
  --allow-root

echo ""
echo "Done. Import on server with phpMyAdmin or:"
echo "  mysql -u USER -p DATABASE < ${OUT}"
