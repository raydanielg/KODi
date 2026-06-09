@extends('layouts.admin')

@section('title', 'Properties')

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
    
    .property-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .property-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
    }
    .property-image {
        height: 160px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 3rem;
    }
    .property-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .property-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
    }
    .property-location {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .property-meta {
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
    <h1>Properties</h1>
    <p>Manage all properties registered on the platform</p>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">156</div>
                    <div class="stat-label">Total Properties</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-1">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">142</div>
                    <div class="stat-label">Occupied</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 animate-fade-in delay-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">14</div>
                    <div class="stat-label">Vacant</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-exclamation-circle"></i>
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
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-clock"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card animate-fade-in delay-2">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
            <input type="text" class="search-input w-100" placeholder="Search properties...">
        </div>
        <div class="col-12 col-md-8">
            <div class="d-flex gap-2 flex-wrap">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Occupied</button>
                <button class="filter-btn">Vacant</button>
                <button class="filter-btn">Pending</button>
                <button class="filter-btn ms-auto">
                    <i class="bi bi-plus-lg me-1"></i> Add Property
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Properties Grid -->
<div class="row g-3">
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-1">
        <div class="property-card">
            <div class="position-relative">
                <div class="property-image">
                    <i class="bi bi-building"></i>
                </div>
                <span class="property-badge bg-success text-white">Occupied</span>
            </div>
            <div class="p-3">
                <h5 class="fw-bold mb-1" style="color: #111827;">Sunset Apartments</h5>
                <p class="property-location mb-2"><i class="bi bi-geo-alt me-1"></i> Dar es Salaam, Tanzania</p>
                <div class="property-price mb-2">TZS 450,000/mo</div>
                <div class="d-flex gap-3 property-meta">
                    <span><i class="bi bi-door-open me-1"></i> 2 Beds</span>
                    <span><i class="bi bi-droplet me-1"></i> 2 Baths</span>
                    <span><i class="bi bi-rulers me-1"></i> 85m²</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-1">
        <div class="property-card">
            <div class="position-relative">
                <div class="property-image" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="bi bi-house"></i>
                </div>
                <span class="property-badge bg-warning text-white">Vacant</span>
            </div>
            <div class="p-3">
                <h5 class="fw-bold mb-1" style="color: #111827;">Green Valley Estate</h5>
                <p class="property-location mb-2"><i class="bi bi-geo-alt me-1"></i> Arusha, Tanzania</p>
                <div class="property-price mb-2">TZS 320,000/mo</div>
                <div class="d-flex gap-3 property-meta">
                    <span><i class="bi bi-door-open me-1"></i> 3 Beds</span>
                    <span><i class="bi bi-droplet me-1"></i> 2 Baths</span>
                    <span><i class="bi bi-rulers me-1"></i> 120m²</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-2">
        <div class="property-card">
            <div class="position-relative">
                <div class="property-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="bi bi-building"></i>
                </div>
                <span class="property-badge bg-success text-white">Occupied</span>
            </div>
            <div class="p-3">
                <h5 class="fw-bold mb-1" style="color: #111827;">Ocean View Towers</h5>
                <p class="property-location mb-2"><i class="bi bi-geo-alt me-1"></i> Mwanza, Tanzania</p>
                <div class="property-price mb-2">TZS 580,000/mo</div>
                <div class="d-flex gap-3 property-meta">
                    <span><i class="bi bi-door-open me-1"></i> 2 Beds</span>
                    <span><i class="bi bi-droplet me-1"></i> 2 Baths</span>
                    <span><i class="bi bi-rulers me-1"></i> 95m²</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-fade-in delay-2">
        <div class="property-card">
            <div class="position-relative">
                <div class="property-image" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="bi bi-house"></i>
                </div>
                <span class="property-badge bg-info text-white">Pending</span>
            </div>
            <div class="p-3">
                <h5 class="fw-bold mb-1" style="color: #111827;">Safari Heights</h5>
                <p class="property-location mb-2"><i class="bi bi-geo-alt me-1"></i> Dodoma, Tanzania</p>
                <div class="property-price mb-2">TZS 280,000/mo</div>
                <div class="d-flex gap-3 property-meta">
                    <span><i class="bi bi-door-open me-1"></i> 1 Bed</span>
                    <span><i class="bi bi-droplet me-1"></i> 1 Bath</span>
                    <span><i class="bi bi-rulers me-1"></i> 60m²</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
