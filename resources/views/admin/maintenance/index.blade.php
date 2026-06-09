@extends('layouts.admin')

@section('title', 'Maintenance')

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
    .maintenance-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
    }
    .maintenance-property {
        font-size: 0.8rem;
        color: #6b7280;
    }
    .maintenance-date {
        font-size: 0.8rem;
        color: #9ca3af;
    }
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    .priority-badge {
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
    <h1>Maintenance</h1>
    <p>Track and manage property maintenance requests</p>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">48</div>
                    <div class="stat-label">Total Requests</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-tools"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-gear"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">8</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">28</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card animate-fade-in delay-2">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
            <input type="text" class="search-input w-100" placeholder="Search requests...">
        </div>
        <div class="col-12 col-md-8">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Pending</button>
                <button class="filter-btn">In Progress</button>
                <button class="filter-btn">Completed</button>
                <button class="filter-btn ms-auto">
                    <i class="bi bi-plus-lg me-1"></i> New Request
                </button>
            </div>
        </div>
    </div>
    <div class="bulk-actions mt-3" id="bulkActions">
        <span class="text-sm text-primary fw-semibold me-3"><span id="selectedCount">0</span> selected</span>
        <button class="action-btn approve" onclick="bulkApprove()">
            <i class="bi bi-check-lg me-1"></i> Mark Complete
        </button>
        <button class="action-btn delete" onclick="bulkDelete()">
            <i class="bi bi-trash me-1"></i> Delete All
        </button>
        <button class="action-btn" onclick="clearSelection()">
            <i class="bi bi-x-lg me-1"></i> Clear
        </button>
    </div>
</div>

<!-- Maintenance Table -->
<div class="table-card animate-fade-in delay-2">
    <table class="custom-table">
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                <th>Request</th>
                <th>Property</th>
                <th>Reported By</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" class="maintenance-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="maintenance-title">Water Leak in Bathroom</div>
                </td>
                <td>
                    <div class="maintenance-property">Sunset Apartments - Unit 2A</div>
                </td>
                <td>
                    <div class="maintenance-property">John Doe</div>
                </td>
                <td>
                    <span class="priority-badge bg-danger bg-opacity-10 text-danger">High</span>
                </td>
                <td>
                    <span class="status-badge bg-info bg-opacity-10 text-info">In Progress</span>
                </td>
                <td>
                    <div class="maintenance-date">Jan 15, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(1)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editRequest(1)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn approve" onclick="markComplete(1)"><i class="bi bi-check"></i></button>
                    <button class="action-btn delete" onclick="deleteRequest(1)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="maintenance-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="maintenance-title">Electrical Issue - Lights Not Working</div>
                </td>
                <td>
                    <div class="maintenance-property">Green Valley Estate - Unit 5B</div>
                </td>
                <td>
                    <div class="maintenance-property">Sarah Miller</div>
                </td>
                <td>
                    <span class="priority-badge bg-warning bg-opacity-10 text-warning">Medium</span>
                </td>
                <td>
                    <span class="status-badge bg-warning bg-opacity-10 text-warning">Pending</span>
                </td>
                <td>
                    <div class="maintenance-date">Jan 14, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(2)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editRequest(2)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn approve" onclick="markComplete(2)"><i class="bi bi-check"></i></button>
                    <button class="action-btn delete" onclick="deleteRequest(2)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="maintenance-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="maintenance-title">AC Repair</div>
                </td>
                <td>
                    <div class="maintenance-property">Ocean View Towers - Unit 3C</div>
                </td>
                <td>
                    <div class="maintenance-property">Michael Johnson</div>
                </td>
                <td>
                    <span class="priority-badge bg-secondary bg-opacity-10 text-secondary">Low</span>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Completed</span>
                </td>
                <td>
                    <div class="maintenance-date">Jan 13, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(3)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editRequest(3)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn delete" onclick="deleteRequest(3)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="maintenance-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="maintenance-title">Door Lock Replacement</div>
                </td>
                <td>
                    <div class="maintenance-property">Safari Heights - Unit 1A</div>
                </td>
                <td>
                    <div class="maintenance-property">Emily Wilson</div>
                </td>
                <td>
                    <span class="priority-badge bg-warning bg-opacity-10 text-warning">Medium</span>
                </td>
                <td>
                    <span class="status-badge bg-info bg-opacity-10 text-info">In Progress</span>
                </td>
                <td>
                    <div class="maintenance-date">Jan 12, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(4)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editRequest(4)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn approve" onclick="markComplete(4)"><i class="bi bi-check"></i></button>
                    <button class="action-btn delete" onclick="deleteRequest(4)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="maintenance-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="maintenance-title">Plumbing Fix - Kitchen Sink</div>
                </td>
                <td>
                    <div class="maintenance-property">City Center Apartments - Unit 4D</div>
                </td>
                <td>
                    <div class="maintenance-property">Robert Brown</div>
                </td>
                <td>
                    <span class="priority-badge bg-secondary bg-opacity-10 text-secondary">Low</span>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Completed</span>
                </td>
                <td>
                    <div class="maintenance-date">Jan 11, 2026</div>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(5)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editRequest(5)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn delete" onclick="deleteRequest(5)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.maintenance-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.maintenance-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.maintenance-checkbox');
    const selectAll = document.getElementById('selectAll');
    checkboxes.forEach(cb => cb.checked = false);
    selectAll.checked = false;
    updateBulkActions();
}

function bulkApprove() {
    const checkboxes = document.querySelectorAll('.maintenance-checkbox:checked');
    alert('Marking ' + checkboxes.length + ' requests as complete');
    clearSelection();
}

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.maintenance-checkbox:checked');
    if (confirm('Are you sure you want to delete ' + checkboxes.length + ' requests?')) {
        alert('Deleting ' + checkboxes.length + ' requests');
        clearSelection();
    }
}

function viewDetails(id) {
    alert('Viewing details for request #' + id);
}

function editRequest(id) {
    alert('Editing request #' + id);
}

function markComplete(id) {
    alert('Marking request #' + id + ' as complete');
}

function deleteRequest(id) {
    if (confirm('Are you sure you want to delete this request?')) {
        alert('Deleting request #' + id);
    }
}
</script>
@endsection
