@extends('layouts.investor')

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

    .stat-icon.green { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #10B981; }
    .stat-icon.blue { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #3b82f6; }
    .stat-icon.purple { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #8b5cf6; }
    .stat-icon.orange { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #f97316; }

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
        border-color: #10B981;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(16,185,129,0.15);
    }

    .quick-action svg { width: 28px; height: 28px; color: #64748b; }
    .quick-action:hover svg { color: #10B981; }

    .quick-action-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
    }

    .quick-action-desc {
        font-size: 0.75rem;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<!-- Welcome Section -->
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
        Karibu Mwekezaji, {{ auth()->user()->name ?? 'Investor' }}
    </h2>
    <p style="color: #64748b; font-size: 1rem;">
        Hii ni dashboard yako ya uwekezaji. Angalia takwimu na taarifa muhimu za kampuni.
    </p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="stat-change positive">+18%</span>
        </div>
        <div class="stat-value">TSh 45.6M</div>
        <div class="stat-label">Mapato ya Jumla</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="stat-change positive">+12%</span>
        </div>
        <div class="stat-value">8,432</div>
        <div class="stat-label">Wateja</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <span class="stat-change positive">+7%</span>
        </div>
        <div class="stat-value">2,156</div>
        <div class="stat-label">Mauzo</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <span class="stat-change positive">+23%</span>
        </div>
        <div class="stat-value">23.5%</div>
        <div class="stat-label">Ukuaji</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">Vitendo vya Haraka</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions">
            <a href="{{ route('investor.financial-dashboard') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="quick-action-title">Financial Dashboard</span>
                <span class="quick-action-desc">Angalia fedha za kampuni</span>
            </a>
            <a href="{{ route('investor.key-metrics') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="quick-action-title">Key Metrics</span>
                <span class="quick-action-desc">Vipimo muhimu vya kampuni</span>
            </a>
            <a href="{{ route('investor.reports') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="quick-action-title">Reports</span>
                <span class="quick-action-desc">Pakua taarifa za fedha</span>
            </a>
            <a href="{{ route('investor.cap-table') }}" class="quick-action">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
                <span class="quick-action-title">Cap Table</span>
                <span class="quick-action-desc">Muundo wa hisa</span>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity / Performance -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Shughuli za Hivi Karibuni</h3>
        <a href="#" style="color: #10B981; text-decoration: none; font-size: 0.875rem; font-weight: 600;">Angalia Zote</a>
    </div>
    <div class="card-body">
        <div class="activity-item">
            <div class="activity-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Mapato ya mwezi yameongezeka kwa 18%</div>
                <div class="activity-time">Saa 2 zilizopita</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Wateja wapya 120 wamejiunga wiki hii</div>
                <div class="activity-time">Saa 5 zilizopita</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Mauzo ya robo mwaka yamefikiwa</div>
                <div class="activity-time">Siku 1 iliyopita</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon orange">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="activity-content">
                <div class="activity-title">Ukuaji wa jumla umeongezeka hadi 23.5%</div>
                <div class="activity-time">Siku 2 zilizopita</div>
            </div>
        </div>
    </div>
</div>
@endsection
