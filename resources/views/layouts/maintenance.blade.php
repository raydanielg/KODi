<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KODI') }} - Maintenance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            min-width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        }

        .sidebar-logo svg { width: 22px; height: 22px; fill: #fff; }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.4);
            padding: 0 0.75rem;
            margin-bottom: 0.75rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(16,185,129,0.1));
            color: #10B981;
            border: 1px solid rgba(16,185,129,0.3);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .sidebar-user-info {
            flex: 1;
        }

        .sidebar-user-name {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .sidebar-user-role {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
        }

        .sidebar-logout {
            background: none;
            border: none;
            color: rgba(255,255,255,0.5);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .sidebar-logout:hover {
            background: rgba(239,68,68,0.2);
            color: #ef4444;
        }

        .sidebar-logout svg { width: 18px; height: 18px; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            display: none;
            transition: background 0.2s ease;
        }

        .menu-toggle:hover { background: #f1f5f9; }
        .menu-toggle svg { width: 24px; height: 24px; color: #64748b; }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-search {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            gap: 0.5rem;
        }

        .header-search svg { width: 18px; height: 18px; color: #94a3b8; }

        .header-search input {
            background: none;
            border: none;
            outline: none;
            font-size: 0.9rem;
            color: #0f172a;
            width: 200px;
        }

        .header-search input::placeholder { color: #94a3b8; }

        .header-btn {
            background: #f1f5f9;
            border: none;
            padding: 0.6rem;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .header-btn:hover { background: #e2e8f0; }
        .header-btn svg { width: 20px; height: 20px; color: #64748b; }

        .header-btn .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.1rem 0.35rem;
            border-radius: 10px;
        }

        /* ===== CONTENT AREA ===== */
        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* ===== DASHBOARD STYLES ===== */
        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .welcome-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .stat-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card-icon svg {
            width: 22px;
            height: 22px;
        }

        .stat-card-icon.blue { background: #eff6ff; }
        .stat-card-icon.blue svg { color: #3b82f6; }
        .stat-card-icon.amber { background: #fffbeb; }
        .stat-card-icon.amber svg { color: #f59e0b; }
        .stat-card-icon.green { background: #f0fdf4; }
        .stat-card-icon.green svg { color: #10b981; }
        .stat-card-icon.purple { background: #f5f3ff; }
        .stat-card-icon.purple svg { color: #8b5cf6; }

        .stat-card-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0f172a;
        }

        .card-body {
            padding: 1.5rem;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: #0f172a;
            transition: all 0.2s ease;
            text-align: center;
        }

        .quick-action:hover {
            background: #fff;
            border-color: #10B981;
            box-shadow: 0 4px 12px rgba(16,185,129,0.15);
            transform: translateY(-2px);
        }

        .quick-action svg {
            width: 28px;
            height: 28px;
            color: #10B981;
        }

        .quick-action span {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .quick-action small {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 400;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-icon svg {
            width: 18px;
            height: 18px;
        }

        .activity-icon.blue { background: #eff6ff; }
        .activity-icon.blue svg { color: #3b82f6; }
        .activity-icon.green { background: #f0fdf4; }
        .activity-icon.green svg { color: #10b981; }
        .activity-icon.amber { background: #fffbeb; }
        .activity-icon.amber svg { color: #f59e0b; }
        .activity-icon.red { background: #fef2f2; }
        .activity-icon.red svg { color: #ef4444; }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            font-size: 0.9rem;
            font-weight: 500;
            color: #0f172a;
            margin-bottom: 0.15rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .view-all {
            color: #10B981;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .view-all:hover {
            color: #059669;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .menu-toggle { display: block; }
            .header-search input { width: 150px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
            .quick-actions-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .header { padding: 1rem; }
            .header-title { font-size: 1.25rem; }
            .header-search { display: none; }
            .content-area { padding: 1rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .quick-actions-grid { grid-template-columns: 1fr; }
        }

        /* ===== OVERLAY ===== */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.show { display: block; }
    </style>
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                    <path d="M2 17L12 22L22 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <path d="M2 12L12 17L22 12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>
            <span class="sidebar-brand">{{ config('app.name', 'KODI') }}</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main Menu</div>
                <a href="{{ route('maintenance.dashboard') }}" class="nav-item {{ request()->is('maintenance/dashboard*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('maintenance.tasks.assigned') }}" class="nav-item {{ request()->is('maintenance/tasks/assigned*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Assigned Tasks
                </a>
                <a href="{{ route('maintenance.tasks.in-progress') }}" class="nav-item {{ request()->is('maintenance/tasks/in-progress*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    In Progress
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Tasks</div>
                <a href="{{ route('maintenance.tasks.completed') }}" class="nav-item {{ request()->is('maintenance/tasks/completed*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Completed
                </a>
                <a href="{{ route('maintenance.schedule') }}" class="nav-item {{ request()->is('maintenance/schedule*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Schedule
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <a href="{{ route('maintenance.messages') }}" class="nav-item {{ request()->is('maintenance/messages*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Messages
                </a>
                <a href="{{ route('maintenance.reports') }}" class="nav-item {{ request()->is('maintenance/reports*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Reports
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ substr(auth()->user()->name ?? 'MS', 0, 2) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Staff' }}</div>
                    <div class="sidebar-user-role">Maintenance Staff</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout" title="Logout">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="header-title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="header-right">
                <div class="header-search">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Search...">
                </div>
                <button class="header-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="badge">3</span>
                </button>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <script>
        // Sidebar Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (menuToggle && sidebar && sidebarOverlay) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('show');
            });

            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('show');
            });
        }
    </script>
</body>
</html>
