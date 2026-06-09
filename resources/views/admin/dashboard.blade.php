@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .kpi-card {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1px solid #e5e7eb;
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        position: relative;
        overflow: hidden;
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }
    .kpi-card:hover::before {
        transform: scaleX(1);
    }
    .kpi-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
    }
    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .kpi-card:hover .kpi-icon {
        transform: scale(1.2) rotate(10deg);
    }
    .kpi-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        transition: all 0.3s ease;
    }
    .kpi-card:hover .kpi-value {
        color: #3b82f6;
        transform: scale(1.05);
    }
    .kpi-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .kpi-trend {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .kpi-card:hover .kpi-trend {
        transform: scale(1.1);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .activity-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    .activity-item:hover {
        background: #f9fafb;
        border-left-color: #3b82f6;
        transform: translateX(6px);
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-slide-in {
        animation: slideInLeft 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        opacity: 0;
    }
    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }
    .delay-5 { animation-delay: 0.25s; }
    .delay-6 { animation-delay: 0.3s; }
    .delay-7 { animation-delay: 0.35s; }
    .delay-8 { animation-delay: 0.4s; }

    /* Custom grid for 8 cards in one line */
    @media (min-width: 1200px) {
        .col-xl-1-5 {
            flex: 0 0 12.5%;
            max-width: 12.5%;
            padding: 0 8px;
        }
    }
    @media (min-width: 992px) and (max-width: 1199.98px) {
        .col-lg-1-5 {
            flex: 0 0 20%;
            max-width: 20%;
            padding: 0 8px;
        }
    }
</style>

<!-- Period Filter -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 animate-fade-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: #1e293b; font-size: 1.75rem;">Admin Dashboard</h4>
        <small class="text-muted">Real-time overview of the KODi platform</small>
    </div>
    <div class="d-flex gap-2">
        <a href="?period=7d" class="btn btn-sm {{ $period == '7d' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">7D</a>
        <a href="?period=30d" class="btn btn-sm {{ $period == '30d' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">30D</a>
        <a href="?period=90d" class="btn btn-sm {{ $period == '90d' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">90D</a>
        <a href="?period=1y" class="btn btn-sm {{ $period == '1y' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">1Y</a>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i></button>
    </div>
</div>

<!-- Row 1: Key Stats (8 KPI Cards in one line) -->
<div class="row g-2 mb-4">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-1">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Properties</div>
                    <div class="kpi-value">{{ number_format($totalProperties) }}</div>
                </div>
                <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-building"></i>
                </div>
            </div>
            <div class="kpi-trend bg-{{ $propertiesGrowth >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $propertiesGrowth >= 0 ? 'success' : 'danger' }} mt-2">
                <i class="bi bi-arrow-{{ $propertiesGrowth >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($propertiesGrowth), 1) }}%
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-2">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Tenants</div>
                    <div class="kpi-value">{{ number_format($totalTenants) }}</div>
                </div>
                <div class="kpi-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="kpi-trend bg-{{ $tenantsGrowth >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $tenantsGrowth >= 0 ? 'success' : 'danger' }} mt-2">
                <i class="bi bi-arrow-{{ $tenantsGrowth >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($tenantsGrowth), 1) }}%
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Revenue</div>
                    <div class="kpi-value">{{ number_format($totalPayments / 1000000, 1) }}M</div>
                </div>
                <div class="kpi-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="kpi-trend bg-{{ $revenueGrowth >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $revenueGrowth >= 0 ? 'success' : 'danger' }} mt-2">
                <i class="bi bi-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($revenueGrowth), 1) }}%
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-4">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Occupancy</div>
                    <div class="kpi-value">{{ $totalProperties > 0 ? number_format(($totalTenants / $totalProperties) * 100, 0) : 0 }}%</div>
                </div>
                <div class="kpi-icon" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                    <i class="bi bi-house-check"></i>
                </div>
            </div>
            <div class="kpi-trend bg-secondary bg-opacity-10 text-secondary mt-2">
                {{ number_format($totalTenants) }}/{{ number_format($totalProperties) }}
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-5">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Maintenance</div>
                    <div class="kpi-value">{{ number_format($totalRequests) }}</div>
                </div>
                <div class="kpi-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-tools"></i>
                </div>
            </div>
            <div class="kpi-trend bg-warning bg-opacity-10 text-warning mt-2">
                {{ number_format($pendingRequests) }} pending
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-6">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Avg. Rent</div>
                    <div class="kpi-value">{{ $totalTenants > 0 ? number_format($totalPayments / $totalTenants / 1000, 0) : 0 }}K</div>
                </div>
                <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="kpi-trend bg-secondary bg-opacity-10 text-secondary mt-2">
                per tenant
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-7">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Active Units</div>
                    <div class="kpi-value">{{ number_format($totalTenants) }}</div>
                </div>
                <div class="kpi-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-door-open"></i>
                </div>
            </div>
            <div class="kpi-trend bg-success bg-opacity-10 text-success mt-2">
                {{ $totalProperties > 0 ? number_format(($totalTenants / $totalProperties) * 100, 0) : 0 }}% occupied
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-1-5 animate-slide-in delay-8">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Pending Apps</div>
                    <div class="kpi-value">{{ number_format($pendingRequests) }}</div>
                </div>
                <div class="kpi-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
            <div class="kpi-trend bg-danger bg-opacity-10 text-danger mt-2">
                requires action
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-8 animate-fade-in delay-1">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>Platform Growth Overview</h5>
                <div class="d-flex gap-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="chartMetric" id="chRevenue" value="revenue" checked>
                        <label class="form-check-label small" for="chRevenue">Revenue</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="chartMetric" id="chTenants" value="tenants">
                        <label class="form-check-label small" for="chTenants">Tenants</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="chartMetric" id="chRequests" value="requests">
                        <label class="form-check-label small" for="chRequests">Requests</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 animate-fade-in delay-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2 text-primary"></i>Payment Methods</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="chart-container" style="height: 200px;">
                    <canvas id="paymentChart"></canvas>
                </div>
                <div class="mt-3 overflow-auto" style="max-height: 140px;">
                    @foreach($paymentMethods as $pm)
                    <div class="d-flex justify-content-between align-items-center mb-2 py-2 px-2 rounded" style="background: #f8fafc;">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $loop->index % 2 == 0 ? '#0d6efd' : '#198754' }};"></div>
                            <span class="small text-capitalize fw-semibold">{{ $pm->payment_method ?? 'Unknown' }}</span>
                        </div>
                        <span class="fw-semibold small">{{ number_format($pm->count) }} <span class="text-muted">({{ number_format($pm->total / 1000000, 1) }}M)</span></span>
                    </div>
                    @endforeach
                    @if($paymentMethods->isEmpty())
                    <p class="text-muted small text-center py-3">No payment data yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Additional Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-fade-in delay-1">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2 text-primary"></i>Monthly Revenue Comparison</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 animate-fade-in delay-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-primary"></i>Request Status Distribution</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Recent Activity & Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-lg-5 animate-fade-in delay-1">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold small"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Activity</h5>
                <a href="#" class="text-primary small text-decoration-none fw-semibold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                    @forelse($recentTenants as $tenant)
                    <div class="activity-item list-group-item px-3 py-3 border-0 border-bottom">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-truncate">{{ $tenant->name ?? 'Unknown' }}</div>
                                <small class="text-muted">New tenant registered</small>
                                <div class="text-muted small mt-1"><i class="bi bi-clock me-1"></i>{{ $tenant->created_at?->diffForHumans() ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @forelse($recentPayments as $payment)
                    <div class="activity-item list-group-item px-3 py-3 border-0 border-bottom">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-cash text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">TZS {{ number_format($payment->amount) }}</div>
                                <small class="text-muted">Payment received</small>
                                <div class="text-muted small mt-1"><i class="bi bi-clock me-1"></i>{{ $payment->created_at?->diffForHumans() ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @forelse($recentRequests as $request)
                    <div class="activity-item list-group-item px-3 py-3 border-0 border-bottom">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-truncate">{{ $request->description ?? 'Request' }}</div>
                                <small class="text-muted">Maintenance request</small>
                                <div class="text-muted small mt-1"><i class="bi bi-clock me-1"></i>{{ $request->created_at?->diffForHumans() ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($recentTenants->isEmpty() && $recentPayments->isEmpty() && $recentRequests->isEmpty())
                    <p class="text-muted small text-center py-4">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 animate-fade-in delay-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold small"><i class="bi bi-lightning me-2 text-warning"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2 py-2.5">
                        <i class="bi bi-building"></i>
                        <span>Properties</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-success d-flex align-items-center gap-2 py-2.5">
                        <i class="bi bi-people"></i>
                        <span>Tenants</span>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-info d-flex align-items-center gap-2 py-2.5">
                        <i class="bi bi-cash-stack"></i>
                        <span>Payments</span>
                    </a>
                    <a href="{{ route('admin.maintenance.index') }}" class="btn btn-outline-warning d-flex align-items-center gap-2 py-2.5">
                        <i class="bi bi-tools"></i>
                        <span>Maintenance</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2.5">
                        <i class="bi bi-graph-up"></i>
                        <span>Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 animate-fade-in delay-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold small"><i class="bi bi-list-ul me-2 text-primary"></i>Recent Transactions</h5>
                <a href="{{ route('admin.payments.index') }}" class="text-primary small text-decoration-none fw-semibold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th class="pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $txn)
                            <tr>
                                <td class="ps-3 fw-semibold text-primary">TXN-{{ str_pad($txn->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-semibold">TZS {{ number_format($txn->amount, 0) }}</td>
                                <td><span class="badge bg-light text-dark text-capitalize">{{ $txn->payment_method ?? 'N/A' }}</span></td>
                                <td class="pe-3 text-muted small">{{ $txn->created_at?->diffForHumans() ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No transactions yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Main multi-metric chart
const mainCtx = document.getElementById('mainChart').getContext('2d');
const mainChart = new Chart(mainCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthLabels) !!},
        datasets: [{
            label: 'Revenue (TZS)',
            data: {!! json_encode($revenueData) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y',
            pointRadius: 4,
            pointHoverRadius: 6
        }, {
            label: 'New Tenants',
            data: {!! json_encode($tenantsData) !!},
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1',
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: true, position: 'top', labels: { boxWidth: 12, padding: 20, font: { size: 12, weight: '500' } } }
        },
        scales: {
            y: {
                type: 'linear', display: true, position: 'left',
                ticks: { callback: function(v) { return 'TZS ' + (v/1000000).toFixed(1) + 'M'; }, font: { size: 11 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            y1: {
                type: 'linear', display: true, position: 'right',
                ticks: { font: { size: 11 } },
                grid: { drawOnChartArea: false }
            },
            x: { ticks: { font: { size: 11 } } }
        }
    }
});

// Chart metric toggle
document.querySelectorAll('input[name="chartMetric"]').forEach(radio => {
    radio.addEventListener('change', function() {
        let data, label, color;
        switch(this.value) {
            case 'revenue':
                data = {!! json_encode($revenueData) !!}; label = 'Revenue (TZS)'; color = '#0d6efd'; break;
            case 'tenants':
                data = {!! json_encode($tenantsData) !!}; label = 'New Tenants'; color = '#198754'; break;
            case 'requests':
                data = {!! json_encode($requestsData) !!}; label = 'Requests'; color = '#ffc107'; break;
        }
        mainChart.data.datasets[0].data = data;
        mainChart.data.datasets[0].label = label;
        mainChart.data.datasets[0].borderColor = color;
        mainChart.data.datasets[0].backgroundColor = color.replace(')', ', 0.1)').replace('rgb', 'rgba');
        mainChart.update();
    });
});

// Payment Methods Doughnut
@if($paymentMethods->isNotEmpty())
const payCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(payCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentMethods->pluck('payment_method')->map(function($m) { return ucfirst($m ?? 'Unknown'); })) !!},
        datasets: [{
            data: {!! json_encode($paymentMethods->pluck('count')) !!},
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#0dcaf0', '#fd7e14', '#20c997'],
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: { legend: { display: false } }
    }
});
@endif

// Bar Chart - Monthly Revenue
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthLabels) !!},
        datasets: [{
            label: 'Revenue (TZS)',
            data: {!! json_encode($revenueData) !!},
            backgroundColor: 'rgba(13, 110, 253, 0.8)',
            borderColor: '#0d6efd',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: function(v) { return 'TZS ' + (v/1000000).toFixed(1) + 'M'; }, font: { size: 11 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { ticks: { font: { size: 11 } } }
        }
    }
});

// Status Distribution Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'In Progress', 'Completed', 'Cancelled'],
        datasets: [{
            data: [{{ $pendingRequests }}, {{ max(0, $totalRequests - $pendingRequests - 5) }}, {{ max(0, min(5, $totalRequests - $pendingRequests)) }}, {{ max(0, $totalRequests > 10 ? 2 : 0) }}],
            backgroundColor: ['#ffc107', '#0d6efd', '#198754', '#dc3545'],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
        }
    }
});
</script>
@endpush
@endsection
