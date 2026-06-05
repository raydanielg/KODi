<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Property Details - {{ config('app.name', 'Manna') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .property-hero {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .property-gallery {
            position: relative;
            height: 500px;
            background: linear-gradient(135deg, #10B981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 6rem;
        }

        .property-gallery::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.8), rgba(5, 150, 105, 0.8));
        }

        .property-gallery span {
            position: relative;
            z-index: 1;
        }

        .gallery-nav {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .gallery-dot {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .gallery-dot.active {
            background: #fff;
            transform: scale(1.2);
        }

        .property-info {
            padding: 2.5rem;
        }

        .property-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .property-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.02em;
            flex: 1;
        }

        .property-price {
            font-size: 2rem;
            font-weight: 800;
            color: #10B981;
            letter-spacing: -0.02em;
        }

        .property-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .property-location svg {
            width: 20px;
            height: 20px;
            color: #10B981;
        }

        .property-features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .feature-item {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: #f0fdf4;
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .feature-icon svg {
            width: 24px;
            height: 24px;
            color: #fff;
        }

        .feature-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .feature-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .main-content {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.5rem;
            letter-spacing: -0.01em;
        }

        .description {
            color: #6b7280;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .amenity-item:hover {
            background: #f0fdf4;
        }

        .amenity-item svg {
            width: 24px;
            height: 24px;
            color: #10B981;
        }

        .amenity-item span {
            font-size: 1rem;
            color: #374151;
            font-weight: 500;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .booking-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 8rem;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
        }

        .form-input {
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-textarea {
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
            resize: vertical;
            min-height: 120px;
        }

        .form-textarea:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .submit-btn {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            letter-spacing: 0.01em;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .contact-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-icon svg {
            width: 20px;
            height: 20px;
            color: #fff;
        }

        .contact-info {
            flex: 1;
        }

        .contact-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .contact-value {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
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
            .content-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }

            .booking-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 7rem 1.5rem 3rem;
            }

            .property-gallery {
                height: 350px;
                font-size: 4rem;
            }

            .property-info {
                padding: 1.5rem;
            }

            .property-header {
                flex-direction: column;
            }

            .property-title {
                font-size: 1.75rem;
            }

            .property-price {
                font-size: 1.5rem;
            }

            .property-features {
                grid-template-columns: repeat(2, 1fr);
            }

            .main-content {
                padding: 1.5rem;
            }

            .booking-card, .contact-card {
                padding: 1.5rem;
            }

            .amenities-grid {
                grid-template-columns: 1fr;
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
            <a href="{{ route('properties') }}" class="nav-back">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Properties
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="property-hero">
            <div class="property-gallery">
                <span>🏠</span>
                <div class="gallery-nav">
                    <div class="gallery-dot active"></div>
                    <div class="gallery-dot"></div>
                    <div class="gallery-dot"></div>
                    <div class="gallery-dot"></div>
                </div>
            </div>
            <div class="property-info">
                <div class="property-header">
                    <h1 class="property-title">Modern 2-Bedroom Apartment</h1>
                    <div class="property-price">$1,200/month</div>
                </div>
                <div class="property-location">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Nairobi, Kenya
                </div>
                <div class="property-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <div class="feature-value">2</div>
                        <div class="feature-label">Bedrooms</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                        </div>
                        <div class="feature-value">2</div>
                        <div class="feature-label">Bathrooms</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </div>
                        <div class="feature-value">1,200</div>
                        <div class="feature-label">Sqft</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="feature-value">2024</div>
                        <div class="feature-label">Built</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="main-content">
                <h2 class="section-title">Description</h2>
                <p class="description">
                    This beautiful modern apartment features spacious rooms, natural lighting, and stunning city views. Perfect for professionals and small families looking for comfort and convenience. The apartment is located in a prime area with easy access to shopping centers, restaurants, and public transportation.
                </p>
                <p class="description">
                    The property has been recently renovated with high-quality finishes, modern appliances, and energy-efficient systems. Enjoy the open-concept living area, gourmet kitchen with granite countertops, and luxurious bathrooms with premium fixtures.
                </p>

                <h2 class="section-title">Amenities</h2>
                <div class="amenities-grid">
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Free WiFi</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Parking</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Security</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Gym Access</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Swimming Pool</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>24/7 Support</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Air Conditioning</span>
                    </div>
                    <div class="amenity-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Balcony</span>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <div class="booking-card">
                    <h2 class="section-title">Book This Property</h2>
                    <form class="booking-form" onsubmit="handleBooking(event)">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-input" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-input" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-input" placeholder="Enter your phone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Move-in Date</label>
                            <input type="date" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea class="form-textarea" placeholder="Tell us about yourself and why you're interested"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Submit Booking Request</button>
                    </form>
                </div>

                <div class="contact-card">
                    <h2 class="section-title">Contact Information</h2>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Phone</div>
                            <div class="contact-value">+254 700 123 456</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Email</div>
                            <div class="contact-value">info@mannaapartments.com</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="contact-info">
                            <div class="contact-label">Address</div>
                            <div class="contact-value">Nairobi, Kenya</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleBooking(event) {
            event.preventDefault();
            
            Swal.fire({
                icon: 'success',
                title: 'Booking Request Sent!',
                text: 'Your booking request has been submitted successfully. We will contact you within 24 hours.',
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

            event.target.reset();
        }
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
