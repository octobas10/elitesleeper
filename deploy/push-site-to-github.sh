#!/usr/bin/env bash
# Push site/ contents to GitHub with WordPress at the REPO ROOT (no site/ folder on remote).
#
# Local layout:   highereducation_eilte/site/wp-admin, site/wp-content, ...
# Remote layout:  wp-admin, wp-content, index.php, ...  (at repo root)
#
# Usage (from repo root):
#   bash deploy/push-site-to-github.sh
#   bash deploy/push-site-to-github.sh main --force   # first push after restructure

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

BRANCH="${1:-main}"
FORCE="${2:-}"
SPLIT_BRANCH="client-main"

# Drop any existing split branch so we rebuild cleanly each run.
if git show-ref --verify --quiet "refs/heads/${SPLIT_BRANCH}"; then
  git branch -D "$SPLIT_BRANCH" >/dev/null
fi

echo "Building split branch from site/ ..."
git subtree split --prefix=site -b "$SPLIT_BRANCH"

# Cursor may inject "Co-authored-by: Cursor <cursoragent@cursor.com>" into
# commit messages. Strip that from the split branch only (what we push),
# so the client remote never shows Cursor attribution.
echo "Stripping Cursor co-author trailers from ${SPLIT_BRANCH} ..."
FILTER_BRANCH_SQUELCH_WARNING=1 git filter-branch -f --msg-filter '
  sed -e "/[Cc]o-authored-by:.*[Cc]ursor/d" -e "/cursoragent@cursor\\.com/d"
' "$SPLIT_BRANCH" >/dev/null

PUSH_ARGS=()
if [ "$FORCE" = "--force" ] || [ "$FORCE" = "-f" ]; then
  echo "Force push enabled (--force-with-lease)."
  PUSH_ARGS=(--force-with-lease)
fi

echo "Pushing to origin/${BRANCH} (WordPress at repo root) ..."
if ! git push "${PUSH_ARGS[@]}" origin "${SPLIT_BRANCH}:${BRANCH}"; then
  echo ""
  echo "Push rejected (histories differ). This is expected on the first push"
  echo "after moving WordPress into site/. Re-run with --force:"
  echo ""
  echo "  bash deploy/push-site-to-github.sh main --force"
  echo ""
  exit 1
fi

echo "Done. Remote ${BRANCH} now has site/ contents at repo root."
