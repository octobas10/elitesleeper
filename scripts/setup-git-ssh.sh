#!/usr/bin/env bash
# One-time GitHub SSH setup for this machine (WSL/Linux).
# Run: bash scripts/setup-git-ssh.sh

set -euo pipefail

KEY="$HOME/.ssh/id_ed25519_github"
EMAIL="${GIT_EMAIL:-kash.singh1989@gmail.com}"
REPO_SSH="git@github.com:kashkumarsingh/hlm-ebp.git"

mkdir -p "$HOME/.ssh"
chmod 700 "$HOME/.ssh"

if [[ ! -f "$KEY" ]]; then
  ssh-keygen -t ed25519 -C "$EMAIL" -f "$KEY" -N ""
  chmod 600 "$KEY"
  chmod 644 "${KEY}.pub"
  echo "Created SSH key: $KEY"
else
  echo "SSH key already exists: $KEY"
fi

CONFIG="$HOME/.ssh/config"
MARKER="Host github.com"
if ! grep -qF "$MARKER" "$CONFIG" 2>/dev/null; then
  cat >> "$CONFIG" <<'EOF'

# GitHub — git push/pull for hlm-ebp
Host github.com
  HostName github.com
  User git
  IdentityFile ~/.ssh/id_ed25519_github
  IdentitiesOnly yes
EOF
  chmod 600 "$CONFIG"
  echo "Added github.com to $CONFIG"
else
  echo "github.com already configured in $CONFIG"
fi

echo ""
echo "Add this public key to GitHub:"
echo "  https://github.com/settings/keys  ->  New SSH key"
echo ""
cat "${KEY}.pub"
echo ""

if git -C "$(dirname "$0")/.." remote get-url origin 2>/dev/null | grep -q '^https://github.com/'; then
  read -r -p "Switch origin remote to SSH ($REPO_SSH)? [y/N] " ans
  if [[ "${ans,,}" == "y" ]]; then
    git -C "$(dirname "$0")/.." remote set-url origin "$REPO_SSH"
    echo "Remote updated to SSH."
  fi
fi

echo ""
echo "Test with: ssh -T git@github.com"
