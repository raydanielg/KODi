<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The modern long-term rental platform connecting tenants with landlords across Africa. Simple, secure, and transparent.">
    <meta name="theme-color" content="#10B981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Manna">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
    <title>{{ config('app.name', 'Manna') }} - Find Your Perfect Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: linear-gradient(-45deg, #10B981, #059669, #047857, #065f46); background-size: 400% 400%; animation: gradientBG 15s ease infinite; min-height: 100vh; color: #1f2937; -webkit-font-smoothing: antialiased; }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .header { position: fixed; top: 0; left: 0; right: 0; background: #ffffff; border-bottom: 1px solid #e5e7eb; z-index: 20; transition: all 0.3s ease; }
        .header:hover { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .nav-container { max-width: 1280px; margin: 0 auto; padding: 0.625rem 1rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; }
        .nav-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .nav-brand-icon { width: 36px; height: 36px; background: #10B981; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-brand-icon svg { width: 20px; height: 20px; fill: #fff; }
        .nav-brand-name { font-size: 1.25rem; font-weight: 600; color: #1f2937; white-space: nowrap; }
        .nav-buttons { display: flex; gap: 0.5rem; align-items: center; order: 3; }
        .nav-menu { display: none; width: 300px; max-width: 85vw; position: fixed; top: 0; right: -300px; height: 100vh; background: #ffffff; box-shadow: -10px 0 40px rgba(0, 0, 0, 0.15); transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1000; overflow-y: auto; }
        .nav-menu.active { display: block; right: 0; }
        .nav-menu-header { padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; background: #ffffff; z-index: 10; }
        .nav-menu-title { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .nav-menu-close { width: 40px; height: 40px; border-radius: 50%; border: none; background: #f3f4f6; color: #6b7280; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
        .nav-menu-close:hover { background: #e5e7eb; color: #111827; }
        .nav-menu-close svg { width: 20px; height: 20px; }
        .nav-menu ul { display: flex; flex-direction: column; padding: 1rem; font-weight: 500; list-style: none; margin: 0; }
        .nav-menu ul li { margin-bottom: 0.5rem; }
        .nav-menu ul li:last-child { margin-bottom: 0; }
        .nav-menu ul li a { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; color: #374151; text-decoration: none; transition: all 0.3s ease; border-radius: 12px; font-size: 1rem; }
        .nav-menu ul li a svg { width: 20px; height: 20px; color: #6b7280; transition: color 0.3s ease; }
        .nav-menu ul li a:hover { background: #f0fdf4; color: #10B981; transform: translateX(-5px); }
        .nav-menu ul li a:hover svg { color: #10B981; }
        .nav-menu ul li a.active { color: #10B981; background: #f0fdf4; }
        .nav-menu ul li a.active svg { color: #10B981; }
        .nav-menu-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 999; opacity: 0; transition: opacity 0.3s ease; }
        .nav-menu-overlay.active { display: block; opacity: 1; }
        .hamburger { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; color: #6b7280; background: transparent; border: none; cursor: pointer; transition: all 0.3s ease; position: relative; z-index: 101; }
        .hamburger:hover { background: #f3f4f6; transform: rotate(180deg); }
        .hamburger.active { background: #f3f4f6; }
        .hamburger svg { transition: transform 0.3s ease; }
        .hamburger.active svg { transform: rotate(90deg); }
        @media (min-width: 1024px) {
            .nav-container { flex-wrap: nowrap; }
            .nav-buttons { order: 2; }
            .nav-menu { display: flex; width: auto; order: 1; margin-top: 0; }
            .nav-menu ul { flex-direction: row; padding: 0; border: none; background: transparent; gap: 2rem; }
            .nav-menu ul li { border-bottom: none; }
            .nav-menu ul li a:hover { background: transparent; }
            .hamburger { display: none; }
        }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.2s ease; text-decoration: none; }
        .btn-primary { background: #10B981; color: #fff; }
        .btn-primary:hover { background: #059669; }
        .btn-secondary { background: transparent; color: #1f2937; border: 1px solid transparent; }
        .btn-secondary:hover { background: #f9fafb; color: #10B981; }
        .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 8rem 2rem 4rem; position: relative; overflow: hidden; background: linear-gradient(-45deg, #10B981, #059669, #047857, #065f46), url('{{ asset('serious-expert-expressing-support-colleague (1).jpg') }}'); background-size: cover; background-position: center; background-blend-mode: overlay; }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(16, 185, 129, 0.85);
            z-index: 0;
        }

        .hero-content {
            max-width: 900px;
            text-align: center;
            position: relative;
            z-index: 1;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.25rem 0.25rem 1rem;
            background: #f3f4f6;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 2rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .hero-badge:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hero-badge .badge-new {
            background: #10B981;
            color: #fff;
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.75rem;
        }

        .hero-badge svg {
            width: 20px;
            height: 20px;
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }

        .hero-badge.active svg {
            transform: rotate(180deg);
        }

        .notification-panel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            max-width: 90vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: -10px 0 40px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }

        .notification-panel.active {
            right: 0;
        }

        .notification-panel-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            z-index: 10;
        }

        .notification-panel-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        .notification-panel-close {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #f3f4f6;
            color: #6b7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .notification-panel-close:hover {
            background: #e5e7eb;
            color: #111827;
        }

        .notification-panel-close svg {
            width: 20px;
            height: 20px;
        }

        .notification-panel-content {
            padding: 1.5rem;
        }

        .notification-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-color: #10B981;
        }

        .notification-item-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .notification-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f0fdf4;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-item-icon svg {
            width: 20px;
            height: 20px;
            color: #10B981;
        }

        .notification-item-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
        }

        .notification-item-time {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-left: auto;
        }

        .notification-item-description {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.5;
        }

        .notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .notification-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            color: #fff;
            line-height: 1.2;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-out 0.4s both;
            min-height: 4.2rem;
        }

        .hero-title-text {
            display: inline-block;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .hero-title-text span {
            display: inline-block;
            margin-right: 0.5em;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .hero-title-text span:last-child {
            margin-right: 0;
        }

        .hero-title-text.fade-out {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }

        .hero-title-text.fade-in {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .hero-subtitle {
            font-size: 1.35rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 0 1px 10px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .newsletter-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            margin-top: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 1s both;
        }

        .download-app-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 1.2s both;
        }

        .download-app-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .download-app-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .download-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #1f2937, #111827);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .download-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .download-btn:hover::before {
            left: 100%;
        }

        .download-btn:hover {
            background: linear-gradient(135deg, #374151, #1f2937);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .download-btn svg {
            width: 32px;
            height: 32px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .download-btn-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.2;
        }

        .download-btn-text span:first-child {
            font-size: 0.65rem;
            opacity: 0.8;
            letter-spacing: 0.05em;
        }

        .download-btn-text span:last-child {
            font-size: 1rem;
            font-weight: 600;
        }

        .newsletter-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .newsletter-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .newsletter-form {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .newsletter-input {
            flex: 1;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
            background: #fff;
        }

        .newsletter-input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            transform: translateY(-1px);
        }

        .newsletter-input::placeholder {
            color: #9ca3af;
        }

        .newsletter-btn {
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .newsletter-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .newsletter-btn:active {
            transform: translateY(0);
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .hero-buttons .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.95);
            color: #10B981;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .hero-buttons .btn:hover {
            background: #fff;
            color: #059669;
            border-color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .hero-buttons .btn-primary {
            background: #fff;
            color: #10B981;
        }

        .hero-buttons .btn-primary:hover {
            background: #f9fafb;
            color: #059669;
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2rem; font-weight: 700; }
            .hero-subtitle { font-size: 1rem; line-height: 1.6; }
            .hero-buttons { flex-direction: row; flex-wrap: wrap; }
            .header { padding: 0.75rem 1rem; }
            .hero { padding: 7rem 1.5rem 3rem; }
            .newsletter-form { flex-direction: row; }
            .newsletter-btn { padding: 0.875rem 1.5rem; }
            .hero-badge { font-size: 0.75rem; padding: 0.2rem 0.2rem 0.2rem 0.75rem; }
            .hero-badge .badge-new { padding: 0.25rem 0.5rem; font-size: 0.65rem; }
            .hero-badge svg { width: 16px; height: 16px; }
            .nav-brand-name { font-size: 1.1rem; }
            .nav-brand-icon { width: 32px; height: 32px; }
            .nav-brand-icon svg { width: 16px; height: 16px; }
        }
    </style>
</head>
<body>
    <nav class="header">
        <div class="nav-container">
            <a href="#" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                        <path d="M2 17L12 22L22 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <path d="M2 12L12 17L22 12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <span class="nav-brand-name">{{ config('app.name', 'Manna') }}</span>
            </a>

            <div class="nav-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    @endif
                @endif
                <button class="hamburger" id="hamburger-btn" aria-label="Open menu">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
                </button>
            </div>

            <div class="nav-menu" id="nav-menu">
                <div class="nav-menu-header">
                    <span class="nav-menu-title">Menu</span>
                    <button class="nav-menu-close" id="nav-menu-close">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <ul>
                    <li><a href="#" class="active">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </a></li>
                    <li><a href="#">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Properties
                    </a></li>
                    <li><a href="#">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        How it Works
                    </a></li>
                    <li><a href="#">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        FAQ
                    </a></li>
                    <li><a href="{{ route('privacy') }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Privacy Policy
                    </a></li>
                    <li><a href="{{ route('terms') }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Terms of Service
                    </a></li>
                    <li><a href="#">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact
                    </a></li>
                </ul>
            </div>
            <div class="nav-menu-overlay" id="nav-menu-overlay"></div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <a href="#" class="hero-badge" id="notification-badge">
                <span class="badge-new">New</span>
                <span class="text-sm font-medium">Manna is out! See what's new</span>
                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
            </a>

            <h1 class="hero-title">
                <span class="hero-title-text" id="hero-title-text">Find Your Perfect Home</span>
            </h1>
            <p class="hero-subtitle">The modern long-term rental platform connecting tenants with landlords across Africa. Simple, secure, and transparent.</p>

            <div class="hero-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Create Account <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-left: 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                        <a href="{{ route('login') }}" class="btn btn-secondary">Sign In</a>
                    @endauth
                @endif
            </div>

            <div class="newsletter-section">
                <h3 class="newsletter-title">Stay Updated</h3>
                <p class="newsletter-subtitle">Subscribe to get notified about new listings and updates</p>
                <form class="newsletter-form" id="newsletter-form" action="#" method="POST">
                    @csrf
                    <input type="email" class="newsletter-input" id="newsletter-email" placeholder="Enter your email" required>
                    <button type="submit" class="newsletter-btn" id="newsletter-btn">Subscribe</button>
                </form>
            </div>

            <div class="download-app-section">
                <h3 class="download-app-title">Download App Now</h3>
                <p class="download-app-subtitle">Get the full experience on your mobile device</p>
                <div class="download-buttons">
                    <a href="#" class="download-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 01-.61-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.198l2.807 1.626a1 1 0 010 1.73l-2.808 1.626L15.206 12l2.492-2.491zM5.864 2.658L16.8 8.99l-2.302 2.302-8.634-8.634z"/>
                        </svg>
                        <div class="download-btn-text">
                            <span>GET IT ON</span>
                            <span>Google Play</span>
                        </div>
                    </a>
                    <a href="#" class="download-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <div class="download-btn-text">
                            <span>Download on the</span>
                            <span>App Store</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Notification Panel -->
    <div class="notification-overlay" id="notification-overlay"></div>
    <div class="notification-panel" id="notification-panel">
        <div class="notification-panel-header">
            <h3 class="notification-panel-title">Notifications</h3>
            <button class="notification-panel-close" id="notification-close">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="notification-panel-content">
            <div class="notification-item">
                <div class="notification-item-header">
                    <div class="notification-item-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="notification-item-title">New Feature Available</span>
                    <span class="notification-item-time">2 min ago</span>
                </div>
                <p class="notification-item-description">We've just launched our new advanced search feature to help you find your perfect home faster!</p>
            </div>
            <div class="notification-item">
                <div class="notification-item-header">
                    <div class="notification-item-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="notification-item-title">Welcome to Manna</span>
                    <span class="notification-item-time">1 hour ago</span>
                </div>
                <p class="notification-item-description">Thank you for joining Manna! Start exploring thousands of rental properties across Africa.</p>
            </div>
            <div class="notification-item">
                <div class="notification-item-header">
                    <div class="notification-item-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="notification-item-title">Mobile App Coming Soon</span>
                    <span class="notification-item-time">3 hours ago</span>
                </div>
                <p class="notification-item-description">Our mobile app is under development. Stay tuned for the official launch on iOS and Android!</p>
            </div>
            <div class="notification-item">
                <div class="notification-item-header">
                    <div class="notification-item-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="notification-item-title">Community Growing</span>
                    <span class="notification-item-time">1 day ago</span>
                </div>
                <p class="notification-item-description">We've reached 10,000 users! Join our growing community of renters and landlords.</p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const navMenu = document.getElementById('nav-menu');
            const navMenuClose = document.getElementById('nav-menu-close');
            const navMenuOverlay = document.getElementById('nav-menu-overlay');
            const newsletterForm = document.getElementById('newsletter-form');
            const newsletterEmail = document.getElementById('newsletter-email');
            const newsletterBtn = document.getElementById('newsletter-btn');
            const notificationBadge = document.getElementById('notification-badge');
            const notificationPanel = document.getElementById('notification-panel');
            const notificationOverlay = document.getElementById('notification-overlay');
            const notificationClose = document.getElementById('notification-close');

            // Mobile menu toggle
            if (hamburgerBtn && navMenu && navMenuClose && navMenuOverlay) {
                hamburgerBtn.addEventListener('click', function() {
                    navMenu.classList.add('active');
                    navMenuOverlay.classList.add('active');
                    hamburgerBtn.classList.add('active');
                });

                navMenuClose.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    navMenuOverlay.classList.remove('active');
                    hamburgerBtn.classList.remove('active');
                });

                navMenuOverlay.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    navMenuOverlay.classList.remove('active');
                    hamburgerBtn.classList.remove('active');
                });
            }

            // Notification panel toggle
            if (notificationBadge && notificationPanel && notificationOverlay && notificationClose) {
                notificationBadge.addEventListener('click', function(e) {
                    e.preventDefault();
                    notificationBadge.classList.toggle('active');
                    notificationPanel.classList.toggle('active');
                    notificationOverlay.classList.toggle('active');
                });

                notificationClose.addEventListener('click', function() {
                    notificationBadge.classList.remove('active');
                    notificationPanel.classList.remove('active');
                    notificationOverlay.classList.remove('active');
                });

                notificationOverlay.addEventListener('click', function() {
                    notificationBadge.classList.remove('active');
                    notificationPanel.classList.remove('active');
                    notificationOverlay.classList.remove('active');
                });
            }

            // Newsletter form AJAX submission
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const email = newsletterEmail.value;
                    const originalBtnText = newsletterBtn.textContent;

                    // Show loading state
                    newsletterBtn.textContent = 'Subscribing...';
                    newsletterBtn.disabled = true;

                    // Simulate AJAX request
                    setTimeout(function() {
                        // Reset button
                        newsletterBtn.textContent = originalBtnText;
                        newsletterBtn.disabled = false;

                        // Show success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully Subscribed!',
                            text: 'Thank you for subscribing to our newsletter. You will receive updates about new listings.',
                            confirmButtonColor: '#10B981',
                            confirmButtonText: 'Great!',
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdrop: 'rgba(0, 0, 0, 0.5)',
                            customClass: {
                                popup: 'swal-custom-popup',
                                title: 'swal-custom-title',
                                content: 'swal-custom-content'
                            }
                        });

                        // Clear form
                        newsletterForm.reset();
                    }, 1500);
                });
            }

            // Register Service Worker for PWA
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(function(registration) {
                            console.log('Service Worker registered with scope:', registration.scope);
                        })
                        .catch(function(error) {
                            console.log('Service Worker registration failed:', error);
                        });
                });
            }

            // Hero Title Carousel - Word by Word Animation
            const heroTitleText = document.getElementById('hero-title-text');
            const titles = [
                'Find Your Perfect Home',
                'Discover Your Dream Space',
                'Live Your Best Life',
                'Your Journey Starts Here'
            ];
            let currentTitleIndex = 0;
            let isAnimating = false;

            function animateWordByWord() {
                if (!heroTitleText || isAnimating) return;
                isAnimating = true;

                const currentTitle = titles[currentTitleIndex];
                const words = currentTitle.split(' ');

                // Clear current text
                heroTitleText.innerHTML = '';
                heroTitleText.style.opacity = '1';

                // Animate words one by one with proper spacing
                words.forEach((word, index) => {
                    setTimeout(() => {
                        const wordSpan = document.createElement('span');
                        wordSpan.textContent = word;
                        wordSpan.style.opacity = '0';
                        wordSpan.style.transform = 'translateY(20px)';
                        wordSpan.style.display = 'inline-block';
                        wordSpan.style.marginRight = '0.5em';
                        wordSpan.style.transition = 'all 0.4s ease-out';
                        heroTitleText.appendChild(wordSpan);

                        // Trigger animation
                        setTimeout(() => {
                            wordSpan.style.opacity = '1';
                            wordSpan.style.transform = 'translateY(0)';
                        }, 50);
                    }, index * 150);
                });

                // After all words are animated, wait then move to next title
                setTimeout(() => {
                    // Fade out all words together
                    const wordSpans = heroTitleText.querySelectorAll('span');
                    wordSpans.forEach(span => {
                        span.style.opacity = '0';
                        span.style.transform = 'translateY(-20px)';
                    });

                    // Move to next title
                    setTimeout(() => {
                        currentTitleIndex = (currentTitleIndex + 1) % titles.length;
                        isAnimating = false;
                    }, 400);
                }, words.length * 150 + 2000);
            }

            // Start carousel after initial animation
            setTimeout(() => {
                setInterval(animateWordByWord, 6000);
                animateWordByWord();
            }, 2000);

            // Scroll-based animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe elements for scroll animations
            document.querySelectorAll('.newsletter-section, .download-app-section').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
            });
        });
    </script>

    <style>
        .swal-custom-popup {
            border-radius: 16px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        .swal-custom-title {
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
            color: #111827 !important;
        }
        .swal-custom-content {
            font-family: 'Inter', sans-serif !important;
            color: #6b7280 !important;
        }
    </style>
</body>
</html>
