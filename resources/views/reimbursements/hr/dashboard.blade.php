@extends('layouts.dashboard')

@section('page-title', 'HR Reimbursement Dashboard')

@section('content')
<div class="row">
    <!-- Pending Employee Approvals -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-clock"></i> Pending Employee</h5>
                <h2 class="card-text">{{ $stats['pending_employee'] ?? 0 }}</h2>
                <small>Manager approved - Employee</small>
            </div>
        </div>
    </div>

    <!-- Pending Manager Approvals -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-tie"></i> Pending Manager</h5>
                <h2 class="card-text">{{ $stats['pending_manager'] ?? 0 }}</h2>
                <small>Manager requests</small>
            </div>
        </div>
    </div>

    <!-- HR Final Approvals -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-double"></i> HR Final</h5>
                <h2 class="card-text">{{ $stats['pending_hr_final'] ?? 0 }}</h2>
                <small>Pending HR final</small>
            </div>
        </div>
    </div>

    <!-- Pending Admin -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-shield"></i> Pending Admin</h5>
                <h2 class="card-text">{{ $stats['pending_admin'] ?? 0 }}</h2>
                <small>HR requests to Admin</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Rejected -->
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times-circle"></i> Rejected</h5>
                <h2 class="card-text">{{ $stats['rejected'] ?? 0 }}</h2>
                <small>HR rejected requests</small>
            </div>
        </div>
    </div>

    <!-- Total Pending Amount -->
    <div class="col-md-8 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Total Pending Amount</h5>
                <h2 class="card-text">₹{{ number_format($stats['total_pending_amount'] ?? 0, 2) }}</h2>
                <small>Amount awaiting approval</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-4">
        <a href="{{ route('reimbursements.hr.pending') }}" class="btn btn-warning w-100">
            <i class="fas fa-clock"></i> Pending Approvals
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