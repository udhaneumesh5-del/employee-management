@extends('layouts.dashboard')

@section('page-title', 'Leave Report')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-calendar-alt"></i> Leave Report</h5>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $leave->first_name }} {{ $leave->last_name }}</td>
                        <td>{{ $leave->leave_type_name }}</td>
                        <td>{{ date('d-m-Y', strtotime($leave->from_date)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($leave->to_date)) }}</td>
                        <td>{{ $leave->total_days }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending_manager' => 'bg-warning',
                                    'manager_approved' => 'bg-info',
                                    'manager_rejected' => 'bg-danger',
                                    'pending_hr' => 'bg-warning',
                                    'hr_rejected' => 'bg-danger',
                                    'approved' => 'bg-success',
                                    'cancelled' => 'bg-secondary'
                                ];
                                $statusTexts = [
                                    'pending_manager' => 'Pending Manager',
                                    'manager_approved' => 'Manager Approved',
                                    'manager_rejected' => 'Manager Rejected',
                                    'pending_hr' => 'Pending HR',
                                    'hr_rejected' => 'HR Rejected',
                                    'approved' => 'Approved',
                                    'cancelled' => 'Cancelled'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$leave->status] ?? 'bg-secondary' }}">
                                {{ $statusTexts[$leave->status] ?? $leave->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No leave records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection