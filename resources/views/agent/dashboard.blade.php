@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>🤝 Agent Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Agent Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">My Listings</h5>
                    <p class="card-text">Manage properties</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Applications</h5>
                    <p class="card-text">Review applications</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Commission</h5>
                    <p class="card-text">Track earnings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Payouts</h5>
                    <p class="card-text">Request payout</p>
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
                    <a href="#" class="btn btn-primary mr-2">Add Listing</a>
                    <a href="#" class="btn btn-secondary mr-2">View Applications</a>
                    <a href="#" class="btn btn-warning mr-2">Request Payout</a>
                    <a href="#" class="btn btn-success">View Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
