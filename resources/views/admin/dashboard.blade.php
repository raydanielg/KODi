@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>🛡️ Admin Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Admin Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text">Manage all users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Properties</h5>
                    <p class="card-text">Verify properties</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Disputes</h5>
                    <p class="card-text">Handle disputes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text">View analytics</p>
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
                    <a href="#" class="btn btn-primary mr-2">Manage Landlords</a>
                    <a href="#" class="btn btn-secondary mr-2">Manage Tenants</a>
                    <a href="#" class="btn btn-warning mr-2">Send Announcement</a>
                    <a href="#" class="btn btn-danger">View Disputes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
