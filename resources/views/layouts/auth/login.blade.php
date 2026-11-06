<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIHUMAS — Portal Layanan Humas, Dokumentasi &amp; Publikasi | Universitas Ibnu Sina</title>
    <meta name="description" content="Sistem Informasi Layanan Terpadu Hubungan Masyarakat, Protokoler, Dokumentasi dan Publikasi Universitas Ibnu Sina Batam">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('volt/assets/img/favicon/uis.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            /* Palette: Deep Navy + UIS Emerald + Royal Gold */
            --navy-950: #050B14;
            --navy-900: #0A1424;
            --navy-850: #0E1B31;
            --navy-800: #142542;
            --navy-700: #1D345C;

            --emerald-500: #10B981;
            --emerald-600: #059669;
            --emerald-400: #34D399;
            --emerald-glow: rgba(16, 185, 129, 0.25);

            --gold-300: #F6E09E;
            --gold-400: #E6C466;
            --gold-500: #CFA43B;
            --gold-600: #A88124;
            --gold-glow: rgba(230, 196, 102, 0.22);

            --white: #FFFFFF;
            --slate-100: #F1F5F9;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;

            --glass-bg: rgba(14, 27, 49, 0.72);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-border-gold: rgba(230, 196, 102, 0.28);
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--navy-950);
            color: var(--white);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== MAIN WRAPPER ===== */
        .login-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            position: relative;
            background: var(--navy-950);
        }

        /* Ambient Lighting / Mesh Orbs */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
            transition: all 1s ease;
        }
        .glow-1 {
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.22) 0%, transparent 70%);
            top: -120px;
            left: -80px;
        }
        .glow-2 {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(207, 164, 59, 0.18) 0%, transparent 70%);
            bottom: -100px;
            left: 30%;
        }
        .glow-3 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(29, 52, 92, 0.7) 0%, transparent 70%);
            top: 20%;
            right: -100px;
        }

        /* ===== LEFT PANEL (HUMAS MEDIA SHOWCASE) ===== */
        .showcase-panel {
            flex: 1.25;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 64px;
            overflow: hidden;
            z-index: 2;
        }

        /* Background Campus Photo with Cinematic Overlay */
        .showcase-bg {
            position: absolute;
            inset: 0;
            background: url('{{ asset('volt/assets/img/uis.png') }}') center/cover no-repeat;
            opacity: 0.28;
            filter: saturate(1.1) contrast(1.1);
            transform: scale(1.04);
            animation: cinematicDrift 28s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes cinematicDrift {
            0% { transform: scale(1.04) translate(0, 0); }
            50% { transform: scale(1.10) translate(-10px, -8px); }
            100% { transform: scale(1.06) translate(8px, 4px); }
        }

        .showcase-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(5, 11, 20, 0.94) 0%,
                rgba(10, 20, 36, 0.85) 45%,
                rgba(5, 11, 20, 0.97) 100%
            );
            z-index: 1;
        }

        /* Subtle Geometric Grid Lines */
        .showcase-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at 40% 50%, black 30%, transparent 80%);
            z-index: 1;
            pointer-events: none;
        }

        /* Showcase Content Elements */
        .showcase-header,
        .showcase-body,
        .showcase-footer {
            position: relative;
            z-index: 3;
        }

        /* Top Bar in Showcase */
        .showcase-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .inst-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 8px 16px;
            border-radius: 100px;
            transition: all 0.3s ease;
        }
        .inst-badge:hover {
            border-color: var(--glass-border-gold);
            background: rgba(230, 196, 102, 0.08);
        }
        .inst-badge .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--emerald-400);
            box-shadow: 0 0 10px var(--emerald-400);
            animation: pulseDot 2s infinite;
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }
        .inst-badge span {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: var(--slate-300);
        }
        .inst-badge strong {
            color: var(--gold-300);
            font-weight: 700;
        }

        .campus-tag {
            font-size: 12px;
            color: var(--slate-400);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .campus-tag i { color: var(--gold-400); }

        /* Showcase Hero Typography */
        .showcase-body {
            max-width: 620px;
            margin: 40px 0;
        }

        .humas-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.15), rgba(207, 164, 59, 0.15));
            border: 1px solid rgba(207, 164, 59, 0.35);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--gold-300);
            margin-bottom: 22px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }
        .humas-pill i {
            color: var(--emerald-400);
            font-size: 12px;
        }

        .showcase-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.1rem;
            line-height: 1.15;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--white);
            margin-bottom: 18px;
        }
        .showcase-title em {
            font-style: italic;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(120deg, #FFFFFF 0%, var(--gold-300) 60%, var(--gold-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .showcase-title .highlight {
            color: var(--emerald-400);
            -webkit-text-fill-color: initial;
            font-style: normal;
        }

        .showcase-desc {
            font-size: 14.5px;
            line-height: 1.75;
            color: var(--slate-300);
            margin-bottom: 34px;
            font-weight: 400;
        }

        /* Floating Activity Cards (Glassmorphism Live Feed) */
        .activity-deck {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 34px;
        }

        .activity-card {
            background: rgba(14, 27, 49, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 16px 16px;
            backdrop-filter: blur(14px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .activity-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold-400), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .activity-card:hover {
            transform: translateY(-4px);
            border-color: rgba(207, 164, 59, 0.35);
            background: rgba(20, 37, 66, 0.7);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }
        .activity-card:hover::before {
            opacity: 1;
        }

        .card-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 12px;
        }
        .card-icon.cam {
            background: rgba(16, 185, 129, 0.15);
            color: var(--emerald-400);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .card-icon.mic {
            background: rgba(207, 164, 59, 0.15);
            color: var(--gold-300);
            border: 1px solid rgba(207, 164, 59, 0.3);
        }
        .card-icon.news {
            background: rgba(56, 189, 248, 0.15);
            color: #38BDF8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .activity-card h4 {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 4px;
        }
        .activity-card p {
            font-size: 11.5px;
            color: var(--slate-400);
            line-height: 1.5;
        }

        /* Metrics Showcase */
        .metrics-strip {
            display: flex;
            align-items: center;
            gap: 28px;
            padding: 18px 24px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }
        .metric-block {
            display: flex;
            flex-direction: column;
        }
        .metric-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gold-300);
            line-height: 1.1;
        }
        .metric-label {
            font-size: 10.5px;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 3px;
        }
        .metric-divider {
            width: 1px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Service Tags */
        .service-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .service-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 11.5px;
            color: var(--slate-300);
            transition: all 0.25s ease;
        }
        .service-tag i {
            color: var(--gold-400);
            font-size: 11px;
        }
        .service-tag:hover {
            background: rgba(230, 196, 102, 0.1);
            border-color: rgba(230, 196, 102, 0.35);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ===== RIGHT PANEL (AUTH FORM) ===== */
        .auth-panel {
            width: 480px;
            min-width: 440px;
            background: linear-gradient(180deg, rgba(10, 20, 36, 0.95) 0%, rgba(5, 11, 20, 0.98) 100%);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px 48px;
            position: relative;
            z-index: 3;
            overflow-y: auto;
        }

        /* Decorative Light Arc */
        .auth-panel::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            position: relative;
            z-index: 2;
        }

        /* Institutional Brand Identity Header */
        .brand-crest {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 34px;
        }

        .crest-wrapper {
            position: relative;
            width: 82px;
            height: 82px;
            margin-bottom: 16px;
        }
        .crest-wrapper::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 24px;
            background: linear-gradient(135deg, var(--emerald-400), var(--gold-400));
            opacity: 0.45;
            filter: blur(8px);
            animation: auraPulse 4s ease-in-out infinite alternate;
        }
        @keyframes auraPulse {
            0% { opacity: 0.35; transform: scale(0.97); }
            100% { opacity: 0.65; transform: scale(1.03); }
        }

        .crest-logo {
            position: relative;
            width: 82px;
            height: 82px;
            object-fit: contain;
            border-radius: 20px;
            background: linear-gradient(145deg, #0e2038, #071220);
            border: 1.5px solid rgba(230, 196, 102, 0.4);
            padding: 8px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.4);
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--white);
            margin-bottom: 3px;
        }
        .brand-title span {
            background: linear-gradient(120deg, var(--gold-300), var(--gold-500));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: var(--emerald-400);
            text-transform: uppercase;
            letter-spacing: 2.2px;
        }
        .brand-dept {
            font-size: 11px;
            color: var(--slate-400);
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        /* Form Greeting */
        .form-intro {
            margin-bottom: 26px;
            text-align: left;
        }
        .form-intro h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.2px;
        }
        .form-intro p {
            font-size: 13px;
            color: var(--slate-400);
            margin-top: 4px;
        }

        /* Form Input Styling */
        .field-group {
            margin-bottom: 20px;
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--slate-300);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }
        .field-label a {
            font-size: 11px;
            text-transform: none;
            color: var(--gold-400);
            text-decoration: none;
            letter-spacing: normal;
            transition: color 0.2s;
        }
        .field-label a:hover {
            color: var(--gold-300);
            text-decoration: underline;
        }

        .field-control {
            position: relative;
        }
        .field-control .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-500);
            font-size: 14px;
            transition: color 0.25s ease;
            pointer-events: none;
        }
        .field-control input {
            width: 100%;
            background: rgba(14, 27, 49, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 13.5px 44px;
            font-size: 13.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--white);
            outline: none;
            transition: all 0.25s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .field-control input::placeholder {
            color: rgba(148, 163, 184, 0.45);
        }
        .field-control input:focus {
            border-color: var(--emerald-400);
            background: rgba(16, 185, 129, 0.05);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .field-control input:focus + .input-icon,
        .field-control:focus-within .input-icon {
            color: var(--emerald-400);
        }

        .field-control input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 50px #0b1526 inset;
            -webkit-text-fill-color: #ffffff;
        }

        /* Password visibility toggle */
        .pw-toggle-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--slate-500);
            cursor: pointer;
            font-size: 14px;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .pw-toggle-btn:hover {
            color: var(--gold-300);
        }

        /* Remember me row */
        .auth-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 12.5px;
        }
        .custom-check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            color: var(--slate-400);
        }
        .custom-check input[type="checkbox"] {
            appearance: none;
            width: 17px;
            height: 17px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        .custom-check input[type="checkbox"]:checked {
            background: var(--emerald-500);
            border-color: var(--emerald-500);
        }
        .custom-check input[type="checkbox"]:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 10px;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .custom-check:hover span {
            color: var(--slate-300);
        }

        /* Modern CTA Button */
        .btn-submit-login {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 24px;
            background: linear-gradient(135deg, #A88124 0%, #CFA43B 40%, #E6C466 100%);
            border: none;
            border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #070F1E;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(207, 164, 59, 0.3);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-submit-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        .btn-submit-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(207, 164, 59, 0.45);
        }
        .btn-submit-login:hover::before {
            left: 100%;
        }
        .btn-submit-login:active {
            transform: translateY(0);
        }

        /* Error Notification Banner */
        .auth-alert-error {
            display: none;
            align-items: center;
            gap: 12px;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            color: #FCA5A5;
            font-size: 12.5px;
            animation: shakeAlert 0.4s ease;
        }
        @keyframes shakeAlert {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        .auth-alert-error i {
            color: #EF4444;
            font-size: 15px;
        }

        /* Help & Support Widget */
        .support-box {
            margin-top: 26px;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .support-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .support-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--emerald-400);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }
        .support-text {
            font-size: 11.5px;
            line-height: 1.4;
            color: var(--slate-300);
        }
        .support-text span {
            color: var(--slate-400);
            display: block;
            font-size: 10.5px;
        }
        .btn-wa-help {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(16, 185, 129, 0.18);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: var(--emerald-400);
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .btn-wa-help:hover {
            background: var(--emerald-600);
            color: var(--white);
            border-color: var(--emerald-500);
        }

        /* Auth Panel Footer */
        .auth-footer {
            margin-top: 28px;
            text-align: center;
        }
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10.5px;
            color: var(--slate-500);
            margin-bottom: 8px;
        }
        .security-badge i {
            color: var(--emerald-400);
        }
        .copyright-text {
            font-size: 11px;
            color: rgba(148, 163, 184, 0.55);
            line-height: 1.5;
        }
        .copyright-text strong {
            color: var(--slate-300);
            font-weight: 600;
        }

        /* ===== LOADING OVERLAY ===== */
        .loading-screen {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(5, 11, 20, 0.85);
            backdrop-filter: blur(10px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
        }
        .spinner-ring {
            width: 48px;
            height: 48px;
            border: 3.5px solid rgba(230, 196, 102, 0.2);
            border-top-color: var(--gold-400);
            border-radius: 50%;
            animation: spinRing 0.85s cubic-bezier(0.6, 0.2, 0.4, 0.8) infinite;
        }
        @keyframes spinRing {
            to { transform: rotate(360deg); }
        }
        .loading-text {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            color: var(--gold-300);
            text-transform: uppercase;
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        @media (max-width: 1080px) {
            .showcase-title { font-size: 2.5rem; }
            .activity-deck { grid-template-columns: 1fr; }
            .auth-panel { width: 420px; min-width: 390px; padding: 40px 32px; }
            .showcase-panel { padding: 40px; }
        }

        @media (max-width: 860px) {
            .showcase-panel { display: none; }
            .auth-panel {
                width: 100%;
                min-width: 100%;
                border-left: none;
                padding: 48px 24px;
                background: radial-gradient(circle at top, #0f2442 0%, #050b14 85%);
            }
            .brand-crest { margin-bottom: 24px; }
        }
    </style>
</head>
<body>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="spinner-ring"></div>
    <div class="loading-text">Memvalidasi Kredensial...</div>
</div>

<!-- Ambient Mesh Lights -->
<div class="ambient-glow glow-1"></div>
<div class="ambient-glow glow-2"></div>
<div class="ambient-glow glow-3"></div>

<div class="login-layout">

    <!-- ===== LEFT PANEL: HUMAS MEDIA & REPUTATION PORTAL ===== -->
    <div class="showcase-panel">
        <div class="showcase-bg"></div>
        <div class="showcase-overlay"></div>
        <div class="showcase-grid"></div>

        <!-- Top Status Bar -->
        <div class="showcase-header">
            <div class="showcase-topbar">
                <div class="inst-badge">
                    <span class="live-dot"></span>
                    <span>Portal Resmi <strong>Humas &amp; Publikasi</strong></span>
                </div>
                <div class="campus-tag">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Universitas Ibnu Sina · Batam</span>
                </div>
            </div>
        </div>

        <!-- Main Editorial Body -->
        <div class="showcase-body">
            <div class="humas-pill">
                <i class="fa-solid fa-tower-broadcast"></i>
                <span>Harmoni Komunikasi &amp; Informasi Kampus</span>
            </div>

            <h1 class="showcase-title">
                Sinergi Media &amp; <br>
                <em>Publikasi Prestasi</em> <span class="highlight">UIS</span>
            </h1>

            <p class="showcase-desc">
                Pusat layanan terintegrasi Biro Hubungan Masyarakat &amp; Protokoler
                <strong style="color:var(--white);">Universitas Ibnu Sina</strong>.
                Menghadirkan tata kelola penugasan peliputan, dokumentasi audio-visual profesional,
                dan peminjaman peralatan multimedia berstandar institusi.
            </p>

            <!-- Activity Cards: 3 Pillars of Humas -->
            <div class="activity-deck">
                <div class="activity-card">
                    <div class="card-icon cam">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <h4>Liputan &amp; Dokumentasi</h4>
                    <p>Penugasan tim fotografer &amp; videografer untuk agenda resmi universitas.</p>
                </div>

                <div class="activity-card">
                    <div class="card-icon mic">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h4>Peminjaman Peralatan</h4>
                    <p>Akses inventaris kamera 4K, audio studio, lighting, dan drone multimedia.</p>
                </div>

                <div class="activity-card">
                    <div class="card-icon news">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <h4>Siaran Pers &amp; Media</h4>
                    <p>Diseminasi berita prestasi, rilis media nasional, dan branding kampus.</p>
                </div>
            </div>

            <!-- Quantitative Metrics Strip -->
            <div class="metrics-strip">
                <div class="metric-block">
                    <span class="metric-num">500+</span>
                    <span class="metric-label">Agenda Terdokumentasi</span>
                </div>
                <div class="metric-divider"></div>
                <div class="metric-block">
                    <span class="metric-num">12K+</span>
                    <span class="metric-label">Arsip Foto &amp; Video</span>
                </div>
                <div class="metric-divider"></div>
                <div class="metric-block">
                    <span class="metric-num">50+</span>
                    <span class="metric-label">Unit &amp; Ormawa</span>
                </div>
                <div class="metric-divider"></div>
                <div class="metric-block">
                    <span class="metric-num">100%</span>
                    <span class="metric-label">Layanan Digital</span>
                </div>
            </div>
        </div>

        <!-- Bottom Service Pills -->
        <div class="showcase-footer">
            <div class="service-tags">
                <span class="service-tag"><i class="fa-solid fa-camera-retro"></i> Liputan Acara &amp; Wisuda</span>
                <span class="service-tag"><i class="fa-solid fa-film"></i> Aftermovie Kegiatan</span>
                <span class="service-tag"><i class="fa-solid fa-newspaper"></i> Publikasi Media Cetak &amp; Online</span>
                <span class="service-tag"><i class="fa-solid fa-podcast"></i> Ruang Podcast &amp; Audio</span>
                <span class="service-tag"><i class="fa-solid fa-plane-up"></i> Dokumentasi Udara (Drone)</span>
                <span class="service-tag"><i class="fa-solid fa-calendar-check"></i> Penjadwalan Protokoler</span>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT PANEL: SECURE AUTHENTICATION FORM ===== -->
    <div class="auth-panel">
        <div class="auth-container">

            <!-- Institutional Crest & Identity -->
            <div class="brand-crest">
                <div class="crest-wrapper">
                    <img 
                        src="{{ asset('volt/assets/img/logouis.png') }}" 
                        alt="Logo Universitas Ibnu Sina" 
                        class="crest-logo"
                        onerror="this.src='https://assets.siakadcloud.com/uploads/uis/logoaplikasi/156.jpg'"
                    >
                </div>
                <div class="brand-title">SI<span>HUMAS</span></div>
                <div class="brand-subtitle">Universitas Ibnu Sina</div>
                <div class="brand-dept">Biro Hubungan Masyarakat &amp; Protokoler</div>
            </div>

            <!-- Form Introduction -->
            <div class="form-intro">
                <h2>Selamat Datang Kembali</h2>
                <p>Masukkan akun institusi Anda untuk mengakses layanan Humas.</p>
            </div>

            <!-- Error Notification Banner -->
            <div class="auth-alert-error" id="authAlertError">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span id="authAlertMsg">Kredensial yang dimasukkan tidak cocok dengan catatan kami.</span>
            </div>

            <!-- Login Form -->
            <form action="{{ route('loginproses') }}" method="POST" id="authLoginForm">
                @csrf

                @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const errBox = document.getElementById('authAlertError');
                            const errMsg = document.getElementById('authAlertMsg');
                            if (errBox && errMsg) {
                                errBox.style.display = 'flex';
                                errMsg.textContent = '{{ $errors->first() }}';
                            }
                        });
                    </script>
                @endif

                <!-- Email Input -->
                <div class="field-group">
                    <div class="field-label">
                        <label for="loginEmail">Alamat Email Institusi</label>
                    </div>
                    <div class="field-control">
                        <input
                            type="email"
                            id="loginEmail"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="nama@uis.ac.id"
                            required
                            autocomplete="email"
                            autofocus
                        >
                        <i class="fa-solid fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="field-group">
                    <div class="field-label">
                        <label for="loginPassword">Kata Sandi</label>
                        <a href="https://wa.me/6282283736481?text=Halo%20Admin%20Humas%20UIS,%20saya%20lupa%20kata%20sandi%20akun%20SIHUMAS%20saya.%20Mohon%20bantuannya." target="_blank" rel="noopener">
                            Lupa sandi?
                        </a>
                    </div>
                    <div class="field-control">
                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            placeholder="Masukkan kata sandi akun"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fa-solid fa-lock input-icon"></i>
                        <button type="button" class="pw-toggle-btn" id="pwToggleBtn" aria-label="Lihat kata sandi">
                            <i class="fa-solid fa-eye" id="pwToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="auth-options">
                    <label class="custom-check">
                        <input type="checkbox" name="remember" id="rememberMe">
                        <span>Ingat sesi perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit-login" id="btnSubmitLogin">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Portal SIHUMAS</span>
                </button>
            </form>

            <!-- WhatsApp Support Card -->
            <div class="support-box">
                <div class="support-info">
                    <div class="support-icon">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="support-text">
                        Bantuan Cepat Humas
                        <span>Kendala akses &amp; pengajuan mendesak</span>
                    </div>
                </div>
                <a 
                    href="https://wa.me/6282283736481?text=Halo%20Admin%20Humas%20UIS,%20saya%20butuh%20bantuan%20mengenai%20layanan%20SIHUMAS." 
                    target="_blank" 
                    rel="noopener" 
                    class="btn-wa-help"
                >
                    <i class="fa-brands fa-whatsapp"></i> Kontak WA
                </a>
            </div>

        </div>

        <!-- Auth Footer -->
        <div class="auth-footer">
            <div class="security-badge">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Enkripsi SSL 256-bit Terproteksi · Sistem Informasi Kampus</span>
            </div>
            <div class="copyright-text">
                &copy; {{ date('Y') }} <strong>Universitas Ibnu Sina</strong><br>
                Biro Hubungan Masyarakat, Protokoler &amp; Publikasi
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script>
    // Toggle Password Visibility
    const pwInput = document.getElementById('loginPassword');
    const pwBtn = document.getElementById('pwToggleBtn');
    const pwIcon = document.getElementById('pwToggleIcon');

    if (pwBtn && pwInput && pwIcon) {
        pwBtn.addEventListener('click', function () {
            if (pwInput.type === 'password') {
                pwInput.type = 'text';
                pwIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwInput.type = 'password';
                pwIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    // Form Submission & Loading Feedback
    const loginForm = document.getElementById('authLoginForm');
    const loadingScreen = document.getElementById('loadingScreen');
    const submitBtn = document.getElementById('btnSubmitLogin');

    if (loginForm) {
        loginForm.addEventListener('submit', function () {
            if (loadingScreen) {
                loadingScreen.style.display = 'flex';
            }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i><span>Memverifikasi...</span>';
            }
        });
    }

    // Enter key submission
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
            const form = document.getElementById('authLoginForm');
            if (form && !loadingScreen.style.display.includes('flex')) {
                form.requestSubmit ? form.requestSubmit() : form.submit();
            }
        }
    });
</script>

<!-- SweetAlert2 Alert Integration -->
@include('vendor.sweetalert.alert')

</body>
</html>