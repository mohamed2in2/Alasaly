# Alasaly Setup Guide

This guide is safe to keep in a public GitHub repository.

## 1. Requirements

- PHP 8.1+
- MySQL or MariaDB
- Web browser

Optional (for real streaming):

- Bunny Stream account and API credentials

## 2. Clone and Prepare

1. Clone the repository.
2. Move into the project folder.
3. Copy environment template:

```bash
cp .env.example .env
```

4. Edit `.env` and add your own values.

Important:

- Never commit `.env`.
- Use placeholder values in screenshots and docs.

## 3. Database Setup

Run the SQL migration file:

```bash
mysql -u root -p < database.sql
```

Or use your own DB credentials from `.env`.

## 4. Start the Local Server

```bash
php -S localhost:8000
```

Open:

- Home: http://localhost:8000/index.php
- Student Portal: http://localhost:8000/portal.php
- Admin Generator: http://localhost:8000/generate.php

## 5. Demo Mode (Optional)

Use demo mode if you want to test without full Bunny setup:

```env
DEMO_MODE_ENABLED=1
DEMO_ACCESS_CODE=DEMO1-PLAY9
DEMO_VIDEO_URL=https://www.youtube.com/embed/e1yDqlXin8g
```

## 6. Production Checklist

- Set `DEMO_MODE_ENABLED=0`.
- Add strong admin authentication for `generate.php` and `add_video.php`.
- Use HTTPS.
- Rotate secrets if they were ever exposed.
- Configure restricted CORS/origin settings where applicable.
- Keep `WATCH_ONE_TIME_USE=1` and tune `WATCH_ACCESS_TTL_SECONDS`.

## 7. Publish on GitHub

Before pushing:

- Confirm `.env` is not tracked.
- Confirm no real API keys in docs, screenshots, or commits.
- Confirm `README.md` and `.env.example` only contain placeholders.

Then push:

```bash
git add .
git commit -m "Prepare app for GitHub publishing"
git push origin main
```
