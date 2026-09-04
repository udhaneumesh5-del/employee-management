@extends('layouts.dashboard')

@section('page-title', 'Reimbursement Dashboard')

@section('content')
<div class="row">
    <!-- Total Claims -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-file-invoice"></i> Total Claims</h5>
                <h2 class="card-text">{{ $stats['total'] ?? 0 }}</h2>
                <small>Total reimbursement requests</small>
            </div>
        </div>
    </div>

    <!-- Pending Claims -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock"></i> Pending Claims</h5>
                <h2 class="card-text">{{ $stats['pending'] ?? 0 }}</h2>
                <small>Awaiting approval</small>
            </div>
        </div>
    </div>

    <!-- Approved Claims -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Approved</h5>
                <h2 class="card-text">{{ $stats['approved'] ?? 0 }}</h2>
                <small>Approved claims</small>
            </div>
        </div>
    </div>

    <!-- Rejected Claims -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times-circle"></i> Rejected</h5>
                <h2 class="card-text">{{ $stats['rejected'] ?? 0 }}</h2>
                <small>Rejected claims</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Paid Amount -->
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Paid Amount</h5>
                <h2 class="card-text">₹{{ number_format($stats['paid'] ?? 0, 2) }}</h2>
                <small>Total reimbursed amount</small>
            </div>
        </div>
    </div>

    <!-- Pending Amount -->
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-hourglass-half"></i> Pending Amount</h5>
                <h2 class="card-text">₹{{ number_format($stats['pending_amount'] ?? 0, 2) }}</h2>
                <small>Amount awaiting approval</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <a href="{{ route('reimbursements.my-requests') }}" class="btn btn-primary w-100">
            <i class="fas fa-list"></i> View My Requests
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('reimbursements.create') }}" class="btn btn-success w-100">
            <i class="fas fa-plus"></i> New Request
        </a>
    </div>
</div>
@endsection