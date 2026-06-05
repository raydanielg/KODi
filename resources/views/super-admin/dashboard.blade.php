@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>👑 Super Admin Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Super Admin Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">System Health</h5>
                    <p class="card-text">All systems operational</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Database</h5>
                    <p class="card-text">Manage database</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Backups</h5>
                    <p class="card-text">System backups</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Logs</h5>
                    <p class="card-text">View system logs</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-primary mr-2">Manage Admins</a>
                    <a href="#" class="btn btn-secondary mr-2">System Settings</a>
                    <a href="#" class="btn btn-warning mr-2">Feature Flags</a>
                    <a href="#" class="btn btn-danger">Maintenance Mode</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
