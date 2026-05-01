<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Source+Serif+4:opsz,wght@8..60,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --sand: #f8f2e5;
            --ink: #0f1720;
            --card: rgba(255, 255, 255, 0.94);
            --accent: #cc4b2c;
            --accent-strong: #a53d24;
            --muted: #5f676f;
            --ring: rgba(204, 75, 44, 0.25);
            --shadow: 0 22px 50px rgba(15, 23, 32, 0.25);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 10%, #f6d5a8 0%, transparent 26%),
                radial-gradient(circle at 82% 82%, #f0b7a0 0%, transparent 28%),
                linear-gradient(160deg, #f1e7d0 0%, #e6ddca 100%);
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .layout {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 1.2rem;
            align-items: stretch;
        }

        .hero,
        .portal-card {
            border-radius: 24px;
            box-shadow: var(--shadow);
        }

        .hero {
            background: linear-gradient(135deg, #1f2a2f 0%, #2b373d 45%, #37464d 100%);
            color: #f6f6f0;
            padding: clamp(1.7rem, 2.6vw, 2.6rem);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 999px;
            top: -95px;
            right: -95px;
            background: linear-gradient(45deg, #e58c6f, #f8c5aa);
            opacity: 0.6;
        }

        .hero .label {
            display: inline-flex;
            width: fit-content;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 0.9rem 0;
            font-family: 'Source Serif 4', Georgia, serif;
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 1.05;
            max-width: 12ch;
        }

        .hero p {
            margin: 0;
            color: rgba(255, 255, 255, 0.84);
            max-width: 44ch;
            line-height: 1.55;
        }

        .facts {
            margin-top: 1.4rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .fact {
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 0.8rem;
        }

        .fact strong {
            display: block;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .fact span {
            font-size: 0.78rem;
            opacity: 0.86;
        }

        .portal-card {
            background: var(--card);
            backdrop-filter: blur(8px);
            padding: clamp(1.4rem, 2.2vw, 2rem);
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.65);
            animation: reveal 400ms ease-out;
        }

        .portal-card h2 {
            margin: 0;
            font-family: 'Source Serif 4', Georgia, serif;
            font-size: 2rem;
        }

        .portal-card .sub {
            margin-top: 0.6rem;
            color: var(--muted);
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .code-label {
            margin-top: 1.3rem;
            margin-bottom: 0.45rem;
            font-size: 0.84rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #2a3036;
            font-weight: 700;
        }

        #codeInput {
            text-align: center;
            letter-spacing: 0.28em;
            font-size: 1.05rem;
            font-weight: 700;
            text-transform: uppercase;
            border: 2px solid #d7dce2;
            border-radius: 14px;
            padding: 0.76rem 0.8rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #ffffff;
        }

        #codeInput:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--ring);
            outline: none;
        }

        #submitBtn {
            margin-top: 1rem;
            border: none;
            border-radius: 14px;
            padding: 0.82rem 1rem;
            font-size: 0.98rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #fff;
            background: linear-gradient(130deg, var(--accent) 0%, #db6243 100%);
            transition: transform 0.14s, box-shadow 0.2s, filter 0.2s;
            box-shadow: 0 14px 26px rgba(204, 75, 44, 0.26);
        }

        #submitBtn:hover {
            transform: translateY(-1px);
            filter: brightness(0.97);
        }

        #submitBtn:active {
            transform: translateY(0);
            box-shadow: 0 10px 18px rgba(204, 75, 44, 0.24);
        }

        #submitBtn:disabled {
            opacity: 0.84;
            cursor: not-allowed;
        }

        #submitBtn .spinner-border {
            width: 0.95rem;
            height: 0.95rem;
            border-width: 2px;
        }

        .helper {
            margin-top: 0.7rem;
            font-size: 0.82rem;
            color: var(--muted);
        }

        #videoArea {
            display: none;
            width: min(1120px, 100%);
            margin-top: 1rem;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: #000;
            animation: reveal 320ms ease-out;
        }

        #videoArea .ratio {
            border-radius: 24px;
            overflow: hidden;
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 992px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .facts {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 1rem 0.8rem;
            }

            .facts {
                grid-template-columns: 1fr;
            }

            #codeInput {
                letter-spacing: 0.18em;
            }
        }
    </style>
