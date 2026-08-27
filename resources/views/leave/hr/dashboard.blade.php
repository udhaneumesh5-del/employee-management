@extends('layouts.dashboard')

@section('page-title', 'HR Dashboard - Leave Management')

@section('content')

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock"></i> Pending HR</h5>
                <h2 class="card-text">{{ $pendingHR ?? 0 }}</h2>
                <small>Manager approved leaves</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-hourglass-half"></i> Pending Admin</h5>
                <h2 class="card-text">{{ $pendingAdmin ?? 0 }}</h2>
                <small>HR leaves pending admin</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check"></i> Approved</h5>
                <h2 class="card-text">{{ $approved ?? 0 }}</h2>
                <small>Total approved leaves</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times"></i> Rejected</h5>
                <h2 class="card-text">{{ $rejected ?? 0 }}</h2>
                <small>Total rejected leaves</small>
            </div>
        </div>
    </div>
</div>

<!-- Leave Balances Card -->
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><i class="fas fa-balance-scale"></i> Leave Balances</h5>
                        <p class="card-text">View all employees leave balances</p>
                    </div>
                    <i class="fas fa-balance-scale fa-3x"></i>
                </div>
                <a href="{{ route('leave.hr.balances') }}" class="btn btn-light btn-sm mt-2">
                    <i class="fas fa-eye"></i> View Balances
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pending HR Approval -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Pending HR Approval</h5>
        <a href="{{ route('leave.hr.pending') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye"></i> View All
        </a>
    </div>
    <div class="card-body">
        @if($pendingRequests->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Days</th>
                            <th>Manager Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $leave)
                        <tr>
                            <td>{{ $loop->iteration + ($pendingRequests->currentPage() - 1) * $pendingRequests->perPage() }}</td>
                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                            <td>{{ $leave->leaveType->name }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td>
                                @if($leave->manager_status == 'approved')
                                    <span class="badge bg-success">Manager Approved</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('leave.view', $leave->id) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-pagination :items="$pendingRequests" />
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No pending HR requests.
            </div>
        @endif
    </div>
</div>

@endsection