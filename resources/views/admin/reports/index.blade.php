@extends('layouts.admin')
@section('title', 'Reports')

@push('styles')
<style>
    .report-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    @media (max-width: 991px) { .report-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <p class="page-subtitle">Platform performance overview and insights</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" style="display:flex;gap:8px;align-items:center;">
            <select name="year" onchange="this.form.submit()"
                style="padding:8px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.875rem;background:#fff;font-weight:600;">
                @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
        <button class="btn-ghost" onclick="Swal.fire('Export','PDF/Excel export coming soon.','info')">
            <i class="ri-download-2-line"></i> Export PDF
        </button>
    </div>
</div>

<!-- YTD Summary -->
<div class="row g-3 mb-4 fade-up delay-1">
    @php $ycards = [
        ['label'=>'YTD Revenue','value'=>'TZS '.number_format($ytdRevenue/1000000,1).'M','color'=>'#16a34a','bg'=>'#dcfce7','icon'=>'ri-money-dollar-circle-line'],
        ['label'=>'Transactions','value'=>number_format($ytdTxns),'color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'ri-exchange-line'],
        ['label'=>'New Tenants','value'=>number_format($ytdTenants),'color'=>'#B44040','bg'=>'#fdf0f0','icon'=>'ri-user-add-line'],
        ['label'=>'New Properties','value'=>number_format($ytdProps),'color'=>'#9333ea','bg'=>'#f3e8ff','icon'=>'ri-building-2-line'],
    ]; @endphp
    @foreach($ycards as $c)
    <div class="col-6 col-lg-3">
        <div class="kpi-card" style="--accent:{{ $c['color'] }};">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">{{ $c['label'] }}</div>
                    <div class="kpi-value" style="font-size:1.5rem;">{{ $c['value'] }}</div>
                </div>
                <div class="kpi-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};"><i class="{{ $c['icon'] }}"></i></div>
            </div>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:8px;">Year {{ $year }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Main Charts -->
<div class="report-grid fade-up delay-2">
    <!-- Revenue Line -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-line-chart-line"></i> Monthly Revenue — {{ $year }}</div>
        </div>
        <div class="k-card-body" style="padding-top:12px;">
            <div style="height:260px;"><canvas id="revenueLine"></canvas></div>
        </div>
    </div>

    <!-- Tenant Growth -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-group-line"></i> New Tenants — {{ $year }}</div>
        </div>
        <div class="k-card-body" style="padding-top:12px;">
            <div style="height:260px;"><canvas id="tenantBar"></canvas></div>
        </div>
    </div>
</div>

<div class="report-grid fade-up delay-3">
    <!-- Property Types Donut -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-pie-chart-2-line"></i> Property Type Distribution</div>
        </div>
        <div class="k-card-body">
            <div style="height:220px;display:flex;align-items:center;justify-content:center;">
                <canvas id="propTypeChart"></canvas>
            </div>
            <div class="mt-3" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                @foreach($propTypes as $type => $count)
                <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;">
                    <div style="width:8px;height:8px;border-radius:50%;background:{{ ['apartment'=>'#B44040','house'=>'#2563eb','commercial'=>'#16a34a','room'=>'#ca8a04'][$type] ?? '#64748b' }};"></div>
                    <span style="color:var(--text-sub);text-transform:capitalize;">{{ str_replace('_',' ',$type) }}</span>
                    <span style="margin-left:auto;font-weight:700;color:var(--text-primary);">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-bank-card-line"></i> Payment Methods — {{ $year }}</div>
        </div>
        <div class="k-card-body">
            @if($payMethods->isEmpty())
            <div style="text-align:center;padding:40px;color:var(--text-muted);font-size:0.85rem;">
                <i class="ri-inbox-line" style="font-size:2rem;display:block;margin-bottom:8px;"></i>No data for {{ $year }}
            </div>
            @else
            @foreach($payMethods as $pm)
            @php $pct = $payMethods->sum('count') > 0 ? ($pm->count / $payMethods->sum('count') * 100) : 0; @endphp
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--text-primary);text-transform:capitalize;">{{ $pm->payment_method ?? 'Unknown' }}</span>
                    <div style="font-size:0.8rem;color:var(--text-muted);">
                        {{ $pm->count }} txns · <span style="font-weight:700;color:var(--text-primary);">TZS {{ number_format($pm->total/1000000,1) }}M</span>
                    </div>
                </div>
                <div style="background:var(--body-bg);border-radius:20px;height:8px;">
                    <div style="width:{{ $pct }}%;height:100%;border-radius:20px;background:var(--brand);transition:width 0.8s ease;"></div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Monthly Breakdown Table -->
<div class="k-card fade-up delay-4">
    <div class="k-card-header">
        <div class="k-card-title"><i class="ri-table-line"></i> Monthly Breakdown — {{ $year }}</div>
        <span style="font-size:0.78rem;color:var(--text-muted);">Revenue per month</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="k-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Revenue (TZS)</th>
                    <th>New Tenants</th>
                    <th>Progress</th>
                </tr>
            </thead>
            <tbody>
                @php $maxRev = max($monthlyRevenue ?: [1]); @endphp
                @foreach($monthLabels as $i => $label)
                <tr>
                    <td style="font-weight:700;color:var(--text-primary);">{{ $label }} {{ $year }}</td>
                    <td style="font-weight:800;color:{{ $monthlyRevenue[$i] > 0 ? 'var(--brand)' : 'var(--text-muted)' }};">
                        {{ $monthlyRevenue[$i] > 0 ? 'TZS '.number_format($monthlyRevenue[$i]) : '—' }}
                    </td>
                    <td style="font-weight:600;color:var(--text-primary);">{{ $tenantGrowth[$i] > 0 ? $tenantGrowth[$i] : '—' }}</td>
                    <td style="min-width:160px;">
                        @if($maxRev > 0)
                        <div style="background:var(--body-bg);border-radius:20px;height:6px;">
                            <div style="width:{{ ($monthlyRevenue[$i]/$maxRev)*100 }}%;height:100%;border-radius:20px;background:var(--brand);"></div>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels   = {!! json_encode($monthLabels) !!};
const revenue  = {!! json_encode($monthlyRevenue) !!};
const tenants  = {!! json_encode($tenantGrowth) !!};
const gridC    = 'rgba(0,0,0,0.04)';

Chart.defaults.font.family = 'Inter, sans-serif';

// Revenue line
new Chart(document.getElementById('revenueLine').getContext('2d'), {
    type: 'line',
    data: { labels, datasets: [{
        label: 'Revenue', data: revenue,
        borderColor: '#B44040', backgroundColor: 'rgba(180,64,64,0.08)',
        tension: 0.45, fill: true, pointRadius: 4,
        pointBackgroundColor: '#B44040', pointBorderColor: '#fff', pointBorderWidth: 2
    }]},
    options: { responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false },
            tooltip: { backgroundColor:'#0f172a', callbacks: { label: ctx => 'TZS '+ctx.parsed.y.toLocaleString() } } },
        scales: {
            y: { beginAtZero: true, grid: { color: gridC }, ticks: { callback: v => (v/1000000).toFixed(1)+'M', color:'#94a3b8' } },
            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
        }
    }
});

// Tenant bar
new Chart(document.getElementById('tenantBar').getContext('2d'), {
    type: 'bar',
    data: { labels, datasets: [{
        label: 'New Tenants', data: tenants,
        backgroundColor: labels.map((_,i) => `rgba(37,99,235,${i===labels.length-1?0.9:0.3})`),
        borderColor: '#2563eb', borderWidth: 1.5, borderRadius: 6
    }]},
    options: { responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: gridC }, ticks: { color: '#94a3b8' } },
            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
        }
    }
});

// Property type donut
@if($propTypes->isNotEmpty())
new Chart(document.getElementById('propTypeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($propTypes->keys()->map(fn($k) => ucfirst(str_replace('_',' ',$k)))) !!},
        datasets: [{ data: {!! json_encode($propTypes->values()) !!},
            backgroundColor: ['#B44040','#2563eb','#16a34a','#ca8a04','#9333ea'],
            borderWidth: 0, hoverOffset: 8 }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '68%',
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a' } }
    }
});
@endif
</script>
@endpush
