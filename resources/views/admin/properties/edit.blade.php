@extends('layouts.admin')

@section('title', 'Edit Property')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
    .page-header p { color: #6b7280; font-size: 0.95rem; }
    
    .form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        margin-bottom: 1.5rem;
    }
    .form-card h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    .form-input {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 0.875rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 0.875rem;
        transition: all 0.2s;
        width: 100%;
    }
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
</style>

<div class="page-header animate-fade-in">
    <h1>Edit Property</h1>
    <p>Update property information</p>
</div>

<!-- Back Button -->
<div class="mb-4 animate-fade-in">
    <button class="btn btn-secondary" onclick="window.history.back()">
        <i class="bi bi-arrow-left me-2"></i> Back to Properties
    </button>
</div>

<!-- Basic Information -->
<div class="form-card animate-fade-in delay-1">
    <h3><i class="bi bi-info-circle me-2"></i>Basic Information</h3>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Property Name</label>
            <input type="text" class="form-input" value="Sunset Apartments">
        </div>
        <div class="col-md-6">
            <label class="form-label">Property Type</label>
            <select class="form-select">
                <option selected>Apartment</option>
                <option>House</option>
                <option>Villa</option>
                <option>Office Space</option>
                <option>Commercial</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Location</label>
            <input type="text" class="form-input" value="Dar es Salaam, Tanzania">
        </div>
        <div class="col-md-6">
            <label class="form-label">Unit Number</label>
            <input type="text" class="form-input" value="Unit 2A">
        </div>
    </div>
</div>

<!-- Property Details -->
<div class="form-card animate-fade-in delay-1">
    <h3><i class="bi bi-building me-2"></i>Property Details</h3>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Bedrooms</label>
            <input type="number" class="form-input" value="2">
        </div>
        <div class="col-md-4">
            <label class="form-label">Bathrooms</label>
            <input type="number" class="form-input" value="2">
        </div>
        <div class="col-md-4">
            <label class="form-label">Size (m²)</label>
            <input type="number" class="form-input" value="85">
        </div>
        <div class="col-md-4">
            <label class="form-label">Floor</label>
            <input type="text" class="form-input" value="2nd Floor">
        </div>
        <div class="col-md-4">
            <label class="form-label">Parking Spaces</label>
            <input type="number" class="form-input" value="1">
        </div>
        <div class="col-md-4">
            <label class="form-label">Furnished</label>
            <select class="form-select">
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
</div>

<!-- Pricing & Status -->
<div class="form-card animate-fade-in delay-2">
    <h3><i class="bi bi-cash-stack me-2"></i>Pricing & Status</h3>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Monthly Rent (TZS)</label>
            <input type="number" class="form-input" value="450000">
        </div>
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select class="form-select">
                <option value="occupied" selected>Occupied</option>
                <option value="vacant">Vacant</option>
                <option value="pending">Pending</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Available From</label>
            <input type="date" class="form-input" value="2026-01-15">
        </div>
        <div class="col-md-6">
            <label class="form-label">Listed On</label>
            <input type="date" class="form-input" value="2026-01-01">
        </div>
    </div>
</div>

<!-- Actions -->
<div class="form-card animate-fade-in delay-2">
    <div class="d-flex gap-2">
        <button class="btn btn-primary" onclick="saveProperty()">
            <i class="bi bi-check-lg me-1"></i> Save Changes
        </button>
        <button class="btn btn-secondary" onclick="window.history.back()">
            <i class="bi bi-x-lg me-1"></i> Cancel
        </button>
    </div>
</div>

<script>
function saveProperty() {
    Swal.fire({
        title: 'Save Changes?',
        text: 'Are you sure you want to save the changes to this property?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, save!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Saved!',
                text: 'Property has been updated successfully.',
                icon: 'success',
                confirmButtonColor: '#3b82f6'
            }).then(() => {
                window.location.href = '/admin/properties/1';
            });
        }
    });
}
</script>
@endsection
