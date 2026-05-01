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
            --sand: #e9f4f2;
            --ink: #122024;
            --card: rgba(255, 255, 255, 0.92);
            --accent: #1f7f71;
            --accent-strong: #185c52;
            --muted: #4d6167;
            --ring: rgba(31, 127, 113, 0.25);
            --shadow: 0 22px 50px rgba(10, 42, 47, 0.2);
            --surface: linear-gradient(160deg, #eaf6f4 0%, #deece9 100%);
            --hero-start: #133537;
            --hero-end: #2b6463;
            --hero-orb: linear-gradient(45deg, #6ec8b7, #b0e9de);
        }

        [data-theme='dark'] {
            --sand: #081517;
            --ink: #eaf5f2;
            --card: rgba(10, 25, 27, 0.9);
            --accent: #66b5a7;
            --accent-strong: #419786;
            --muted: #a9c4be;
            --ring: rgba(102, 181, 167, 0.3);
            --shadow: 0 22px 50px rgba(0, 0, 0, 0.42);
            --surface: linear-gradient(160deg, #081517 0%, #112529 100%);
            --hero-start: #041113;
            --hero-end: #1c4a48;
            --hero-orb: linear-gradient(45deg, #4a9e90, #7bc7ba);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 10%, rgba(142, 200, 191, 0.4) 0%, transparent 26%),
                radial-gradient(circle at 82% 82%, rgba(96, 164, 151, 0.25) 0%, transparent 28%),
                var(--surface);
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            transition: background 0.25s ease, color 0.2s ease;
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
            background: linear-gradient(135deg, var(--hero-start) 0%, var(--hero-end) 100%);
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
            background: var(--hero-orb);
            opacity: 0.6;
        }

        .top-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 3;
            display: flex;
            gap: 0.5rem;
        }

        .ghost-btn {
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.08);
            color: #f8fffd;
            font-family: inherit;
            font-weight: 700;
            font-size: 0.78rem;
            border-radius: 999px;
            padding: 0.42rem 0.75rem;
            text-decoration: none;
            cursor: pointer;
        }

        .ghost-btn:hover {
            background: rgba(255, 255, 255, 0.17);
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
            color: var(--ink);
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
            color: var(--ink);
            background: #ffffff;
        }

        [data-theme='dark'] #codeInput {
            border-color: rgba(111, 173, 162, 0.35);
            background: rgba(8, 21, 23, 0.82);
            color: #effaf6;
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
            background: linear-gradient(130deg, var(--accent) 0%, #53a99b 100%);
            transition: transform 0.14s, box-shadow 0.2s, filter 0.2s;
            box-shadow: 0 14px 26px rgba(31, 127, 113, 0.26);
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
            background: rgba(255, 255, 255, 0.78);
            animation: reveal 320ms ease-out;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        [data-theme='dark'] #videoArea {
            background: rgba(8, 21, 23, 0.85);
            border-color: rgba(103, 167, 156, 0.25);
        }

        .loader-card {
            text-align: left;
            padding: 1.2rem;
            background: transparent;
        }

        .loader-card h3 {
            margin: 0;
            font-family: 'Source Serif 4', Georgia, serif;
            color: var(--ink);
        }

        .loader-card p {
            margin: 0.45rem 0 0;
            color: var(--muted);
        }

        .loader-steps {
            margin: 0.9rem 0 0;
            padding-left: 1rem;
            color: var(--muted);
            font-size: 0.92rem;
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
            <div class="top-actions">
                <a class="ghost-btn" href="index.php">Home</a>
                <button id="themeToggle" class="ghost-btn" type="button">Night Mode</button>
            </div>
            <div>
                <span class="label">Senior 1 Private Lesson Access</span>
                <h1>Verify once. Watch deeply. Apply immediately.</h1>
                <p>
                    This portal verifies your one-time access code and opens your secured Senior 1 lesson instantly.
                    Every session is designed to build analysis, writing, and leadership clarity.
                </p>
            </div>
            <div class="facts" aria-label="Portal Facts">
                <article class="fact"><strong>1-Time</strong><span>Single-use access code</span></article>
                <article class="fact"><strong>Senior 1</strong><span>Structured documentary learning</span></article>
                <article class="fact"><strong>Secure</strong><span>Session-bound watch URL</span></article>
            </div>
        </section>

        <section class="portal-card" id="portalCard" aria-label="Enter Access Code">
            <h2>Access Code</h2>
            <p class="sub">Use your instructor code to unlock the next Senior 1 lesson. Keep a notebook ready for reflection points.</p>

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
            <p class="helper">If your code is valid, your custom watch space will open in seconds.</p>
        </section>
    </main>

    <section id="videoArea" aria-label="Preparing Player">
        <div class="loader-card">
            <h3>Preparing your Senior 1 watch space</h3>
            <p>Please wait while we open your protected session and attach your learner watermark.</p>
            <ul class="loader-steps">
                <li>Validating code and session identity</li>
                <li>Loading protected player URL</li>
                <li>Opening your personalized watch room</li>
            </ul>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    const input = document.getElementById('codeInput');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('btnSpinner');
    const submitBtn = document.getElementById('submitBtn');
    const root = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const urlParams = new URLSearchParams(window.location.search);
        const savedTheme = localStorage.getItem('alasly-theme');
        if (savedTheme === 'dark' || savedTheme === 'light') {
            root.setAttribute('data-theme', savedTheme);
        }

        function syncThemeLabel() {
            const isDark = root.getAttribute('data-theme') === 'dark';
            themeToggle.textContent = isDark ? 'Day Mode' : 'Night Mode';
        }

        syncThemeLabel();
        themeToggle.addEventListener('click', function () {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('alasly-theme', next);
            syncThemeLabel();
        });

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
                confirmButtonColor: getComputedStyle(root).getPropertyValue('--accent').trim()
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
                    confirmButtonColor: getComputedStyle(root).getPropertyValue('--accent').trim()
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
                confirmButtonColor: getComputedStyle(root).getPropertyValue('--accent').trim()
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
