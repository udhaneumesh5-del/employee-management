@extends('layouts.dashboard')

@section('page-title', 'Admin Reimbursement Dashboard')

@section('content')
<div class="row">
    <!-- Pending HR Approvals -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-clock"></i> Pending HR</h5>
                <h2 class="card-text">{{ $stats['pending_hr'] ?? 0 }}</h2>
                <small>HR requests pending approval</small>
            </div>
        </div>
    </div>

    <!-- Final Approvals -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-double"></i> Final Approvals</h5>
                <h2 class="card-text">{{ $stats['final_approvals'] ?? 0 }}</h2>
                <small>Admin approved requests</small>
            </div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times-circle"></i> Rejected</h5>
                <h2 class="card-text">{{ $stats['rejected'] ?? 0 }}</h2>
                <small>Admin rejected requests</small>
            </div>
        </div>
    </div>

    <!-- Paid -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Paid</h5>
                <h2 class="card-text">{{ $stats['paid'] ?? 0 }}</h2>
                <small>Paid reimbursements</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Total Amount -->
    <div class="col-md-12 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Total Reimbursement Amount</h5>
                <h2 class="card-text">₹{{ number_format($stats['total_amount'] ?? 0, 2) }}</h2>
                <small>Total approved amount</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-4">
        <a href="{{ route('reimbursements.admin.pending') }}" class="btn btn-warning w-100">
            <i class="fas fa-clock"></i> Pending HR Approvals
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('reimbursements.policies.index') }}" class="btn btn-info w-100">
            <i class="fas fa-gavel"></i> Policies
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('reimbursements.reports.index') }}" class="btn btn-success w-100">
            <i class="fas fa-chart-bar"></i> Reports
        </a>
    </div>
</div>
@endsection