@extends('layouts.support')

@section('title', 'Dashboard')

@section('content')
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Karibu Msaidizi, {{ auth()->user()->name ?? 'Support Agent' }}</h2>
    <p style="color: #64748b; margin-bottom: 2rem;">Hivi ndivyo hali ya mfumo leo.</p>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Tiketi Fungu</div>
                <div class="stat-value">{{ $openTickets ?? 24 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Tiketi Zilizofungwa</div>
                <div class="stat-value">{{ $closedTickets ?? 156 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Chat Hai</div>
                <div class="stat-value">{{ $activeChats ?? 7 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Wateja Leo</div>
                <div class="stat-value">{{ $customersToday ?? 18 }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions + Recent Activity -->
    <div class="grid-2">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="{{ route('support.tickets.index') }}" class="quick-action">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #f59e0b;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span class="qa-label">Tengeneza Tiketi Mpya</span>
                    <span class="qa-arrow">&rarr;</span>
                </a>
                <a href="{{ route('support.live-chat') }}" class="quick-action">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #3b82f6;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="qa-label">Anza Mazungumzo</span>
                    <span class="qa-arrow">&rarr;</span>
                </a>
                <a href="{{ route('support.knowledge-base') }}" class="quick-action">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #8b5cf6;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="qa-label">Tafuta Makala</span>
                    <span class="qa-arrow">&rarr;</span>
                </a>
                <a href="{{ route('support.reports') }}" class="quick-action">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #059669;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="qa-label">Angalia Ripoti</span>
                    <span class="qa-arrow">&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h3>Shughuli za Hivi Karibuni</h3>
            </div>
            <div class="card-body">
                <div class="activity-item">
                    <div class="activity-dot activity-dot-green"></div>
                    <div class="activity-content">
                        <div class="activity-text"><span>John Doe</span> alifunga tiketi #1423</div>
                        <div class="activity-time">Dakika 5 zilizopita</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot activity-dot-blue"></div>
                    <div class="activity-content">
                        <div class="activity-text"><span>Jane Smith</span> ametuma ujumbe mpya</div>
                        <div class="activity-time">Dakika 12 zilizopita</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot activity-dot-orange"></div>
                    <div class="activity-content">
                        <div class="activity-text"><span>Peter Kamau</span> amefungua tiketi #1456</div>
                        <div class="activity-time">Saa 1 iliyopita</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot activity-dot-red"></div>
                    <div class="activity-content">
                        <div class="activity-text">Tiketi #1401 <span>imepandishwa</span> kwa kipaumbele</div>
                        <div class="activity-time">Saa 2 zilizopita</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot activity-dot-green"></div>
                    <div class="activity-content">
                        <div class="activity-text"><span>Mary Wanjiku</span> ameridhika na huduma</div>
                        <div class="activity-time">Saa 3 zilizopita</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
