@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<!-- Header -->
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">Users</h1>
        <p class="page-subtitle">Manage all platform users — tenants, landlords, agents, and admins</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn-ghost" onclick="exportUsers()"><i class="ri-download-2-line"></i> Export</button>
        <a href="#" class="btn-brand" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="ri-user-add-line"></i> Add User
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4 fade-up delay-1">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="?role=" class="text-decoration-none">
            <div class="kpi-card" style="--accent:#B44040;">
                <div class="kpi-label">All Users</div>
                <div class="kpi-value">{{ number_format($stats['total']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="?role=tenant" class="text-decoration-none">
            <div class="kpi-card" style="--accent:#16a34a;">
                <div class="kpi-label">Tenants</div>
                <div class="kpi-value">{{ number_format($stats['tenants']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="?role=landlord" class="text-decoration-none">
            <div class="kpi-card" style="--accent:#2563eb;">
                <div class="kpi-label">Landlords</div>
                <div class="kpi-value">{{ number_format($stats['landlords']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="?role=agent" class="text-decoration-none">
            <div class="kpi-card" style="--accent:#ca8a04;">
                <div class="kpi-label">Agents</div>
                <div class="kpi-value">{{ number_format($stats['agents']) }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="?role=admin" class="text-decoration-none">
            <div class="kpi-card" style="--accent:#9333ea;">
                <div class="kpi-label">Admins</div>
                <div class="kpi-value">{{ number_format($stats['admins']) }}</div>
            </div>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="k-card mb-4 fade-up delay-2">
    <div class="k-card-body" style="padding: 16px 20px;">
        <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
            <div style="position:relative; flex: 1; min-width:220px;">
                <i class="ri-search-line" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.9rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search name, email, phone..."
                    style="width:100%;padding:9px 12px 9px 34px;border:1.5px solid var(--border);border-radius:8px;font-size:0.875rem;color:var(--text-primary);background:var(--body-bg);transition:all 0.2s;"
                    onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div class="filter-bar">
                <a href="?" class="filter-chip {{ !request('role') ? 'active' : '' }}">All</a>
                <a href="?role=tenant"    class="filter-chip {{ request('role')=='tenant' ? 'active' : '' }}">Tenants</a>
                <a href="?role=landlord"  class="filter-chip {{ request('role')=='landlord' ? 'active' : '' }}">Landlords</a>
                <a href="?role=agent"     class="filter-chip {{ request('role')=='agent' ? 'active' : '' }}">Agents</a>
                <a href="?role=admin"     class="filter-chip {{ request('role')=='admin' ? 'active' : '' }}">Admins</a>
            </div>
            <button type="submit" class="btn-brand" style="padding:9px 16px;"><i class="ri-search-line"></i></button>
        </form>
    </div>
</div>

<!-- Table -->
<div class="k-card fade-up delay-3">
    <div class="k-card-header">
        <div class="k-card-title"><i class="ri-group-line"></i> User List</div>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $users->total() }} total</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="k-table">
            <thead>
                <tr>
                    <th style="width:40px;"><input type="checkbox" id="selectAll" style="cursor:pointer;"></th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Properties</th>
                    <th>Leases</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $colors = ['#B44040','#16a34a','#2563eb','#ca8a04','#9333ea','#0891b2','#ea580c'];
                    $ci = abs(crc32($user->email)) % count($colors);
                    $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                @endphp
                <tr>
                    <td><input type="checkbox" class="row-cb" style="cursor:pointer;"></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="user-avatar" style="background:{{ $colors[$ci] }};">{{ $initials }}</div>
                            <div>
                                <div style="font-weight:700;font-size:0.875rem;color:var(--text-primary);">{{ $user->name }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleBadge = match($user->role) {
                                'tenant'     => 'badge-success',
                                'landlord'   => 'badge-info',
                                'agent'      => 'badge-warning',
                                'admin'      => 'badge-brand',
                                'super_admin'=> 'badge-purple',
                                default      => 'badge-neutral',
                            };
                        @endphp
                        <span class="k-badge {{ $roleBadge }}">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
                    </td>
                    <td style="font-size:0.83rem;color:var(--text-sub);">{{ $user->phone ?? '—' }}</td>
                    <td style="text-align:center;font-weight:700;color:var(--text-primary);">{{ $user->properties_count }}</td>
                    <td style="text-align:center;font-weight:700;color:var(--text-primary);">{{ $user->leases_as_tenant_count }}</td>
                    <td>
                        @if($user->suspended_at)
                        <span class="k-badge badge-danger"><i class="ri-close-circle-line"></i> Suspended</span>
                        @else
                        <span class="k-badge badge-success"><i class="ri-checkbox-circle-line"></i> Active</span>
                        @endif
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted);">{{ $user->created_at?->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:4px;">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="icon-btn" title="View"><i class="ri-eye-line"></i></a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="icon-btn" title="Edit"><i class="ri-pencil-line"></i></a>
                            <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="icon-btn {{ $user->suspended_at ? 'success' : '' }}"
                                    title="{{ $user->suspended_at ? 'Activate' : 'Suspend' }}"
                                    onclick="return confirm('{{ $user->suspended_at ? 'Activate' : 'Suspend' }} this user?')">
                                    <i class="ri-{{ $user->suspended_at ? 'user-follow' : 'user-forbid' }}-line"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="icon-btn danger" title="Delete"
                                    onclick="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                        <i class="ri-user-search-line" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                        No users found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:0.8rem;color:var(--text-muted);">
            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:1px solid var(--border);">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:20px 24px;">
                <h5 style="font-weight:700;color:var(--text-primary);margin:0;font-size:1rem;">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body" style="padding:24px;">
                    <div class="mb-3">
                        <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Full Name</label>
                        <input type="text" name="name" required class="form-control form-control-sm" placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Email Address</label>
                        <input type="email" name="email" required class="form-control form-control-sm" placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Phone</label>
                        <input type="tel" name="phone" class="form-control form-control-sm" placeholder="+255...">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Role</label>
                        <select name="role" required class="form-select form-select-sm">
                            <option value="tenant">Tenant</option>
                            <option value="landlord">Landlord</option>
                            <option value="agent">Agent</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Password</label>
                        <input type="password" name="password" required class="form-control form-control-sm" placeholder="Min 8 characters">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:16px 24px;gap:8px;">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-brand">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-cb').forEach(cb => cb.checked = this.checked);
});
function exportUsers() {
    Swal.fire({ title: 'Export Users', text: 'Choose format:', icon: 'info',
        showDenyButton: true, confirmButtonText: 'CSV', denyButtonText: 'Excel',
        confirmButtonColor: '#B44040', denyButtonColor: '#2563eb' });
}
</script>
@endpush
@endsection
