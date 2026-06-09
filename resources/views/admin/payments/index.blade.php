@extends('layouts.admin')

@section('title', 'Payments')

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
    .payment-amount {
        font-size: 0.95rem;
        font-weight: 700;
        color: #111827;
    }
    .payment-tenant {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
    }
    .payment-property {
        font-size: 0.8rem;
        color: #6b7280;
    }
    .payment-date {
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
    <h1>Payments</h1>
    <p>Track and manage all rental payments</p>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">TZS 45.2M</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">TZS 8.5M</div>
                    <div class="stat-label">This Month</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">3</div>
                    <div class="stat-label">Overdue</div>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card animate-fade-in delay-2">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
            <input type="text" class="search-input w-100" placeholder="Search payments...">
        </div>
        <div class="col-12 col-md-8">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Completed</button>
                <button class="filter-btn">Pending</button>
                <button class="filter-btn">Overdue</button>
                <button class="filter-btn ms-auto">
                    <i class="bi bi-download me-1"></i> Export
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

<!-- Payments Table -->
<div class="table-card animate-fade-in delay-2">
    <table class="custom-table">
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                <th>Tenant</th>
                <th>Property</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" class="payment-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="payment-tenant">John Doe</div>
                    <div class="payment-property">john@example.com</div>
                </td>
                <td>
                    <div class="payment-property">Sunset Apartments - Unit 2A</div>
                </td>
                <td>
                    <div class="payment-amount">TZS 450,000</div>
                </td>
                <td>
                    <div class="payment-date">Jan 15, 2026</div>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Completed</span>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(1)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editPayment(1)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="downloadReceipt(1)"><i class="bi bi-download"></i></button>
                    <button class="action-btn delete" onclick="deletePayment(1)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="payment-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="payment-tenant">Sarah Miller</div>
                    <div class="payment-property">sarah@example.com</div>
                </td>
                <td>
                    <div class="payment-property">Green Valley Estate - Unit 5B</div>
                </td>
                <td>
                    <div class="payment-amount">TZS 320,000</div>
                </td>
                <td>
                    <div class="payment-date">Jan 14, 2026</div>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Completed</span>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(2)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editPayment(2)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="downloadReceipt(2)"><i class="bi bi-download"></i></button>
                    <button class="action-btn delete" onclick="deletePayment(2)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="payment-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="payment-tenant">Michael Johnson</div>
                    <div class="payment-property">michael@example.com</div>
                </td>
                <td>
                    <div class="payment-property">Ocean View Towers - Unit 3C</div>
                </td>
                <td>
                    <div class="payment-amount">TZS 580,000</div>
                </td>
                <td>
                    <div class="payment-date">Jan 13, 2026</div>
                </td>
                <td>
                    <span class="status-badge bg-warning bg-opacity-10 text-warning">Pending</span>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(3)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn approve" onclick="markComplete(3)"><i class="bi bi-check"></i></button>
                    <button class="action-btn delete" onclick="deletePayment(3)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="payment-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="payment-tenant">Emily Wilson</div>
                    <div class="payment-property">emily@example.com</div>
                </td>
                <td>
                    <div class="payment-property">Safari Heights - Unit 1A</div>
                </td>
                <td>
                    <div class="payment-amount">TZS 280,000</div>
                </td>
                <td>
                    <div class="payment-date">Jan 10, 2026</div>
                </td>
                <td>
                    <span class="status-badge bg-danger bg-opacity-10 text-danger">Overdue</span>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(4)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="sendReminder(4)"><i class="bi bi-bell"></i></button>
                    <button class="action-btn delete" onclick="deletePayment(4)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox" class="payment-checkbox" onchange="updateBulkActions()"></td>
                <td>
                    <div class="payment-tenant">Robert Brown</div>
                    <div class="payment-property">robert@example.com</div>
                </td>
                <td>
                    <div class="payment-property">City Center Apartments - Unit 4D</div>
                </td>
                <td>
                    <div class="payment-amount">TZS 520,000</div>
                </td>
                <td>
                    <div class="payment-date">Jan 12, 2026</div>
                </td>
                <td>
                    <span class="status-badge bg-success bg-opacity-10 text-success">Completed</span>
                </td>
                <td>
                    <button class="action-btn" onclick="viewDetails(5)"><i class="bi bi-eye"></i></button>
                    <button class="action-btn" onclick="editPayment(5)"><i class="bi bi-pencil"></i></button>
                    <button class="action-btn" onclick="downloadReceipt(5)"><i class="bi bi-download"></i></button>
                    <button class="action-btn delete" onclick="deletePayment(5)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.payment-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    const selectAll = document.getElementById('selectAll');
    checkboxes.forEach(cb => cb.checked = false);
    selectAll.checked = false;
    updateBulkActions();
}

function bulkApprove() {
    const checkboxes = document.querySelectorAll('.payment-checkbox:checked');
    alert('Marking ' + checkboxes.length + ' payments as complete');
    clearSelection();
}

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.payment-checkbox:checked');
    if (confirm('Are you sure you want to delete ' + checkboxes.length + ' payments?')) {
        alert('Deleting ' + checkboxes.length + ' payments');
        clearSelection();
    }
}

function viewDetails(id) {
    alert('Viewing details for payment #' + id);
}

function editPayment(id) {
    alert('Editing payment #' + id);
}

function downloadReceipt(id) {
    alert('Downloading receipt for payment #' + id);
}

function markComplete(id) {
    alert('Marking payment #' + id + ' as complete');
}

function sendReminder(id) {
    alert('Sending reminder for payment #' + id);
}

function deletePayment(id) {
    if (confirm('Are you sure you want to delete this payment?')) {
        alert('Deleting payment #' + id);
    }
}
</script>
@endsection
