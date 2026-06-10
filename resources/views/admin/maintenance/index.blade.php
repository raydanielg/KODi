@extends('layouts.admin')
@section('title', 'Maintenance')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">Maintenance Requests</h1>
        <p class="page-subtitle">Manage and resolve property maintenance issues</p>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4 fade-up delay-1">
    @php $mcards = [
        ['label'=>'Total','value'=>$stats['total'],'color'=>'#64748b','bg'=>'#f1f5f9','icon'=>'ri-tools-line'],
        ['label'=>'Open','value'=>$stats['open'],'color'=>'#dc2626','bg'=>'#fee2e2','icon'=>'ri-alarm-warning-line'],
        ['label'=>'In Progress','value'=>$stats['in_progress'],'color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'ri-loader-4-line'],
        ['label'=>'Completed','value'=>$stats['completed'],'color'=>'#16a34a','bg'=>'#dcfce7','icon'=>'ri-checkbox-circle-line'],
    ]; @endphp
    @foreach($mcards as $c)
    <div class="col-6 col-lg-3">
        <div class="kpi-card" style="--accent:{{ $c['color'] }};">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">{{ $c['label'] }}</div>
                    <div class="kpi-value">{{ number_format($c['value']) }}</div>
                </div>
                <div class="kpi-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};"><i class="{{ $c['icon'] }}"></i></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="k-card mb-4 fade-up delay-2">
    <div class="k-card-body" style="padding:16px 20px;">
        <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="ri-search-line" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.9rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search requests..."
                    style="width:100%;padding:9px 12px 9px 34px;border:1.5px solid var(--border);border-radius:8px;font-size:0.875rem;background:var(--body-bg);"
                    onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div class="filter-bar">
                <a href="?"                  class="filter-chip {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="?status=open"       class="filter-chip {{ request('status')=='open' ? 'active' : '' }}">Open</a>
                <a href="?status=in_progress"class="filter-chip {{ request('status')=='in_progress' ? 'active' : '' }}">In Progress</a>
                <a href="?status=completed"  class="filter-chip {{ request('status')=='completed' ? 'active' : '' }}">Completed</a>
            </div>
            <select name="priority" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:0.82rem;background:#fff;">
                <option value="">All Priorities</option>
                <option value="high"   {{ request('priority')=='high'?'selected':'' }}>High</option>
                <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>Medium</option>
                <option value="low"    {{ request('priority')=='low'?'selected':'' }}>Low</option>
            </select>
        </form>
    </div>
</div>

<!-- Table -->
<div class="k-card fade-up delay-3">
    <div class="k-card-header">
        <div class="k-card-title"><i class="ri-hammer-line"></i> Requests List</div>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $requests->total() }} total</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="k-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Tenant</th>
                    <th>Property</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Reported</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $mr)
                <tr>
                    <td>
                        <div style="font-weight:700;font-size:0.875rem;color:var(--text-primary);">{{ $mr->title ?? 'Maintenance Request' }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $mr->description }}</div>
                    </td>
                    <td style="font-size:0.82rem;color:var(--text-sub);">{{ $mr->tenant?->name ?? '—' }}</td>
                    <td style="font-size:0.82rem;color:var(--text-sub);max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $mr->property?->title ?? '—' }}</td>
                    <td>
                        @php $pb = match($mr->priority) { 'high'=>'badge-danger', 'medium'=>'badge-warning', 'low'=>'badge-neutral', default=>'badge-neutral' }; @endphp
                        <span class="k-badge {{ $pb }} text-capitalize">{{ $mr->priority ?? '—' }}</span>
                    </td>
                    <td>
                        @php $sb = match($mr->status) { 'open'=>'badge-danger', 'in_progress'=>'badge-info', 'completed'=>'badge-success', default=>'badge-neutral' }; @endphp
                        <span class="k-badge {{ $sb }} text-capitalize">{{ str_replace('_',' ',$mr->status) }}</span>
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted);">{{ $mr->created_at?->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:4px;align-items:center;">
                            <a href="{{ route('admin.maintenance.show', $mr->id) }}" class="icon-btn" title="View"><i class="ri-eye-line"></i></a>
                            <!-- Quick status update -->
                            <form method="POST" action="{{ route('admin.maintenance.updateStatus', $mr->id) }}" style="margin:0;">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    style="padding:5px 8px;border:1px solid var(--border);border-radius:6px;font-size:0.75rem;background:#fff;color:var(--text-sub);cursor:pointer;">
                                    <option value="open"        {{ $mr->status=='open'?'selected':'' }}>Open</option>
                                    <option value="in_progress" {{ $mr->status=='in_progress'?'selected':'' }}>In Progress</option>
                                    <option value="completed"   {{ $mr->status=='completed'?'selected':'' }}>Completed</option>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                        <i class="ri-tools-line" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                        No maintenance requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:0.8rem;color:var(--text-muted);">Showing {{ $requests->firstItem() }}–{{ $requests->lastItem() }} of {{ $requests->total() }}</div>
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection
