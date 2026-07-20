#!/usr/bin/env bash
# Run on the server after git pull. Copies HLM theme + plugins into WordPress.
#
# Edit WP_ROOT and REPO_DIR if your paths differ.

set -euo pipefail

WP_ROOT="${WP_ROOT:-/usr/virtual/www.higherlearningmarketers.com}"
REPO_DIR="${REPO_DIR:-$HOME/hlm-ebp}"
BRANCH="${BRANCH:-main}"

if [ ! -d "$REPO_DIR/.git" ]; then
  echo "ERROR: $REPO_DIR is not a git repo. Clone the repo there first."
  exit 1
fi

cd "$REPO_DIR"
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

deploy_dir() {
  local src="$1"
  local dest="$2"
  echo "Syncing $src -> $dest"
  mkdir -p "$dest"
  rsync -a --delete "${src}/" "${dest}/"
}

deploy_dir "$REPO_DIR/site/wp-content/themes/hlm-theme-kadence" \
  "$WP_ROOT/wp-content/themes/hlm-theme-kadence"

for plugin in hlm-lead-capture hlm-security hlm-seo; do
  deploy_dir "$REPO_DIR/site/wp-content/plugins/$plugin" \
    "$WP_ROOT/wp-content/plugins/$plugin"
done

echo "Deploy complete: $(git -C "$REPO_DIR" rev-parse --short HEAD)"
