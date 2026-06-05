@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>💰 Accountant Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Accountant Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Payments</h5>
                    <p class="card-text">View all payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Payouts</h5>
                    <p class="card-text">Landlord payouts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Revenue</h5>
                    <p class="card-text">Platform revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text">Financial reports</p>
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
                    <a href="#" class="btn btn-primary mr-2">View Payments</a>
                    <a href="#" class="btn btn-secondary mr-2">View Payouts</a>
                    <a href="#" class="btn btn-warning mr-2">Tax Reports</a>
                    <a href="#" class="btn btn-success">Financial Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
