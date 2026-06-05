<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>How it Works - {{ config('app.name', 'Manna') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            z-index: 20;
            transition: all 0.3s ease;
        }

        .header:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0.75rem 1rem;
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
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .nav-brand-icon svg {
            width: 22px;
            height: 22px;
            fill: #fff;
        }

        .nav-brand-name {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #1f2937;
        }

        .nav-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-radius: 10px;
            text-decoration: none;
            color: #374151;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .nav-back:hover {
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border-color: #10B981;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
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
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #111827;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #111827, #374151);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.35rem;
            color: #6b7280;
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .role-selector {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .role-btn {
            padding: 1.25rem 2.5rem;
            background: linear-gradient(135deg, #ffffff, #f9fafb);
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 1.05rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #1f2937;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.875rem;
            min-width: 220px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.02em;
            text-transform: none;
        }

        .role-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.12), transparent);
            transition: left 0.6s ease;
        }

        .role-btn:hover::before {
            left: 100%;
        }

        .role-btn svg {
            width: 26px;
            height: 26px;
            transition: all 0.3s ease;
            flex-shrink: 0;
            color: #10B981;
        }

        .role-btn:hover {
            border-color: #10B981;
            color: #10B981;
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, #ffffff, #f0fdf4);
        }

        .role-btn:hover svg {
            transform: scale(1.2) rotate(8deg);
            color: #10B981;
        }

        .role-btn.active {
            background: linear-gradient(135deg, #10B981, #059669);
            border-color: #10B981;
            color: #fff;
            box-shadow: 0 12px 35px rgba(16, 185, 129, 0.45);
            transform: translateY(-3px);
        }

        .role-btn.active svg {
            color: #fff;
        }

        .role-btn.active:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(16, 185, 129, 0.55);
        }

        .role-btn.active:hover svg {
            transform: scale(1.2) rotate(8deg);
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .step-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            animation: fadeInUp 0.8s ease-out both;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10B981, #059669);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .step-card:hover::before {
            transform: scaleX(1);
        }

        .step-card:nth-child(1) { animation-delay: 0.1s; }
        .step-card:nth-child(2) { animation-delay: 0.2s; }
        .step-card:nth-child(3) { animation-delay: 0.3s; }
        .step-card:nth-child(4) { animation-delay: 0.4s; }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            border-color: #10B981;
        }

        .step-number {
            width: 56px;
            height: 56px;
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
            width: 64px;
            height: 64px;
            background: #f0fdf4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            border: 1px solid #d1fae5;
        }

        .step-icon svg {
            width: 32px;
            height: 32px;
            color: #10B981;
        }

        .step-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.75rem;
            letter-spacing: -0.01em;
        }

        .step-description {
            font-size: 1rem;
            color: #6b7280;
            line-height: 1.7;
        }

        .cta-section {
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 24px;
            padding: 3.5rem;
            text-align: center;
            color: #fff;
            animation: fadeInUp 0.8s ease-out 0.5s both;
            box-shadow: 0 20px 60px rgba(16, 185, 129, 0.3);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .cta-subtitle {
            font-size: 1.15rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.125rem 2.5rem;
            background: #fff;
            color: #10B981;
            border: none;
            border-radius: 14px;
            font-size: 1.1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .page-title {
                font-size: 2.75rem;
            }

            .steps-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2.25rem;
            }

            .page-subtitle {
                font-size: 1.1rem;
            }

            .steps-container {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 7rem 1.5rem 3rem;
            }

            .cta-section {
                padding: 2.5rem 1.5rem;
            }

            .cta-title {
                font-size: 1.75rem;
            }

            .cta-subtitle {
                font-size: 1rem;
            }

            .role-selector {
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .role-btn {
                width: auto;
                flex: 1;
                text-align: center;
                min-width: auto;
                padding: 1rem 1.25rem;
                font-size: 0.9rem;
                letter-spacing: 0.01em;
                font-weight: 700;
            }

            .role-btn svg {
                width: 22px;
                height: 22px;
            }

            .nav-brand-name {
                font-size: 1.1rem;
            }

            .nav-brand-icon {
                width: 32px;
                height: 32px;
            }

            .nav-brand-icon svg {
                width: 18px;
                height: 18px;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.85rem;
            }

            .step-card {
                padding: 2rem 1.5rem;
            }

            .step-number {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .step-icon {
                width: 60px;
                height: 60px;
            }

            .step-icon svg {
                width: 30px;
                height: 30px;
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
            <p class="page-subtitle">Find your perfect home in just 4 simple steps. Choose your role to see personalized steps.</p>
        </div>

        <div class="role-selector">
            <button class="role-btn active" data-role="tenant">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                For Tenants
            </button>
            <button class="role-btn" data-role="landlord">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                For Landlords
            </button>
            <button class="role-btn" data-role="agent">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                For Agents
            </button>
        </div>

        <div class="steps-container" id="steps-container">
            <!-- Tenant Steps -->
            <div class="steps-tenant">
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

            <!-- Landlord Steps -->
            <div class="steps-landlord" style="display: none;">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h3 class="step-title">List Your Property</h3>
                    <p class="step-description">Create an account and add your property details. Upload photos, set pricing, and describe amenities to attract tenants.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Verify Your Listing</h3>
                    <p class="step-description">Our team will verify your property to ensure authenticity. This builds trust with potential tenants and increases visibility.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Connect with Tenants</h3>
                    <p class="step-description">Receive inquiries from interested tenants, schedule viewings, and communicate through our secure messaging system.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Receive Payments</h3>
                    <p class="step-description">Manage rental agreements, collect payments securely, and track all transactions through your landlord dashboard.</p>
                </div>
            </div>

            <!-- Agent Steps -->
            <div class="steps-agent" style="display: none;">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Create Agent Profile</h3>
                    <p class="step-description">Register as an agent, complete your profile with credentials, and get verified to start listing properties.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Add Properties</h3>
                    <p class="step-description">List multiple properties on behalf of landlords. Manage all listings from your agent dashboard efficiently.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Engage Clients</h3>
                    <p class="step-description">Communicate with tenants, schedule viewings, and provide excellent service to close deals successfully.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="step-title">Earn Commissions</h3>
                    <p class="step-description">Track your earnings, manage commissions, and grow your real estate business with our comprehensive tools.</p>
                </div>
            </div>
        </div>

        <div class="cta-section">
            <div class="cta-content">
                <h2 class="cta-title" id="cta-title">Ready to Find Your Home?</h2>
                <p class="cta-subtitle" id="cta-subtitle">Join thousands of happy tenants who found their perfect rental with Manna</p>
                <a href="{{ route('register') }}" class="cta-button" id="cta-button">
                    Get Started
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleBtns = document.querySelectorAll('.role-btn');
            const stepsContainer = document.getElementById('steps-container');
            const ctaTitle = document.getElementById('cta-title');
            const ctaSubtitle = document.getElementById('cta-subtitle');
            const ctaButton = document.getElementById('cta-button');

            const ctaContent = {
                tenant: {
                    title: 'Ready to Find Your Home?',
                    subtitle: 'Join thousands of happy tenants who found their perfect rental with Manna',
                    buttonText: 'Get Started'
                },
                landlord: {
                    title: 'Ready to List Your Property?',
                    subtitle: 'Start earning from your property by listing it on Manna today',
                    buttonText: 'List Property'
                },
                agent: {
                    title: 'Ready to Grow Your Business?',
                    subtitle: 'Join our network of successful agents and expand your real estate portfolio',
                    buttonText: 'Become an Agent'
                }
            };

            roleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const role = this.dataset.role;

                    // Update active button
                    roleBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Hide all steps
                    stepsContainer.querySelectorAll('[class^="steps-"]').forEach(el => {
                        el.style.display = 'none';
                    });

                    // Show selected steps
                    const selectedSteps = stepsContainer.querySelector(`.steps-${role}`);
                    if (selectedSteps) {
                        selectedSteps.style.display = 'contents';
                    }

                    // Update CTA content
                    if (ctaContent[role]) {
                        ctaTitle.textContent = ctaContent[role].title;
                        ctaSubtitle.textContent = ctaContent[role].subtitle;
                        ctaButton.innerHTML = `${ctaContent[role].buttonText}
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>`;
                    }
                });
            });
        });
    </script>
</body>
</html>
