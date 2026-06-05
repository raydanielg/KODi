<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        .header { position: fixed; top: 0; left: 0; right: 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.2); z-index: 20; transition: all 0.3s ease; }
        .header:hover { background: rgba(255, 255, 255, 0.15); }
        .nav-container { max-width: 1280px; margin: 0 auto; padding: 1rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; }
        .nav-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .nav-brand-icon { width: 28px; height: 28px; background: #10B981; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-brand-icon svg { width: 16px; height: 16px; fill: #fff; }
        .nav-brand-name { font-size: 1.25rem; font-weight: 600; color: #fff; white-space: nowrap; }
        .nav-buttons { display: flex; gap: 0.75rem; align-items: center; order: 3; }
        .nav-menu { display: none; width: 100%; order: 2; margin-top: 1rem; }
        .nav-menu.active { display: block; }
        .nav-menu ul { display: flex; flex-direction: column; padding: 1rem; font-weight: 500; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; background: rgba(255, 255, 255, 0.1); list-style: none; margin: 0; }
        .nav-menu ul li { margin-bottom: 0.25rem; }
        .nav-menu ul li:last-child { margin-bottom: 0; }
        .nav-menu ul li a { display: block; padding: 0.75rem 1rem; color: #fff; text-decoration: none; border-radius: 6px; transition: all 0.2s ease; }
        .nav-menu ul li a:hover { background: rgba(255, 255, 255, 0.2); }
        .nav-menu ul li a.active { background: #10B981; }
        .hamburger { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 6px; color: rgba(255, 255, 255, 0.9); background: transparent; border: none; cursor: pointer; transition: all 0.2s ease; }
        .hamburger:hover { background: rgba(255, 255, 255, 0.2); }
        @media (min-width: 768px) {
            .nav-container { flex-wrap: nowrap; }
            .nav-buttons { order: 2; }
            .nav-menu { display: block; width: auto; order: 1; margin-top: 0; }
            .nav-menu ul { flex-direction: row; padding: 0; border: none; background: transparent; gap: 2rem; }
            .nav-menu ul li { margin-bottom: 0; }
            .nav-menu ul li a:hover { background: transparent; }
            .hamburger { display: none; }
        }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1.75rem; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 500; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.2s ease; text-decoration: none; }
        .btn-primary { background: #10B981; color: #fff; }
        .btn-primary:hover { background: #059669; transform: translateY(-1px); }
        .btn-secondary { background: rgba(255, 255, 255, 0.2); color: #fff; border: 1px solid rgba(255, 255, 255, 0.3); backdrop-filter: blur(10px); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.3); border-color: rgba(255, 255, 255, 0.5); transform: translateY(-1px); }
        .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 8rem 2rem 4rem; position: relative; overflow: hidden; }

        .hero-content {
            max-width: 900px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #fff;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .hero-badge svg { width: 16px; height: 16px; }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            color: #fff;
            line-height: 1.2;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
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
        }

        .hero-buttons .btn {
            padding: 1rem 2.5rem;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.95);
            color: #10B981;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .hero-buttons .btn:hover {
            background: #fff;
            color: #059669;
            border-color: #fff;
        }

        .hero-buttons .btn-primary {
            background: #fff;
            color: #10B981;
        }

        .hero-buttons .btn-primary:hover {
            background: #f9fafb;
            color: #059669;
        }
        @media (max-width: 768px) { .hero-title { font-size: 2.25rem; } .hero-subtitle { font-size: 1.1rem; } .hero-buttons { flex-direction: column; } .header { padding: 1rem; } .hero { padding: 6rem 1.5rem 3rem; } .newsletter-form { flex-direction: column; } .newsletter-btn { width: 100%; } }
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
            <div class="hero-badge">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Modern Rental Platform</span>
            </div>

            <h1 class="hero-title">Find Your Perfect Home</h1>
            <p class="hero-subtitle">The modern long-term rental platform connecting tenants with landlords across Africa. Simple, secure, and transparent.</p>

            <div class="hero-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
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
