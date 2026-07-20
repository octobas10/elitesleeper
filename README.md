# Higher Learning Marketers — Flask app

Pure Python site and admin. **No Node.js** is required to build or run.

## Setup

```bash
cd flask
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
cp .env.example .env
# Edit .env — at least set SECRET_KEY and ADMIN_PASSWORD for admin
```

## Run (development)

```bash
flask --app wsgi:app run --host 0.0.0.0 --port 5000
```

## Run (production)

```bash
gunicorn -b 0.0.0.0:5000 wsgi:app
```

Or with Docker:

```bash
docker build -t hlm-flask .
docker run --env-file .env -p 5000:5000 hlm-flask
```

## Nginx reverse proxy tip

Proxy HTTPS to Gunicorn on localhost:5000 and forward the real client IP:

```nginx
location / {
    proxy_pass http://127.0.0.1:5000;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

`ADMIN_ALLOWED_IPS` uses `X-Forwarded-For` when present, so set that header from a trusted proxy only.

## Admin

Leads admin UI: **[/admin/](/admin/)**

- Login: `/admin/login/`
- Logout: `/admin/logout/` (GET or POST)
- Requires `ADMIN_PASSWORD` in the environment
