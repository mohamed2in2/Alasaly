<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Video Portal — Enter Your Code</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --brand: #5b21b6;
            --brand-light: #ede9fe;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .portal-card {
            background: #fff;
            border-radius: 18px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .4);
            width: 100%;
            max-width: 480px;
            text-align: center;
        }

        .portal-card .logo {
            font-size: 3rem;
            line-height: 1;
            margin-bottom: .5rem;
        }

        .portal-card h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: .25rem;
        }

        .portal-card p.subtitle {
            color: #6b7280;
            font-size: .9rem;
            margin-bottom: 1.75rem;
        }

        #codeInput {
            text-align: center;
            font-size: 1.1rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            border: 2px solid #d1d5db;
            border-radius: 10px;
            padding: .65rem 1rem;
            transition: border-color .2s;
        }

        #codeInput:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-light);
            outline: none;
        }

        #submitBtn {
            background: var(--brand);
            border: none;
            border-radius: 10px;
            padding: .7rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: .5px;
            transition: background .2s, transform .1s;
        }

        #submitBtn:hover  { background: #4c1d95; transform: translateY(-1px); }
        #submitBtn:active { transform: translateY(0); }

        #submitBtn .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }

        /* ── Video area ── */
        #videoArea {
            display: none;
            width: 100%;
            max-width: 900px;
            margin-top: 2rem;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
            background: #000;
        }

        #videoArea .ratio { border-radius: 14px; overflow: hidden; }
    </style>
</head>
<body>

    <!-- ── Login card ──────────────────────────────────────────────────────── -->
    <div class="portal-card" id="portalCard">
        <div class="logo">🎓</div>
        <h1>Video Portal</h1>
        <p class="subtitle">Enter your access code below to watch your lesson.</p>

        <div class="mb-3">
            <input type="text"
                   id="codeInput"
                   class="form-control"
                   placeholder="XXXXX-XXXXX"
                   maxlength="50"
                   autocomplete="off"
                   autocapitalize="characters"
                   spellcheck="false">
        </div>

        <button id="submitBtn" class="btn btn-primary w-100 text-white"
                onclick="verifyCode()">
            <span id="btnText">▶ Watch Now</span>
            <span id="btnSpinner" class="spinner-border ms-2 d-none" role="status"></span>
        </button>
    </div>

    <!-- ── Video player (hidden until code is verified) ───────────────────── -->
    <div id="videoArea">
        <div class="ratio ratio-16x9">
            <iframe id="bunnyPlayer"
                    src=""
                    allowfullscreen
                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                    loading="lazy">
            </iframe>
        </div>
    </div>

    <!-- ── Scripts ────────────────────────────────────────────────────────── -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    /**
     * verifyCode()
     * Sends the entered code to verify.php via Fetch (AJAX).
     * On success  → hides the login card and shows the Bunny.net video player.
     * On failure  → shows a SweetAlert2 error popup (page layout never breaks).
     */
    async function verifyCode() {
        const input    = document.getElementById('codeInput');
        const btnText  = document.getElementById('btnText');
        const spinner  = document.getElementById('btnSpinner');
        const submitBtn = document.getElementById('submitBtn');

        const code = input.value.trim().toUpperCase();

        // ── Client-side pre-validation ────────────────────────────────────
        if (code === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Code',
                text: 'Please enter your access code first.',
                confirmButtonColor: '#5b21b6'
            });
            input.focus();
            return;
        }

        // ── Loading state ─────────────────────────────────────────────────
        submitBtn.disabled = true;
        btnText.textContent = 'Verifying…';
        spinner.classList.remove('d-none');

        try {
            const response = await fetch('verify.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'code=' + encodeURIComponent(code)
            });

            // Guard against non-OK HTTP responses
            if (!response.ok) {
                throw new Error('Server returned status ' + response.status);
            }

            let data;
            try {
                data = await response.json();
            } catch (_) {
                throw new Error('The server returned an unexpected response. Please try again.');
            }

            if (data.success) {
                // ── Success: show the video player ────────────────────────
                document.getElementById('portalCard').style.display  = 'none';
                document.getElementById('videoArea').style.display   = 'block';
                document.getElementById('bunnyPlayer').src            = data.video_url;

                // Smooth scroll to video on mobile
                document.getElementById('videoArea').scrollIntoView({ behavior: 'smooth' });

            } else {
                // ── Failure: friendly popup ───────────────────────────────
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: data.message || 'Invalid or expired code. Please try again.',
                    confirmButtonColor: '#5b21b6'
                });
                input.select();
            }

        } catch (err) {
            // ── Network or parse error ────────────────────────────────────
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: err.message || 'Could not reach the server. Please check your internet connection.',
                confirmButtonColor: '#5b21b6'
            });
        } finally {
            // ── Restore button ────────────────────────────────────────────
            submitBtn.disabled = false;
            btnText.textContent = '▶ Watch Now';
            spinner.classList.add('d-none');
        }
    }

    // Allow pressing Enter in the input field to trigger verification
    document.getElementById('codeInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') verifyCode();
    });
    </script>

</body>
</html>
