@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .metric-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    @media (max-width: 1199px) { .metric-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .metric-grid { grid-template-columns: 1fr; } }

    .metric-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
        position: relative;
        overflow: hidden;
    }
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--card-accent, var(--brand));
        border-radius: 3px 3px 0 0;
    }
    .metric-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
    .metric-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .metric-value { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); line-height: 1.1; margin-top: 12px; }
    .metric-label { font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-top: 2px; }
    .metric-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 12px; }
    .metric-trend { display: inline-flex; align-items: center; gap: 3px; font-size: 0.78rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
    .trend-up   { background: #dcfce7; color: #16a34a; }
    .trend-down { background: #fee2e2; color: #dc2626; }
    .trend-neu  { background: #f1f5f9; color: #64748b; }
    .metric-sub { font-size: 0.72rem; color: var(--text-muted); }

    .chart-row { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 24px; }
    @media (max-width: 991px) { .chart-row { grid-template-columns: 1fr; } }

    .bottom-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    @media (max-width: 991px) { .bottom-row { grid-template-columns: 1fr; } }

    .activity-list { max-height: 340px; overflow-y: auto; }
    .activity-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 20px;
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-item:hover { background: #fafbfc; }
    .activity-dot {
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .quick-action {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 16px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--text-sub);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 8px;
    }
    .quick-action:last-child { margin-bottom: 0; }
    .quick-action:hover { border-color: var(--brand); color: var(--brand); transform: translateX(4px); }
    .quick-action .qa-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .quick-action .qa-label { flex: 1; }
    .quick-action i.arrow { font-size: 0.8rem; color: var(--text-muted); }

    .period-btns { display: flex; gap: 4px; }
    .period-btn {
        padding: 6px 14px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-sub);
        background: #fff;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }
    .period-btn:hover { border-color: var(--brand); color: var(--brand); }
    .period-btn.active { background: var(--brand); border-color: var(--brand); color: #fff; }

    .txn-row td { font-size: 0.82rem; }
    .txn-id { font-weight: 700; color: var(--brand); }
</style>
@endpush

@section('content')
<!-- Page header -->
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">Good {{ now()->format('H') < 12 ? 'morning' : (now()->format('H') < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }} 👋</h1>
        <p class="page-subtitle">Here's what's happening on KODI today — {{ now()->format('l, d F Y') }}</p>
    </div>
    <div class="period-btns">
        <a href="?period=7d"  class="period-btn {{ $period=='7d'  ? 'active' : '' }}">7D</a>
        <a href="?period=30d" class="period-btn {{ $period=='30d' ? 'active' : '' }}">30D</a>
        <a href="?period=90d" class="period-btn {{ $period=='90d' ? 'active' : '' }}">90D</a>
        <a href="?period=1y"  class="period-btn {{ $period=='1y'  ? 'active' : '' }}">1Y</a>
        <button class="period-btn" onclick="location.reload()"><i class="ri-refresh-line"></i></button>
    </div>
</div>

<!-- KPI Metrics -->
<div class="metric-grid">
    <div class="metric-card fade-up delay-1" style="--card-accent:#B44040;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Total Properties</div>
                <div class="metric-value">{{ number_format($totalProperties) }}</div>
            </div>
            <div class="metric-icon" style="background:#fdf0f0;color:#B44040;">
                <i class="ri-building-2-line"></i>
            </div>
        </div>
        <div class="metric-footer">
            <span class="metric-trend {{ $propertiesGrowth >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="ri-arrow-{{ $propertiesGrowth >= 0 ? 'up' : 'down' }}-line"></i>
                {{ number_format(abs($propertiesGrowth), 1) }}%
            </span>
            <span class="metric-sub">vs last period</span>
        </div>
    </div>

    <div class="metric-card fade-up delay-2" style="--card-accent:#16a34a;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Active Tenants</div>
                <div class="metric-value">{{ number_format($totalTenants) }}</div>
            </div>
            <div class="metric-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="ri-group-line"></i>
            </div>
        </div>
        <div class="metric-footer">
            <span class="metric-trend {{ $tenantsGrowth >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="ri-arrow-{{ $tenantsGrowth >= 0 ? 'up' : 'down' }}-line"></i>
                {{ number_format(abs($tenantsGrowth), 1) }}%
            </span>
            <span class="metric-sub">new this period</span>
        </div>
    </div>

    <div class="metric-card fade-up delay-3" style="--card-accent:#2563eb;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Revenue (TZS)</div>
                <div class="metric-value">{{ number_format($totalPayments/1000000, 1) }}M</div>
            </div>
            <div class="metric-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
        </div>
        <div class="metric-footer">
            <span class="metric-trend {{ $revenueGrowth >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="ri-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}-line"></i>
                {{ number_format(abs($revenueGrowth), 1) }}%
            </span>
            <span class="metric-sub">vs last period</span>
        </div>
    </div>

    <div class="metric-card fade-up delay-4" style="--card-accent:#ca8a04;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="metric-label">Maintenance</div>
                <div class="metric-value">{{ number_format($totalRequests) }}</div>
            </div>
            <div class="metric-icon" style="background:#fef9c3;color:#ca8a04;">
                <i class="ri-hammer-line"></i>
            </div>
        </div>
        <div class="metric-footer">
            <span class="metric-trend trend-neu">
                <i class="ri-time-line"></i> {{ $pendingRequests }} pending
            </span>
            <span class="metric-sub">open requests</span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="chart-row fade-up delay-2">
    <!-- Main Revenue Chart -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title">
                <i class="ri-line-chart-line"></i>
                Platform Growth
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex gap-3" id="chartLegend" style="font-size:0.78rem; color: var(--text-muted);">
                    <label class="d-flex align-items-center gap-1 cursor-pointer mb-0" style="cursor:pointer;">
                        <input type="radio" name="chartMetric" value="revenue" checked style="accent-color:var(--brand);"> Revenue
                    </label>
                    <label class="d-flex align-items-center gap-1 mb-0" style="cursor:pointer;">
                        <input type="radio" name="chartMetric" value="tenants" style="accent-color:#16a34a;"> Tenants
                    </label>
                </div>
            </div>
        </div>
        <div class="k-card-body" style="padding-top: 12px;">
            <div style="height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Chart -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-pie-chart-2-line"></i> Payment Methods</div>
        </div>
        <div class="k-card-body">
            <div style="height: 180px; display:flex; align-items:center; justify-content:center;">
                <canvas id="paymentDonut"></canvas>
            </div>
            <div class="mt-3" style="max-height:120px; overflow-y:auto;">
                @forelse($paymentMethods as $i => $pm)
                <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom: 1px solid var(--border); font-size:0.82rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:8px;height:8px;border-radius:50%;background:{{ ['#B44040','#2563eb','#16a34a','#ca8a04','#9333ea'][$i % 5] }};"></div>
                        <span class="text-capitalize fw-600" style="color:var(--text-sub);">{{ $pm->payment_method ?? 'Unknown' }}</span>
                    </div>
                    <div style="color:var(--text-primary);font-weight:700;">
                        {{ number_format($pm->count) }} <span style="color:var(--text-muted);font-weight:400;">({{ number_format($pm->total/1000000,1) }}M)</span>
                    </div>
                </div>
                @empty
                <p style="color:var(--text-muted);text-align:center;font-size:0.82rem;padding:16px 0;">No payment data yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="bottom-row fade-up delay-3">
    <!-- Recent Activity -->
    <div class="k-card" style="grid-column: span 1;">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-pulse-line"></i> Recent Activity</div>
            <a href="#" style="font-size:0.78rem;color:var(--brand);font-weight:600;text-decoration:none;">View all</a>
        </div>
        <div class="activity-list">
            @forelse($recentTenants as $t)
            <div class="activity-item">
                <div class="activity-dot" style="background:#dcfce7;color:#16a34a;">
                    <i class="ri-user-add-line"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $t->name }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">New tenant registered</div>
                </div>
                <div style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;flex-shrink:0;">{{ $t->created_at?->diffForHumans() }}</div>
            </div>
            @endforeach
            @forelse($recentPayments as $p)
            <div class="activity-item">
                <div class="activity-dot" style="background:#dbeafe;color:#2563eb;">
                    <i class="ri-bank-card-line"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);">TZS {{ number_format($p->amount) }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">Payment via {{ ucfirst($p->payment_method ?? '—') }}</div>
                </div>
                <div style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;flex-shrink:0;">{{ $p->created_at?->diffForHumans() }}</div>
            </div>
            @endforeach
            @forelse($recentRequests as $r)
            <div class="activity-item">
                <div class="activity-dot" style="background:#fef9c3;color:#ca8a04;">
                    <i class="ri-tools-line"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit($r->title ?? $r->description ?? 'Maintenance', 28) }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">Maintenance request</div>
                </div>
                <div style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;flex-shrink:0;">{{ $r->created_at?->diffForHumans() }}</div>
            </div>
            @endforeach
            @if($recentTenants->isEmpty() && $recentPayments->isEmpty() && $recentRequests->isEmpty())
            <div style="text-align:center;padding:40px 20px;color:var(--text-muted);font-size:0.85rem;">
                <i class="ri-inbox-line" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                No recent activity
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-flashlight-line"></i> Quick Actions</div>
        </div>
        <div class="k-card-body">
            <a href="{{ route('admin.users.index') }}" class="quick-action">
                <div class="qa-icon" style="background:#dcfce7;color:#16a34a;"><i class="ri-group-line"></i></div>
                <span class="qa-label">Manage Users</span>
                <i class="ri-arrow-right-s-line arrow"></i>
            </a>
            <a href="{{ route('admin.properties.index') }}" class="quick-action">
                <div class="qa-icon" style="background:#fdf0f0;color:#B44040;"><i class="ri-building-2-line"></i></div>
                <span class="qa-label">Properties</span>
                <i class="ri-arrow-right-s-line arrow"></i>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="quick-action">
                <div class="qa-icon" style="background:#dbeafe;color:#2563eb;"><i class="ri-bank-card-line"></i></div>
                <span class="qa-label">Payments</span>
                <i class="ri-arrow-right-s-line arrow"></i>
            </a>
            <a href="{{ route('admin.maintenance.index') }}" class="quick-action">
                <div class="qa-icon" style="background:#fef9c3;color:#ca8a04;"><i class="ri-hammer-line"></i></div>
                <span class="qa-label">Maintenance</span>
                <i class="ri-arrow-right-s-line arrow"></i>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="quick-action">
                <div class="qa-icon" style="background:#f3e8ff;color:#9333ea;"><i class="ri-bar-chart-2-line"></i></div>
                <span class="qa-label">View Reports</span>
                <i class="ri-arrow-right-s-line arrow"></i>
            </a>
            <a href="{{ route('admin.announcements.create') }}" class="quick-action">
                <div class="qa-icon" style="background:#fdf0f0;color:#B44040;"><i class="ri-megaphone-line"></i></div>
                <span class="qa-label">New Announcement</span>
                <i class="ri-arrow-right-s-line arrow"></i>
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-exchange-line"></i> Recent Transactions</div>
            <a href="{{ route('admin.payments.index') }}" style="font-size:0.78rem;color:var(--brand);font-weight:600;text-decoration:none;">View all</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="k-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $txn)
                    <tr class="txn-row">
                        <td class="txn-id">#{{ str_pad($txn->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td style="font-weight:700;color:var(--text-primary);">{{ number_format($txn->amount) }}</td>
                        <td><span class="k-badge badge-neutral text-capitalize">{{ $txn->payment_method ?? '—' }}</span></td>
                        <td style="color:var(--text-muted);">{{ $txn->created_at?->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted);">No transactions</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bar Chart -->
<div class="k-card fade-up delay-4">
    <div class="k-card-header">
        <div class="k-card-title"><i class="ri-bar-chart-grouped-line"></i> Monthly Revenue — {{ now()->format('Y') }}</div>
        <a href="{{ route('admin.reports.index') }}" class="btn-ghost" style="font-size:0.78rem;padding:6px 12px;">Full Report <i class="ri-external-link-line"></i></a>
    </div>
    <div class="k-card-body" style="padding-top:12px;">
        <div style="height:240px;">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const brandRed   = '#B44040';
const brandGreen = '#16a34a';
const brandBlue  = '#2563eb';
const gridColor  = 'rgba(0,0,0,0.04)';

Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.font.size   = 12;

// ─── Revenue / Tenant line chart ───────────────────────────
const revenueData = {!! json_encode($revenueData) !!};
const tenantsData = {!! json_encode($tenantsData) !!};
const labels      = {!! json_encode($monthLabels) !!};

const revCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revCtx, {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Revenue (TZS)',
            data: revenueData,
            borderColor: brandRed,
            backgroundColor: 'rgba(180,64,64,0.08)',
            tension: 0.45,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: brandRed,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleFont: { size: 12, weight: '700' },
                bodyFont: { size: 11 },
                padding: 12,
                callbacks: {
                    label: ctx => 'TZS ' + ctx.parsed.y.toLocaleString()
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: gridColor },
                ticks: { callback: v => 'TZS ' + (v/1000000).toFixed(1) + 'M', color: '#94a3b8' }
            },
            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
        }
    }
});

