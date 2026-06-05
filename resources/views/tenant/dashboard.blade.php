@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>👤 Tenant Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Tenant Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Search Properties</h5>
                    <p class="card-text">Find your next home</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">My Rentals</h5>
                    <p class="card-text">View your rentals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pay Rent</h5>
                    <p class="card-text">Make payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Maintenance</h5>
                    <p class="card-text">Report issues</p>
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
                    <a href="#" class="btn btn-primary mr-2">Browse Properties</a>
                    <a href="#" class="btn btn-secondary mr-2">My Applications</a>
                    <a href="#" class="btn btn-warning mr-2">Pay Rent</a>
                    <a href="#" class="btn btn-success">Messages</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
