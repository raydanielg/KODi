@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>🏘️ Landlord Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Landlord Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">My Properties</h5>
                    <p class="card-text">Manage your listings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Applications</h5>
                    <p class="card-text">Review tenant applications</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Rent Payments</h5>
                    <p class="card-text">Track rent collection</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Maintenance</h5>
                    <p class="card-text">Handle requests</p>
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
                    <a href="#" class="btn btn-primary mr-2">Add Property</a>
                    <a href="#" class="btn btn-secondary mr-2">View Applications</a>
                    <a href="#" class="btn btn-warning mr-2">Request Payout</a>
                    <a href="#" class="btn btn-success">View Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
