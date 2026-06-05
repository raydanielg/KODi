<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact - {{ config('app.name', 'Manna') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            color: #000;
            -webkit-font-smoothing: antialiased;
        }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ===== LEFT BRAND PANEL ===== */
        .auth-brand-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.75) 0%, rgba(240, 240, 240, 0.75) 100%), url('{{ asset('serious-expert-expressing-support-colleague (1).jpg') }}');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .auth-brand-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(16,185,129,0.06) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(5,150,105,0.04) 0%, transparent 40%);
            animation: breathe 8s ease-in-out infinite alternate;
        }

        @keyframes breathe {
            0% { opacity: 0.5; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.05); }
        }

        .auth-brand-inner {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 2rem 2.5rem;
            justify-content: space-between;
        }

        .auth-brand-top {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg { width: 18px; height: 18px; fill: #fff; }

        .brand-name {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #000;
        }

        .auth-brand-bottom {
            border-top: 1px solid #e5e7eb;
            padding-top: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .auth-brand-links {
            display: flex;
            gap: 1.5rem;
        }

        .auth-brand-links a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .auth-brand-links a:hover { color: #000; }

        .auth-brand-copy {
            color: #9ca3af;
            font-size: 0.72rem;
        }

        .auth-brand-copy span { color: #6b7280; }

        /* ===== RIGHT FORM PANEL ===== */
        .auth-form-side {
            width: 540px;
            min-width: 540px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3.5rem;
            background: #ffffff;
            border-left: 1px solid #e5e7eb;
            position: relative;
            box-shadow: -5px 0 25px rgba(0,0,0,0.03);
        }

        .auth-header {
            margin-bottom: 2.5rem;
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: #000;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .auth-header a {
            color: #10B981;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .auth-header a:hover { color: #059669; }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
            background: #fff;
        }

        .form-input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-button {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .form-button:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .form-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #10B981;
        }

        .back-link svg {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 1024px) {
            .auth-brand-side { display: none; }
            .auth-form-side { width: 100%; min-width: 100%; border-left: none; }
        }

        @media (max-width: 640px) {
            .auth-form-side { padding: 2rem 1.5rem; }
            .auth-header h1 { font-size: 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- LEFT BRAND PANEL -->
        <div class="auth-brand-side">
            <div class="auth-brand-inner">
                <div class="auth-brand-top">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                            <path d="M2 17L12 22L22 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            <path d="M2 12L12 17L22 12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </svg>
                    </div>
                    <span class="brand-name">{{ config('app.name', 'Manna') }}</span>
                </div>

                <div class="auth-brand-bottom">
                    <div class="auth-brand-links">
                        <a href="{{ route('privacy') }}">Privacy Policy</a>
                        <a href="{{ route('terms') }}">Terms of Service</a>
                        <a href="{{ route('contact') }}">Contact</a>
                    </div>
                    <p class="auth-brand-copy">
                        © {{ date('Y') }} {{ config('app.name', 'Manna') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM PANEL -->
        <div class="auth-form-side">
            <a href="{{ url('/') }}" class="back-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>

            <div class="auth-header">
                <h1>Contact Us</h1>
                <p>Have a question or feedback? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>

            <form id="contact-form">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" id="name" name="name" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" id="email" name="email" placeholder="john@example.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-input" id="subject" name="subject" placeholder="How can we help?" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-input form-textarea" id="message" name="message" placeholder="Tell us more..." required></textarea>
                </div>
                <button type="submit" class="form-button" id="submit-btn">Send Message</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contact-form');
            const submitBtn = document.getElementById('submit-btn');

            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(contactForm);
                const originalBtnText = submitBtn.textContent;

                // Show loading state
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;

                fetch('/contact', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitBtn.textContent = originalBtnText;
                    submitBtn.disabled = false;

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Message Sent!',
                            text: 'Thank you for contacting us. We will get back to you soon.',
                            confirmButtonColor: '#10B981',
                            confirmButtonText: 'Great!',
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdrop: 'rgba(0, 0, 0, 0.5)',
                            customClass: {
                                popup: 'swal-custom-popup',
                                title: 'swal-custom-title',
                                confirmButton: 'swal-custom-button'
                            }
                        });
                        contactForm.reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Something went wrong. Please try again.',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'OK',
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdrop: 'rgba(0, 0, 0, 0.5)',
                            customClass: {
                                popup: 'swal-custom-popup',
                                title: 'swal-custom-title',
                                confirmButton: 'swal-custom-button'
                            }
                        });
                    }
                })
                .catch(error => {
                    // Reset button
                    submitBtn.textContent = originalBtnText;
                    submitBtn.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK',
                        background: 'rgba(255, 255, 255, 0.95)',
                        backdrop: 'rgba(0, 0, 0, 0.5)',
                        customClass: {
                            popup: 'swal-custom-popup',
                            title: 'swal-custom-title',
                            confirmButton: 'swal-custom-button'
                        }
                    });
                });
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
            font-weight: 600 !important;
        }
        .swal-custom-button {
            font-family: 'Inter', sans-serif !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            padding: 0.75rem 1.5rem !important;
        }
    </style>
</body>
</html>
