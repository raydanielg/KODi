@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">Payments</h1>
        <p class="page-subtitle">Track all rent payments across the platform</p>
    </div>
    <button class="btn-ghost" onclick="Swal.fire('Export','Choose format: CSV or Excel','info')">
        <i class="ri-download-2-line"></i> Export
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4 fade-up delay-1">
    <div class="col-6 col-lg-3">
        <div class="kpi-card" style="--accent:#16a34a;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Total Collected</div>
                    <div class="kpi-value">{{ number_format($stats['total_amount']/1000000,1) }}M</div>
                </div>
                <div class="kpi-icon" style="background:#dcfce7;color:#16a34a;"><i class="ri-money-dollar-circle-line"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card" style="--accent:#2563eb;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">This Month</div>
                    <div class="kpi-value">{{ number_format($stats['this_month']/1000000,1) }}M</div>
                </div>
                <div class="kpi-icon" style="background:#dbeafe;color:#2563eb;"><i class="ri-calendar-check-line"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card" style="--accent:#B44040;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Transactions</div>
                    <div class="kpi-value">{{ number_format($stats['count']) }}</div>
                </div>
                <div class="kpi-icon" style="background:#fdf0f0;color:#B44040;"><i class="ri-exchange-line"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card" style="--accent:#9333ea;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Completed</div>
                    <div class="kpi-value">{{ number_format($stats['completed']) }}</div>
                </div>
                <div class="kpi-icon" style="background:#f3e8ff;color:#9333ea;"><i class="ri-checkbox-circle-line"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="k-card mb-4 fade-up delay-2">
    <div class="k-card-body" style="padding:16px 20px;">
        <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="ri-search-line" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.9rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tenant name..."
                    style="width:100%;padding:9px 12px 9px 34px;border:1.5px solid var(--border);border-radius:8px;font-size:0.875rem;background:var(--body-bg);"
                    onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <select name="method" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:0.82rem;background:#fff;">
                <option value="">All Methods</option>
                @foreach($methods as $m)
                <option value="{{ $m }}" {{ request('method')==$m?'selected':'' }}>{{ ucfirst($m) }}</option>
                @endforeach
            </select>
            <div class="filter-bar">
                <a href="?"                  class="filter-chip {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="?status=completed"  class="filter-chip {{ request('status')=='completed' ? 'active' : '' }}">Completed</a>
                <a href="?status=pending"    class="filter-chip {{ request('status')=='pending' ? 'active' : '' }}">Pending</a>
                <a href="?status=failed"     class="filter-chip {{ request('status')=='failed' ? 'active' : '' }}">Failed</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="k-card fade-up delay-3">
    <div class="k-card-header">
        <div class="k-card-title"><i class="ri-bank-card-line"></i> Payment History</div>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $payments->total() }} records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="k-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tenant</th>
                    <th>Property</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Period</th>
                    <th>Paid At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $pay)
                <tr>
                    <td style="font-weight:700;color:var(--brand);">#{{ str_pad($pay->id,5,'0',STR_PAD_LEFT) }}</td>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;color:var(--text-primary);">{{ $pay->tenant?->name ?? '—' }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);">{{ $pay->tenant?->email }}</div>
                    </td>
                    <td style="font-size:0.82rem;color:var(--text-sub);max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $pay->property?->title ?? '—' }}
                    </td>
                    <td style="font-weight:800;color:var(--text-primary);">TZS {{ number_format($pay->amount) }}</td>
                    <td><span class="k-badge badge-neutral text-capitalize">{{ $pay->payment_method ?? '—' }}</span></td>
                    <td>
                        @php $sb = match($pay->status) { 'completed'=>'badge-success', 'pending'=>'badge-warning', 'failed'=>'badge-danger', default=>'badge-neutral' }; @endphp
                        <span class="k-badge {{ $sb }} text-capitalize">{{ $pay->status }}</span>
                    </td>
                    <td style="font-size:0.78rem;color:var(--text-muted);">
                        {{ $pay->period_start ? \Carbon\Carbon::parse($pay->period_start)->format('M Y') : '—' }}
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted);">
                        {{ $pay->paid_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.payments.show', $pay->id) }}" class="icon-btn" title="View receipt"><i class="ri-eye-line"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                        <i class="ri-bank-card-line" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:0.8rem;color:var(--text-muted);">Showing {{ $payments->firstItem() }}–{{ $payments->lastItem() }} of {{ $payments->total() }}</div>
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
