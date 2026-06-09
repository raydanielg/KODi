@extends('layouts.super-admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon svg { width: 24px; height: 24px; }

    .stat-icon.green { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #0d6efd; }
    .stat-icon.blue { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #10B981; }
    .stat-icon.purple { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #8b5cf6; }
    .stat-icon.orange { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #f97316; }
    .stat-icon.red { background: linear-gradient(135deg, #fef2f2, #fee2e2); color: #ef4444; }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-change {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    .stat-change.positive { background: #ecfdf5; color: #10B981; }
    .stat-change.negative { background: #fef2f2; color: #ef4444; }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .btn-action {
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

    .card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .card-body {
        padding: 1.5rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .activity-item:last-child { border-bottom: none; }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon svg { width: 20px; height: 20px; }

    .activity-content { flex: 1; }

    .activity-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-action {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        text-align: center;
    }

    .quick-action:hover {
        background: #fff;
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
    }

    .quick-action svg { width: 28px; height: 28px; color: #64748b; }
    .quick-action:hover svg { color: #0d6efd; }

    .quick-action-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
    }

    .quick-action-desc {
        font-size: 0.75rem;
        color: #64748b;
    }

    .system-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #ecfdf5;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #10B981;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10B981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<!-- Welcome Section -->
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
        Karibu, {{ auth()->user()->name ?? 'Super Admin' }} 👋
    </h2>
    <p style="color: #64748b; font-size: 1rem;">
        Hii ni dashboard yako ya Super Admin. Una udhibiti kamili wa mfumo mzima.
    </p>
</div>


<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="stat-change positive">+12%</span>
        </div>
        <div class="stat-value">1,234</div>
        <div class="stat-label">Watumiaji Wote</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="stat-change positive">+5%</span>
        </div>
        <div class="stat-value">56</div>
        <div class="stat-label">Watumiaji Walioingia Leo</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
            <span class="stat-change positive">+2%</span>
        </div>
        <div class="stat-value">24</div>
        <div class="stat-label">Databases</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <span class="stat-change positive">+8%</span>
        </div>
        <div class="stat-value">156</div>
        <div class="stat-label">Backups Zilizohifadhiwa</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <span class="stat-change negative">-15%</span>
        </div>
        <div class="stat-value">3</div>
        <div class="stat-label">Matatizo Yaliyobaki</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">Haraka - Vitendo</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions">
            <a href="{{ route('super-admin.users.index') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="quick-action-title">Ongeza Mtumiaji Mpya</span>
                <span class="quick-action-desc">Unda akaunti mpya</span>
            </a>
            <a href="{{ route('super-admin.database.index') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                <span class="quick-action-title">Hifadhi Database</span>
                <span class="quick-action-desc">Fanya backup ya data</span>
            </a>
            <a href="{{ route('super-admin.settings.index') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="quick-action-title">Mipangilio ya Mfumo</span>
                <span class="quick-action-desc">Badilisha settings</span>
            </a>
            <a href="{{ route('super-admin.maintenance.index') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="quick-action-title">Hali ya Matengenezo</span>
                <span class="quick-action-desc">Washa/zima maintenance</span>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Shughuli za Hivi Karibuni</h3>
        <a href="#" style="color: #0d6efd; text-decoration: none; font-size: 0.875rem; font-weight: 600;">Angalia Zote</a>
    </div>
    <div class="card-body">
        <div class="activity-item">
            <div class="activity-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Mtumiaji mpya alijiunga - John Doe</div>
                <div class="activity-time">Dakika 5 zilizopita</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Backup ya database ikikamilika</div>
                <div class="activity-time">Saa 1 iliyopita</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Mipangilio ya mfumo yalibadilishwa</div>
                <div class="activity-time">Saa 3 zilizopita</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon orange">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Alert ya kutathmini iliondolewa</div>
                <div class="activity-time">Saa 6 zilizopita</div>
            </div>
        </div>
    </div>
</div>
@endsection
