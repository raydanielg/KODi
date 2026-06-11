@extends('layouts.admin')
@section('title', 'Properties')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">Properties</h1>
        <p class="page-subtitle">Review, verify and manage all listed properties</p>
    </div>
    <button class="btn-ghost"><i class="ri-download-2-line"></i> Export</button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4 fade-up delay-1">
    @php $pcards = [
        ['label'=>'Total','value'=>$stats['total'],'color'=>'#B44040','bg'=>'#fdf0f0','icon'=>'ri-building-2-line'],
        ['label'=>'Available','value'=>$stats['available'],'color'=>'#16a34a','bg'=>'#dcfce7','icon'=>'ri-door-open-line'],
        ['label'=>'Rented','value'=>$stats['rented'],'color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'ri-home-heart-line'],
        ['label'=>'Pending Verify','value'=>$stats['pending'],'color'=>'#ca8a04','bg'=>'#fef9c3','icon'=>'ri-time-line'],
    ]; @endphp
    @foreach($pcards as $c)
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search properties..."
                    style="width:100%;padding:9px 12px 9px 34px;border:1.5px solid var(--border);border-radius:8px;font-size:0.875rem;background:var(--body-bg);"
                    onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div class="filter-bar">
                <a href="?"                 class="filter-chip {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="?status=available" class="filter-chip {{ request('status')=='available' ? 'active' : '' }}">Available</a>
                <a href="?status=rented"    class="filter-chip {{ request('status')=='rented' ? 'active' : '' }}">Rented</a>
            </div>
            <select name="type" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:0.82rem;background:#fff;color:var(--text-sub);">
                <option value="">All Types</option>
                <option value="apartment" {{ request('type')=='apartment'?'selected':'' }}>Apartment</option>
                <option value="house"     {{ request('type')=='house'?'selected':'' }}>House</option>
                <option value="commercial"{{ request('type')=='commercial'?'selected':'' }}>Commercial</option>
                <option value="room"      {{ request('type')=='room'?'selected':'' }}>Room</option>
            </select>
            <button type="submit" class="btn-brand" style="padding:9px 16px;"><i class="ri-search-line"></i></button>
        </form>
    </div>
</div>

<!-- Table -->
<div class="k-card fade-up delay-3">
    <div class="k-card-header">
        <div class="k-card-title"><i class="ri-building-2-line"></i> Properties List</div>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $properties->total() }} total</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="k-table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Owner</th>
                    <th>Price / mo</th>
                    <th>Status</th>
                    <th>Verified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($properties as $prop)
                <tr>
                    <td>
                        <div style="font-weight:700;font-size:0.875rem;color:var(--text-primary);max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $prop->title }}
                        </div>
                        <div style="font-size:0.72rem;color:var(--text-muted);">
                            {{ $prop->bedrooms ?? '—' }} bed · {{ $prop->bathrooms ?? '—' }} bath{{ $prop->area_sqft ? ' · '.number_format($prop->area_sqft).' sqft' : '' }}
                        </div>
                    </td>
                    <td>
                        <span class="k-badge badge-neutral text-capitalize">{{ str_replace('_',' ',$prop->property_type) }}</span>
                    </td>
                    <td style="font-size:0.82rem;color:var(--text-sub);">{{ $prop->location_area }}, {{ $prop->location_city }}</td>
                    <td>
                        <div style="font-size:0.82rem;font-weight:600;color:var(--text-primary);">{{ $prop->landlord?->name ?? '—' }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);">{{ $prop->landlord?->email }}</div>
                    </td>
                    <td style="font-weight:700;color:var(--text-primary);">TZS {{ number_format($prop->price) }}</td>
                    <td>
                        @php $sb = match($prop->status) { 'available'=>'badge-success', 'rented'=>'badge-info', default=>'badge-warning' }; @endphp
                        <span class="k-badge {{ $sb }} text-capitalize">{{ $prop->status }}</span>
                    </td>
                    <td>
                        @if($prop->approved_at)
                            <span class="k-badge badge-success"><i class="ri-shield-check-line"></i> Verified</span>
                        @else
                            <span class="k-badge badge-warning"><i class="ri-time-line"></i> Pending</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.properties.show', $prop->id) }}" class="icon-btn" title="View"><i class="ri-eye-line"></i></a>
                            <a href="{{ route('admin.properties.edit', $prop->id) }}" class="icon-btn" title="Edit"><i class="ri-pencil-line"></i></a>
                            @if(!$prop->approved_at)
                            <form method="POST" action="{{ route('admin.properties.verify', $prop->id) }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="icon-btn success" title="Verify" onclick="return confirm('Approve this property?')">
                                    <i class="ri-shield-check-line"></i>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.properties.destroy', $prop->id) }}" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="icon-btn danger" title="Delete" onclick="return confirm('Delete this property permanently?')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                        <i class="ri-building-line" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                        No properties found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($properties->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:0.8rem;color:var(--text-muted);">Showing {{ $properties->firstItem() }}–{{ $properties->lastItem() }} of {{ $properties->total() }}</div>
        {{ $properties->links() }}
    </div>
    @endif
</div>
@endsection
