<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons8-logo-16.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons8-logo-50.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons8-logo-50.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #fff;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1760px;
            margin: 0 auto;
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-brand:hover {
            transform: translateY(-1px);
        }

        .nav-brand-icon {
            width: 36px;
            height: 36px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .nav-brand-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #222222;
        }

        @media (max-width: 768px) {
            .nav-brand-icon {
                width: 28px;
                height: 28px;
            }

            .nav-brand-name {
                font-size: 1rem;
            }
        }

        .nav-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: #fff;
            border-radius: 10px;
            text-decoration: none;
            color: #222222;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }

        .nav-back:hover {
            background: #f7f7f7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-back svg {
            width: 18px;
            height: 18px;
        }

        .nav-back-text {
            display: block;
        }

        .container {
            max-width: 1760px;
            margin: 0 auto;
            padding: 5rem 2rem 4rem;
        }

        .gallery-section {
            margin-bottom: 3rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 8px;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
        }

        .gallery-main {
            grid-row: 1 / -1;
            background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
            position: relative;
            overflow: hidden;
        }

        .gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-side {
            background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
            position: relative;
            overflow: hidden;
        }

        .gallery-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .gallery-main:hover .gallery-overlay,
        .gallery-side:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay span {
            color: #fff;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .property-header {
            margin-bottom: 2rem;
        }

        .property-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #222222;
            margin-bottom: 0.5rem;
        }

        .property-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #222222;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .property-location svg {
            width: 18px;
            height: 18px;
        }

        .property-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #222222;
        }

        .property-rating svg {
            width: 18px;
            height: 18px;
            fill: #222222;
        }

        .host-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .host-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .host-avatar {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #FF385C, #E61E4D);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .host-details h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #222222;
            margin-bottom: 0.25rem;
        }

        .host-details p {
            font-size: 0.875rem;
            color: #6a6a6a;
        }

        .host-badge {
            background: #222222;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 5rem;
            margin-bottom: 3rem;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 3rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #222222;
            margin-bottom: 1.5rem;
        }

        .description {
            color: #222222;
            line-height: 1.7;
            font-size: 1rem;
        }

        .amenities-section {
            padding: 2rem 0;
            border-top: 1px solid #e5e7eb;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .amenity-icon {
            width: 24px;
            height: 24px;
            color: #222222;
        }

        .amenity-item span {
            font-size: 1rem;
            color: #222222;
        }

        .rooms-section {
            padding: 2rem 0;
            border-top: 1px solid #e5e7eb;
        }

        .room-item {
            margin-bottom: 2rem;
        }

        .room-item:last-child {
            margin-bottom: 0;
        }

        .room-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #222222;
            margin-bottom: 1rem;
        }

        .room-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
            border-radius: 12px;
            overflow: hidden;
        }

        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar {
            position: sticky;
            top: 5rem;
            height: fit-content;
        }

        .booking-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 1.5rem;
        }

        .booking-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #222222;
        }

        .booking-price span {
            font-size: 1rem;
            font-weight: 400;
            color: #6a6a6a;
        }

        .booking-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 600;
        }

        .booking-rating svg {
            width: 14px;
            height: 14px;
            fill: #222222;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-input {
            padding: 0.75rem 1rem;
            border: 1px solid #b0b0b0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #222222;
        }

        .form-input::placeholder {
            color: #6a6a6a;
        }

        .submit-btn {
            padding: 0.875rem 1.5rem;
            background: #222222;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background: #000000;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .booking-total {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 1rem;
        }

        .booking-total-label {
            font-weight: 700;
            color: #222222;
        }

        .booking-total-value {
            font-weight: 700;
            color: #222222;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .sidebar {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0.75rem 1rem;
            }

            .nav-back {
                padding: 0.625rem;
            }

            .nav-back-text {
                display: none;
            }

            .nav-back svg {
                width: 20px;
                height: 20px;
            }

            .container {
                padding: 4rem 1rem 3rem;
            }

            .gallery-grid {
                height: 300px;
                grid-template-columns: 1fr;
                grid-template-rows: 1fr;
            }

            .gallery-side {
                display: none;
            }

            .property-title {
                font-size: 1.5rem;
            }

            .host-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .amenities-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="header">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <img src="{{ asset('icons8-logo-50.png') }}" alt="Manna" class="nav-brand-icon">
                <span class="nav-brand-name">{{ config('app.name', 'Manna') }}</span>
            </a>
            <a href="{{ route('properties') }}" class="nav-back">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="nav-back-text">Back to Properties</span>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="gallery-section">
            <div class="gallery-grid">
                <div class="gallery-main">
                    <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop" alt="Living Room">
                    <div class="gallery-overlay">
                        <span>Show all photos</span>
                    </div>
                </div>
                <div class="gallery-side">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop" alt="Bedroom">
                    <div class="gallery-overlay">
                        <span>Show all photos</span>
                    </div>
                </div>
                <div class="gallery-side">
                    <img src="https://images.unsplash.com/photo-1560185007-cde436f6a4d0?w=400&h=300&fit=crop" alt="Kitchen">
                    <div class="gallery-overlay">
                        <span>Show all photos</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="property-header">
            <h1 class="property-title">Modern 2-Bedroom Apartment in Nairobi</h1>
            <div class="property-location">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Nairobi, Kenya
            </div>
            <div class="property-rating">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                4.92 · 127 reviews
            </div>
        </div>

        <div class="host-section">
            <div class="host-info">
                <div class="host-avatar">JD</div>
                <div class="host-details">
                    <h3>Hosted by John Doe</h3>
                    <p>11 years hosting · Superhost</p>
                </div>
            </div>
            <div class="host-badge">Superhost</div>
        </div>

        <div class="content-grid">
            <div class="main-content">
                <div class="description-section">
                    <h2 class="section-title">About this space</h2>
                    <p class="description">
                        This beautiful modern apartment features spacious rooms, natural lighting, and stunning city views. Perfect for professionals and small families looking for comfort and convenience. The apartment is located in a prime area with easy access to shopping centers, restaurants, and public transportation.
                    </p>
                    <p class="description">
                        The property has been recently renovated with high-quality finishes, modern appliances, and energy-efficient systems. Enjoy the open-concept living area, gourmet kitchen with granite countertops, and luxurious bathrooms with premium fixtures.
                    </p>
                </div>

                <div class="amenities-section">
                    <h2 class="section-title">What this place offers</h2>
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <span>Mountain view</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                            <span>Kitchen</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span>Fast wifi – 143 Mbps</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Dedicated workspace</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>Free parking on premises</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>40 inch HDTV with Netflix</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01"/>
                            </svg>
                            <span>Elevator</span>
                        </div>
                        <div class="amenity-item">
                            <svg class="amenity-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Free washer – In building</span>
                        </div>
                    </div>
                </div>

                <div class="rooms-section">
                    <h2 class="section-title">Where you'll sleep</h2>
                    <div class="room-item">
                        <h3 class="room-title">Bedroom 1</h3>
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=600&h=400&fit=crop" alt="Bedroom 1">
                        </div>
                    </div>
                    <div class="room-item">
                        <h3 class="room-title">Bedroom 2</h3>
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600&h=400&fit=crop" alt="Bedroom 2">
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="booking-price">$1,200 <span>/ night</span></div>
                        <div class="booking-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.92 · 127 reviews
                        </div>
                    </div>
                    <form class="booking-form" onsubmit="handleBooking(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" class="form-input" placeholder="Check-in" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-input" placeholder="Check-out" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" placeholder="Guests" required>
                        </div>
                        <button type="submit" class="submit-btn">Reserve</button>
                        <div class="booking-total">
                            <span class="booking-total-label">Total</span>
                            <span class="booking-total-value">$1,200</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleBooking(event) {
            event.preventDefault();
            
            @auth
            Swal.fire({
                icon: 'success',
                title: 'Booking Request Sent!',
                text: 'Your booking request has been submitted successfully. We will contact you within 24 hours.',
                confirmButtonColor: '#222222',
                confirmButtonText: 'Great!',
                background: '#fff',
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-custom-title',
                    confirmButton: 'swal-custom-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.reset();
                }
            });
            @else
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'You need to login or register to book this property.',
                showCancelButton: true,
                confirmButtonColor: '#222222',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Login',
                cancelButtonText: 'Register'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}?redirect={{ request()->fullUrl() }}';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = '{{ route('register') }}?redirect={{ request()->fullUrl() }}';
                }
            });
            @endif
        }
    </script>

    <style>
        .swal-custom-popup {
            border-radius: 12px !important;
        }
        .swal-custom-title {
            font-family: 'Inter', sans-serif !important;
            font-weight: 600 !important;
        }
        .swal-custom-button {
            font-family: 'Inter', sans-serif !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
        }
    </style>
</body>
</html>
