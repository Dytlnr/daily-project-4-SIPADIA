<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Alumni</title>
    <style>
        :root {
            --bg: #f3efe7;
            --bg-accent: #dfe8d8;
            --panel: rgba(255, 252, 247, 0.82);
            --panel-strong: #fffaf2;
            --line: rgba(87, 71, 49, 0.12);
            --text: #2b241d;
            --muted: #6f6355;
            --brand: #146356;
            --brand-strong: #0f4e44;
            --accent: #d98f3f;
            --danger-bg: #fff1ef;
            --danger-text: #a84032;
            --success-bg: #e8f7ef;
            --success-text: #196144;
            --shadow: 0 22px 55px rgba(64, 51, 35, 0.12);
            --radius-xl: 28px;
            --radius-lg: 18px;
            --radius-md: 14px;
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            font-family: Georgia, "Times New Roman", serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(217, 143, 63, 0.15), transparent 28%),
                radial-gradient(circle at top right, rgba(20, 99, 86, 0.18), transparent 24%),
                linear-gradient(180deg, var(--bg-accent) 0%, var(--bg) 36%, #f7f3ec 100%);
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(43, 36, 29, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(43, 36, 29, 0.03) 1px, transparent 1px);
            background-size: 24px 24px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.5), transparent 85%);
        }
        .page-shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 34px 20px 48px;
            position: relative;
            z-index: 1;
        }
        .container {
            background: var(--panel);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: var(--radius-xl);
            padding: 26px;
            box-shadow: var(--shadow);
        }
        .hero {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            padding: 26px 28px;
            margin-bottom: 22px;
            border-radius: 26px;
            background:
                linear-gradient(135deg, rgba(20, 99, 86, 0.96), rgba(11, 56, 49, 0.94)),
                linear-gradient(135deg, rgba(217, 143, 63, 0.12), transparent);
            color: #f8f2e8;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: "";
            position: absolute;
            right: -48px;
            top: -48px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255,255,255,0.16), transparent 65%);
        }
        .hero h1, .hero h2, .hero p { position: relative; z-index: 1; }
        .hero h1, .hero h2 { margin: 0 0 8px; font-size: clamp(1.6rem, 2.3vw, 2.5rem); line-height: 1.08; }
        .hero p { margin: 0; color: rgba(248, 242, 232, 0.82); max-width: 680px; }
        .hero-actions { position: relative; z-index: 1; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .hero-actions > a,
        .hero-actions > form { min-width: 170px; }
        .hero-actions > form { margin: 0; }
        .hero-actions > a,
        .hero-actions > form > button { width: 100%; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 16px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }
        .full { grid-column: 1 / -1; }
        .card {
            background: var(--panel-strong);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 20px;
            box-shadow: 0 10px 28px rgba(76, 60, 41, 0.06);
        }
        .card h3 { margin: 0 0 8px; font-size: 1.05rem; }
        .card p { margin: 0; color: var(--muted); line-height: 1.55; }
        .section-title { margin: 0 0 12px; font-size: 1.1rem; }
        .muted { color: var(--muted); }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            font-weight: 700;
            color: #544939;
        }
        input, select, textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid rgba(87, 71, 49, 0.16);
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.82);
            color: var(--text);
            font: inherit;
            transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: rgba(20, 99, 86, 0.7);
            box-shadow: 0 0 0 4px rgba(20, 99, 86, 0.12);
            transform: translateY(-1px);
        }
        textarea { min-height: 120px; resize: vertical; }
        button, .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border: none;
            border-radius: 999px;
            text-decoration: none;
            cursor: pointer;
            background: linear-gradient(135deg, var(--brand), var(--brand-strong));
            color: #f7f2e7;
            font-weight: 700;
            letter-spacing: 0.01em;
            box-shadow: 0 12px 24px rgba(15, 78, 68, 0.18);
        }
        .btn:hover, button:hover { filter: brightness(1.04); transform: translateY(-1px); }
        .secondary {
            background: linear-gradient(135deg, #7c6a55, #5d4f40);
            box-shadow: 0 12px 24px rgba(93, 79, 64, 0.15);
        }
        .ghost {
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.16);
            box-shadow: none;
        }
        .success, .error {
            padding: 14px 16px;
            border-radius: 18px;
            margin-bottom: 14px;
            border: 1px solid transparent;
        }
        .success { background: var(--success-bg); color: var(--success-text); border-color: rgba(25, 97, 68, 0.12); }
        .error { background: var(--danger-bg); color: var(--danger-text); border-color: rgba(168, 64, 50, 0.12); }
        .pill {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(20, 99, 86, 0.10);
            color: var(--brand-strong);
            font-size: 0.88rem;
            font-weight: 700;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }
        .stat {
            padding: 20px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 250, 242, 0.96), rgba(252, 245, 235, 0.92));
            border: 1px solid var(--line);
        }
        .stat-label { color: var(--muted); font-size: 0.92rem; margin-bottom: 8px; }
        .stat-value { font-size: clamp(1.6rem, 2vw, 2.2rem); font-weight: 700; }
        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: rgba(255, 252, 247, 0.72);
        }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th, td {
            border-bottom: 1px solid rgba(87, 71, 49, 0.08);
            padding: 14px 16px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: rgba(20, 99, 86, 0.08);
            color: #32443f;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        tbody tr:nth-child(even) { background: rgba(255, 255, 255, 0.42); }
        tbody tr:hover { background: rgba(217, 143, 63, 0.08); }
        .table-titlebar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 18px 0 12px;
        }
        .table-titlebar p { margin: 0; color: var(--muted); }
        .pagination-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 16px;
        }
        .stack { display: grid; gap: 14px; }
        .inline-actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .login-shell {
            min-height: calc(100vh - 80px);
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 24px;
            align-items: center;
        }
        .feature-list { display: grid; gap: 12px; margin-top: 22px; }
        .feature-item {
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            background: rgba(255,255,255,0.06);
            color: rgba(248, 242, 232, 0.88);
        }
        .detail-layout {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 22px;
        }
        .detail-meta { display: grid; gap: 14px; }
        .meta-row {
            padding: 16px 18px;
            border-radius: 18px;
            background: var(--panel-strong);
            border: 1px solid var(--line);
        }
        .meta-row small { display: block; color: var(--muted); margin-bottom: 6px; }
        .meta-row strong { font-size: 1rem; }
        @media (max-width: 768px) {
            .page-shell { padding: 16px 14px 28px; }
            .container { padding: 18px; border-radius: 22px; }
            .hero { padding: 20px; }
            .topbar, .hero-actions, .table-titlebar, .pagination-bar { flex-direction: column; align-items: stretch; }
            .grid, .grid-3, .stats, .login-shell, .detail-layout { grid-template-columns: 1fr; }
            table { min-width: 760px; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="container">
            @yield('content')
        </div>
    </div>
</body>
</html>
