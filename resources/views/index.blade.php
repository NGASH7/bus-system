<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mwigito Excel Bus System — One System. School Safety. Fleet Leasing.</title>
    <meta name="description"
        content="Mwigito Excel Bus Management System — Manage fixed routes, track students in real time, and rent entire buses — all from a single platform.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #5c0000;
            --maroon-deep: #3d0000;
            --maroon-light: #a30000;
            --maroon-pale: #fff0f0;
            --maroon-glass: rgba(128, 0, 0, 0.12);
            --gold: #c9a84c;
            --gold-light: #e8c96d;
            --white: #ffffff;
            --off-white: #fdfaf8;
            --text-dark: #1a0a0a;
            --text-mid: #4a2020;
            --text-muted: #7a5050;
            --border: #e8d5d5;
            --shadow-sm: 0 2px 8px rgba(128, 0, 0, 0.08);
            --shadow-md: 0 8px 32px rgba(128, 0, 0, 0.14);
            --shadow-lg: 0 20px 60px rgba(128, 0, 0, 0.20);
            --radius: 14px;
            --radius-lg: 22px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ─── NAVBAR ─────────────────────────────────── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 20px rgba(128, 0, 0, 0.06);
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--maroon), var(--maroon-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.35);
        }

        .nav-logo-icon i {
            color: white;
            font-size: 20px;
        }

        .nav-logo-text {
            line-height: 1.15;
        }

        .nav-logo-text .school {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 17px;
            color: var(--maroon);
            letter-spacing: -0.3px;
        }

        .nav-logo-text .tagline {
            font-size: 10.5px;
            color: var(--text-muted);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-chip {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 40px;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.22s ease;
            border: 1.5px solid transparent;
            color: var(--text-mid);
        }

        .nav-chip:hover {
            background: var(--maroon-pale);
            border-color: var(--maroon-light);
            color: var(--maroon);
        }

        .nav-chip i {
            font-size: 14px;
        }

        .nav-chip.parent {
            color: #1a7a1a;
        }

        .nav-chip.parent:hover {
            background: #f0fff0;
            border-color: #1a7a1a;
            color: #1a7a1a;
        }

        .nav-chip.renter {
            color: #5a1a9a;
        }

        .nav-chip.renter:hover {
            background: #f8f0ff;
            border-color: #5a1a9a;
            color: #5a1a9a;
        }

        .btn-login {
            padding: 10px 22px;
            border-radius: 40px;
            background: linear-gradient(135deg, var(--maroon), var(--maroon-dark));
            color: white;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 16px rgba(128, 0, 0, 0.30);
            transition: all 0.22s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(128, 0, 0, 0.40);
        }

        /* ─── HERO ─────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 600px;
            background: linear-gradient(160deg, rgba(61, 0, 0, 0.85) 0%, rgba(107, 0, 0, 0.75) 35%, rgba(128, 0, 0, 0.80) 65%, rgba(153, 0, 0, 0.90) 100%), url('/Images/bus.jpeg') center/cover no-repeat;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .hero-bg-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(201, 168, 76, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 40%);
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            opacity: 0.06;
            background-image: linear-gradient(rgba(255, 255, 255, 0.5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 24px 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .hero-content {
            max-width: 700px;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(36px, 5vw, 58px);
            font-weight: 900;
            color: white;
            line-height: 1.08;
            letter-spacing: -1.5px;
            margin-bottom: 22px;
        }

        .hero-sub {
            font-size: 17px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.65;
            margin-bottom: 36px;
            font-weight: 400;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-cards-wrap {
            max-width: 900px;
            margin: -140px auto 60px;
            padding: 0 24px;
            position: relative;
            z-index: 10;
        }

        .hero-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .hero-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            text-align: left;
            transition: all 0.28s ease;
        }

        .hero-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 70px rgba(128, 0, 0, 0.25);
        }

        .btn-primary {
            padding: 14px 30px;
            background: var(--gold);
            color: var(--maroon-deep);
            font-weight: 700;
            font-size: 15px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            transition: all 0.22s ease;
            box-shadow: 0 6px 24px rgba(201, 168, 76, 0.40);
        }

        .btn-primary:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(201, 168, 76, 0.50);
        }

        .btn-outline-white {
            padding: 13px 28px;
            background: rgba(255, 255, 255, 0.10);
            border: 1.5px solid rgba(255, 255, 255, 0.30);
            color: white;
            font-weight: 600;
            font-size: 15px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            backdrop-filter: blur(10px);
            transition: all 0.22s ease;
        }

        .btn-outline-white:hover {
            background: rgba(255, 255, 255, 0.20);
            border-color: rgba(255, 255, 255, 0.50);
            transform: translateY(-2px);
        }

        .hero-visual {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-bus-card {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: var(--radius-lg);
            padding: 32px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
        }

        .hero-bus-icon {
            font-size: 64px;
            text-align: center;
            margin-bottom: 20px;
            display: block;
        }

        .hero-stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 20px;
        }

        .hero-stat {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .hero-stat .num {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--gold-light);
        }

        .hero-stat .lbl {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 3px;
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(77, 255, 145, 0.15);
            border: 1px solid rgba(77, 255, 145, 0.30);
            padding: 5px 12px;
            border-radius: 40px;
            color: #4dff91;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        /* ─── SECTION COMMON ────────────────────────── */
        .section {
            padding: 80px 24px;
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(26px, 3.5vw, 38px);
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.8px;
            margin-bottom: 10px;
        }

        .section-sub {
            color: var(--text-muted);
            font-size: 16px;
            margin-bottom: 48px;
        }

        .section-title span {
            color: var(--maroon);
        }

        /* ─── SERVICE CARDS ────────────────────────── */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .service-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1.5px solid var(--border);
            padding: 36px 28px;
            box-shadow: var(--shadow-sm);
            transition: all 0.28s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--maroon), var(--maroon-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.28s ease;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: rgba(128, 0, 0, 0.20);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .service-icon.maroon {
            background: var(--maroon-pale);
            color: var(--maroon);
        }

        .service-icon.green {
            background: #f0fff0;
            color: #1a7a1a;
        }

        .service-icon.purple {
            background: #f8f0ff;
            color: #5a1a9a;
        }

        .service-card h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .service-card h3.green {
            color: #1a7a1a;
        }

        .service-card h3.purple {
            color: #5a1a9a;
        }

        .service-card h3.maroon {
            color: var(--maroon);
        }

        .service-card p {
            color: var(--text-muted);
            font-size: 14.5px;
            line-height: 1.65;
            margin-bottom: 20px;
        }

        .service-list {
            list-style: none;
            margin-bottom: 28px;
        }

        .service-list li {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            color: var(--text-mid);
            padding: 5px 0;
        }

        .service-list li i {
            font-size: 13px;
        }

        .service-list li i.green {
            color: #1a7a1a;
        }

        .service-list li i.purple {
            color: #5a1a9a;
        }

        .service-list li i.maroon {
            color: var(--maroon);
        }

        .btn-service {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.22s ease;
        }

        .btn-service.green {
            background: #1a7a1a;
            color: white;
            box-shadow: 0 4px 14px rgba(26, 122, 26, 0.25);
        }

        .btn-service.green:hover {
            background: #155015;
            transform: translateY(-2px);
        }

        .btn-service.purple {
            background: #5a1a9a;
            color: white;
            box-shadow: 0 4px 14px rgba(90, 26, 154, 0.25);
        }

        .btn-service.purple:hover {
            background: #3d0075;
            transform: translateY(-2px);
        }

        .btn-service.maroon {
            background: var(--maroon);
            color: white;
            box-shadow: 0 4px 14px rgba(128, 0, 0, 0.25);
        }

        .btn-service.maroon:hover {
            background: var(--maroon-dark);
            transform: translateY(-2px);
        }

        /* ─── QUICK ACTIONS ────────────────────────── */
        .quick-actions-wrap {
            background: white;
            border-radius: var(--radius-lg);
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .quick-actions-header {
            background: linear-gradient(135deg, var(--maroon-deep), var(--maroon));
            padding: 24px 32px;
            color: white;
        }

        .quick-actions-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
        }

        .quick-actions-header p {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 4px;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0;
            border-top: 1px solid var(--border);
        }

        .quick-action-panel {
            padding: 32px 28px;
            border-right: 1px solid var(--border);
        }

        .quick-action-panel:last-child {
            border-right: none;
        }

        .qa-icon {
            font-size: 20px;
            margin-bottom: 12px;
        }

        .qa-title {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .qa-title.green {
            color: #1a7a1a;
        }

        .qa-title.purple {
            color: #5a1a9a;
        }

        .qa-title.maroon {
            color: var(--maroon);
        }

        .qa-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .availability-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .availability-form .qa-input,
        .availability-form .btn-qa {
            width: 100%;
        }

        .qa-error {
            font-size: 12px;
            color: #b42318;
            font-weight: 600;
            margin-top: 2px;
        }

        .availability-result {
            margin-top: 12px;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: linear-gradient(180deg, #ffffff, #faf7ff);
        }

        .availability-empty {
            font-size: 13px;
            color: #7a1a1a;
            font-weight: 600;
            margin: 0;
        }

        .availability-title {
            font-size: 13px;
            color: var(--text-primary);
            font-weight: 700;
            margin: 0 0 8px;
        }

        .availability-filter {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0 0 10px;
        }

        .availability-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .availability-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            padding: 8px 10px;
            border-radius: 9px;
            background: rgba(90, 26, 154, 0.06);
        }

        .book-now-link {
            color: #5a1a9a;
            font-weight: 700;
            text-decoration: none;
            padding: 6px 10px;
            border: 1px solid rgba(90, 26, 154, 0.35);
            border-radius: 999px;
            white-space: nowrap;
        }

        .book-now-link:hover {
            background: rgba(90, 26, 154, 0.08);
        }

        .renter-cta {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed var(--border);
        }

        .renter-cta p {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .renter-cta-actions {
            display: flex;
            gap: 10px;
        }

        .renter-cta-actions .btn-qa {
            text-decoration: none;
            padding: 8px 15px;
            font-size: 12px;
            flex: 1;
        }

        .qa-input {
            padding: 11px 15px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            font-size: 14px;
            outline: none;
            transition: border-color 0.22s;
            font-family: 'Inter', sans-serif;
        }

        .qa-input:focus {
            border-color: var(--maroon);
        }

        .btn-qa {
            padding: 11px 18px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.22s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-qa.green {
            background: #1a7a1a;
            color: white;
        }

        .btn-qa.green:hover {
            background: #155015;
            transform: translateY(-1px);
        }

        .btn-qa.purple {
            background: #5a1a9a;
            color: white;
        }

        .btn-qa.purple:hover {
            background: #3d0075;
            transform: translateY(-1px);
        }

        .btn-qa.maroon {
            background: var(--maroon);
            color: white;
        }

        .btn-qa.maroon:hover {
            background: var(--maroon-dark);
            transform: translateY(-1px);
        }

        .qa-note {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ─── LIVE STATUS ──────────────────────────── */
        .status-section {
            background: white;
            border-radius: var(--radius-lg);
            border: 1.5px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .status-header {
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .status-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
        }

        .status-feed {
            padding: 0;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 28px;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
            cursor: default;
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-item:hover {
            background: var(--maroon-pale);
        }

        .status-bus-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .status-bus-icon.orange {
            background: #fff3e0;
            color: #e65100;
        }

        .status-bus-icon.blue {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status-text {
            flex: 1;
        }

        .status-text strong {
            font-size: 14px;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }

        .status-text span {
            font-size: 13px;
            color: var(--text-muted);
        }

        .status-meta {
            text-align: right;
        }

        .status-time {
            font-size: 12px;
            color: var(--text-muted);
            display: block;
            margin-bottom: 5px;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #4dff91;
            display: inline-block;
            animation: pulse 2s infinite;
            margin: 0 auto;
        }

        .status-dot.yellow {
            background: #ffc107;
        }

        .view-all {
            color: var(--maroon);
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        /* ─── WHY SECTION ──────────────────────────── */
        .why-wrap {
            background: linear-gradient(135deg, var(--maroon-deep), var(--maroon));
            border-radius: var(--radius-lg);
            padding: 60px 48px;
        }

        .why-wrap .section-title {
            color: white;
        }

        .why-wrap .section-sub {
            color: rgba(255, 255, 255, 0.7);
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .why-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--radius);
            padding: 28px;
            backdrop-filter: blur(10px);
            transition: all 0.28s;
        }

        .why-card:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-4px);
        }

        .why-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .why-card-icon.gold {
            background: rgba(201, 168, 76, 0.20);
            color: var(--gold-light);
        }

        .why-card-icon.white {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .why-card-icon.green {
            background: rgba(77, 200, 100, 0.20);
            color: #6fdf8c;
        }

        .why-card h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
        }

        .why-card ul {
            list-style: none;
        }

        .why-card ul li {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.78);
            padding: 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .why-card ul li i {
            font-size: 12px;
            color: var(--gold-light);
        }

        /* ─── FOOTER ───────────────────────────────── */
        footer {
            background: var(--maroon-deep);
            color: rgba(255, 255, 255, 0.85);
            padding: 60px 24px 28px;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }

        .footer-brand .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .footer-brand .logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-brand .logo-icon i {
            color: var(--gold-light);
            font-size: 19px;
        }

        .footer-brand .name {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: white;
        }

        .footer-brand p {
            font-size: 13.5px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 18px;
        }

        .emergency-box {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .emergency-box i {
            font-size: 22px;
            color: var(--gold-light);
        }

        .emergency-box .em-title {
            font-weight: 700;
            font-size: 13px;
            color: white;
        }

        .emergency-box .em-num {
            font-size: 17px;
            font-weight: 800;
            color: var(--gold-light);
            letter-spacing: 0.5px;
        }

        .emergency-box .em-sub {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-col h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: white;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: var(--gold-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.10);
            padding-top: 24px;
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.45);
        }

        /* ─── RESPONSIVE ───────────────────────────── */
        @media (max-width: 1024px) {
            .hero-cards-wrap {
                margin-top: -60px;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .quick-action-panel {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .quick-action-panel:last-child {
                border-bottom: none;
            }

            .why-grid {
                grid-template-columns: 1fr 1fr;
            }

            .footer-top {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .availability-list li {
                flex-direction: column;
                align-items: flex-start;
            }

            .renter-cta-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .nav-links .nav-chip span {
                display: none;
            }

            .section {
                padding: 60px 16px;
            }

            .hero-cards-grid {
                grid-template-columns: 1fr;
            }

            .hero-cards-wrap {
                margin-top: -40px;
            }

            .why-grid {
                grid-template-columns: 1fr;
            }

            .why-wrap {
                padding: 40px 24px;
            }

            .footer-top {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        /* ─── ANIMATIONS ───────────────────────────── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease both;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        /* ─── BADGE STAT BAR ───────────────────────── */
        .stat-bar {
            background: var(--maroon-pale);
            border-bottom: 1px solid var(--border);
            padding: 12px 24px;
        }

        .stat-bar-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13.5px;
            color: var(--text-mid);
        }

        .stat-item strong {
            color: var(--maroon);
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
        }

        .stat-item i {
            color: var(--maroon);
            font-size: 15px;
        }

        /* scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--off-white);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--maroon-light);
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="{{ asset('Images/image.png') }}" alt="Mwigito Excel Bus System" title="Mwigito Excel Bus System"
                    style="height: 50px; width: auto; object-fit: contain;">
                <div class="nav-logo-text">
                    <div class="school">Mwigito Excel</div>
                    <div class="tagline">Bus Management System</div>
                </div>
            </a>
            <div class="nav-links">
                <a href="#services" class="nav-chip parent">
                    <i class="fas fa-user-graduate"></i><span>I'm a Parent</span>
                </a>
                <a href="#services" class="nav-chip renter">
                    <i class="fas fa-key"></i><span>I'm a Renter</span>
                </a>
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>



    <!-- HERO -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-content fade-up">
                <h1>One System.<br>School Safety.<br>Fleet Leasing.</h1>
                <p class="hero-sub" style="margin-bottom: 0;">
                    Manage fixed routes, track students in real time,<br>
                    and rent entire buses by the hour —<br>
                    all from a single platform.
                </p>
            </div>
        </div>
    </section>

    <!-- OVERLAPPING CARDS -->
    <div class="hero-cards-wrap" id="services">
        <div class="hero-cards-grid fade-up delay-1">
            <!-- School Bus -->
            <div class="hero-card">
                <div style="display:flex; align-items:center; gap: 16px; margin-bottom: 16px;">
                    <div class="service-icon green" style="margin-bottom: 0;"><i class="fas fa-bus-school"></i></div>
                    <div>
                        <h3 class="green"
                            style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 4px;">
                            School Bus</h3>
                        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">For parents and school
                            districts.</p>
                    </div>
                </div>
                <ul class="service-list" style="margin-bottom: 24px;">
                    <li><i class="fas fa-check-circle green"></i> Track my child's bus in real time</li>
                    <li><i class="fas fa-check-circle green"></i> Receive stop arrival alerts</li>
                    <li><i class="fas fa-check-circle green"></i> Report absence quickly</li>
                </ul>
                <a href="#quick-actions" class="btn-service green" style="width: 100%; justify-content: center;">Parent
                    portal <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- Lease a Bus -->
            <div class="hero-card">
                <div style="display:flex; align-items:center; gap: 16px; margin-bottom: 16px;">
                    <div class="service-icon purple" style="margin-bottom: 0;"><i class="fas fa-key"></i></div>
                    <div>
                        <h3 class="purple"
                            style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 4px;">
                            Lease a Bus</h3>
                        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">For teams, corporate events, or
                            private groups.</p>
                    </div>
                </div>
                <ul class="service-list" style="margin-bottom: 24px;">
                    <li><i class="fas fa-check-circle purple"></i> Check bus availability</li>
                    <li><i class="fas fa-check-circle purple"></i> Get instant price quote</li>
                    <li><i class="fas fa-check-circle purple"></i> Book driver and bus</li>
                </ul>
                <a href="{{ route('bookings.create') }}" class="btn-service purple" style="width: 100%; justify-content: center;">Rent a
                    bus <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div id="quick-actions" style="max-width: 1280px; margin: 0 auto 80px; padding: 0 24px;">
        <div class="quick-actions-wrap">
            <div class="quick-actions-header">
                <h2><i class="fas fa-bolt" style="margin-right:10px;color:var(--gold-light);"></i>Quick Actions</h2>
                <p>Fast access to what matters most.</p>
            </div>
            <div class="quick-actions-grid">
                <!-- Parent -->
                <div class="quick-action-panel">
                    <div class="qa-icon">👨‍👧</div>
                    <div class="qa-title green">I'm a Parent</div>
                    <div class="qa-form">
                        <input class="qa-input" type="text" id="student-id" placeholder="Enter student name or ID">
                        <button class="btn-qa green" onclick="alert('Feature available after login')">
                            <i class="fas fa-search"></i> Track Bus
                        </button>
                        <p class="qa-note"><i class="fas fa-map-marker-alt" style="color:#1a7a1a;"></i> See where your
                            child's bus is right now.</p>
                    </div>
                </div>

                <!-- Renter -->
                <div class="quick-action-panel">
                    <div class="qa-icon">🔑</div>
                    <div class="qa-title purple">I'm a Renter</div>
                    <div class="qa-form">
                        <form method="GET" action="{{ route('landing') }}" class="availability-form">
                            <input class="qa-input" type="date" id="rental-date" name="availability_date"
                                value="{{ old('availability_date', $selectedDate ?? '') }}">
                            <input class="qa-input" type="text" id="rental-dest" name="destination"
                                value="{{ old('destination', $destination ?? '') }}"
                                placeholder="Destination / Occasion">
                            <input class="qa-input" type="number" id="preferred-capacity" name="preferred_capacity"
                                min="1" max="200"
                                value="{{ old('preferred_capacity', $preferredCapacity ?? '') }}"
                                placeholder="Preferred seating capacity">
                            <button class="btn-qa purple" type="submit">
                                <i class="fas fa-calendar-check"></i> Check Availability
                            </button>
                        </form>
                        @error('availability_date')
                            <p class="qa-error">
                                {{ $message }}
                            </p>
                        @enderror
                        @error('preferred_capacity')
                            <p class="qa-error">
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="qa-note"><i class="fas fa-tag" style="color:#5a1a9a;"></i> Check availability and get
                            instant pricing.</p>
                        @if(!is_null($availableBuses))
                            <div class="availability-result">
                                @if($availableBuses->isEmpty())
                                    <p class="availability-empty">
                                        No buses available on {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('d M Y') }}.
                                    </p>
                                @else
                                    <p class="availability-title">
                                        {{ $availableBuses->count() }} bus(es) available on {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('d M Y') }}:
                                    </p>
                                    @if(!empty($preferredCapacity))
                                        <p class="availability-filter">
                                            Filter applied: minimum {{ $preferredCapacity }} seats.
                                        </p>
                                    @endif
                                    <ul class="availability-list">
                                        @foreach($availableBuses as $bus)
                                            <li>
                                                <span>{{ $bus->plate_number }} - {{ $bus->model }} ({{ $bus->capacity }} seats)</span>
                                                <a href="{{ route('bookings.create', [
                                                    'bus_id' => $bus->id,
                                                    'date' => $selectedDate,
                                                    'destination' => $destination,
                                                    'preferred_capacity' => $preferredCapacity,
                                                    'service_type' => 'Private Tour',
                                                ]) }}" class="book-now-link">
                                                    Book now
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                        <div class="renter-cta">
                            <p>To book our bus, please:</p>
                            <div class="renter-cta-actions">
                                <a href="{{ route('register', [
                                    'redirect_to' => route('bookings.create', [
                                        'date' => $selectedDate,
                                        'destination' => $destination,
                                        'preferred_capacity' => $preferredCapacity,
                                        'service_type' => 'Private Tour',
                                    ], false),
                                ]) }}" class="btn-qa purple">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                                <a href="{{ route('login', [
                                    'redirect_to' => route('bookings.create', [
                                        'date' => $selectedDate,
                                        'destination' => $destination,
                                        'preferred_capacity' => $preferredCapacity,
                                        'service_type' => 'Private Tour',
                                    ], false),
                                ]) }}" class="btn-qa maroon">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Member Login -->
                <div class="quick-action-panel">
                    <div class="qa-icon">🔐</div>
                    <div class="qa-title maroon">Member Login</div>
                    <p style="font-size:13.5px; color:var(--text-muted); margin-bottom:14px; line-height:1.6;">Access your personalized dashboard to manage bookings, view shifts, or check fleet status.</p>
                    <a href="{{ route('login') }}" class="btn-qa maroon"
                        style="text-decoration:none; margin-bottom:8px;">
                        <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                    </a>
                    <p class="qa-note"><i class="fas fa-clock" style="color:var(--maroon);"></i> View your shifts,
                        routes and updates.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- LIVE STATUS -->
    <div style="max-width:1280px; margin:0 auto 80px; padding:0 24px;">
        <div class="status-section">
            <div class="status-header">
                <h2><span class="live-badge"
                        style="margin-bottom:0;margin-right:12px;font-size:11px;padding:4px 10px;"><span class="dot"
                            style="width:7px;height:7px;border-radius:50%;background:#4dff91;animation:pulse 2s infinite;display:inline-block;"></span>
                        Live</span>Live Status Feed</h2>
                <a href="{{ route('login') }}" class="view-all">View all updates →</a>
            </div>
            <div class="status-feed" id="status-feed">
                <div class="status-item">
                    <div class="status-bus-icon orange"><i class="fas fa-bus"></i></div>
                    <div class="status-text">
                        <strong>School Bus #ME-007: Arrived at Mwigito Stop.</strong>
                        <span>24 students on board. Next stop: Main Gate.</span>
                    </div>
                    <div class="status-meta">
                        <span class="status-time" id="t1"></span>
                        <span class="status-dot"></span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-bus-icon blue"><i class="fas fa-bus-alt"></i></div>
                    <div class="status-text">
                        <strong>Charter Bus #ME-03: Leased by Teachers' Staff.</strong>
                        <span>ETA to Sports Centre: 18 min. Driver: James Mwangi.</span>
                    </div>
                    <div class="status-meta">
                        <span class="status-time" id="t2"></span>
                        <span class="status-dot yellow"></span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-bus-icon orange"><i class="fas fa-bus"></i></div>
                    <div class="status-text">
                        <strong>School Bus #ME-012: Departed Greenview Junction.</strong>
                        <span>On route. ETA 22 minutes. All students accounted for.</span>
                    </div>
                    <div class="status-meta">
                        <span class="status-time" id="t3"></span>
                        <span class="status-dot"></span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-bus-icon blue"><i class="fas fa-bus-alt"></i></div>
                    <div class="status-text">
                        <strong>Charter Bus #ME-05: Picked up group at Airport.</strong>
                        <span>Heading to Mwigito Excel School. Driver: Peter Kamau.</span>
                    </div>
                    <div class="status-meta">
                        <span class="status-time" id="t4"></span>
                        <span class="status-dot yellow"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WHY CHOOSE US -->
    <div style="max-width:1280px; margin:0 auto 80px; padding:0 24px;">
        <div class="why-wrap">
            <div class="section-title fade-up">Why Choose <span style="color:var(--gold-light);">Our Bus System?</span>
            </div>
            <div class="section-sub" style="color:rgba(255,255,255,0.7);margin-bottom:40px;">Built specifically for
                Mwigito Excel's needs.</div>
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-card-icon gold"><i class="fas fa-school"></i></div>
                    <h3>For Schools</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Student attendance tracking</li>
                        <li><i class="fas fa-check"></i> Geofenced stop alerts</li>
                        <li><i class="fas fa-check"></i> Anti-child left-behind alarms</li>
                        <li><i class="fas fa-check"></i> Real-time parent notifications</li>
                        <li><i class="fas fa-check"></i> Absence reporting</li>
                    </ul>
                </div>
                <div class="why-card">
                    <div class="why-card-icon white"><i class="fas fa-handshake"></i></div>
                    <h3>For Leasing</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Automated billing & invoices</li>
                        <li><i class="fas fa-check"></i> Damage deposit tracking</li>
                        <li><i class="fas fa-check"></i> Post-trip automated invoicing</li>
                        <li><i class="fas fa-check"></i> Driver verification</li>
                        <li><i class="fas fa-check"></i> Fleet availability calendar</li>
                    </ul>
                </div>
                <div class="why-card">
                    <div class="why-card-icon green"><i class="fas fa-user-tie"></i></div>
                    <h3>For Operators & Drivers</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Driver scheduling</li>
                        <li><i class="fas fa-check"></i> Route & shift management</li>
                        <li><i class="fas fa-check"></i> Insurance expiry alerts</li>
                        <li><i class="fas fa-check"></i> Real-time notifications</li>
                        <li><i class="fas fa-check"></i> Performance analytics</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="logo" style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <img src="{{ asset('Images/image.png') }}" alt="Mwigito Excel Bus System" title="Mwigito Excel Bus System"
                            style="height: 45px; width: auto; object-fit: contain;">
                        <div class="name"
                            style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; color: white;">
                            Mwigito Excel Bus System</div>
                    </div>
                    <p>Manage fixed routes, track students in real time, and rent entire buses — all from a single
                        platform built for Mwigito Excel School.</p>
                    <div class="emergency-box">
                        <i class="fas fa-headset"></i>
                        <div>
                            <div class="em-title">Support</div>
                            <div class="em-num">+254722319146</div>
                            <div class="em-sub">mwigitoexcelseniorschool@gmail.com</div>
                        </div>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Important Links</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Leasing Agreement</a></li>
                        <li><a href="#">Refund Policy</a></li>
                        <li><a href="#">Accessibility</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Quick Access</h4>
                    <ul>
                        <li><a href="#">Rent a Bus</a></li>
                        <li><a href="#">Parents Portal</a></li>
                        <li><a href="#">Driver Portal</a></li>
                        <li><a href="#">Insurance Tracking</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>See It In Action</h4>
                    <p style="font-size:13.5px; color:rgba(255,255,255,0.65); margin-bottom:16px; line-height:1.7;">Want
                        to have an easy time using our services next time? Register or Login.</p>
                    <a href="{{ route('login') }}" class="btn-service maroon"
                        style="font-size:13.5px; padding:11px 20px;">
                        <i class="fas fa-sign-in-alt"></i> Login Now
                    </a>
                </div>
            </div>
            <div class="footer-bottom">
                © {{ date('Y') }} Mwigito Excel Bus Management System. All rights reserved. Built with ❤️ for student
                safety.
            </div>
        </div>
    </footer>

    <script>
        // Relative timestamps
        function timeAgo(minutes) {
            if (minutes < 1) return 'Just now';
            return minutes + ' min ago';
        }
        document.getElementById('t1').textContent = timeAgo(2);
        document.getElementById('t2').textContent = timeAgo(5);
        document.getElementById('t3').textContent = timeAgo(9);
        document.getElementById('t4').textContent = timeAgo(14);

        // Intersection observer for fade-up
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.style.animationPlayState = 'running'; });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-up').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        // Set rental date min to today
        const dateEl = document.getElementById('rental-date');
        if (dateEl) dateEl.min = new Date().toISOString().split('T')[0];
    </script>
</body>

</html>