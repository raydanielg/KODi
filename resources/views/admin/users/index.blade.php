@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
    .page-header p { color: #6b7280; font-size: 0.95rem; }
    
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #111827;
    }
    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    .search-input {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .filter-btn {
        padding: 10px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        font-size: 0.875rem;
        color: #374151;
        transition: all 0.2s;
    }
    .filter-btn:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    .filter-btn.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }
    
    .table-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th {
        background: #f9fafb;
        padding: 16px;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
    }
    .custom-table td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: #f9fafb;
    }
    .user-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
    }
    .user-email {
        font-size: 0.8rem;
        color: #6b7280;
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
    }
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    .role-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    .action-btn {
        padding: 6px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #fff;
        font-size: 0.75rem;
        color: #374151;
        transition: all 0.2s;
        margin-right: 4px;
    }
    .action-btn:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    .action-btn.approve {
        background: #10b981;
        border-color: #10b981;
        color: #fff;
    }
    .action-btn.approve:hover {
        background: #059669;
    }
    .action-btn.delete {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
    }
    .action-btn.delete:hover {
        background: #dc2626;
    }
    .bulk-actions {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 12px 16px;
        display: none;
    }
    .bulk-actions.show {
        display: flex;
        align-items: center;
        gap-2;
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
</style>

<div class="page-header animate-fade-in">
    <h1>Users</h1>
    <p>Manage all users registered on the platform</p>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">1,248</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">856</div>
                    <div class="stat-label">Tenants</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">342</div>
                    <div class="stat-label">Landlords</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">50</div>
                    <div class="stat-label">Agents</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card animate-fade-in delay-2">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
            <input type="text" class="search-input w-100" placeholder="Search users...">
        </div>
        <div class="col-12 col-md-8">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Tenants</button>
                <button class="filter-btn">Landlords</button>
                <button class="filter-btn">Agents</button>
                <button class="filter-btn ms-auto">
                    <i class="bi bi-plus-lg me-1"></i> Add User
                </button>
            </div>
        </div>
    </div>
    <div class="bulk-actions mt-3" id="bulkActions">
        <span class="text-sm text-primary fw-semibold me-3"><span id="selectedCount">0</span> selected</span>
        <button class="action-btn approve" onclick="bulkApprove()">
            <i class="bi bi-check-lg me-1"></i> Activate All
        </button>
        <button class="action-btn delete" onclick="bulkDelete()">
            <i class="bi bi-trash me-1"></i> Delete All
        </button>
        <button class="action-btn" onclick="clearSelection()">
            <i class="bi bi-x-lg me-1"></i> Clear
        </button>
    </div>
</div>

<!-- Users Table -->
<div class="table-card animate-fade-in delay-2">
    <table class="custom-table">
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" class="user-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">JD</div>
                        <div>
                            <div class="user-name">John Doe</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-email">john@example.com</div>
                </td>
                <td>
                    <span class="role-badge bg-primary bg-opacity-10 text-primary">Tenant</span>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Active</span>
                </td>
                <td>
                    <div class="user-email">Jan 15, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(1)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editUser(1)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="toggleStatus(1)"><i class="bi bi-power"></i></button>
                    <button class="action-btn delete" onclick="deleteUser(1)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="user-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">SM</div>
                        <div>
                            <div class="user-name">Sarah Miller</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-email">sarah@example.com</div>
                </td>
                <td>
                    <span class="role-badge bg-info bg-opacity-10 text-info">Landlord</span>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Active</span>
                </td>
                <td>
                    <div class="user-email">Jan 14, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(2)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editUser(2)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="toggleStatus(2)"><i class="bi bi-power"></i></button>
                    <button class="action-btn delete" onclick="deleteUser(2)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="user-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">MJ</div>
                        <div>
                            <div class="user-name">Michael Johnson</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-email">michael@example.com</div>
                </td>
                <td>
                    <span class="role-badge bg-warning bg-opacity-10 text-warning">Agent</span>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Active</span>
                </td>
                <td>
                    <div class="user-email">Jan 13, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(3)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editUser(3)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="toggleStatus(3)"><i class="bi bi-power"></i></button>
                    <button class="action-btn delete" onclick="deleteUser(3)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="user-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">EW</div>
                        <div>
                            <div class="user-name">Emily Wilson</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-email">emily@example.com</div>
                </td>
                <td>
                    <span class="role-badge bg-primary bg-opacity-10 text-primary">Tenant</span>
                </td>
                <td>
                    <span class="status-badge bg-danger bg-opacity-10 text-danger">Inactive</span>
                </td>
                <td>
                    <div class="user-email">Jan 12, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(4)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editUser(4)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="toggleStatus(4)"><i class="bi bi-power"></i></button>
                    <button class="action-btn delete" onclick="deleteUser(4)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="user-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">RB</div>
                        <div>
                            <div class="user-name">Robert Brown</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-email">robert@example.com</div>
                </td>
                <td>
                    <span class="role-badge bg-info bg-opacity-10 text-info">Landlord</span>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Active</span>
                </td>
                <td>
                    <div class="user-email">Jan 11, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(5)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editUser(5)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="toggleStatus(5)"><i class="bi bi-power"></i></button>
                    <button class="action-btn delete" onclick="deleteUser(5)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkActions.classList.add('show');
        selectedCount.textContent = checkboxes.length;
    } else {
        bulkActions.classList.remove('show');
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAll = document.getElementById('selectAll');
    checkboxes.forEach(cb => cb.checked = false);
    selectAll.checked = false;
    updateBulkActions();
}

function bulkApprove() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    alert('Activating ' + checkboxes.length + ' users');
    clearSelection();
}

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    if (confirm('Are you sure you want to delete ' + checkboxes.length + ' users?')) {
        alert('Deleting ' + checkboxes.length + ' users');
        clearSelection();
    }
}

function viewDetails(id) {
    alert('Viewing details for user #' + id);
}

function editUser(id) {
    alert('Editing user #' + id);
}

function toggleStatus(id) {
    alert('Toggling status for user #' + id);
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        alert('Deleting user #' + id);
    }
}
</script>
@endsection
