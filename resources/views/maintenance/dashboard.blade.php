@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>🔧 Maintenance Dashboard</h1>
            <p class="text-muted">Welcome to the KODI Maintenance Panel</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Assigned Tasks</h5>
                    <p class="card-text">View your tasks</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">In Progress</h5>
                    <p class="card-text">Active repairs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <p class="card-text">Finished tasks</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">History</h5>
                    <p class="card-text">Past work</p>
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
                    <a href="#" class="btn btn-primary mr-2">View Tasks</a>
                    <a href="#" class="btn btn-secondary mr-2">Task History</a>
                    <a href="#" class="btn btn-success">Messages</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
