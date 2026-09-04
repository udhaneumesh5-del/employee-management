@extends('layouts.dashboard')

@section('page-title', 'Manager Reimbursement Dashboard')

@section('content')
<div class="row">
    <!-- My Pending Claims -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-clock"></i> My Pending</h5>
                <h2 class="card-text">{{ $stats['my_pending'] ?? 0 }}</h2>
                <small>My pending requests</small>
            </div>
        </div>
    </div>

    <!-- Team Pending Approvals -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users-clock"></i> Team Pending</h5>
                <h2 class="card-text">{{ $stats['team_pending'] ?? 0 }}</h2>
                <small>Team approvals pending</small>
            </div>
        </div>
    </div>

    <!-- Approved -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Approved</h5>
                <h2 class="card-text">{{ $stats['approved'] ?? 0 }}</h2>
                <small>Team approved requests</small>
            </div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times-circle"></i> Rejected</h5>
                <h2 class="card-text">{{ $stats['rejected'] ?? 0 }}</h2>
                <small>Team rejected requests</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Total Amount -->
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Total Amount</h5>
                <h2 class="card-text">₹{{ number_format($stats['total_amount'] ?? 0, 2) }}</h2>
                <small>Total approved amount</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <a href="{{ route('reimbursements.my-requests') }}" class="btn btn-primary w-100">
            <i class="fas fa-list"></i> My Requests
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('reimbursements.manager.pending') }}" class="btn btn-warning w-100">
            <i class="fas fa-clock"></i> Pending Approvals
        </a>
    </div>
</div>
@endsection