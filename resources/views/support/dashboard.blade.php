@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>🎧 Support Agent Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Support Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tickets</h5>
                    <p class="card-text">Manage support tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Knowledge Base</h5>
                    <p class="card-text">View articles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">FAQ</h5>
                    <p class="card-text">Common questions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Messages</h5>
                    <p class="card-text">User messages</p>
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
                    <a href="#" class="btn btn-primary mr-2">View Tickets</a>
                    <a href="#" class="btn btn-secondary mr-2">Knowledge Base</a>
                    <a href="#" class="btn btn-warning mr-2">FAQ</a>
                    <a href="#" class="btn btn-success">Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
