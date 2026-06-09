@extends('layouts.admin')

@section('title', 'Users')

@section('content')
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
    
    .user-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .user-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
    }
    .user-name {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
    }
    .user-email {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .user-role {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .user-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
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
    .delay-3 { animation-delay: 0.3s; }
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
            <div class="d-flex gap-2 flex-wrap">
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
</div>

<!-- Users Grid -->
<div class="row g-3">
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-1">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    JD
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">John Doe</div>
                    <div class="user-email mb-2">john@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-primary bg-opacity-10 text-primary">Tenant</span>
                        <span class="user-status bg-success bg-opacity-10 text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-1">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    SM
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">Sarah Miller</div>
                    <div class="user-email mb-2">sarah@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-info bg-opacity-10 text-info">Landlord</span>
                        <span class="user-status bg-success bg-opacity-10 text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-2">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    MJ
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">Michael Johnson</div>
                    <div class="user-email mb-2">michael@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-warning bg-opacity-10 text-warning">Agent</span>
                        <span class="user-status bg-success bg-opacity-10 text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-2">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    EW
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">Emily Wilson</div>
                    <div class="user-email mb-2">emily@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-primary bg-opacity-10 text-primary">Tenant</span>
                        <span class="user-status bg-danger bg-opacity-10 text-danger">Inactive</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-1">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    RB
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">Robert Brown</div>
                    <div class="user-email mb-2">robert@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-info bg-opacity-10 text-info">Landlord</span>
                        <span class="user-status bg-success bg-opacity-10 text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-1">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                    LT
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">Lisa Taylor</div>
                    <div class="user-email mb-2">lisa@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-primary bg-opacity-10 text-primary">Tenant</span>
                        <span class="user-status bg-success bg-opacity-10 text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-2">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                    DK
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">David Kim</div>
                    <div class="user-email mb-2">david@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-warning bg-opacity-10 text-warning">Agent</span>
                        <span class="user-status bg-warning bg-opacity-10 text-warning">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-2">
        <div class="user-card">
            <div class="d-flex align-items-start gap-3">
                <div class="user-avatar" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);">
                    AP
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">Anna Peterson</div>
                    <div class="user-email mb-2">anna@example.com</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="user-role bg-primary bg-opacity-10 text-primary">Tenant</span>
                        <span class="user-status bg-success bg-opacity-10 text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
