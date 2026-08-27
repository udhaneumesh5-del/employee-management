@extends('layouts.dashboard')

@section('page-title', 'Manager Dashboard - Leave Management')

@section('content')

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock"></i> Pending Manager</h5>
                <h2 class="card-text">{{ $pendingManager ?? 0 }}</h2>
                <small>Team employees waiting for approval</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-arrow-right"></i> Pending HR</h5>
                <h2 class="card-text">{{ $pendingHR ?? 0 }}</h2>
                <small>Manager approved - waiting for HR</small>
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

<!-- Pending Requests Table -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Pending Manager Approval</h5>
        <a href="{{ route('leave.manager.pending') }}" class="btn btn-primary btn-sm">
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
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $leave)
                        <tr>
                            <td>{{ $loop->iteration + ($pendingRequests->currentPage() - 1) * $pendingRequests->perPage() }}</td>
                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                            <td>{{ $leave->leaveType->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($leave->from_date)) }} to {{ date('d-m-Y', strtotime($leave->to_date)) }}</td>
                            <td>{{ $leave->total_days }}</td>
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
                <i class="fas fa-info-circle"></i> No pending requests for your team.
            </div>
        @endif
    </div>
</div>

<!-- Quick Links -->
<div class="mt-3">
    <a href="{{ route('leave.apply-form') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Apply Leave
    </a>
</div>

@endsection