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
            margin-bottom: 3rem;
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

        .filters-section {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.8s ease-out 0.1s both;
            border: none;
            position: relative;
        }

        .filters-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .filters-header:hover {
            background: #f9fafb;
        }

        .filters-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        .filters-title svg {
            width: 24px;
            height: 24px;
            color: #10B981;
        }

        .filters-toggle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .filters-toggle svg {
            width: 20px;
            height: 20px;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .filters-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .filters-toggle.active svg {
            transform: rotate(180deg);
        }

        .filters-content {
            display: block;
            transition: all 0.3s ease;
        }

        .filters-content.hidden {
            display: none;
        }

        .filter-icons {
            display: none;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .filter-icon-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-icon-btn:hover {
            background: #f0fdf4;
            border-color: #10B981;
            transform: translateY(-2px);
        }

        .filter-icon-btn.active {
            background: linear-gradient(135deg, #10B981, #059669);
            border-color: #10B981;
        }

        .filter-icon-btn.active svg {
            color: #fff;
        }

        .filter-icon-btn.active span {
            color: #fff;
        }

        .filter-icon-btn svg {
            width: 28px;
            height: 28px;
            color: #10B981;
            transition: all 0.3s ease;
        }

        .filter-icon-btn span {
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            transition: all 0.3s ease;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-label svg {
            width: 16px;
            height: 16px;
            color: #10B981;
        }

        .filter-input, .filter-select {
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
            background: #fff;
        }

        .filter-input:focus, .filter-select:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .filter-select {
            cursor: pointer;
        }

        .filter-button {
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            letter-spacing: 0.01em;
        }

        .filter-button:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .property-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid #e5e7eb;
            position: relative;
        }

        .property-number {
            position: absolute;
            top: 1rem;
            left: 1rem;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            z-index: 5;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .property-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border-color: #10B981;
        }

        .property-image {
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #10B981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }

        .property-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.8), rgba(5, 150, 105, 0.8));
        }

        .property-image span {
            position: relative;
            z-index: 1;
        }

        .property-content {
            padding: 2rem;
        }

        .property-price {
            font-size: 1.75rem;
            font-weight: 800;
            color: #10B981;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .property-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.75rem;
            letter-spacing: -0.01em;
        }

        .property-location {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .property-location svg {
            width: 18px;
            height: 18px;
            color: #10B981;
        }

        .property-features {
            display: flex;
            gap: 1.25rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
        }

        .feature svg {
            width: 18px;
            height: 18px;
            color: #10B981;
        }

        .property-actions {
            display: flex;
            gap: 1rem;
        }

        .view-details-btn {
            flex: 1;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .view-details-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            text-decoration: none;
        }

        .book-btn {
            padding: 0.875rem 1.5rem;
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .book-btn:hover {
            background: #10B981;
            color: #fff;
            border-color: #10B981;
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: #fff;
            border-radius: 20px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        .modal-header {
            padding: 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
        }

        .modal-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
        }

        .modal-close {
            width: 40px;
            height: 40px;
            background: #f3f4f6;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: #ef4444;
            color: #fff;
        }

        .modal-close svg {
            width: 20px;
            height: 20px;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-image {
            width: 100%;
            height: 350px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 5rem;
            margin-bottom: 2rem;
        }

        .modal-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 12px;
        }

        .detail-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .detail-value {
            font-size: 1.125rem;
            color: #111827;
            font-weight: 600;
        }

        .modal-description {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .modal-description h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .modal-description p {
            color: #6b7280;
            line-height: 1.7;
        }

        .modal-amenities {
            margin-bottom: 2rem;
        }

        .modal-amenities h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }

        .amenities-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .amenity {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #f9fafb;
            border-radius: 10px;
        }

        .amenity svg {
            width: 20px;
            height: 20px;
            color: #10B981;
        }

        .amenity span {
            font-size: 0.95rem;
            color: #374151;
            font-weight: 500;
        }

        .modal-footer {
            padding: 2rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 1rem;
            position: sticky;
            bottom: 0;
            background: #fff;
        }

        .modal-book-btn {
            flex: 1;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .modal-book-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
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

        @media (min-width: 769px) {
            .property-number {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .properties-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .modal-details {
                grid-template-columns: 1fr;
            }

            .amenities-list {
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

            .container {
                padding: 7rem 1.5rem 3rem;
            }

            .filters-section {
                padding: 1.5rem;
            }

            .filters-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .filter-group {
                margin-bottom: 0;
            }

            .filter-button {
                grid-column: span 2;
                padding: 1rem;
                font-size: 1rem;
            }

            .filter-icons {
                display: grid;
            }

            .filters-grid {
                display: none;
            }

            .property-number {
                display: flex;
            }

            .property-actions {
                flex-direction: column;
            }

            .modal-content {
                margin: 1rem;
                max-height: 95vh;
            }

            .modal-header, .modal-body, .modal-footer {
                padding: 1.5rem;
            }

            .modal-image {
                height: 250px;
                font-size: 3rem;
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

        <div class="filters-section">
            <div class="filters-header" onclick="toggleFilters()">
                <div class="filters-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filters
                </div>
                <div class="filters-toggle" id="filters-toggle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div class="filters-content" id="filters-content">
                <div class="filter-icons" id="filter-icons">
                    <div class="filter-icon-btn" onclick="toggleFilterIcon(this, 'location')">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Location</span>
                    </div>
                    <div class="filter-icon-btn" onclick="toggleFilterIcon(this, 'type')">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Type</span>
                    </div>
                    <div class="filter-icon-btn" onclick="toggleFilterIcon(this, 'price')">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Price</span>
                    </div>
                    <div class="filter-icon-btn" onclick="toggleFilterIcon(this, 'bedrooms')">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Beds</span>
                    </div>
                </div>
                <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Location
                    </label>
                    <select class="filter-select" id="location-filter">
                        <option value="">All Locations</option>
                        <option value="nairobi">Nairobi</option>
                        <option value="lagos">Lagos</option>
                        <option value="accra">Accra</option>
                        <option value="johannesburg">Johannesburg</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Property Type
                    </label>
                    <select class="filter-select" id="type-filter">
                        <option value="">All Types</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="penthouse">Penthouse</option>
                        <option value="commercial">Commercial</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Price Range
                    </label>
                    <select class="filter-select" id="price-filter">
                        <option value="">Any Price</option>
                        <option value="0-1500">$0 - $1,500</option>
                        <option value="1500-3000">$1,500 - $3,000</option>
                        <option value="3000+">$3,000+</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Bedrooms
                    </label>
                    <select class="filter-select" id="bedrooms-filter">
                        <option value="">Any</option>
                        <option value="1">1 Bedroom</option>
                        <option value="2">2 Bedrooms</option>
                        <option value="3">3 Bedrooms</option>
                        <option value="4+">4+ Bedrooms</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <button class="filter-button" onclick="applyFilters()">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="properties-grid" id="properties-grid">
            <div class="property-card" data-location="nairobi" data-type="apartment" data-price="1200" data-bedrooms="2">
                <div class="property-number">1</div>
                <div class="property-image"><span>🏠</span></div>
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
                    <div class="property-actions">
                        <a href="/properties/1" class="view-details-btn">View Details</a>
                        <button class="book-btn" onclick="bookProperty(this)">Book Now</button>
                    </div>
                </div>
            </div>

            <div class="property-card" data-location="lagos" data-type="penthouse" data-price="2500" data-bedrooms="4">
                <div class="property-number">2</div>
                <div class="property-image"><span>🏢</span></div>
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
                    <div class="property-actions">
                        <a href="/properties/2" class="view-details-btn">View Details</a>
                        <button class="book-btn" onclick="bookProperty(this)">Book Now</button>
                    </div>
                </div>
            </div>

            <div class="property-card" data-location="accra" data-type="house" data-price="1800" data-bedrooms="3">
                <div class="property-number">3</div>
                <div class="property-image"><span>🏡</span></div>
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
                    <div class="property-actions">
                        <a href="/properties/3" class="view-details-btn">View Details</a>
                        <button class="book-btn" onclick="bookProperty(this)">Book Now</button>
                    </div>
                </div>
            </div>

            <div class="property-card" data-location="johannesburg" data-type="commercial" data-price="3000" data-bedrooms="5">
                <div class="property-number">4</div>
                <div class="property-image"><span>🏗️</span></div>
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
                    <div class="property-actions">
                        <a href="/properties/4" class="view-details-btn">View Details</a>
                        <button class="book-btn" onclick="bookProperty(this)">Book Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Property Detail Modal -->
    <div class="modal-overlay" id="property-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-title">Property Details</h2>
                <button class="modal-close" onclick="closeModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-image" id="modal-image">🏠</div>
                <div class="modal-details">
                    <div class="detail-item">
                        <div class="detail-label">Price</div>
                        <div class="detail-value" id="modal-price">$1,200/month</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Location</div>
                        <div class="detail-value" id="modal-location">Nairobi, Kenya</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Bedrooms</div>
                        <div class="detail-value" id="modal-bedrooms">2 Beds</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Bathrooms</div>
                        <div class="detail-value" id="modal-bathrooms">2 Baths</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Size</div>
                        <div class="detail-value" id="modal-size">1,200 sqft</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Property Type</div>
                        <div class="detail-value" id="modal-type">Apartment</div>
                    </div>
                </div>
                <div class="modal-description">
                    <h4>Description</h4>
                    <p id="modal-description">This beautiful modern apartment features spacious rooms, natural lighting, and stunning city views. Perfect for professionals and small families looking for comfort and convenience.</p>
                </div>
                <div class="modal-amenities">
                    <h4>Amenities</h4>
                    <div class="amenities-list">
                        <div class="amenity">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Free WiFi</span>
                        </div>
                        <div class="amenity">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Parking</span>
                        </div>
                        <div class="amenity">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Security</span>
                        </div>
                        <div class="amenity">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Gym Access</span>
                        </div>
                        <div class="amenity">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Swimming Pool</span>
                        </div>
                        <div class="amenity">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-book-btn" onclick="bookPropertyFromModal()">Book This Property</button>
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const content = document.getElementById('filters-content');
            const toggle = document.getElementById('filters-toggle');
            
            content.classList.toggle('hidden');
            toggle.classList.toggle('active');
        }

        function toggleFilterIcon(btn, filterType) {
            btn.classList.toggle('active');
        }

        function openModal(button) {
            const card = button.closest('.property-card');
            const title = card.querySelector('.property-title').textContent;
            const price = card.querySelector('.property-price').textContent;
            const location = card.querySelector('.property-location').textContent.trim();
            const features = card.querySelectorAll('.feature');
            
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-price').textContent = price;
            document.getElementById('modal-location').textContent = location;
            document.getElementById('modal-bedrooms').textContent = features[0].textContent.trim();
            document.getElementById('modal-bathrooms').textContent = features[1].textContent.trim();
            document.getElementById('modal-size').textContent = features[2].textContent.trim();
            
            const modal = document.getElementById('property-modal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('property-modal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function bookProperty(button) {
            const card = button.closest('.property-card');
            const title = card.querySelector('.property-title').textContent;
            
            Swal.fire({
                icon: 'success',
                title: 'Booking Request Sent!',
                text: `Your booking request for "${title}" has been sent. We will contact you shortly.`,
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
        }

        function bookPropertyFromModal() {
            const title = document.getElementById('modal-title').textContent;
            closeModal();
            
            Swal.fire({
                icon: 'success',
                title: 'Booking Request Sent!',
                text: `Your booking request for "${title}" has been sent. We will contact you shortly.`,
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
        }

        function applyFilters() {
            const location = document.getElementById('location-filter').value;
            const type = document.getElementById('type-filter').value;
            const price = document.getElementById('price-filter').value;
            const bedrooms = document.getElementById('bedrooms-filter').value;
            
            const cards = document.querySelectorAll('.property-card');
            
            cards.forEach(card => {
                const cardLocation = card.dataset.location;
                const cardType = card.dataset.type;
                const cardPrice = parseInt(card.dataset.price);
                const cardBedrooms = card.dataset.bedrooms;
                
                let show = true;
                
                if (location && cardLocation !== location) show = false;
                if (type && cardType !== type) show = false;
                if (price) {
                    if (price === '0-1500' && cardPrice > 1500) show = false;
                    if (price === '1500-3000' && (cardPrice < 1500 || cardPrice > 3000)) show = false;
                    if (price === '3000+' && cardPrice < 3000) show = false;
                }
                if (bedrooms) {
                    if (bedrooms === '4+' && parseInt(cardBedrooms) < 4) show = false;
                    if (bedrooms !== '4+' && cardBedrooms !== bedrooms) show = false;
                }
                
                card.style.display = show ? 'block' : 'none';
            });
        }

        // Close modal on overlay click
        document.getElementById('property-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
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