// Toggle metric
document.querySelectorAll('input[name="chartMetric"]').forEach(r => {
    r.addEventListener('change', function() {
        if (this.value === 'revenue') {
            revenueChart.data.datasets[0].data = revenueData;
            revenueChart.data.datasets[0].borderColor = brandRed;
            revenueChart.data.datasets[0].backgroundColor = 'rgba(180,64,64,0.08)';
            revenueChart.data.datasets[0].pointBackgroundColor = brandRed;
            revenueChart.data.datasets[0].label = 'Revenue (TZS)';
            revenueChart.options.scales.y.ticks.callback = v => 'TZS ' + (v/1000000).toFixed(1) + 'M';
        } else {
            revenueChart.data.datasets[0].data = tenantsData;
            revenueChart.data.datasets[0].borderColor = brandGreen;
            revenueChart.data.datasets[0].backgroundColor = 'rgba(22,163,74,0.08)';
            revenueChart.data.datasets[0].pointBackgroundColor = brandGreen;
            revenueChart.data.datasets[0].label = 'New Tenants';
            revenueChart.options.scales.y.ticks.callback = v => v;
        }
        revenueChart.update();
    });
});

// ─── Payment donut ─────────────────────────────────────────
@if($paymentMethods->isNotEmpty())
const donutCtx = document.getElementById('paymentDonut').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentMethods->pluck('payment_method')->map(fn($m) => ucfirst($m ?? 'Unknown'))) !!},
        datasets: [{
            data: {!! json_encode($paymentMethods->pluck('count')) !!},
            backgroundColor: ['#B44040','#2563eb','#16a34a','#ca8a04','#9333ea'],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#0f172a', padding: 10 }
        }
    }
});
@else
document.getElementById('paymentDonut').parentElement.innerHTML = '<p style="text-align:center;padding:40px 0;color:#94a3b8;font-size:0.82rem;">No data</p>';
@endif

// ─── Bar chart ─────────────────────────────────────────────
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Revenue',
            data: revenueData,
            backgroundColor: labels.map((_, i) => i === labels.length-1 ? brandRed : 'rgba(180,64,64,0.2)'),
            borderColor: brandRed,
            borderWidth: 1.5,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                callbacks: { label: ctx => 'TZS ' + ctx.parsed.y.toLocaleString() }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: gridColor },
                ticks: { callback: v => (v/1000000).toFixed(1) + 'M', color: '#94a3b8' }
            },
            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
        }
    }
});
</script>
@endpush
