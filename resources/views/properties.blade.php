<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('logo.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
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
            gap: 0.75rem;
            text-decoration: none;
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

        .search-bar {
            display: none;
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

        .get-started-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: #10B981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .get-started-btn:hover {
            background: #059669;
        }

        .mobile-filter-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mobile-filter-btn:hover {
            background: #f7f7f7;
        }

        .mobile-filter-btn svg {
            width: 18px;
            height: 18px;
        }

        .mobile-filter-btn span {
            font-size: 0.875rem;
            font-weight: 600;
            color: #222222;
        }

        .mobile-search-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mobile-search-btn:hover {
            background: #f7f7f7;
        }

        .mobile-search-btn svg {
            width: 18px;
            height: 18px;
        }

        .mobile-search-btn span {
            font-size: 0.875rem;
            font-weight: 600;
            color: #222222;
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
            transition: all 0.3s ease;
        }

        .categories-bar.hidden {
            display: none;
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
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 80px;
            cursor: pointer;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            background: #fff;
        }

        .category-item:hover {
            background: #f7f7f7;
            border-color: #e5e7eb;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .category-item.active {
            border-color: #222222;
            border-bottom: 2px solid #222222;
        }

        .category-item.selected {
            background: #222222;
            color: #fff;
            border-color: #222222;
        }

        .category-item.selected .category-label {
            color: #fff;
        }

        .category-label {
            font-size: 0.875rem;
            color: #222222;
            font-weight: 500;
            white-space: nowrap;
        }

        .container {
            max-width: 1760px;
            margin: 0 auto;
            padding: 10rem 2rem 3rem;
            transition: all 0.3s ease;
        }

        .container.categories-hidden {
            padding-top: 5rem;
        }

        .search-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .search-sidebar.active {
            display: flex;
        }

        .search-sidebar-content {
            background: #fff;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            border-radius: 20px;
            padding: 1.5rem;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        .search-sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .search-sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #222222;
        }

        .search-sidebar-close {
            width: 32px;
            height: 32px;
            background: #f7f7f7;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .search-sidebar-close svg {
            width: 18px;
            height: 18px;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .search-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .search-form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #222222;
        }

        .search-form-input {
            padding: 0.875rem 1rem;
            border: 1px solid #b0b0b0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }

        .search-form-input:focus {
            border-color: #222222;
        }

        .search-form-input::placeholder {
            color: #6a6a6a;
        }

        .search-submit-btn {
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #E61E4D, #D70466);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-submit-btn:hover {
            background: linear-gradient(135deg, #D70466, #BD1E59);
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .mobile-sidebar.active {
            display: flex;
        }

        .sidebar-content {
            background: #fff;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            border-radius: 20px;
            padding: 1.5rem;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #222222;
        }

        .sidebar-close {
            width: 32px;
            height: 32px;
            background: #f7f7f7;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .sidebar-close svg {
            width: 18px;
            height: 18px;
        }

        .sidebar-categories {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .sidebar-category-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #f7f7f7;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .sidebar-category-item:hover {
            background: #f0f0f0;
        }

        .sidebar-category-item.selected {
            background: #222222;
            border-color: #222222;
        }

        .sidebar-category-item.selected .sidebar-category-icon,
        .sidebar-category-item.selected .sidebar-category-label {
            color: #fff;
        }

        .sidebar-category-icon {
            width: 24px;
            height: 24px;
            color: #222222;
        }

        .sidebar-category-label {
            font-size: 0.75rem;
            color: #222222;
            font-weight: 500;
            text-align: center;
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
            position: relative;
            overflow: hidden;
        }

        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .property-card:hover .property-image img {
            transform: scale(1.05);
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
            padding: 0.75rem 0;
        }

        .property-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.25rem;
            gap: 0.5rem;
        }

        .property-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #222222;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .property-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            color: #222222;
            flex-shrink: 0;
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
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .property-location svg {
            width: 14px;
            height: 14px;
            color: #717171;
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
                padding: 0.5rem 1rem;
            }

            .nav-brand {
                font-size: 1rem;
            }

            .nav-brand-icon {
                width: 28px;
                height: 28px;
            }

            .nav-brand-icon svg {
                width: 16px;
                height: 16px;
            }

            .nav-brand-name {
                font-size: 1rem;
            }

            .search-bar {
                display: 0.5rem 0.75rem;
            }

            .mobile-search-btn span {
                display: none;
            }

            .mobile-filter-btn {
                padding: 0.5rem 0.75rem;
            }

            .mobile-filter-btn span {
                display: none;
            }

            .get-started-btn {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }

            .categories-bar {
                display: none;
            }

            .mobile-sidebar {
                align-items: flex-end;
            }

            .sidebar-content {
                width: 100%;
                max-width: 100%;
                border-radius: 20px 20px 0 0;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                }
                to {
                    transform: translateY(0);
                }
            }

            .search-sidebar {
                align-items: flex-end;
            }

            .search-sidebar-content {
                width: 100%;
                max-width: 100%;
                border-radius: 20px 20px 0 0;
            }

            .container {
                padding: 5rem 1rem 3rem;
            }

            .container.categories-hidden {
                padding-top: 5rem;
            }

            .properties-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .property-image {
                height: 180px;
            }

            .property-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .property-title {
                font-size: 0.875rem;
            }

            .property-rating {
                font-size: 0.75rem;
            }

            .property-location {
                font-size: 0.75rem;
            }

            .property-price {
                font-size: 0.875rem;
            }

            .property-favorite {
                width: 28px;
                height: 28px;
                top: 0.5rem;
                right: 0.5rem;
            }

            .property-favorite svg {
                width: 16px;
                height: 16px;
            }
        }
    </style>
</head>
<body>
    <nav class="header">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <img src="{{ asset('logo.png') }}" alt="Manna" class="nav-brand-icon">
                <span class="nav-brand-name">Manna</span>
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
                <a href="{{ route('register') }}" class="get-started-btn">Get Started</a>
                <button class="mobile-search-btn" onclick="toggleSearchSidebar()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Search</span>
                </button>
                <button class="mobile-filter-btn" onclick="toggleSidebar()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filters</span>
                </button>
            </div>
        </div>
    </nav>

    <div class="search-sidebar" id="search-sidebar">
        <div class="search-sidebar-content">
            <div class="search-sidebar-header">
                <h2 class="search-sidebar-title">Search Properties</h2>
                <button class="search-sidebar-close" onclick="toggleSearchSidebar()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form class="search-form" onsubmit="handleSearch(event)">
                <div class="search-form-group">
                    <label class="search-form-label">Where</label>
                    <input type="text" class="search-form-input" placeholder="Search destinations" id="search-where">
                </div>
                <div class="search-form-group">
                    <label class="search-form-label">Check in</label>
                    <input type="date" class="search-form-input" placeholder="Add dates" id="search-checkin">
                </div>
                <div class="search-form-group">
                    <label class="search-form-label">Check out</label>
                    <input type="date" class="search-form-input" placeholder="Add dates" id="search-checkout">
                </div>
                <div class="search-form-group">
                    <label class="search-form-label">Who</label>
                    <input type="number" class="search-form-input" placeholder="Add guests" id="search-guests" min="1">
                </div>
                <button type="submit" class="search-submit-btn">Search</button>
            </form>
        </div>
    </div>

    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="sidebar-content">
            <div class="sidebar-header">
                <h2 class="sidebar-title">Filters</h2>
                <button class="sidebar-close" onclick="toggleSidebar()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="sidebar-categories">
                <div class="sidebar-category-item" data-category="all" onclick="selectCategory('all')">
                    <svg class="sidebar-category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="sidebar-category-label">All</span>
                </div>
                <div class="sidebar-category-item" data-category="apartment" onclick="selectCategory('apartment')">
                    <svg class="sidebar-category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="sidebar-category-label">Apartments</span>
                </div>
                <div class="sidebar-category-item" data-category="house" onclick="selectCategory('house')">
                    <svg class="sidebar-category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="sidebar-category-label">Houses</span>
                </div>
                <div class="sidebar-category-item" data-category="studio" onclick="selectCategory('studio')">
                    <svg class="sidebar-category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    <span class="sidebar-category-label">Studios</span>
                </div>
                <div class="sidebar-category-item" data-category="penthouse" onclick="selectCategory('penthouse')">
                    <svg class="sidebar-category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span class="sidebar-category-label">Penthouses</span>
                </div>
                <div class="sidebar-category-item" data-category="villa" onclick="selectCategory('villa')">
                    <svg class="sidebar-category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    <span class="sidebar-category-label">Villas</span>
                </div>
            </div>
        </div>
    </div>

    <div class="categories-bar" id="categories-bar">
        <div class="categories-container">
            <div class="category-item" data-category="all" onclick="selectCategory('all')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="category-label">All</span>
            </div>
            <div class="category-item" data-category="apartments" onclick="selectCategory('apartments')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="category-label">Apartments</span>
            </div>
            <div class="category-item" data-category="homes" onclick="selectCategory('homes')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="category-label">Homes</span>
            </div>
            <div class="category-item" data-category="penthouses" onclick="selectCategory('penthouses')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="category-label">Penthouses</span>
            </div>
            <div class="category-item" data-category="commercial" onclick="selectCategory('commercial')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                <span class="category-label">Commercial</span>
            </div>
            <div class="category-item" data-category="views" onclick="selectCategory('views')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span class="category-label">Amazing views</span>
            </div>
            <div class="category-item" data-category="beachfront" onclick="selectCategory('beachfront')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                    <path d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                </svg>
                <span class="category-label">Beachfront</span>
            </div>
            <div class="category-item" data-category="cabins" onclick="selectCategory('cabins')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.819 20.264a1.5 1.5 0 00-1.073-1.073l-2.895-.964a1.5 1.5 0 00-.95 0l-2.895.964a1.5 1.5 0 00-1.073 1.073L8 21.5v.5h8v-.5l-.181-1.236zM4 21.5v-.5l.181-1.236a1.5 1.5 0 011.073-1.073l2.895-.964a1.5 1.5 0 00.95 0l2.895.964a1.5 1.5 0 011.073 1.073L13 21v.5"/>
                </svg>
                <span class="category-label">Cabins</span>
            </div>
            <div class="category-item" data-category="tiny" onclick="selectCategory('tiny')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="category-label">Tiny homes</span>
            </div>
            <div class="category-item" data-category="luxe" onclick="selectCategory('luxe')">
                <svg class="category-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="category-label">Luxe</span>
            </div>
        </div>
    </div>

    <div class="container" id="container">
        <div class="properties-grid">
            <div class="property-card" data-category="apartments" onclick="window.location.href='/properties/1'">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop" alt="Modern 2-Bedroom Apartment">
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
                    <div class="property-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Nairobi, Kenya
                    </div>
                    <div class="property-price">$1,200 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" data-category="penthouses" onclick="window.location.href='/properties/2'">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop" alt="Luxury Penthouse Suite">
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
                    <div class="property-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lagos, Nigeria
                    </div>
                    <div class="property-price">$2,500 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" data-category="homes" onclick="window.location.href='/properties/3'">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop" alt="Cozy Family Home">
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
                    <div class="property-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Accra, Ghana
                    </div>
                    <div class="property-price">$1,800 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" data-category="villa" onclick="window.location.href='/properties/4'">
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
                    <div class="property-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Johannesburg, South Africa
                    </div>
                    <div class="property-price">$3,000 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" data-category="beachfront" onclick="window.location.href='/properties/5'">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&h=600&fit=crop" alt="Beachfront Villa">
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
                    <div class="property-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Mombasa, Kenya
                    </div>
                    <div class="property-price">$2,200 <span>night</span></div>
                </div>
            </div>

            <div class="property-card" data-category="apartments" onclick="window.location.href='/properties/6'">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop" alt="City Center Loft">
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
                    <div class="property-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Kampala, Uganda
                    </div>
                    <div class="property-price">$1,500 <span>night</span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCategory = 'all';

        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            sidebar.classList.toggle('active');
        }

        function toggleSearchSidebar() {
            const sidebar = document.getElementById('search-sidebar');
            sidebar.classList.toggle('active');
        }

        function handleSearch(event) {
            event.preventDefault();
            
            const where = document.getElementById('search-where').value.toLowerCase();
            const cards = document.querySelectorAll('.property-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.property-title').textContent.toLowerCase();
                const location = card.querySelector('.property-location').textContent.toLowerCase();
                
                if (where === '' || title.includes(where) || location.includes(where)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            toggleSearchSidebar();
        }

        function selectCategory(category) {
            currentCategory = category;
            
            // Update desktop category UI
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('active', 'selected');
                if (item.dataset.category === category) {
                    item.classList.add('selected');
                }
            });

            // Update mobile sidebar category UI
            document.querySelectorAll('.sidebar-category-item').forEach(item => {
                item.classList.remove('selected');
                if (item.dataset.category === category) {
                    item.classList.add('selected');
                }
            });

            // Close sidebar after selection
            const sidebar = document.getElementById('mobile-sidebar');
            sidebar.classList.remove('active');

            // Filter properties
            filterProperties(category);
        }

        function filterProperties(category) {
            const cards = document.querySelectorAll('.property-card');
            
            cards.forEach(card => {
                const cardCategory = card.dataset.category;
                
                if (category === 'all' || cardCategory === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.category-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
