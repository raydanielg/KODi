@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
    .page-header p { color: #6b7280; font-size: 0.95rem; }
    
    .report-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
    }
    .report-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
    }
    .report-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 16px;
    }
    .report-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }
    .report-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 16px;
    }
    .report-meta {
        font-size: 0.75rem;
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
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }
    .delay-6 { animation-delay: 0.6s; }
</style>

<div class="page-header animate-fade-in">
    <h1>Reports</h1>
    <p>Generate and view detailed analytics reports</p>
</div>

<!-- Reports Grid -->
<div class="row g-3">
    <div class="col-12 col-sm-6 col-lg-4 animate-fade-in delay-1">
        <div class="report-card">
            <div class="report-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="report-title">Financial Report</div>
            <div class="report-description">Revenue, expenses, and profit analysis</div>
            <div class="report-meta">Last updated: Jan 15, 2026</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 animate-fade-in delay-2">
        <div class="report-card">
            <div class="report-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-people"></i>
            </div>
            <div class="report-title">Tenant Report</div>
            <div class="report-description">Occupancy rates and tenant demographics</div>
            <div class="report-meta">Last updated: Jan 14, 2026</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 animate-fade-in delay-3">
        <div class="report-card">
            <div class="report-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-building"></i>
            </div>
            <div class="report-title">Property Report</div>
            <div class="report-description">Property performance and utilization</div>
            <div class="report-meta">Last updated: Jan 13, 2026</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 animate-fade-in delay-4">
        <div class="report-card">
            <div class="report-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-tools"></i>
            </div>
            <div class="report-title">Maintenance Report</div>
            <div class="report-description">Maintenance requests and resolution times</div>
            <div class="report-meta">Last updated: Jan 12, 2026</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 animate-fade-in delay-5">
        <div class="report-card">
            <div class="report-icon bg-danger bg-opacity-10 text-danger">
                <i class="bi bi-graph-down-arrow"></i>
            </div>
            <div class="report-title">Collection Report</div>
            <div class="report-description">Payment collection and overdue analysis</div>
            <div class="report-meta">Last updated: Jan 11, 2026</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 animate-fade-in delay-6">
        <div class="report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                <i class="bi bi-file-earmark-bar-graph"></i>
            </div>
            <div class="report-title">Custom Report</div>
            <div class="report-description">Generate custom reports with your parameters</div>
            <div class="report-meta">Create new report</div>
        </div>
    </div>
</div>
@endsection
