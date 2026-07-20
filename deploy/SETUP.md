# Server setup (one time) — webhook deploy, no self-hosted runner

## 1. SSH into server

```bash
ssh vish@74.205.70.188
```

## 2. Clone repo (NOT inside WordPress folder)

```bash
cd ~
git clone https://github.com/kashkumarsingh/hlm-ebp.git
```

If the repo is private, use a GitHub Personal Access Token when prompted, or set up a deploy key.

## 3. Create deploy folder + token

```bash
mkdir -p ~/hlm-deploy
openssl rand -hex 32 > ~/hlm-deploy/token
chmod 600 ~/hlm-deploy/token
cat ~/hlm-deploy/token
```

Copy that token — you need it for GitHub secret `DEPLOY_WEBHOOK_TOKEN`.

## 4. Install deploy script

```bash
cp ~/hlm-ebp/deploy/server-deploy.sh ~/hlm-deploy/server-deploy.sh
chmod +x ~/hlm-deploy/server-deploy.sh
```

Test manually:

```bash
bash ~/hlm-deploy/server-deploy.sh
```

## 5. Upload webhook PHP to site root

Copy `deploy/hlm-deploy-webhook.php` to WordPress root:

```bash
cp ~/hlm-ebp/deploy/hlm-deploy-webhook.php \
  /usr/virtual/www.higherlearningmarketers.com/hlm-deploy-webhook.php
```

## 6. GitHub secrets (Secrets tab)

| Secret | Value |
|---|---|
| `DEPLOY_WEBHOOK_URL` | `https://higherlearningmarketers.com/hlm-deploy-webhook.php` |
| `DEPLOY_WEBHOOK_TOKEN` | same string as `~/hlm-deploy/token` on server |

You can remove old SSH secrets — they are no longer used.

## 7. Push workflow and test

Push to `main`, or run **Actions → Deploy via Webhook → Run workflow**.
