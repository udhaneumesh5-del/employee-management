@extends('layouts.dashboard')

@section('page-title', 'Pending HR Leave Requests')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Pending HR Leave Requests</h5>
        <a href="{{ route('leave.admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('leave.admin.pending') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-input name="search" label="Search" placeholder="Search by employee name..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('leave.admin.pending') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Pending Leaves Table -->
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
                        <th>HR Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration + ($leaves->currentPage() - 1) * $leaves->perPage() }}</td>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->employee->department ? $leave->employee->department->department_name : 'N/A' }}</td>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ date('d-m-Y', strtotime($leave->from_date)) }} to {{ date('d-m-Y', strtotime($leave->to_date)) }}</td>
                        <td>{{ $leave->total_days }}</td>
                        <td>
                            @if($leave->hr_status == 'approved')
                                <span class="badge bg-success">HR Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('leave.view', $leave->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="chas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No pending HR requests found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$leaves" />
    </div>
</div>
@endsection