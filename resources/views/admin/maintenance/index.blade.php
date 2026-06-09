@extends('layouts.admin')

@section('title', 'Maintenance')

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
    
    .maintenance-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .maintenance-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .maintenance-title {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
    }
    .maintenance-property {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .maintenance-priority {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .maintenance-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .maintenance-date {
        font-size: 0.8rem;
        color: #9ca3af;
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
            <div class="d-flex gap-2 flex-wrap">
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
</div>

<!-- Maintenance Requests List -->
<div class="row g-3">
    <div class="col-12 animate-fade-in delay-1">
        <div class="maintenance-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="maintenance-title">Water Leak in Bathroom</div>
                        <div class="maintenance-property">Sunset Apartments - Unit 2A</div>
                        <div class="maintenance-date mt-1">Reported by John Doe</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="maintenance-priority bg-danger bg-opacity-10 text-danger">High Priority</span>
                    <span class="maintenance-status bg-info bg-opacity-10 text-info ms-2">In Progress</span>
                    <div class="maintenance-date mt-1">Jan 15, 2026</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 animate-fade-in delay-1">
        <div class="maintenance-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div>
                        <div class="maintenance-title">Electrical Issue - Lights Not Working</div>
                        <div class="maintenance-property">Green Valley Estate - Unit 5B</div>
                        <div class="maintenance-date mt-1">Reported by Sarah Miller</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="maintenance-priority bg-warning bg-opacity-10 text-warning">Medium Priority</span>
                    <span class="maintenance-status bg-warning bg-opacity-10 text-warning ms-2">Pending</span>
                    <div class="maintenance-date mt-1">Jan 14, 2026</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 animate-fade-in delay-2">
        <div class="maintenance-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="maintenance-title">AC Repair</div>
                        <div class="maintenance-property">Ocean View Towers - Unit 3C</div>
                        <div class="maintenance-date mt-1">Reported by Michael Johnson</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="maintenance-priority bg-secondary bg-opacity-10 text-secondary">Low Priority</span>
                    <span class="maintenance-status bg-success bg-opacity-10 text-success ms-2">Completed</span>
                    <div class="maintenance-date mt-1">Jan 13, 2026</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 animate-fade-in delay-2">
        <div class="maintenance-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div>
                        <div class="maintenance-title">Door Lock Replacement</div>
                        <div class="maintenance-property">Safari Heights - Unit 1A</div>
                        <div class="maintenance-date mt-1">Reported by Emily Wilson</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="maintenance-priority bg-warning bg-opacity-10 text-warning">Medium Priority</span>
                    <span class="maintenance-status bg-info bg-opacity-10 text-info ms-2">In Progress</span>
                    <div class="maintenance-date mt-1">Jan 12, 2026</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 animate-fade-in delay-1">
        <div class="maintenance-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="maintenance-title">Plumbing Fix - Kitchen Sink</div>
                        <div class="maintenance-property">City Center Apartments - Unit 4D</div>
                        <div class="maintenance-date mt-1">Reported by Robert Brown</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="maintenance-priority bg-secondary bg-opacity-10 text-secondary">Low Priority</span>
                    <span class="maintenance-status bg-success bg-opacity-10 text-success ms-2">Completed</span>
                    <div class="maintenance-date mt-1">Jan 11, 2026</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
