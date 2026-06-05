@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')
<style>
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
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: #10B981;
        box-shadow: 0 4px 20px rgba(16,185,129,0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 24px;
        height: 24px;
    }

    .stat-icon.green { background: #ecfdf5; }
    .stat-icon.green svg { color: #10B981; stroke: #10B981; }
    .stat-icon.blue { background: #eff6ff; }
    .stat-icon.blue svg { color: #3b82f6; stroke: #3b82f6; }
    .stat-icon.orange { background: #fff7ed; }
    .stat-icon.orange svg { color: #f97316; stroke: #f97316; }
    .stat-icon.purple { background: #f5f3ff; }
    .stat-icon.purple svg { color: #8b5cf6; stroke: #8b5cf6; }

    .stat-info { flex: 1; }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .stat-label span {
        display: block;
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    .bottom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
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

    .card-link {
        font-size: 0.85rem;
        color: #10B981;
        text-decoration: none;
        font-weight: 600;
    }

    .card-link:hover { color: #059669; }

    .card-body { padding: 1.5rem; }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fafafa;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        border-color: #10B981;
        background: #f0fdf4;
    }

    .action-btn svg {
        width: 20px;
        height: 20px;
        color: #10B981;
        flex-shrink: 0;
    }

    .action-btn-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
    }

    .action-btn-desc {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .activity-item:last-child { border-bottom: none; }

    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .activity-dot.green { background: #10B981; }
    .activity-dot.blue { background: #3b82f6; }
    .activity-dot.orange { background: #f97316; }
    .activity-dot.purple { background: #8b5cf6; }

    .activity-text {
        flex: 1;
        font-size: 0.85rem;
        color: #334155;
        line-height: 1.4;
    }

    .activity-text strong { color: #0f172a; }

    .activity-time {
        font-size: 0.75rem;
        color: #94a3b8;
        flex-shrink: 0;
    }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .bottom-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .actions-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="welcome-section">
    <h2 class="welcome-title">Karibu Mpangaji, {{ auth()->user()->name ?? 'Mpangaji' }} 👋</h2>
    <p class="welcome-subtitle">Huu ni mraba wako wa nyumbani — simamia ukodishaji wako kwa urahisi.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $rentalCount ?? '—' }}</div>
            <div class="stat-label">Nyumba Yangu <span>{{ $rentalAddress ?? 'Hakuna nyumba' }}</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingPayments ?? '—' }}</div>
            <div class="stat-label">Malipo <span>{{ $nextPaymentDate ?? 'Hakuna malipo yanayotarajiwa' }}</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $openMaintenance ?? '—' }}</div>
            <div class="stat-label">Matengenezo <span>{{ $openMaintenance > 0 ? 'Inahitaji tahadhari' : 'Hakuna matengenezo' }}</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $unreadMessages ?? '—' }}</div>
            <div class="stat-label">Ujumbe <span>{{ $unreadMessages > 0 ? $unreadMessages.' yasisomwa' : 'Hakuna ujumbe mpya' }}</span></div>
        </div>
    </div>
</div>

<div class="bottom-grid">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Vitendo vya Haraka</span>
            <a href="{{ route('tenant.rentals.index') }}" class="card-link">Tazama zote</a>
        </div>
        <div class="card-body">
            <div class="actions-grid">
                <a href="{{ route('tenant.payments.index') }}" class="action-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <div class="action-btn-text">Lipa Kodi</div>
                        <div class="action-btn-desc">Lipa kodi yako ya nyumba</div>
                    </div>
                </a>
                <a href="{{ route('tenant.maintenance.create') }}" class="action-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div>
                        <div class="action-btn-text">Ripoti Matatizo</div>
                        <div class="action-btn-desc">Ripoti tatizo la nyumba</div>
                    </div>
                </a>
                <a href="{{ route('tenant.messages.create') }}" class="action-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <div>
                        <div class="action-btn-text">Tuma Ujumbe</div>
                        <div class="action-btn-desc">Tuma ujumbe kwa mwenye nyumba</div>
                    </div>
                </a>
                <a href="{{ route('tenant.rentals.contact') }}" class="action-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <div class="action-btn-text">Wasiliana na Mwenye Nyumba</div>
                        <div class="action-btn-desc">Pata msaada kutoka kwa mmiliki</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Shughuli za Karibuni</span>
            <a href="{{ route('tenant.notifications.index') }}" class="card-link">Nyote</a>
        </div>
        <div class="card-body">
            @if(isset($recentActivities) && count($recentActivities) > 0)
                @foreach($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-dot {{ $activity['color'] ?? 'green' }}"></div>
                        <div class="activity-text">{!! $activity['text'] !!}</div>
                        <div class="activity-time">{{ $activity['time'] }}</div>
                    </div>
                @endforeach
            @else
                <div class="activity-item">
                    <div class="activity-dot green"></div>
                    <div class="activity-text"><strong>Karibu kwenye KODI!</strong> Anza kwa kulipa kodi yako au kuangalia nyumba yako.</div>
                    <div class="activity-time">Sasa</div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot blue"></div>
                    <div class="activity-text">Hakuna shughuli za hivi karibuni. Shughuli zako zitaonekana hapa.</div>
                    <div class="activity-time">—</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