</head>
<body>
    <main class="layout" id="introLayout">
        <section class="hero" aria-label="Portal Intro">
            <div>
                <span class="label">Private Lesson Access</span>
                <h1>Enter once. Learn without interruptions.</h1>
                <p>
                    This portal verifies your one-time access code and opens your secured video lesson instantly.
                    Codes are case-insensitive and can only be used once.
                </p>
            </div>
            <div class="facts" aria-label="Portal Facts">
                <article class="fact"><strong>1-Time</strong><span>Single-use access code</span></article>
                <article class="fact"><strong>2-Hour</strong><span>Signed playback window</span></article>
                <article class="fact"><strong>Secure</strong><span>Tokenized stream URL</span></article>
            </div>
        </section>

        <section class="portal-card" id="portalCard" aria-label="Enter Access Code">
            <h2>Access Code</h2>
            <p class="sub">Use the code you received from your instructor to unlock your lesson video.</p>

            <label for="codeInput" class="code-label">Code format: 10 characters</label>
            <input
                type="text"
                id="codeInput"
                class="form-control"
                placeholder="A1B2C3D4E5"
                maxlength="11"
                autocomplete="off"
                autocapitalize="characters"
                spellcheck="false"
                inputmode="text"
            >

            <button id="submitBtn" class="btn w-100" type="button" onclick="verifyCode()">
                <span id="btnText">Open Lesson</span>
                <span id="btnSpinner" class="spinner-border ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>
            <p class="helper">If your code is valid, your video will load below immediately.</p>
        </section>
    </main>

    <section id="videoArea" aria-label="Preparing Player">
        <div class="p-4 bg-white rounded-4 text-center">
            <h3 class="mb-2">Preparing your secure player</h3>
            <p class="text-muted mb-0">Please wait while we open your protected watch session...</p>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    const input = document.getElementById('codeInput');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('btnSpinner');
    const submitBtn = document.getElementById('submitBtn');
    const urlParams = new URLSearchParams(window.location.search);
    const initialCode = urlParams.get('code');
    const previewMode = urlParams.get('preview') === '1';

    function normalizeCode(value) {
        return value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10);
    }

    function formatCode(value) {
        const plain = normalizeCode(value);
        if (plain.length <= 5) {
            return plain;
        }
        return plain.slice(0, 5) + '-' + plain.slice(5);
    }

    input.addEventListener('input', function () {
        const cursor = this.selectionStart;
        const beforeLen = this.value.length;
        this.value = formatCode(this.value);
        const afterLen = this.value.length;
        const nextCursor = Math.max(0, (cursor || 0) + (afterLen - beforeLen));
        this.setSelectionRange(nextCursor, nextCursor);
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            verifyCode();
        }
    });

    if (initialCode) {
        input.value = formatCode(initialCode);
        // Don't auto-submit if admin opened the portal in preview mode
        // or if the page was loaded via the back/forward history (to avoid re-consuming codes).
        const navEntry = (performance.getEntriesByType && performance.getEntriesByType('navigation') && performance.getEntriesByType('navigation')[0]) || null;
        const navType = navEntry ? navEntry.type : (performance.navigation ? performance.navigation.type : 0);
        const isHistoryBack = (navType === 'back_forward' || navType === 2);
        if (!previewMode && !isHistoryBack) {
            window.setTimeout(function () {
                verifyCode();
            }, 100);
        }
    }

    async function verifyCode() {
        const normalized = normalizeCode(input.value.trim());
        const code = formatCode(normalized);
        input.value = code;

        if (normalized.length !== 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Enter a valid code',
                text: 'Codes must be 10 characters long.',
                confirmButtonColor: '#cc4b2c'
            });
            input.focus();
            return;
        }

        submitBtn.disabled = true;
        btnText.textContent = 'Verifying';
        spinner.classList.remove('d-none');

        try {
            const response = await fetch('verify.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: 'code=' + encodeURIComponent(normalized)
            });

            if (!response.ok) {
                throw new Error('Server returned status ' + response.status + '.');
            }

            const data = await response.json();

            if (!data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access denied',
                    text: data.message || 'Invalid or expired code.',
                    confirmButtonColor: '#cc4b2c'
                });
                input.select();
                return;
            }

            document.getElementById('portalCard').style.display = 'none';
            document.querySelector('.hero').style.display = 'none';
            document.getElementById('videoArea').style.display = 'block';
            document.getElementById('videoArea').scrollIntoView({ behavior: 'smooth', block: 'start' });

            // Redirect to session-bound watch page so raw stream URLs are not exposed in this page.
            window.setTimeout(function () {
                window.location.href = data.video_url;
            }, 450);

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Connection error',
                text: error.message || 'Could not verify your code. Please try again.',
                confirmButtonColor: '#cc4b2c'
            });
        } finally {
            submitBtn.disabled = false;
            btnText.textContent = 'Open Lesson';
            spinner.classList.add('d-none');
        }
    }
    </script>
</body>
</html>
