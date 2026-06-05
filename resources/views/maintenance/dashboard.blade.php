@extends('layouts.maintenance')

@section('title', 'Dashboard')

@section('content')
    <div class="welcome-section">
        <h2 class="welcome-title">Karibu Fundi, {{ auth()->user()->name ?? 'Rafiki' }}</h2>
        <p class="welcome-subtitle">Huu ni muhtasari wa kazi zako za leo</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Kazi Nilizopewa</span>
                <div class="stat-card-icon blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $assignedCount ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Inayoendelea</span>
                <div class="stat-card-icon amber">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $inProgressCount ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Imekamilika</span>
                <div class="stat-card-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $completedCount ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Leo</span>
                <div class="stat-card-icon purple">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $todayCount ?? 0 }}</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h3>Vitendo Haraka</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions-grid">
                    <a href="{{ route('maintenance.tasks.assigned') }}" class="quick-action">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Kazi Mpya</span>
                        <small>Tazama kazi ulizopewa</small>
                    </a>
                    <a href="{{ route('maintenance.schedule') }}" class="quick-action">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Ratiba</span>
                        <small>Angalia ratiba yako</small>
                    </a>
                    <a href="{{ route('maintenance.tasks.in-progress') }}" class="quick-action">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Inayoendelea</span>
                        <small>Endelea na kazi</small>
                    </a>
                    <a href="{{ route('maintenance.tasks.completed') }}" class="quick-action">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Imekamilika</span>
                        <small>Kazi zilizokamilika</small>
                    </a>
                    <a href="{{ route('maintenance.messages') }}" class="quick-action">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span>Ujumbe</span>
                        <small>Tuma ujumbe</small>
                    </a>
                    <a href="{{ route('maintenance.reports') }}" class="quick-action">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Ripoti</span>
                        <small>Angalia ripoti</small>
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Shughuli za Hivi Karibuni</h3>
                <a href="{{ route('maintenance.tasks.assigned') }}" class="view-all">Angalia Zote</a>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-icon {{ $activity['color'] ?? 'blue' }}">
                                {!! $activity['icon'] ?? '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' !!}
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">{{ $activity['text'] ?? 'Hakuna shughuli' }}</div>
                                <div class="activity-time">{{ $activity['time'] ?? '' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-icon blue">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">Hakuna shughuli za hivi karibuni</div>
                                <div class="activity-time">Endelea na kazi yako</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
