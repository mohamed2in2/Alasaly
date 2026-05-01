<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alasly | Senior 1 Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Noto+Kufi+Arabic:wght@500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #1a2430;
            --muted: #5a6a75;
            --surface: rgba(255, 255, 255, 0.84);
            --surface-strong: rgba(255, 255, 255, 0.94);
            --line: rgba(47, 86, 120, 0.2);
            --brand-1: #1f4f80;
            --brand-2: #2f7dbf;
            --brand-3: #f1b44c;
            --hero-ink: #f4f9ff;
            --bg:
                radial-gradient(circle at 6% 8%, rgba(109, 179, 255, 0.24) 0%, transparent 24%),
                radial-gradient(circle at 90% 10%, rgba(252, 201, 104, 0.23) 0%, transparent 28%),
                linear-gradient(145deg, #eef5fb 0%, #dae8f3 52%, #cedeea 100%);
            --tile-bg: rgba(255, 255, 255, 0.72);
            --shadow: 0 24px 64px rgba(36, 64, 95, 0.2);
            --btn-ink: #14293d;
            --footer-ink: #4f6270;
        }

        [data-theme='dark'] {
            --ink: #e5edf6;
            --muted: #adc0d2;
            --surface: rgba(13, 22, 34, 0.84);
            --surface-strong: rgba(9, 17, 28, 0.9);
            --line: rgba(148, 183, 222, 0.2);
            --brand-1: #16395f;
            --brand-2: #2b6da8;
            --brand-3: #f0b14a;
            --hero-ink: #eef5ff;
            --bg:
                radial-gradient(circle at 6% 8%, rgba(82, 138, 196, 0.34) 0%, transparent 24%),
                radial-gradient(circle at 90% 10%, rgba(241, 177, 73, 0.22) 0%, transparent 28%),
                linear-gradient(145deg, #060f1a 0%, #0e1a2a 52%, #122033 100%);
            --tile-bg: rgba(9, 19, 30, 0.62);
            --shadow: 0 24px 64px rgba(0, 0, 0, 0.45);
            --btn-ink: #f4f8ff;
            --footer-ink: #9fb4c8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            color: var(--ink);
            background: var(--bg);
            padding: 1rem;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .site {
            width: min(1200px, 100%);
            margin: 0 auto;
        }

        .glass {
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--surface);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(145deg, var(--brand-3), #ffd284 42%, var(--brand-2));
            box-shadow: inset 0 0 14px rgba(255, 255, 255, 0.4);
        }

        .brand-ar {
            font-family: 'Noto Kufi Arabic', sans-serif;
            font-size: 0.9rem;
            color: var(--brand-1);
        }

        .top-nav {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .chip,
        .toggle {
            border-radius: 999px;
            border: 1px solid var(--line);
            padding: 0.5rem 0.8rem;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            background: transparent;
            color: var(--ink);
            font-family: inherit;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.2s ease;
        }

        .chip:hover,
        .toggle:hover {
            transform: translateY(-1px);
            background: rgba(142, 176, 208, 0.18);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1.2rem, 3vw, 2.4rem);
            margin-bottom: 1rem;
            background: linear-gradient(125deg, var(--brand-1), var(--brand-2) 68%, #67a4dc 100%);
            color: var(--hero-ink);
        }

        .hero::before {
            content: '';
            position: absolute;
            left: -60px;
            bottom: -70px;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0));
            animation: drift 10s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            right: -72px;
            top: -72px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(255, 224, 160, 0.95), rgba(255, 224, 160, 0.05));
            animation: float 8s ease-in-out infinite;
        }

        .hero-wrap {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 1rem;
            align-items: start;
        }

        .badge {
            display: inline-flex;
            border-radius: 999px;
            border: 1px solid rgba(255, 245, 218, 0.5);
            background: rgba(255, 245, 218, 0.12);
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.35rem 0.75rem;
        }

        h1 {
            margin: 0.8rem 0 0.5rem;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(2.1rem, 5vw, 3.6rem);
            line-height: 1.02;
            max-width: 15ch;
        }

        .hero p {
            margin: 0;
            max-width: 55ch;
            line-height: 1.55;
            color: rgba(243, 248, 255, 0.94);
        }

        .hero-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .btn-main {
            border-radius: 12px;
            text-decoration: none;
            padding: 0.72rem 0.95rem;
            font-weight: 800;
            transition: transform 0.15s ease, filter 0.2s ease;
        }

        .btn-main:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
        }

        .btn-primary {
            color: var(--btn-ink);
            background: linear-gradient(145deg, #ffe4af, #f3b651);
        }

        .btn-ghost {
            color: #edf5ff;
            border: 1px solid rgba(235, 246, 255, 0.6);
            background: rgba(235, 246, 255, 0.12);
        }

        .hero-panel {
            border-radius: 16px;
            border: 1px solid rgba(240, 247, 255, 0.3);
            background: rgba(240, 247, 255, 0.12);
            padding: 0.9rem;
            animation: panelLift 900ms ease both;
        }

        .hero-panel h3 {
            margin: 0;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
        }

        .hero-panel ul {
            margin: 0.55rem 0 0;
            padding-left: 1rem;
            font-size: 0.84rem;
            line-height: 1.5;
            color: rgba(245, 249, 255, 0.9);
        }

        .stats {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .stat {
            border-radius: 12px;
            padding: 0.72rem;
            border: 1px solid rgba(255, 243, 214, 0.28);
            background: rgba(255, 243, 214, 0.11);
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
            background: var(--surface-strong);
        }

        .section-title {
            margin: 0;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.38rem;
        }

        .section-sub {
            margin: 0.5rem 0 0;
            color: var(--muted);
            line-height: 1.55;
            font-size: 0.95rem;
        }

        .course-list,
        .suggest-list,
        .review-list {
            list-style: none;
            padding: 0;
            margin: 0.9rem 0 0;
            display: grid;
            gap: 0.62rem;
        }

        .tile {
            border-radius: 12px;
            border: 1px solid var(--line);
            background: var(--tile-bg);
            padding: 0.72rem;
            transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        }

        .tile:hover {
            transform: translateY(-2px);
            border-color: rgba(47, 86, 120, 0.34);
            background: rgba(255, 255, 255, 0.86);
        }

        .tile strong {
            display: block;
            font-size: 0.95rem;
        }

        .tile span {
            display: block;
            margin-top: 0.24rem;
            color: var(--muted);
            line-height: 1.45;
            font-size: 0.85rem;
        }

        .journey-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.7rem;
            margin-top: 0.95rem;
        }

        .journey-card,
        .tool-card {
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), var(--tile-bg));
            padding: 0.8rem;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        [data-theme='dark'] .journey-card,
        [data-theme='dark'] .tool-card {
            background: linear-gradient(180deg, rgba(14, 24, 38, 0.9), var(--tile-bg));
        }

        .journey-card::after,
        .tool-card::after {
            content: '';
            position: absolute;
            inset: auto -20px -24px auto;
            width: 88px;
            height: 88px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(47, 125, 191, 0.18), transparent 70%);
            pointer-events: none;
        }

        .journey-card:hover,
        .tool-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 30px rgba(36, 64, 95, 0.12);
        }

        .journey-card strong,
        .tool-card strong {
            display: block;
            font-size: 0.95rem;
        }

        .journey-card span,
        .tool-card span {
            display: block;
            margin-top: 0.24rem;
            color: var(--muted);
            line-height: 1.45;
            font-size: 0.85rem;
        }

        .tempo-track {
            margin-top: 0.95rem;
            display: grid;
            gap: 0.6rem;
        }

        .tempo-item {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.48);
            padding: 0.7rem 0.8rem;
            animation: floatIn 520ms ease both;
        }

        [data-theme='dark'] .tempo-item {
            background: rgba(10, 20, 34, 0.5);
        }

        .tempo-dot {
            width: 12px;
            height: 12px;
            flex: 0 0 12px;
            border-radius: 999px;
            background: linear-gradient(145deg, var(--brand-3), var(--brand-2));
            box-shadow: 0 0 0 6px rgba(47, 125, 191, 0.08);
        }

        .tool-stack {
            display: grid;
            gap: 0.7rem;
            margin-top: 0.9rem;
        }

        .section-banner {
            margin-top: 1rem;
            display: inline-flex;
            gap: 0.4rem;
            align-items: center;
            padding: 0.42rem 0.72rem;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.38);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            animation: floatIn 520ms ease both;
        }

        [data-theme='dark'] .section-banner {
            background: rgba(10, 20, 34, 0.45);
        }

        .playbook {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .action-grid {
            margin-top: 0.9rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .action-link {
            text-decoration: none;
            text-align: center;
            border-radius: 12px;
            border: 1px solid var(--line);
            color: var(--ink);
            background: linear-gradient(145deg, #d7e9fa, #9dc4e6);
            font-weight: 800;
            padding: 0.75rem 0.8rem;
            transition: transform 0.15s ease;
        }

        .action-link:hover {
            transform: translateY(-1px);
        }

        .suggest-box {
            margin-top: 0.9rem;
            display: grid;
            gap: 0.55rem;
        }

        .input,
        .textarea {
            width: 100%;
            border-radius: 10px;
            border: 1px solid var(--line);
            padding: 0.68rem 0.72rem;
            font-family: inherit;
            font-size: 0.88rem;
            color: var(--ink);
            background: var(--surface);
        }

        .textarea {
            min-height: 110px;
            resize: vertical;
        }

        .send {
            width: fit-content;
            border: none;
            border-radius: 10px;
            padding: 0.62rem 0.94rem;
            background: linear-gradient(150deg, var(--brand-2), var(--brand-1));
            color: #eef6ff;
            font-weight: 700;
            cursor: pointer;
        }

        .quote {
            margin: 0;
            line-height: 1.56;
            font-size: 0.92rem;
        }

        .author {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--muted);
            font-weight: 700;
        }

        .student-stats {
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            gap: 1rem;
            margin-top: 0.9rem;
        }

        .stats-hero,
        .stats-panel {
            border-radius: 16px;
            border: 1px solid var(--line);
            overflow: hidden;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), var(--tile-bg));
        }

        [data-theme='dark'] .stats-hero,
        [data-theme='dark'] .stats-panel {
            background: linear-gradient(180deg, rgba(14, 24, 38, 0.9), var(--tile-bg));
        }

        .stats-hero {
            padding: 1rem;
            display: grid;
            gap: 0.85rem;
            align-content: start;
        }

        .stats-hero img,
        .student-photo {
            width: 100%;
            display: block;
            object-fit: cover;
        }

        .stats-hero img {
            height: 230px;
            border-radius: 14px;
            animation: floatIn 560ms ease both;
        }

        .stats-hero strong {
            display: block;
            font-size: 1rem;
        }

        .stats-hero span {
            display: block;
            margin-top: 0.3rem;
            color: var(--muted);
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .stats-panel {
            padding: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 0.9rem;
        }

        .metric-card {
            border-radius: 14px;
            border: 1px solid var(--line);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.52);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        [data-theme='dark'] .metric-card {
            background: rgba(11, 20, 33, 0.48);
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 28px rgba(36, 64, 95, 0.12);
        }

        .student-photo {
            height: 150px;
        }

        .metric-body {
            padding: 0.72rem;
        }

        .metric-body strong {
            display: block;
            font-size: 0.95rem;
        }

        .metric-body span {
            display: block;
            margin-top: 0.24rem;
            color: var(--muted);
            line-height: 1.45;
            font-size: 0.84rem;
        }

        .metric-value {
            margin-top: 0.55rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--brand-2);
        }

        .footer {
            text-align: center;
            color: var(--footer-ink);
            padding: 0.9rem;
            font-size: 0.83rem;
        }

        .developer-contact {
            margin-top: 0.45rem;
            font-size: 0.82rem;
        }

        .developer-contact a {
            text-decoration: none;
            color: var(--brand-2);
            font-weight: 700;
        }

        .developer-contact a:hover {
            text-decoration: underline;
        }

        .fade {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeUp 560ms ease forwards;
        }

        .d1 { animation-delay: 90ms; }
        .d2 { animation-delay: 150ms; }
        .d3 { animation-delay: 220ms; }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(10px) scale(1.03); }
        }

        @keyframes drift {
            0%, 100% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(12px) translateY(-8px); }
        }

        @keyframes panelLift {
            from {
                opacity: 0;
                transform: translateY(12px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 960px) {
            .hero-wrap,
            .grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 560px) {
            .stats,
            .playbook,
            .action-grid,
            .student-stats,
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .hero-actions {
                flex-direction: column;
            }

            .btn-main {
                width: 100%;
                text-align: center;
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
                <button id="themeToggle" class="toggle" type="button" aria-label="Switch color theme">Night Mode</button>
            </nav>
        </header>

        <section class="hero glass fade d1" aria-label="Main Intro">
            <div class="hero-wrap">
                <div>
                    <span class="badge">Senior 1 Learning Experience</span>
                    <h1>Watch with purpose. Think with style. Lead with proof.</h1>
                    <p>
                        Alasly helps Senior 1 learners turn every video into a clear idea, a stronger voice, and one practical move they can actually use.
                    </p>

                    <div class="hero-actions">
                        <a class="btn-main btn-primary" href="portal.php">Open Student Portal</a>
                        <a class="btn-main btn-ghost" href="#reviews">See Student Voice</a>
                    </div>

                    <div class="stats" aria-label="Highlights">
                        <article class="stat"><strong>Watch</strong><span>Curated videos with a learning goal</span></article>
                        <article class="stat"><strong>Discuss</strong><span>Push ideas deeper with guided reflection</span></article>
                        <article class="stat"><strong>Act</strong><span>Leave each week with one real outcome</span></article>
                    </div>
                </div>

                <aside class="hero-panel" aria-label="This Week Focus">
                    <h3>This Week's Flow</h3>
                    <ul>
                        <li>Start with one powerful video.</li>
                        <li>Break the idea apart in discussion.</li>
                        <li>Turn the lesson into a weekly action.</li>
                        <li>Finish with a short reflection note.</li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="grid">
            <article class="panel glass fade d1" aria-label="Learning Journey">
                <span class="section-banner">Senior 1 Journey</span>
                <h2 class="section-title">Learning Journey</h2>
                <p class="section-sub">A more visual path for learners who want ideas to become habits, not just notes.</p>

                <div class="journey-grid">
                    <div class="journey-card">
                        <strong>Discovery</strong>
                        <span>Open with one strong video that frames the main idea.</span>
                    </div>
                    <div class="journey-card">
                        <strong>Discussion</strong>
                        <span>Talk through the lesson and test your thinking with others.</span>
                    </div>
                    <div class="journey-card">
                        <strong>Practice</strong>
                        <span>Use a quick drill or challenge to make the lesson active.</span>
                    </div>
                    <div class="journey-card">
                        <strong>Proof</strong>
                        <span>Finish by showing one real result, note, or decision.</span>
                    </div>
                </div>

                <div class="tempo-track" aria-label="Weekly Rhythm">
                    <div class="tempo-item"><span class="tempo-dot" aria-hidden="true"></span><div><strong>Monday</strong><span>Watch, capture, and rank the most important idea.</span></div></div>
                    <div class="tempo-item"><span class="tempo-dot" aria-hidden="true"></span><div><strong>Wednesday</strong><span>Challenge the lesson in discussion and build a better answer.</span></div></div>
                    <div class="tempo-item"><span class="tempo-dot" aria-hidden="true"></span><div><strong>Friday</strong><span>Apply one idea in real life and log the result.</span></div></div>
                </div>

                <div class="action-grid">
                    <a class="action-link" href="portal.php">Student Access Page</a>
                    <a class="action-link" href="add_video.php">Admin Generator</a>
                </div>
            </article>

            <article class="panel glass fade d2" aria-label="Learning Tools">
                <span class="section-banner">Fresh Tools</span>
                <h2 class="section-title">Learning Tools</h2>
                <p class="section-sub">Simple helpers that make every session easier to follow and more memorable.</p>

                <div class="tool-stack">
                    <div class="tool-card"><strong>Before You Watch</strong><span>See a short goal card so learners know what they are looking for.</span></div>
                    <div class="tool-card"><strong>During the Video</strong><span>Give students a note space or prompt bar for fast reflection.</span></div>
                    <div class="tool-card"><strong>After the Lesson</strong><span>Show one tiny action, one summary, and one confidence check.</span></div>
                </div>

                <form id="suggestForm" class="suggest-box" onsubmit="event.preventDefault(); sendSuggestionByEmail();" aria-label="Suggestion Form">
                    <input id="suggName" class="input" type="text" placeholder="Your name (optional)">
                    <textarea id="suggBody" class="textarea" placeholder="Share one improvement that would make Senior 1 more creative..."></textarea>
                    <button class="send" type="submit">Send Idea</button>
                </form>
            </article>
        </section>

        <section class="panel glass fade d3" id="reviews" aria-label="Student Statistics">
            <span class="section-banner">Student Stats</span>
            <h2 class="section-title">Students Using Our Apps</h2>
            <p class="section-sub">A visual snapshot of the learners who use Alasly to watch, discuss, and complete weekly actions.</p>

            <div class="student-stats">
                <article class="stats-hero">
                    <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1200&q=80" alt="Student studying with a notebook">
                    <div>
                        <strong>Active Learning Community</strong>
                        <span>Students use the app for documentary sessions, talks, and weekly follow-through. The experience is built to feel focused, modern, and easy to return to.</span>
                    </div>
                </article>

                <article class="stats-panel">
                    <div class="stats-grid">
                        <div class="metric-card">
                            <img class="student-photo" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80" alt="Student smiling in a classroom">
                            <div class="metric-body">
                                <strong>Weekly Learners</strong>
                                <span>Students who return every week for new videos and discussion.</span>
                                <div class="metric-value">120+</div>
                            </div>
                        </div>
                        <div class="metric-card">
                            <img class="student-photo" src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1000&q=80" alt="Student working on a laptop">
                            <div class="metric-body">
                                <strong>Session Completion</strong>
                                <span>Lessons completed by students who finish the full watch-discuss-action flow.</span>
                                <div class="metric-value">98%</div>
                            </div>
                        </div>
                        <div class="metric-card">
                            <img class="student-photo" src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1000&q=80" alt="Student reading with focus">
                            <div class="metric-body">
                                <strong>Saved Notes</strong>
                                <span>Quick reflections and study notes created by learners after each video.</span>
                                <div class="metric-value">340+</div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <footer class="footer">
            Alasly | الاصلي - Senior 1 documentary and leadership learning platform.
            <div class="developer-contact">
                Developer Contact:
                <a href="https://wa.me/201101670389" target="_blank" rel="noopener noreferrer">WhatsApp +201101670389</a>
                |
                <a href="mailto:ahmedehab2n5@gmail.com">ahmedehab2n5@gmail.com</a>
            </div>
        </footer>
    </main>

    <script>
        (function () {
            const root = document.documentElement;
            const savedTheme = localStorage.getItem('alasly-theme');
            if (savedTheme === 'dark' || savedTheme === 'light') {
                root.setAttribute('data-theme', savedTheme);
            }

            const toggle = document.getElementById('themeToggle');
            const syncLabel = function () {
                const isDark = root.getAttribute('data-theme') === 'dark';
                toggle.textContent = isDark ? 'Day Mode' : 'Night Mode';
            };

            syncLabel();
            toggle.addEventListener('click', function () {
                const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                localStorage.setItem('alasly-theme', next);
                syncLabel();
            });
        })();

        function sendSuggestionByEmail() {
            try {
                const name = document.getElementById('suggName').value.trim();
                const body = document.getElementById('suggBody').value.trim();
                if (!body) {
                    alert('Please write your suggestion before sending.');
                    return;
                }

                const to = 'contact@alasly.live';
                const subject = (name ? name + ' – ' : '') + 'Alasly suggestion';
                const fullBody = `Suggestion from: ${name || 'Anonymous'}\n\n${body}\n\n--\nSent from Alasly landing page`;

                const mailto = `mailto:${encodeURIComponent(to)}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(fullBody)}`;

                // Try opening the user's mail client
                window.location.href = mailto;

                // Provide quick feedback
                setTimeout(() => {
                    alert('If your mail client opened successfully, your suggestion is ready to send.');
                }, 300);
            } catch (err) {
                console.error('sendSuggestionByEmail error', err);
                alert('Unable to open mail client. Please copy your suggestion and email contact@alasly.live');
            }
        }
    </script>
</body>
</html>
