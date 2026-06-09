<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KODI') }} - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap');
        
        /* Fix Bootstrap Icons display */
        .bi {
            display: inline-block;
            font-family: bootstrap-icons !important;
            font-style: normal;
            font-weight: 400;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            vertical-align: -0.125em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body { 
            font-family: 'Public Sans', sans-serif; 
            overflow-x: hidden; 
            background-color: #eef2f6; 
            color: #475569; 
            transition: padding 0.3s; 
        }
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* Sidebar Styling */
        #sidebar-wrapper {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            transition: all 0.3s;
            background: #f8fafc;
            z-index: 1000;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                margin-left: -260px !important;
                position: fixed !important;
                height: 100vh !important;
                top: 0 !important;
                left: 0 !important;
                display: block !important;
            }
            #sidebar-wrapper.toggled {
                margin-left: 0 !important;
                box-shadow: 0 0 20px rgba(0,0,0,0.2) !important;
            }
            #page-content-wrapper {
                min-width: 100vw !important;
                width: 100% !important;
            }
        }
        
        .sidebar-brand {
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        .sidebar-brand-logo {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .sidebar-brand .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .sidebar-brand .brand-name .brand-accent {
            color: #0d6efd;
        }

        .sidebar-brand .admin-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #0d6efd;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }

        .sidebar-heading {
            padding: 20px 25px 10px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            font-weight: 700;
        }

        .list-group-item {
            background: transparent;
            color: #64748b;
            border: none;
            padding: 12px 16px;
            transition: background-color 0.2s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            font-weight: 400;
            border-radius: 8px;
            margin: 0 10px 2px;
        }

        .list-group-item i {
            font-size: 1.1rem;
            margin-right: 12px;
            color: #9ca3af;
            width: 20px;
            text-align: center;
        }

        .list-group-item:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .list-group-item:hover i {
            color: #111827;
        }

        .list-group-item.active {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: 500;
        }

        .list-group-item.active i {
            color: #111827;
        }

        .list-group-item .chevron {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.2s;
        }

        .list-group-item[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }

        .collapse .list-group-item {
            padding: 10px 25px 10px 57px;
            font-size: 0.8rem;
            margin: 0 10px;
        }

        .collapse .list-group-item:hover {
            transform: translateX(2px);
        }

        .logout-section {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            margin-top: auto;
            background: #fff;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            color: #ef4444;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 12px 15px;
            border-radius: 10px;
            transition: all 0.2s;
            gap: 12px;
        }

        .logout-btn:hover {
            background: #fef2f2;
            transform: translateX(4px);
        }

        .logout-btn i {
            font-size: 1.1rem;
        }

        #page-content-wrapper { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }
        
        /* Navbar Styling */
        .navbar {
            background: #fff;
            padding: 10px 16px;
            border: none;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 384px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            width: 16px;
            height: 16px;
        }

        .search-input {
            width: 100%;
            padding: 10px 10px 10px 36px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #111827;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        /* Header Buttons */
        .header-btn {
            padding: 8px;
            color: #6b7280;
            background: transparent;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-btn:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .header-btn svg {
            width: 20px;
            height: 20px;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        /* User Profile */
        .header-profile {
            background: #f3f4f6;
            border-radius: 9999px;
            padding: 4px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .header-profile:hover {
            background: #e5e7eb;
        }

        .header-profile-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 8px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            min-width: 224px;
            z-index: 50;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            display: block;
            font-size: 0.875rem;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-divider {
            border-top: 1px solid #e5e7eb;
            margin: 8px 0;
        }

        .dropdown-header {
            padding: 12px 16px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        #sidebar-wrapper.toggled { margin-left: -260px; }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            top: 0;
            left: 0;
        }
        .sidebar-overlay.active {
            display: block;
        }

        /* Menu toggle button */
        .menu-toggle {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: none;
        }

        .menu-toggle:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .menu-toggle i {
            font-size: 1.2rem;
            color: #64748b;
        }

        @media (max-width: 991.98px) {
            .menu-toggle {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .navbar { padding: 15px; }
            .content-area { padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="d-flex flex-column" style="overflow-y: auto;">
            <div class="sidebar-brand sticky-top">
                <div class="sidebar-brand-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="KODI Logo">
                </div>
                <div>
                    <span class="brand-name"><span class="brand-accent">KODI</span> Admin</span>
                    <div class="admin-label">{{ auth()->user()->role ?? 'Administrator' }}</div>
                </div>
            </div>

            <!-- Main Menu -->
            <div class="list-group list-group-flush px-2 mt-3">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
                <a href="{{ route('admin.properties.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.properties*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Properties
                </a>
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Users
                </a>
                <a href="{{ route('admin.payments.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i> Payments
                </a>
                <a href="{{ route('admin.maintenance.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.maintenance*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Maintenance
                </a>
                <a href="{{ route('admin.reports.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports
                </a>
                <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Settings
                </a>
            </div>

            <!-- Logout -->
            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <button class="menu-toggle" id="menu-toggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <h1 class="page-title mb-0 d-none d-md-block">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Search Bar -->
                        <div class="search-container d-none d-lg-block">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" class="search-input" placeholder="Search...">
                        </div>

                        <!-- Notifications -->
                        <button class="header-btn position-relative">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-.293L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            <span class="notification-badge">3</span>
                        </button>

                        <!-- Apps -->
                        <button class="header-btn">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>

                        <!-- User Profile -->
                        <div class="position-relative">
                            <div class="header-profile" id="user-menu-btn">
                                <div class="header-profile-avatar">
                                    {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                                </div>
                            </div>
                            <div class="dropdown-menu" id="user-dropdown">
                                <div class="dropdown-header">
                                    <div class="font-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@manna.com' }}</div>
                                </div>
                                <a href="#" class="dropdown-item">My Profile</a>
                                <a href="#" class="dropdown-item">Account Settings</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item w-100 text-left" style="border: none; background: none; cursor: pointer;">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarWrapper = document.getElementById('sidebar-wrapper');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');

        if (menuToggle && sidebarWrapper && sidebarOverlay) {
            menuToggle.addEventListener('click', function() {
                sidebarWrapper.classList.toggle('toggled');
                sidebarOverlay.classList.toggle('active');
            });

            sidebarOverlay.addEventListener('click', function() {
                sidebarWrapper.classList.remove('toggled');
                sidebarOverlay.classList.remove('active');
            });
        }

        // User dropdown toggle
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }
    </script>
</body>
</html>
