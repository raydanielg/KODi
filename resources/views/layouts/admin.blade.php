<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — KODI Admin</title>

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand:       #B44040;
            --brand-dark:  #9A3535;
            --brand-light: #FDF0F0;
            --sidebar-bg:  #0f172a;
            --sidebar-sec: #1e293b;
            --sidebar-brd: #334155;
            --sidebar-txt: #94a3b8;
            --sidebar-act: #f1f5f9;
            --topbar-h:    64px;
            --sidebar-w:   260px;
            --body-bg:     #f1f5f9;
            --card-bg:     #ffffff;
            --text-primary:#0f172a;
            --text-sub:    #475569;
            --text-muted:  #94a3b8;
            --border:      #e2e8f0;
            --radius:      12px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ── SIDEBAR ──────────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
        }
        #sidebar::-webkit-scrollbar { display: none; }

        .sidebar-brand {
            padding: 20px 20px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--sidebar-brd);
            flex-shrink: 0;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: var(--brand);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; font-weight: 900;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #f8fafc;
            letter-spacing: -0.3px;
        }
        .sidebar-brand-sub {
            font-size: 0.65rem;
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-top: 2px;
        }

        .sidebar-section {
            padding: 20px 12px 4px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: var(--sidebar-brd);
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            margin: 1px 8px;
            border-radius: 8px;
            color: var(--sidebar-txt);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }
        .nav-link-item i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .nav-link-item:hover {
            background: var(--sidebar-sec);
            color: #f1f5f9;
        }
        .nav-link-item.active {
            background: var(--brand);
            color: #fff;
            font-weight: 600;
        }
        .nav-link-item.active i { color: #fff; }
        .nav-badge {
            margin-left: auto;
            background: var(--brand);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }
        .nav-link-item.active .nav-badge {
            background: rgba(255,255,255,0.25);
        }

        /* sub-menu */
        .nav-sub-toggle { cursor: pointer; user-select: none; }
        .nav-sub-toggle .chevron {
            margin-left: auto;
            font-size: 0.8rem;
            transition: transform 0.2s;
        }
        .nav-sub-toggle[aria-expanded="true"] .chevron { transform: rotate(90deg); }
        .nav-sub-menu {
            list-style: none;
            padding: 0;
        }
        .nav-sub-menu .nav-link-item {
            padding: 9px 12px 9px 43px;
            font-size: 0.83rem;
            margin: 1px 8px;
        }

        .sidebar-divider {
            border-top: 1px solid var(--sidebar-brd);
            margin: 12px 16px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 12px;
            border-top: 1px solid var(--sidebar-brd);
            flex-shrink: 0;
        }
        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 8px;
            background: var(--sidebar-sec);
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px;
            background: var(--brand);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-role {
            font-size: 0.7rem;
            color: var(--sidebar-txt);
        }
        .sidebar-user-actions {
            margin-left: auto;
            display: flex;
            gap: 4px;
        }
        .sidebar-icon-btn {
            width: 28px; height: 28px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: var(--sidebar-txt);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar-icon-btn:hover { background: var(--sidebar-brd); color: #f1f5f9; }

        /* ── OVERLAY (mobile) ─────────────────────────────── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 1039;
        }
        #sidebar-overlay.show { display: block; }

        /* ── TOPBAR ───────────────────────────────────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            z-index: 1030;
            transition: left 0.3s ease;
        }

        .topbar-toggle {
            display: none;
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--text-sub);
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .topbar-toggle:hover { background: var(--body-bg); }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
        }
        .breadcrumb-item {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .breadcrumb-item.current {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .breadcrumb-sep {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        .topbar-search {
            position: relative;
            flex: 0 0 280px;
        }
        .topbar-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .topbar-search input {
            width: 100%;
            padding: 8px 12px 8px 34px;
            background: var(--body-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--text-primary);
            transition: all 0.2s;
        }
        .topbar-search input:focus {
            outline: none;
            border-color: var(--brand);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(180,64,64,0.08);
        }
        .topbar-search input::placeholder { color: var(--text-muted); }

        .topbar-actions { display: flex; align-items: center; gap: 4px; }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--text-sub);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            transition: all 0.2s;
            position: relative;
        }
        .topbar-btn:hover { background: var(--body-bg); color: var(--text-primary); }

        .topbar-badge {
            position: absolute;
            top: 5px; right: 5px;
            width: 16px; height: 16px;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            font-size: 0.55rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: var(--border);
            margin: 0 4px;
        }

        .topbar-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .topbar-profile:hover { background: var(--body-bg); }
        .topbar-profile-avatar {
            width: 32px; height: 32px;
            background: var(--brand);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem;
            font-weight: 700;
            color: #fff;
        }
        .topbar-profile-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .topbar-profile-role {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Dropdown */
        .topbar-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 220px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
            z-index: 200;
            display: none;
            overflow: hidden;
        }
        .topbar-dropdown.open { display: block; }
        .topbar-dropdown-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            background: var(--body-bg);
        }
        .topbar-dropdown-header .name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .topbar-dropdown-header .email {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 1px;
        }
        .topbar-dropdown a, .topbar-dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 11px 16px;
            font-size: 0.875rem;
            color: var(--text-sub);
            text-decoration: none;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background 0.15s;
            text-align: left;
        }
        .topbar-dropdown a:hover, .topbar-dropdown button:hover {
            background: var(--body-bg);
            color: var(--text-primary);
        }
        .topbar-dropdown a i, .topbar-dropdown button i { font-size: 1rem; width: 18px; }
        .topbar-dropdown .dd-divider { border-top: 1px solid var(--border); margin: 4px 0; }
        .topbar-dropdown .dd-danger { color: #ef4444; }
        .topbar-dropdown .dd-danger:hover { background: #fef2f2; color: #ef4444; }

        /* ── MAIN CONTENT ─────────────────────────────────── */
        #main {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            min-height: calc(100vh - var(--topbar-h));
            padding: 28px;
            transition: margin-left 0.3s ease;
        }

        /* ── CARDS ────────────────────────────────────────── */
        .k-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }
        .k-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .k-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .k-card-title i { color: var(--brand); font-size: 1rem; }
        .k-card-body { padding: 20px; }

        /* KPI cards */
        .kpi-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
            position: relative;
            overflow: hidden;
        }
        .kpi-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: var(--accent, var(--brand));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        .kpi-card:hover::after { transform: scaleX(1); }
        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.08);
            border-color: transparent;
        }
        .kpi-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
        }
        .kpi-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
        }
        .kpi-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 2px;
        }
        .kpi-trend {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            margin-top: 8px;
        }

        /* Tables */
        .k-table { width: 100%; border-collapse: collapse; }
        .k-table thead th {
            background: var(--body-bg);
            padding: 12px 16px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .k-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
            color: var(--text-sub);
            vertical-align: middle;
        }
        .k-table tbody tr:hover td { background: #fafbfc; }
        .k-table tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .k-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
        }
        .k-badge i { font-size: 0.7rem; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-danger  { background: #fee2e2; color: #dc2626; }
        .badge-warning { background: #fef9c3; color: #ca8a04; }
        .badge-info    { background: #dbeafe; color: #2563eb; }
        .badge-purple  { background: #f3e8ff; color: #9333ea; }
        .badge-brand   { background: var(--brand-light); color: var(--brand); }
        .badge-neutral { background: #f1f5f9; color: #64748b; }

        /* Buttons */
        .btn-brand {
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-brand:hover { background: var(--brand-dark); color: #fff; transform: translateY(-1px); }
        .btn-brand-outline {
            background: transparent;
            color: var(--brand);
            border: 1.5px solid var(--brand);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-brand-outline:hover { background: var(--brand-light); }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-sub);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-ghost:hover { background: var(--body-bg); border-color: var(--text-muted); color: var(--text-primary); }

        /* Avatar */
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.4s ease both; }
        .delay-1 { animation-delay: 0.06s; }
        .delay-2 { animation-delay: 0.12s; }
        .delay-3 { animation-delay: 0.18s; }
        .delay-4 { animation-delay: 0.24s; }
        .delay-5 { animation-delay: 0.30s; }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar { left: 0; }
            #main { margin-left: 0; padding: 16px; }
            .topbar-toggle { display: flex; }
            .topbar-search { flex: 0 0 200px; }
        }
        @media (max-width: 575px) {
            .topbar-search { display: none; }
            #main { padding: 12px; }
        }

        /* Page header */
        .page-header {
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }
        .page-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Filter bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .filter-chip {
            padding: 7px 16px;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-sub);
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .filter-chip:hover { border-color: var(--brand); color: var(--brand); }
        .filter-chip.active {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        /* Action icon buttons */
        .icon-btn {
            width: 32px; height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: #fff;
            cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            color: var(--text-sub);
            transition: all 0.15s;
        }
        .icon-btn:hover { background: var(--body-bg); color: var(--text-primary); }
        .icon-btn.danger:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }
        .icon-btn.success:hover { background: #dcfce7; border-color: #86efac; color: #16a34a; }

        @yield('styles')
    </style>
    @stack('styles')
</head>
<body>

<div id="sidebar-overlay"></div>

<!-- ── SIDEBAR ──────────────────────────────────── -->
<aside id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">K</div>
        <div>
            <div class="sidebar-brand-name">KODI</div>
            <div class="sidebar-brand-sub">Admin Panel</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav style="flex: 1; padding-top: 8px;">
        <div class="sidebar-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="ri-dashboard-3-line"></i>
            Dashboard
        </a>

        <div class="sidebar-section" style="margin-top: 8px;">Management</div>
        <a href="{{ route('admin.users.index') }}" class="nav-link-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="ri-group-line"></i>
            Users
        </a>
        <a href="{{ route('admin.properties.index') }}" class="nav-link-item {{ request()->routeIs('admin.properties*') ? 'active' : '' }}">
            <i class="ri-building-2-line"></i>
            Properties
        </a>

        <!-- Leases sub-menu -->
        <a class="nav-link-item nav-sub-toggle {{ request()->routeIs('admin.leases*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#leasesMenu" role="button"
           aria-expanded="{{ request()->routeIs('admin.leases*') ? 'true' : 'false' }}">
            <i class="ri-file-text-line"></i>
            Leases
            <i class="ri-arrow-right-s-line chevron"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.leases*') ? 'show' : '' }}" id="leasesMenu">
            <ul class="nav-sub-menu">
                <li><a href="{{ route('admin.applications.index') }}" class="nav-link-item {{ request()->routeIs('admin.applications*') ? 'active' : '' }}">Applications</a></li>
            </ul>
        </div>

        <a href="{{ route('admin.payments.index') }}" class="nav-link-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="ri-bank-card-line"></i>
            Payments
        </a>
        <a href="{{ route('admin.maintenance.index') }}" class="nav-link-item {{ request()->routeIs('admin.maintenance*') ? 'active' : '' }}">
            <i class="ri-hammer-line"></i>
            Maintenance
        </a>
        <a href="{{ route('admin.disputes.index') }}" class="nav-link-item {{ request()->routeIs('admin.disputes*') ? 'active' : '' }}">
            <i class="ri-scales-3-line"></i>
            Disputes
        </a>

        <div class="sidebar-section" style="margin-top: 8px;">Insights</div>
        <a href="{{ route('admin.reports.index') }}" class="nav-link-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="ri-bar-chart-grouped-line"></i>
            Reports
        </a>
        <a href="{{ route('admin.logs.index') }}" class="nav-link-item {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
            <i class="ri-file-list-3-line"></i>
            Logs
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="nav-link-item {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
            <i class="ri-megaphone-line"></i>
            Announcements
        </a>

        <div class="sidebar-section" style="margin-top: 8px;">System</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="ri-settings-3-line"></i>
            Settings
        </a>
    </nav>

    <!-- Sidebar Footer User -->
    <div class="sidebar-footer">
        <div class="sidebar-user-card">
            <div class="sidebar-user-avatar">{{ substr(auth()->user()->name ?? 'AD', 0, 2) }}</div>
            <div style="flex: 1; min-width: 0;">
                <div class="sidebar-user-name">{{ Str::limit(auth()->user()->name ?? 'Admin', 18) }}</div>
                <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
            </div>
            <div class="sidebar-user-actions">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-icon-btn" title="Logout">
                        <i class="ri-logout-box-r-line"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- ── TOPBAR ────────────────────────────────────── -->
<header id="topbar">
    <button class="topbar-toggle" id="sidebar-toggle">
        <i class="ri-menu-line"></i>
    </button>

    <div class="topbar-breadcrumb">
        <span class="breadcrumb-item">Admin</span>
        <i class="ri-arrow-right-s-line breadcrumb-sep"></i>
        <span class="breadcrumb-item current">@yield('title', 'Dashboard')</span>
    </div>

    <div class="topbar-search d-none d-md-block">
        <i class="ri-search-line"></i>
        <input type="text" placeholder="Search anything...">
    </div>

    <div class="topbar-actions">
        <!-- Fullscreen -->
        <button class="topbar-btn d-none d-lg-flex" id="fullscreen-btn" title="Fullscreen">
            <i class="ri-fullscreen-line"></i>
        </button>

        <!-- Notifications -->
        <button class="topbar-btn" title="Notifications">
            <i class="ri-notification-3-line"></i>
            <span class="topbar-badge">3</span>
        </button>

        <div class="topbar-divider"></div>

        <!-- Profile dropdown -->
        <div class="topbar-profile" id="profile-toggle">
            <div class="topbar-profile-avatar">{{ substr(auth()->user()->name ?? 'AD', 0, 2) }}</div>
            <div class="d-none d-md-block">
                <div class="topbar-profile-name">{{ Str::limit(auth()->user()->name ?? 'Admin', 14) }}</div>
                <div class="topbar-profile-role">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
            </div>
            <i class="ri-arrow-down-s-line text-muted ms-1"></i>

            <div class="topbar-dropdown" id="profile-dropdown">
                <div class="topbar-dropdown-header">
                    <div class="name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="email">{{ auth()->user()->email ?? '' }}</div>
                </div>
                <a href="#"><i class="ri-user-line"></i> My Profile</a>
                <a href="{{ route('admin.settings.index') }}"><i class="ri-settings-3-line"></i> Settings</a>
                <div class="dd-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dd-danger"><i class="ri-logout-box-r-line"></i> Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- ── MAIN ──────────────────────────────────────── -->
<main id="main">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" style="background:#dcfce7;color:#16a34a;font-size:.875rem;">
        <i class="ri-checkbox-circle-line me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 rounded-3" style="background:#fee2e2;color:#dc2626;font-size:.875rem;">
        <i class="ri-error-warning-line me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('content')
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggle  = document.getElementById('sidebar-toggle');

    if (toggle) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    // Profile dropdown
    const profileToggle   = document.getElementById('profile-toggle');
    const profileDropdown = document.getElementById('profile-dropdown');
    if (profileToggle && profileDropdown) {
        profileToggle.addEventListener('click', e => {
            e.stopPropagation();
            profileDropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => profileDropdown.classList.remove('open'));
    }

    // Fullscreen
    const fsBtn = document.getElementById('fullscreen-btn');
    if (fsBtn) {
        fsBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fsBtn.querySelector('i').className = 'ri-fullscreen-exit-line';
            } else {
                document.exitFullscreen();
                fsBtn.querySelector('i').className = 'ri-fullscreen-line';
            }
        });
    }
</script>
@stack('scripts')
</body>
</html>
