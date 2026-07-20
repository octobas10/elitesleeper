# One-time setup — then you only `git push`

Your Mac runs a tiny background service so GitHub Actions can SSH to your server.
You never run deploy manually. No webhook.

## 1. Install runner (once)

GitHub → **octobas10/hlm-ebp → Settings → Actions → Runners → New self-hosted runner**

Pick **macOS** + your chip (ARM64 or x64). Run the commands GitHub shows:

```bash
mkdir ~/actions-runner && cd ~/actions-runner
# curl + tar (copy from GitHub page)
./config.sh --url https://github.com/octobas10/hlm-ebp --token YOUR_TOKEN
```

Press Enter for all defaults.

## 2. Start runner as background service (once)

```bash
cd ~/actions-runner
sudo ./svc.sh install
sudo ./svc.sh start
```

Runner shows **Idle** on GitHub. Starts automatically when Mac boots.

## 3. GitHub secrets (octobas10/hlm-ebp)

| Secret | Value |
|---|---|
| `SSH_HOST` | `74.205.70.188` |
| `SSH_USER` | `vish` |
| `SSH_PRIVATE_KEY` | `cat ~/.ssh/id_ed25519_personal` (private key, full file) |
| `WP_ROOT` | `/usr/virtual/www.higherlearningmarketers.com` |

## 4. Server — public key in authorized_keys (once)

Your `id_ed25519_personal.pub` must be in `vish` user's `~/.ssh/authorized_keys` on the server.

## 5. Daily use — only this

```bash
cd ~/highereducation_eilte
git add .
git commit -m "my change"
git push origin main
```

GitHub Actions deploys automatically in ~1 minute.

## Why self-hosted?

GitHub's cloud servers cannot SSH to your host (timeout). Your Mac can.
The runner is invisible after step 2 — you only ever push code.
