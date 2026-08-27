@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard - Leave Management')

@section('content')


<!-- Stats Cards -->

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock"></i> Pending Admin</h5>
                <h2 class="card-text">{{ $pendingAdmin ?? 0 }}</h2>
                <small>HR Leaves pending approval</small>
            </div>
        </div>
    </div>
</div>

<!-- Pending HR Leave Requests -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Pending HR Leave Requests</h5>
        <a href="{{ route('leave.admin.pending') }}" class="btn btn-primary btn-sm">
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
                            <th>Department</th>
                            <th>Leave Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $leave)
                        <tr>
                            <td>{{ $loop->iteration + ($pendingRequests->currentPage() - 1) * $pendingRequests->perPage() }}</td>
                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                            <td>{{ $leave->employee->department ? $leave->employee->department->department_name : 'N/A' }}</td>
                            <td>{{ $leave->leaveType->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($leave->from_date)) }} to {{ date('d-m-Y', strtotime($leave->to_date)) }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td>
                                <span class="badge bg-warning">Pending Admin</span>
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
                <i class="fas fa-info-circle"></i> No pending HR leave requests.
            </div>
        @endif
    </div>
</div>

@endsection