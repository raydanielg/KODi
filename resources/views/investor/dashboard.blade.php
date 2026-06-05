@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>📊 Investor Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Investor Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Financial Dashboard</h5>
                    <p class="card-text">View performance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Key Metrics</h5>
                    <p class="card-text">KPIs overview</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">User Growth</h5>
                    <p class="card-text">Growth analytics</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text">Download reports</p>
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
                    <a href="#" class="btn btn-primary mr-2">Financial Dashboard</a>
                    <a href="#" class="btn btn-secondary mr-2">Key Metrics</a>
                    <a href="#" class="btn btn-warning mr-2">User Growth</a>
                    <a href="#" class="btn btn-success">Cap Table</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
