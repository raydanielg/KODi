<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How it Works - {{ config('app.name', 'Manna') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            min-height: 100vh;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            z-index: 20;
            transition: all 0.3s ease;
        }

        .header:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0.625rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 36px;
            height: 36px;
            background: #10B981;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-brand-icon svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        .nav-brand-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        .nav-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-back:hover {
            background: #e5e7eb;
            color: #10B981;
        }

        .nav-back svg {
            width: 20px;
            height: 20px;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 8rem 2rem 4rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 4rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 1.25rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .step-card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            animation: fadeInUp 0.8s ease-out both;
        }

        .step-card:nth-child(1) { animation-delay: 0.1s; }
        .step-card:nth-child(2) { animation-delay: 0.2s; }
        .step-card:nth-child(3) { animation-delay: 0.3s; }
        .step-card:nth-child(4) { animation-delay: 0.4s; }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .step-icon {
            width: 60px;
            height: 60px;
            background: #f0fdf4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .step-icon svg {
            width: 30px;
            height: 30px;
            color: #10B981;
        }

        .step-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .step-description {
            font-size: 0.95rem;
            color: #6b7280;
            line-height: 1.6;
        }

        .cta-section {
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            color: #fff;
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }

        .cta-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #fff;
            color: #10B981;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .steps-container {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 7rem 1.5rem 3rem;
            }

            .cta-section {
                padding: 2rem;
            }

            .cta-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="header">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                        <path d="M2 17L12 22L22 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <path d="M2 12L12 17L22 12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <span class="nav-brand-name">{{ config('app.name', 'Manna') }}</span>
            </a>
            <a href="{{ url('/') }}" class="nav-back">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">How it Works</h1>
            <p class="page-subtitle">Find your perfect home in just 4 simple steps</p>
        </div>

        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="step-title">Search Properties</h3>
                <p class="step-description">Browse through our extensive collection of rental properties across Africa. Use filters to find exactly what you're looking for.</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="step-title">View Details</h3>
                <p class="step-description">Explore property details, photos, amenities, and neighborhood information. Take virtual tours from the comfort of your home.</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="step-title">Contact Landlord</h3>
                <p class="step-description">Connect directly with property owners or verified agents. Schedule viewings and ask questions about the property.</p>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="step-title">Move In</h3>
                <p class="step-description">Complete your rental agreement online, make secure payments, and get ready to move into your new home.</p>
            </div>
        </div>

        <div class="cta-section">
            <h2 class="cta-title">Ready to Find Your Home?</h2>
            <p class="cta-subtitle">Join thousands of happy tenants who found their perfect rental with Manna</p>
            <a href="{{ route('register') }}" class="cta-button">
                Get Started
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</body>
</html>
