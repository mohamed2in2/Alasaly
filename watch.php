<?php
/**
 * watch.php — Session-bound video player page.
 * Prevents simple URL sharing by requiring a valid session nonce.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$watch = $_SESSION['watch_access'] ?? null;
$nonce = (string)($_GET['n'] ?? '');

$clientIp = get_client_ip();
if (is_ip_banned($clientIp)) {
    http_response_code(403);
    echo 'Access denied from your network.';
    exit;
}

$invalid = !is_array($watch)
    || $nonce === ''
    || !hash_equals((string)($watch['nonce'] ?? ''), $nonce)
    || ((int)($watch['expires_at'] ?? 0) < time())
    || !hash_equals((string)($watch['ua'] ?? ''), hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'))
    || ((string)($watch['ip'] ?? '') !== '' && (string)($watch['ip'] ?? '') !== (string)($_SERVER['REMOTE_ADDR'] ?? ''));

if ($invalid) {
    http_response_code(403);
    ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Expired</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: system-ui, -apple-system, Segoe UI, sans-serif;
            background: linear-gradient(135deg, #2a1b10, #6f481d);
            color: #fff6e3;
            padding: 1rem;
        }

        .box {
            max-width: 540px;
            border: 1px solid rgba(255, 230, 177, 0.35);
            background: rgba(30, 18, 8, 0.6);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
        }

        a {
            color: #ffd38a;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Access expired</h1>
        <p>Your secure watch session is no longer valid. Please return to the portal and verify your code again.</p>
        <p><a href="portal.php">Go to Access Portal</a></p>
    </div>
</body>
</html>
    <?php
    exit;
}

$streamUrl = (string)($watch['stream_url'] ?? '');
if ($streamUrl === '') {
    http_response_code(400);
    echo 'Invalid stream configuration.';
    exit;
}

if (WATCH_ONE_TIME_USE) {
    // Consume nonce on first valid open to reduce replay risk.
    $_SESSION['watch_access']['nonce'] = bin2hex(random_bytes(24));
    $_SESSION['watch_access']['expires_at'] = time() + WATCH_ACCESS_TTL_SECONDS;
}

$issuedAt = (int)($watch['issued_at'] ?? time());
$issuedAtText = gmdate('Y-m-d H:i:s', $issuedAt) . ' UTC';
$clientIp = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$watermarkText = 'Alasly | SESSION ' . $issuedAtText . ' | IP: ' . $clientIp;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Watch Lesson</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&family=Prata&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg1: #120e08;
            --bg2: #3a2a14;
            --gold1: #f6d48f;
            --gold2: #cc9a43;
            --ink: #211606;
            --paper: rgba(255, 247, 227, 0.93);
            --muted: #6b5836;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', system-ui, sans-serif;
            background:
                radial-gradient(circle at 12% 6%, rgba(245, 192, 115, 0.24), transparent 28%),
                radial-gradient(circle at 88% 88%, rgba(245, 215, 146, 0.2), transparent 28%),
                linear-gradient(140deg, var(--bg1), var(--bg2));
            color: #fff7e6;
            padding: 1rem;
        }

        .wrap {
            width: min(1200px, 100%);
            margin: 0 auto;
        }

        .top {
            border-radius: 18px;
            border: 1px solid rgba(248, 220, 162, 0.3);
            background: rgba(23, 16, 9, 0.5);
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .brand {
            font-family: 'Prata', Georgia, serif;
            letter-spacing: 0.02em;
            font-size: 1.05rem;
        }

        .meta {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pill {
            border: 1px solid rgba(246, 212, 143, 0.36);
            border-radius: 999px;
            padding: 0.32rem 0.62rem;
            font-size: 0.76rem;
            color: #f8e6bf;
            background: rgba(255, 247, 229, 0.06);
        }

        .layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1rem;
        }

        .player-shell {
            border-radius: 22px;
            border: 1px solid rgba(246, 212, 143, 0.32);
            background: rgba(19, 13, 7, 0.6);
            padding: 0.85rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.36);
        }

        .ratio {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
        }

        .ratio iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            z-index: 1;
        }

        .watermark {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            opacity: 0.22;
            font-size: clamp(10px, 1.25vw, 14px);
            font-weight: 700;
            color: #f4d993;
            letter-spacing: 0.04em;
            z-index: 3;
        }

        .watermark span {
            position: absolute;
            white-space: nowrap;
            transform: rotate(-18deg);
        }

        .wm1 { top: 9%; left: 4%; }
        .wm2 { top: 30%; left: 18%; }
        .wm3 { top: 54%; left: 8%; }
        .wm4 { top: 76%; left: 24%; }
        .wm5 { top: 22%; right: 8%; }
        .wm6 { top: 48%; right: 14%; }
        .wm7 { top: 71%; right: 5%; }

        .side {
            border-radius: 22px;
            background: var(--paper);
            border: 1px solid rgba(246, 212, 143, 0.5);
            padding: 1rem;
            color: var(--ink);
        }

        .side h2 {
            margin: 0;
            font-family: 'Prata', Georgia, serif;
            font-size: 1.2rem;
        }

        .side p {
            margin: 0.55rem 0 0;
            color: var(--muted);
            line-height: 1.55;
            font-size: 0.92rem;
        }

        .side ul {
            margin: 0.8rem 0 0;
            padding-left: 1rem;
            color: var(--muted);
            font-size: 0.86rem;
            line-height: 1.55;
        }

        .side .small {
            margin-top: 0.65rem;
            font-size: 0.78rem;
            color: #7b6640;
        }

        .back {
            display: inline-block;
            margin-top: 0.9rem;
            text-decoration: none;
            font-weight: 800;
            border-radius: 10px;
            padding: 0.55rem 0.72rem;
            color: #fffaf0;
            background: linear-gradient(145deg, var(--gold2), #926225);
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="top">
            <div class="brand">Alasly Secure Watch</div>
            <div class="meta">
                <span class="pill">Session Protected</span>
                <span class="pill">Link Sharing Restricted</span>
                <span class="pill">Senior 1 Track</span>
            </div>
        </header>

        <section class="layout">
            <article class="player-shell" aria-label="Video Player">
                <div class="ratio">
                    <iframe
                        src="<?= htmlspecialchars($streamUrl, ENT_QUOTES, 'UTF-8') ?>"
                        allowfullscreen
                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                        referrerpolicy="strict-origin-when-cross-origin"
                        loading="eager">
                    </iframe>
                    <?php if (WATCH_WATERMARK_ENABLED): ?>
                    <div class="watermark" aria-hidden="true">
                        <span class="wm1"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="wm2"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="wm3"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="wm4"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="wm5"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="wm6"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="wm7"><?= htmlspecialchars($watermarkText, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </article>

            <aside class="side" aria-label="Lesson Details">
                <h2>Now Watching</h2>
                <p>
                    Your watch session is tied to this browser session. If this page expires, return to the portal and verify your code again.
                </p>
                <ul>
                    <li>This page uses short-lived session access.</li>
                    <li>Direct watch link sharing is blocked for other users.</li>
                    <li>For best quality, use full-screen mode in the player.</li>
                    <?php if (WATCH_ONE_TIME_USE): ?>
                    <li>This watch link is one-time use per verification.</li>
                    <?php endif; ?>
                </ul>
                <p class="small">Issued at: <?= htmlspecialchars($issuedAtText, ENT_QUOTES, 'UTF-8') ?></p>
                <a class="back" href="portal.php">Back to Access Portal</a>
            </aside>
        </section>
    </main>
</body>
</html>
