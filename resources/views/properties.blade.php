<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Properties - {{ config('app.name', 'Manna') }}</title>
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
        }

        .nav-container {
            max-width: 1760px;
            margin: 0 auto;
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #FF385C;
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
        }

        .nav-brand svg {
            width: 32px;
            height: 32px;
            fill: #FF385C;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 40px;
            padding: 0.5rem 0.5rem 0.5rem 1.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            max-width: 850px;
            width: 100%;
        }

        .search-bar:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        }

        .search-item {
            flex: 1;
            padding: 0.5rem 1rem;
            border-right: 1px solid #e5e7eb;
            cursor: pointer;
        }

        .search-item:last-child {
            border-right: none;
        }

        .search-item:hover {
            background: #f7f7f7;
            border-radius: 32px;
        }

        .search-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #717171;
            margin-bottom: 0.25rem;
        }

        .search-value {
            font-size: 0.875rem;
            color: #222222;
            font-weight: 500;
        }

        .search-button {
            background: linear-gradient(90deg, #E61E4D, #E31C5F);
            color: #fff;
 border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-button:hover {
            transform: scale(1.05);
        }

        .search-button svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-link {
            padding: 0.5rem 1rem;
            color: #222222;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 24px;
            transition: all 0.2s ease;
        }

        .user-link:hover {
            background: #f7f7f7;
        }

        .categories-bar {
            position: fixed;
            top: 73px;
            left: 0;
            right: 0;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            z-index: 90;
            padding: 1rem 0;
        }

        .categories-container {
            max-width: 1760px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            gap: 2rem;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .categories-container::-webkit-scrollbar {
            display: none;
        }

        .category-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 64px;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .category-item:hover {
            border-color: #e5e7eb;
        }

        .category-item.active {
            border-color: #222222;
            border-bottom: 2px solid #222222;
        }

        .category-icon {
            width: 24px;
            height: 24px;
            color: #222222;
        }

        .category-label {
            font-size: 0.75rem;
            color: #222222;
            font-weight: 500;
            white-space: nowrap;
        }

        .container {
            max-width: 1760px;
            margin: 0 auto;
            padding: 10rem 2rem 3rem;
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .property-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .property-card:hover {
            transform: translateY(-4px);
        }

        .property-image {
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
        }

        .property-favorite {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .property-favorite:hover {
            transform: scale(1.1);
        }

        .property-favorite svg {
            width: 20px;
            height: 20px;
            color: #717171;
        }

        .property-favorite.active svg {
            color: #FF385C;
            fill: #FF385C;
        }

        .property-content {
            padding: 1rem 0;
        }

        .property-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.25rem;
        }

        .property-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #222222;
            margin-bottom: 0.25rem;
        }

        .property-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            color: #222222;
        }

        .property-rating svg {
            width: 12px;
            height: 12px;
            fill: #222222;
        }

        .property-location {
            font-size: 0.875rem;
            color: #717171;
            margin-bottom: 0.25rem;
        }

        .property-price {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #222222;
        }

        .property-price span {
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0.75rem 1rem;
            }

            .search-bar {
                display: none;
            }

            .categories-bar {
                top: 60px;
            }

            .categories-container {
                padding: 0 1rem;
            }

            .container {
                padding: 8rem 1rem 3rem;
            }

            .properties-grid {
                grid-template-columns: 1fr;
            }

            .property-image {
                height: 220px;
            }
        }
    </style>
</head>
<body>
    <nav class="header">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 1c2.008 0 3.463.963 4.751 3.269l.533 1.025c1.954 3.83 6.114 12.54 7.1 14.836l.145.353c.667 1.591.91 3.162.723 4.691-.285 2.328-1.876 4.169-4.155 5.004-1.322.475-2.916.624-4.597.424l-.328-.042C19.418 30.34 17.838 30 16 30c-1.838 0-3.418.34-4.172.56l-.328.042c-1.681.2-3.275.051-4.597-.424-2.279-.835-3.87-2.676-4.155-5.004-.187-1.529.056-3.1.723-4.691l.145-.353c.986-2.296 5.146-11.006 7.1-14.836l.533-1.025C12.537 1.963 13.992 1 16 1zm0 2c-1.239 0-2.053.539-2.987 2.21l-.523 1.008c-1.926 3.776-6.058 12.43-7.031 14.697l-.145.353c-.539 1.286-.736 2.568-.578 3.839.215 1.754 1.418 3.138 3.13 3.77 1.07.384 2.356.5 3.733.329l.328-.042C12.557 27.936 14.162 27.5 16 27.5c1.838 0 3.443.436 4.073.564l.328.042c1.377.171 2.663.055 3.733-.329 1.712-.632 2.915-2.016 3.13-3.77.158-1.271-.039-2.553-.578-3.839l-.145-.353c-.973-2.267-5.105-10.921-7.031-14.697l-.523-1.008C18.053 3.539 17.239 3 16 3z"/>
                </svg>
                Manna
            </a>
            <div class="search-bar">
                <div class="search-item">
                    <div class="search-label">Where</div>
                    <div class="search-value">Search destinations</div>
                </div>
                <div class="search-item">
                    <div class="search-label">Check in</div>
                    <div class="search-value">Add dates</div>
                </div>
                <div class="search-item">
                    <div class="search-label">Check out</div>
                    <div class="search-value">Add dates</div>
                </div>
                <div class="search-item">
                    <div class="search-label">Who</div>
                    <div class="search-value">Add guests</div>
                </div>
                <button class="search-button">
                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 24a11 11 0 1 0 0-22 11 11 0 0 0 0 22zm8-3 9 9" stroke="currentColor" stroke-width="3" fill="none"/>
                    </svg>
                </button>
            </div>
            <div class="user-menu">
                <a href="{{ route('login') }}" class="user-link">Log in</a>
                <a href="{{ route('register') }}" class="user-link" style="background: #222222; color: #fff;">Sign up</a>
            </div>
        </div>
    </nav>

    <div class="categories-bar">
        <div class="categories-container">
            <div class="category-item active">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="category-label">All</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="category-label">Apartments</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="category-label">Homes</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="category-label">Penthouses</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                <span class="category-label">Commercial</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="category-label">Amazing views</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="category-label">Beachfront</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="category-label">Cabins</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="category-label">Tiny homes</span>
            </div>
            <div class="category-item">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="category-label">Luxe</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="properties-grid">
            <div class="property-card" onclick="window.location.href='/properties/1'">
                <div class="property-image">
                    <span>🏠</span>
                    <div class="property-favorite" onclick="event.stopPropagation(); this.classList.toggle('active')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                </div>
                <div class="property-content">
                    <div class="property-header">
                        <div class="property-title">Modern 2-Bedroom Apartment</div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.95
                        </div>
                    </div>
                    <div class="property-location">Nairobi, Kenya</div>
                    <div class="property-price">$1,200 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" onclick="window.location.href='/properties/2'">
                <div class="property-image">
                    <span>🏢</span>
                    <div class="property-favorite" onclick="event.stopPropagation(); this.classList.toggle('active')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                </div>
                <div class="property-content">
                    <div class="property-header">
                        <div class="property-title">Luxury Penthouse Suite</div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.87
                        </div>
                    </div>
                    <div class="property-location">Lagos, Nigeria</div>
                    <div class="property-price">$2,500 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" onclick="window.location.href='/properties/3'">
                <div class="property-image">
                    <span>🏡</span>
                    <div class="property-favorite" onclick="event.stopPropagation(); this.classList.toggle('active')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                </div>
                <div class="property-content">
                    <div class="property-header">
                        <div class="property-title">Cozy Family Home</div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.92
                        </div>
                    </div>
                    <div class="property-location">Accra, Ghana</div>
                    <div class="property-price">$1,800 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" onclick="window.location.href='/properties/4'">
                <div class="property-image">
                    <span>🏗️</span>
                    <div class="property-favorite" onclick="event.stopPropagation(); this.classList.toggle('active')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                </div>
                <div class="property-content">
                    <div class="property-header">
                        <div class="property-title">Commercial Office Space</div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.85
                        </div>
                    </div>
                    <div class="property-location">Johannesburg, South Africa</div>
                    <div class="property-price">$3,000 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" onclick="window.location.href='/properties/1'">
                <div class="property-image">
                    <span>🏠</span>
                    <div class="property-favorite" onclick="event.stopPropagation(); this.classList.toggle('active')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                </div>
                <div class="property-content">
                    <div class="property-header">
                        <div class="property-title">Beachfront Villa</div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.98
                        </div>
                    </div>
                    <div class="property-location">Mombasa, Kenya</div>
                    <div class="property-price">$2,200 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" onclick="window.location.href='/properties/2'">
                <div class="property-image">
                    <span>🏢</span>
                    <div class="property-favorite" onclick="event.stopPropagation(); this.classList.toggle('active')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                </div>
                <div class="property-content">
                    <div class="property-header">
                        <div class="property-title">City Center Loft</div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            4.90
                        </div>
                    </div>
                    <div class="property-location">Kampala, Uganda</div>
                    <div class="property-price">$1,500 <span>night</span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.category-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
