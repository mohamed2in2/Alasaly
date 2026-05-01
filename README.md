# Alasaly - Secure Video Access Portal

This project provides:
- A student portal that verifies one-time access codes and opens a signed Bunny Stream URL.
- An admin panel that generates and stores access codes in MySQL.

## 1. Requirements

- PHP 8.1+
- MySQL or MariaDB
- Bunny Stream library and token security key

## 2. Project Setup

1. Create your database and table:

```bash
mysql -u root -p < database.sql
```

2. Create your environment file from the template:

```bash
cp .env.example .env
```

3. Edit `.env` and fill your real values:

```env
BUNNY_LIBRARY_ID=your_library_id
BUNNY_SECURITY_KEY=your_token_key
BUNNY_API_KEY=your_api_key
BUNNY_CDN_HOSTNAME=your_cdn_hostname
BUNNY_PULL_ZONE=your_pull_zone
BUNNY_TOKEN_EXPIRY=7200

DB_HOST=localhost
DB_PORT=3306
DB_NAME=school_platform
DB_USER=root
DB_PASS=your_db_password
```

## 3. Run Locally

From the project root:

```bash
php -S localhost:8000
```

Then open:
- Main page: http://localhost:8000/index.php
- Student page: http://localhost:8000/portal.php
- Admin page: http://localhost:8000/generate.php

## 4. How to Test the App

### Fast Demo Test (No Bunny Setup Needed)

If you just want a valid code and a random internet video to verify everything works:

1. In `.env`, add:

```env
DEMO_MODE_ENABLED=1
DEMO_ACCESS_CODE=DEMO1-PLAY9
DEMO_VIDEO_URL=https://www.youtube.com/embed/e1yDqlXin8g

WATCH_ACCESS_TTL_SECONDS=300
WATCH_ONE_TIME_USE=1
WATCH_WATERMARK_ENABLED=1
BANNED_IPS=
```

2. Start server:

```bash
php -S localhost:8000
```

3. Open student page and use this valid code:

```text
DEMO1-PLAY9
```

Expected result: the demo video loads successfully.

### A. Quick Backend Sanity Check

```bash
php -l config.php
php -l verify.php
php -l generate.php
php -l index.php
```

Expected result: "No syntax errors detected" for all files.

### B. Generate Codes (Admin Flow)

1. Open http://localhost:8000/generate.php
2. Enter a real Bunny video ID.
3. Enter number of codes (for example 3).
4. Click Generate and Save.
5. Confirm new codes appear and table is updated.

### C. Verify Playback (Student Flow)

1. Open http://localhost:8000/portal.php
2. Paste one generated code.
3. Click Open Lesson.
4. Confirm video player loads successfully.

### D. One-Time Code Test

1. Reuse the same code from step C.
2. Expected result: app returns "This code has already been used."

### E. Invalid Code Test

1. Submit a random code like ABCDE-12345.
2. Expected result: clear "invalid code" error message.

## 5. Environment Variables

The app now reads configuration from `.env` via `config.php`:

- `BUNNY_LIBRARY_ID`
- `BUNNY_SECURITY_KEY`
- `BUNNY_API_KEY`
- `BUNNY_CDN_HOSTNAME`
- `BUNNY_PULL_ZONE`
- `BUNNY_TOKEN_EXPIRY`
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DEMO_MODE_ENABLED`
- `DEMO_ACCESS_CODE`
- `DEMO_VIDEO_URL`
- `WATCH_ACCESS_TTL_SECONDS`
- `WATCH_ONE_TIME_USE`
- `WATCH_WATERMARK_ENABLED`
- `BANNED_IPS`

If Bunny keys are missing, the app responds safely with a configuration error message.

## 6. Security Notes

- `.env` is ignored by git (do not commit secrets).
- Protect `generate.php` behind authentication before production.
- Rotate Bunny token key if it is ever exposed.
- Watch links are session-bound and expire quickly (configurable via `WATCH_ACCESS_TTL_SECONDS`).
- Optional one-time watch tokens can be enforced via `WATCH_ONE_TIME_USE=1`.
- Optional on-screen watermark overlay is controlled via `WATCH_WATERMARK_ENABLED=1`.
- You can block abusive networks via `BANNED_IPS` (exact IPs or CIDR ranges).