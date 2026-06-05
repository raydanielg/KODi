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
        .nav-menu { display: none; width: 100%; order: 2; margin-top: 0; position: absolute; top: 100%; left: 0; right: 0; background: #ffffff; border-bottom: 1px solid #e5e7eb; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); transform: translateY(-10px); opacity: 0; visibility: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; }
        .nav-menu.active { display: block; transform: translateY(0); opacity: 1; visibility: visible; }
        .nav-menu ul { display: flex; flex-direction: column; padding: 1rem; font-weight: 500; list-style: none; margin: 0; }
        .nav-menu ul li { border-bottom: 1px solid #f3f4f6; }
        .nav-menu ul li:last-child { border-bottom: none; }
        .nav-menu ul li a { display: block; padding: 1rem; color: #374151; text-decoration: none; transition: all 0.2s ease; border-radius: 8px; }
        .nav-menu ul li a:hover { background: #f9fafb; color: #10B981; transform: translateX(5px); }
        .nav-menu ul li a.active { color: #10B981; background: #f0fdf4; }
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
            animation: fadeInDown 0.8s ease-out 0.2s both;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-badge:hover {
            background: #e5e7eb;
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
        }

        .hero-title-text span {
            display: inline-block;
            margin-right: 0.3em;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
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
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .download-btn:hover {
            background: #1f2937;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .download-btn svg {
            width: 24px;
            height: 24px;
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
        }

        .download-btn-text span:last-child {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .coming-soon-section {
            margin-top: 3rem;
            text-align: center;
            animation: fadeInUp 1s ease-out 1.4s both;
        }

        .coming-soon-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            backdrop-filter: blur(10px);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
        }

        .coming-soon-badge svg {
            width: 18px;
            height: 18px;
        }

        .newsletter-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .newsletter-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .newsletter-form {
            display: flex;
            gap: 0.75rem;
        }

        .newsletter-input {
            flex: 1;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }

        .newsletter-input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .newsletter-input::placeholder {
            color: #9ca3af;
        }

        .newsletter-btn {
            padding: 0.875rem 1.5rem;
            background: #10B981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .newsletter-btn:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
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
            .newsletter-form { flex-direction: column; }
            .newsletter-btn { width: 100%; }
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
                <ul>
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <a href="#" class="hero-badge">
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
                            <path d="M3 20.5v-17c0-.83.67-1.5 1.5-1.5h15c.83 0 1.5.67 1.5 1.5v17c0 .83-.67 1.5-1.5 1.5h-15c-.83 0-1.5-.67-1.5-1.5zm6-11.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5 1.5zm2.5 5c0-1.67-1.33-3-3-3s-3 1.33-3 3 1.33 3 3 3 3-1.33 3-3zm-5.5 5h11v-1h-11v1z"/>
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

            <div class="coming-soon-section">
                <div class="coming-soon-badge">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Coming Soon</span>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const navMenu = document.getElementById('nav-menu');
            const newsletterForm = document.getElementById('newsletter-form');
            const newsletterEmail = document.getElementById('newsletter-email');
            const newsletterBtn = document.getElementById('newsletter-btn');

            // Hamburger menu toggle
            if (hamburgerBtn && navMenu) {
                hamburgerBtn.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    hamburgerBtn.classList.toggle('active');
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
                        wordSpan.style.transform = 'translateY(30px) scale(0.8)';
                        wordSpan.style.display = 'inline-block';
                        wordSpan.style.marginRight = '0.4em';
                        wordSpan.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                        heroTitleText.appendChild(wordSpan);

                        // Trigger animation with slight delay
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                wordSpan.style.opacity = '1';
                                wordSpan.style.transform = 'translateY(0) scale(1)';
                            }, 50);
                        });
                    }, index * 200);
                });

                // After all words are animated, wait then move to next title
                setTimeout(() => {
                    // Fade out all words in reverse order
                    const wordSpans = heroTitleText.querySelectorAll('span');
                    wordSpans.forEach((span, index) => {
                        setTimeout(() => {
                            span.style.opacity = '0';
                            span.style.transform = 'translateY(-30px) scale(0.8)';
                        }, (wordSpans.length - 1 - index) * 100);
                    });

                    // Move to next title
                    setTimeout(() => {
                        currentTitleIndex = (currentTitleIndex + 1) % titles.length;
                        isAnimating = false;
                    }, wordSpans.length * 100 + 400);
                }, words.length * 200 + 2500);
            }

            // Start carousel after initial animation
            setTimeout(() => {
                setInterval(animateWordByWord, 7000);
                animateWordByWord();
            }, 2500);

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
            document.querySelectorAll('.newsletter-section, .download-app-section, .coming-soon-section').forEach(el => {
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
