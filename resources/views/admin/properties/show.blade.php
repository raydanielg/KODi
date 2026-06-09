@extends('layouts.admin')

@section('title', 'Property Details')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
    .page-header p { color: #6b7280; font-size: 0.95rem; }
    
    .info-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        margin-bottom: 1.5rem;
    }
    .info-card h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .property-image {
        width: 100%;
        height: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }
    
    .property-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }
    .property-location {
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 16px;
    }
    .property-price {
        font-size: 1.75rem;
        font-weight: 800;
        color: #3b82f6;
        margin-bottom: 16px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    .info-value {
        font-size: 0.875rem;
        color: #111827;
        font-weight: 600;
    }
    
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    
    .person-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 1rem;
    }
    .person-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
    }
    .person-name {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }
    .person-role {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 8px;
    }
    .person-contact {
        font-size: 0.8rem;
        color: #9ca3af;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background: #3b82f6;
        color: #fff;
    }
    .btn-primary:hover {
        background: #2563eb;
    }
    .btn-danger {
        background: #ef4444;
        color: #fff;
    }
    .btn-danger:hover {
        background: #dc2626;
    }
    .btn-secondary {
        background: #6b7280;
        color: #fff;
    }
    .btn-secondary:hover {
        background: #4b5563;
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
    <h1>Property Details</h1>
    <p>View complete information about this property</p>
</div>

<!-- Back Button -->
<div class="mb-4 animate-fade-in">
    <button class="btn btn-secondary" onclick="window.history.back()">
        <i class="bi bi-arrow-left me-2"></i> Back to Properties
    </button>
</div>

<!-- Property Image -->
<div class="property-image animate-fade-in delay-1">
    <i class="bi bi-building"></i>
</div>

<!-- Property Basic Info -->
<div class="info-card animate-fade-in delay-1">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2 class="property-title">Sunset Apartments</h2>
            <p class="property-location"><i class="bi bi-geo-alt me-1"></i> Dar es Salaam, Tanzania - Unit 2A</p>
            <div class="property-price">TZS 450,000/month</div>
            <span class="status-badge bg-success bg-opacity-10 text-success">Occupied</span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="editProperty()">
                <i class="bi bi-pencil me-1"></i> Edit
            </button>
            <button class="btn btn-danger" onclick="deleteProperty()">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </div>
    </div>
</div>

<!-- Property Details -->
<div class="row g-3 mb-4">
    <div class="col-lg-8 animate-fade-in delay-2">
        <div class="info-card">
            <h3><i class="bi bi-info-circle me-2"></i>Property Details</h3>
            <div class="info-row">
                <span class="info-label">Property Type</span>
                <span class="info-value">Apartment</span>
            </div>
            <div class="info-row">
                <span class="info-label">Bedrooms</span>
                <span class="info-value">2</span>
            </div>
            <div class="info-row">
                <span class="info-label">Bathrooms</span>
                <span class="info-value">2</span>
            </div>
            <div class="info-row">
                <span class="info-label">Size</span>
                <span class="info-value">85 m²</span>
            </div>
            <div class="info-row">
                <span class="info-label">Floor</span>
                <span class="info-value">2nd Floor</span>
            </div>
            <div class="info-row">
                <span class="info-label">Furnished</span>
                <span class="info-value">Yes</span>
            </div>
            <div class="info-row">
                <span class="info-label">Parking</span>
                <span class="info-value">1 Space</span>
            </div>
            <div class="info-row">
                <span class="info-label">Available From</span>
                <span class="info-value">January 15, 2026</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 animate-fade-in delay-2">
        <div class="info-card">
            <h3><i class="bi bi-activity me-2"></i>Quick Stats</h3>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="status-badge bg-success bg-opacity-10 text-success">Occupied</span>
            </div>
            <div class="info-row">
                <span class="info-label">Listed On</span>
                <span class="info-value">Jan 1, 2026</span>
            </div>
            <div class="info-row">
                <span class="info-label">Views</span>
                <span class="info-value">1,248</span>
            </div>
            <div class="info-row">
                <span class="info-label">Inquiries</span>
                <span class="info-value">45</span>
            </div>
        </div>
    </div>
</div>

<!-- Owner & Agent Information -->
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-fade-in delay-3">
        <div class="info-card">
            <h3><i class="bi bi-person-check me-2"></i>Property Owner</h3>
            <div class="person-card">
                <div class="person-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    SM
                </div>
                <div>
                    <div class="person-name">Sarah Miller</div>
                    <div class="person-role">Landlord</div>
                    <div class="person-contact">
                        <i class="bi bi-envelope me-1"></i> sarah@example.com<br>
                        <i class="bi bi-phone me-1"></i> +255 712 345 678
                    </div>
                </div>
            </div>
            <div class="info-row">
                <span class="info-label">Owner Since</span>
                <span class="info-value">Jan 2020</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Properties</span>
                <span class="info-value">5</span>
            </div>
            <div class="info-row">
                <span class="info-label">Verification</span>
                <span class="status-badge bg-success bg-opacity-10 text-success">Verified</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 animate-fade-in delay-3">
        <div class="info-card">
            <h3><i class="bi bi-person-badge me-2"></i>Agent Information</h3>
            <div class="person-card">
                <div class="person-avatar" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    MJ
                </div>
                <div>
                    <div class="person-name">Michael Johnson</div>
                    <div class="person-role">Real Estate Agent</div>
                    <div class="person-contact">
                        <i class="bi bi-envelope me-1"></i> michael@agent.com<br>
                        <i class="bi bi-phone me-1"></i> +255 765 432 109
                    </div>
                </div>
            </div>
            <div class="info-row">
                <span class="info-label">Agency</span>
                <span class="info-value">Premium Properties Ltd</span>
            </div>
            <div class="info-row">
                <span class="info-label">Commission</span>
                <span class="info-value">5%</span>
            </div>
            <div class="info-row">
                <span class="info-label">License</span>
                <span class="status-badge bg-success bg-opacity-10 text-success">Licensed</span>
            </div>
        </div>
    </div>
</div>

<!-- Current Tenant -->
<div class="info-card animate-fade-in delay-3">
    <h3><i class="bi bi-person me-2"></i>Current Tenant</h3>
    <div class="person-card">
        <div class="person-avatar" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            JD
        </div>
        <div>
            <div class="person-name">John Doe</div>
            <div class="person-role">Tenant</div>
            <div class="person-contact">
                <i class="bi bi-envelope me-1"></i> john@example.com<br>
                <i class="bi bi-phone me-1"></i> +255 789 012 345
            </div>
        </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-md-4">
            <div class="info-row">
                <span class="info-label">Lease Start</span>
                <span class="info-value">Jan 15, 2026</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-row">
                <span class="info-label">Lease End</span>
                <span class="info-value">Jan 14, 2027</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-row">
                <span class="info-label">Payment Status</span>
                <span class="status-badge bg-success bg-opacity-10 text-success">Paid</span>
            </div>
        </div>
    </div>
</div>

<script>
function editProperty() {
    window.location.href = `/admin/properties/1/edit`;
}

function deleteProperty() {
    Swal.fire({
        title: 'Delete Property?',
        text: 'Are you sure you want to delete this property? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleted!',
                text: 'Property has been deleted.',
                icon: 'success',
                confirmButtonColor: '#3b82f6'
            }).then(() => {
                window.location.href = '/admin/properties';
            });
        }
    });
}
</script>
@endsection
