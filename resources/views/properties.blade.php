<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - {{ config('app.name', 'Manna') }}</title>
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
            margin-bottom: 3rem;
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

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .property-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .property-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .property-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #10B981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 3rem;
        }

        .property-content {
            padding: 1.5rem;
        }

        .property-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10B981;
            margin-bottom: 0.5rem;
        }

        .property-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .property-location {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .property-location svg {
            width: 16px;
            height: 16px;
        }

        .property-features {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .feature svg {
            width: 16px;
            height: 16px;
            color: #10B981;
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

            .properties-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 7rem 1.5rem 3rem;
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
            <h1 class="page-title">Explore Properties</h1>
            <p class="page-subtitle">Discover your perfect home from our curated selection of rental properties across Africa</p>
        </div>

        <div class="properties-grid">
            <div class="property-card">
                <div class="property-image">🏠</div>
                <div class="property-content">
                    <div class="property-price">$1,200/month</div>
                    <h3 class="property-title">Modern 2-Bedroom Apartment</h3>
                    <div class="property-location">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Nairobi, Kenya
                    </div>
                    <div class="property-features">
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            2 Beds
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                            2 Baths
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            1,200 sqft
                        </div>
                    </div>
                </div>
            </div>

            <div class="property-card">
                <div class="property-image">🏢</div>
                <div class="property-content">
                    <div class="property-price">$2,500/month</div>
                    <h3 class="property-title">Luxury Penthouse Suite</h3>
                    <div class="property-location">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lagos, Nigeria
                    </div>
                    <div class="property-features">
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            4 Beds
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                            3 Baths
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            2,500 sqft
                        </div>
                    </div>
                </div>
            </div>

            <div class="property-card">
                <div class="property-image">🏡</div>
                <div class="property-content">
                    <div class="property-price">$1,800/month</div>
                    <h3 class="property-title">Cozy Family Home</h3>
                    <div class="property-location">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Accra, Ghana
                    </div>
                    <div class="property-features">
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            3 Beds
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                            2 Baths
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            1,800 sqft
                        </div>
                    </div>
                </div>
            </div>

            <div class="property-card">
                <div class="property-image">🏗️</div>
                <div class="property-content">
                    <div class="property-price">$3,000/month</div>
                    <h3 class="property-title">Commercial Office Space</h3>
                    <div class="property-location">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Johannesburg, South Africa
                    </div>
                    <div class="property-features">
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            5 Rooms
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                            2 Baths
                        </div>
                        <div class="feature">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            3,000 sqft
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
