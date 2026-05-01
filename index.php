<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alasly | الاصلي</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Noto+Kufi+Arabic:wght@500;700&family=Prata&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #17140e;
            --muted: #645744;
            --panel: rgba(255, 250, 239, 0.86);
            --gold-1: #f7d88c;
            --gold-2: #deb45f;
            --gold-3: #9d6d26;
            --night: #2a1c0b;
            --cream: #fff8e8;
            --line: rgba(157, 109, 38, 0.25);
            --shadow: 0 24px 60px rgba(66, 41, 8, 0.22);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 8% 4%, rgba(255, 232, 169, 0.6) 0%, transparent 24%),
                radial-gradient(circle at 88% 22%, rgba(246, 205, 122, 0.36) 0%, transparent 30%),
                linear-gradient(140deg, #fffaf0 0%, #f2e4c4 45%, #ecd5a5 100%);
            padding: 1rem;
        }

        .site {
            width: min(1200px, 100%);
            margin: 0 auto;
        }

        .glass {
            background: var(--panel);
            border: 1px solid rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(8px);
            border-radius: 24px;
            box-shadow: var(--shadow);
        }

        .topbar {
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.03em;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(145deg, var(--gold-1), var(--gold-2), var(--gold-3));
            box-shadow: inset 0 0 16px rgba(255, 255, 255, 0.35);
        }

        .brand-ar {
            font-family: 'Noto Kufi Arabic', sans-serif;
            font-size: 0.92rem;
            color: var(--gold-3);
        }

        .top-nav {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .chip {
            text-decoration: none;
            border: 1px solid var(--line);
            color: var(--ink);
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 700;
            transition: transform 0.15s, background 0.2s;
        }

        .chip:hover {
            transform: translateY(-1px);
            background: rgba(255, 244, 218, 0.8);
        }

        .hero {
            background: linear-gradient(125deg, #2b1d0b 0%, #6d4c1a 36%, #bc8a39 72%, #f1d28f 100%);
            color: var(--cream);
            padding: clamp(1.4rem, 3vw, 2.8rem);
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .hero::before,
        .hero::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            filter: blur(2px);
        }

        .hero::before {
            right: -74px;
            top: -74px;
            width: 210px;
            height: 210px;
            background: radial-gradient(circle, rgba(255, 237, 193, 0.9), rgba(255, 214, 130, 0.2));
        }

        .hero::after {
            left: -60px;
            bottom: -80px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255, 207, 125, 0.45), rgba(255, 208, 118, 0.05));
        }

        .shine {
            position: absolute;
            inset: 0;
            background: linear-gradient(108deg, transparent 15%, rgba(255, 255, 255, 0.2) 42%, transparent 70%);
            transform: translateX(-120%);
            animation: sweep 5.5s linear infinite;
            pointer-events: none;
        }

        .badge {
            display: inline-flex;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: 1px solid rgba(255, 249, 230, 0.5);
            border-radius: 999px;
            padding: 0.36rem 0.78rem;
            background: rgba(255, 247, 218, 0.12);
        }

        h1 {
            margin: 0.9rem 0 0.6rem;
            font-size: clamp(2rem, 4.8vw, 3.5rem);
            line-height: 1.05;
            font-family: 'Prata', Georgia, serif;
            max-width: 14ch;
        }

        .hero p {
            margin: 0;
            max-width: 50ch;
            color: rgba(255, 247, 226, 0.9);
            line-height: 1.55;
        }

        .hero-actions {
            margin-top: 1.2rem;
            display: flex;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .btn-main {
            text-decoration: none;
            border-radius: 12px;
            padding: 0.72rem 1rem;
            font-weight: 800;
            transition: transform 0.15s, filter 0.2s;
        }

        .btn-main:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
        }

        .btn-primary {
            background: linear-gradient(145deg, #fff2cf, #f3cf85);
            color: #3f2a07;
            box-shadow: inset 0 0 12px rgba(255, 255, 255, 0.4);
        }

        .btn-ghost {
            border: 1px solid rgba(255, 244, 214, 0.6);
            color: #fff6de;
            background: rgba(255, 247, 221, 0.1);
        }

        .stats {
            margin-top: 1.2rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .stat {
            border-radius: 12px;
            background: rgba(255, 244, 214, 0.12);
            border: 1px solid rgba(255, 233, 184, 0.28);
            padding: 0.74rem;
        }

        .stat strong {
            display: block;
            font-size: 1rem;
        }

        .stat span {
            font-size: 0.78rem;
            opacity: 0.9;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .panel {
            padding: 1.1rem;
        }

        .section-title {
            margin: 0;
            font-family: 'Prata', Georgia, serif;
            font-size: 1.35rem;
        }

        .section-sub {
            margin: 0.5rem 0 0;
            color: var(--muted);
            line-height: 1.52;
            font-size: 0.95rem;
        }

        .course-list,
        .suggest-list,
        .review-list {
            margin: 0.9rem 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 0.65rem;
        }

        .tile {
            padding: 0.72rem;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.6);
        }

        .tile strong {
            display: block;
            font-size: 0.95rem;
        }

        .tile span {
            display: block;
            margin-top: 0.22rem;
            color: var(--muted);
            font-size: 0.85rem;
            line-height: 1.45;
        }

        .quote {
            font-size: 0.92rem;
            line-height: 1.55;
            color: #382b16;
            margin: 0;
        }

        .author {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #6a5b45;
            font-weight: 700;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
            margin-top: 0.85rem;
        }

        .action-link {
            text-decoration: none;
            text-align: center;
            padding: 0.76rem 0.8rem;
            border-radius: 12px;
            font-weight: 800;
            border: 1px solid var(--line);
            color: #4a360f;
            background: linear-gradient(145deg, #fff4d4, #f0cc86);
            transition: transform 0.15s, filter 0.2s;
        }

        .action-link:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        .suggest-box {
            margin-top: 0.9rem;
            display: grid;
            gap: 0.55rem;
        }

        .input,
        .textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.68rem 0.72rem;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.84);
        }

        .textarea {
            min-height: 105px;
            resize: vertical;
        }

        .send {
            width: fit-content;
            border: none;
            border-radius: 10px;
            background: linear-gradient(150deg, #c9973f, #8f5d1c);
            color: #fff9ea;
            padding: 0.62rem 0.92rem;
            font-weight: 700;
            cursor: pointer;
        }

        .send:hover {
            filter: brightness(1.06);
        }

        .footer {
            padding: 0.9rem;
            text-align: center;
            color: var(--muted);
            font-size: 0.83rem;
        }

        .fade {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeUp 550ms ease forwards;
        }

        .d1 { animation-delay: 80ms; }
        .d2 { animation-delay: 150ms; }
        .d3 { animation-delay: 220ms; }

        @keyframes sweep {
            to {
                transform: translateX(120%);
            }
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
            }

            .hero-actions {
                flex-direction: column;
            }

            .btn-main {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 560px) {
            .stats,
            .action-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="site">
        <header class="topbar glass fade">
            <div class="brand">
                <span class="brand-mark" aria-hidden="true"></span>
                <div>
                    <div>Alasly</div>
                    <div class="brand-ar">الاصلي</div>
                </div>
            </div>
            <nav class="top-nav" aria-label="Main Navigation">
                <a class="chip" href="portal.php">Student Access</a>
                <a class="chip" href="add_video.php">Admin Panel</a>
            </nav>
        </header>

        <section class="hero glass fade d1" aria-label="Main Intro">
            <span class="shine"></span>
            <span class="badge">Alasly Documentary and Talks Hub</span>
            <h1>Premium learning for Senior 1 minds.</h1>
            <p>
                A focused home for documentaries, curated talks, and advanced discussion-based courses.
                Built for depth, clarity, and consistent learning momentum.
            </p>

            <div class="hero-actions">
                <a class="btn-main btn-primary" href="portal.php">Start Learning</a>
                <a class="btn-main btn-ghost" href="#reviews">Read Reviews</a>
            </div>

            <div class="stats" aria-label="Highlights">
                <article class="stat"><strong>Curated Tracks</strong><span>Documentaries + expert talks</span></article>
                <article class="stat"><strong>Senior 1 Focus</strong><span>Critical thinking and leadership depth</span></article>
                <article class="stat"><strong>Secure Access</strong><span>One-time code verification flow</span></article>
            </div>
        </section>

        <section class="grid">
            <article class="panel glass fade d1" aria-label="Courses and Programs">
                <h2 class="section-title">Programs and Course Suggestions</h2>
                <p class="section-sub">No fluff. Each path is designed to move from insight to action.</p>

                <ul class="course-list">
                    <li class="tile">
                        <strong>Documentary Lab: Systems and Society</strong>
                        <span>Weekly documentary breakdown with structured reflection prompts.</span>
                    </li>
                    <li class="tile">
                        <strong>Senior 1 Talks: Decision-Making Under Pressure</strong>
                        <span>Talk-based sessions focused on strategic communication and leadership judgment.</span>
                    </li>
                    <li class="tile">
                        <strong>Case Room: Real-World Debriefs</strong>
                        <span>Discuss practical case studies and turn lessons into repeatable frameworks.</span>
                    </li>
                    <li class="tile">
                        <strong>Mentor Circle: Monthly Review</strong>
                        <span>Small-group review format for accountability and improvement planning.</span>
                    </li>
                </ul>

                <div class="action-grid">
                    <a class="action-link" href="portal.php">Student Access Page</a>
                    <a class="action-link" href="add_video.php">Admin Generator</a>
                </div>
            </article>

            <article class="panel glass fade d2" aria-label="Suggestions">
                <h2 class="section-title">Suggestions for Better Learning UX</h2>
                <p class="section-sub">Recommended by active learners for stronger outcomes.</p>

                <ul class="suggest-list">
                    <li class="tile"><strong>Talk Notes Template</strong><span>Keep one guided template for every session to speed retention.</span></li>
                    <li class="tile"><strong>Weekly Recap Block</strong><span>Add a 15-minute review at the end of each week.</span></li>
                    <li class="tile"><strong>Two-Speed Tracks</strong><span>Offer standard and intensive pacing for the same course.</span></li>
                </ul>

                <form class="suggest-box" onsubmit="event.preventDefault(); showSuggestionThanks();" aria-label="Suggestion Form">
                    <input class="input" type="text" placeholder="Your name (optional)">
                    <textarea class="textarea" placeholder="Write your suggestion for Alasly..."></textarea>
                    <button class="send" type="submit">Send Suggestion</button>
                </form>
            </article>
        </section>

        <section class="panel glass fade d3" id="reviews" aria-label="Reviews">
            <h2 class="section-title">Learner Reviews</h2>
            <p class="section-sub">Text-only reviews from people who value depth, clarity, and practical outcomes.</p>

            <ul class="review-list">
                <li class="tile">
                    <p class="quote">"The documentary sessions are structured, not random. I now connect ideas faster and speak with more confidence in meetings."</p>
                    <div class="author">- Senior 1 Cohort Learner</div>
                </li>
                <li class="tile">
                    <p class="quote">"Talk analysis felt premium. The pacing and reflection prompts made every session useful, not just interesting."</p>
                    <div class="author">- Product Strategy Participant</div>
                </li>
                <li class="tile">
                    <p class="quote">"Best part is the balance between inspiration and execution. I always leave with practical next steps."</p>
                    <div class="author">- Leadership Track Member</div>
                </li>
            </ul>
        </section>

        <footer class="footer">
            Alasly | الاصلي - Curated documentaries, talks, and advanced learning experiences.
        </footer>
    </main>

    <script>
        function showSuggestionThanks() {
            alert('Thanks for your suggestion. Your feedback helps improve Alasly UX and course quality.');
        }
    </script>
</body>
</html>
