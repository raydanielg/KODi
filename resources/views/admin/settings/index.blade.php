@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
    .page-header p { color: #6b7280; font-size: 0.95rem; }
    
    .settings-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        margin-bottom: 1rem;
    }
    .settings-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    .settings-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .settings-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }
    .settings-description {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .settings-toggle {
        width: 48px;
        height: 26px;
        border-radius: 13px;
        background: #d1d5db;
        position: relative;
        cursor: pointer;
        transition: all 0.3s;
    }
    .settings-toggle.active {
        background: #3b82f6;
    }
    .settings-toggle::after {
        content: '';
        position: absolute;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #fff;
        top: 2px;
        left: 2px;
        transition: all 0.3s;
    }
    .settings-toggle.active::after {
        left: 24px;
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
    .btn-primary {
        background: #3b82f6;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
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
    <h1>Settings</h1>
    <p>Configure your admin panel preferences</p>
</div>

<!-- General Settings -->
<div class="settings-card animate-fade-in delay-1">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="settings-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-gear"></i>
            </div>
            <div>
                <div class="settings-title">General Settings</div>
                <div class="settings-description">Configure basic system preferences</div>
            </div>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>
</div>

<!-- Notification Settings -->
<div class="settings-card animate-fade-in delay-1">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="settings-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-bell"></i>
            </div>
            <div>
                <div class="settings-title">Notifications</div>
                <div class="settings-description">Manage email and push notifications</div>
            </div>
        </div>
        <div class="settings-toggle active"></div>
    </div>
</div>

<!-- Security Settings -->
<div class="settings-card animate-fade-in delay-2">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="settings-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-shield-lock"></i>
            </div>
            <div>
                <div class="settings-title">Security</div>
                <div class="settings-description">Password, 2FA, and login settings</div>
            </div>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>
</div>

<!-- Payment Settings -->
<div class="settings-card animate-fade-in delay-2">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="settings-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-credit-card"></i>
            </div>
            <div>
                <div class="settings-title">Payment Settings</div>
                <div class="settings-description">Configure payment gateways and fees</div>
            </div>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>
</div>

<!-- Profile Settings -->
<div class="settings-card animate-fade-in delay-3">
    <div class="p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="settings-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-person"></i>
            </div>
            <div>
                <div class="settings-title">Profile Settings</div>
                <div class="settings-description">Update your account information</div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-input" value="Admin User">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" value="admin@manna.com">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-input" value="+255 712 345 678">
            </div>
            <div class="col-12">
                <button class="btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
