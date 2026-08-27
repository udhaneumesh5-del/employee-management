@extends('layouts.dashboard')

@section('page-title', 'Pending HR Approval')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Pending HR Approval</h5>
        <a href="{{ route('leave.hr.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('leave.hr.pending') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-input name="search" label="Search" placeholder="Search by employee name..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('leave.hr.pending') }}" class="btn btn-secondary" style="margin-top: 30px;">
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
                        <th>Manager</th>
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Manager Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration + ($leaves->currentPage() - 1) * $leaves->perPage() }}</td>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->employee->department ? $leave->employee->department->department_name : 'N/A' }}</td>
                        <td>{{ $leave->employee->manager ? $leave->employee->manager->first_name . ' ' . $leave->employee->manager->last_name : 'N/A' }}</td>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ date('d-m-Y', strtotime($leave->from_date)) }} to {{ date('d-m-Y', strtotime($leave->to_date)) }}</td>
                        <td>{{ $leave->total_days }}</td>
                        <td>
                            @if($leave->manager_status == 'approved')
                                <span class="badge bg-success">Manager Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <!-- View Button -->
                            <a href="{{ route('leave.view', $leave->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i> View
                            </a>
                            
                            <!-- Approve Button -->
                            <form action="{{ route('leave.hr.approve', $leave->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this leave?')">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            
                            <!-- Reject Button -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('leave.hr.reject', $leave->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Leave</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Employee:</strong> {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                                <p><strong>Leave Type:</strong> {{ $leave->leaveType->name }}</p>
                                                <p><strong>Days:</strong> {{ $leave->total_days }}</p>
                                                <p><strong>Manager:</strong> {{ $leave->employee->manager ? $leave->employee->manager->first_name . ' ' . $leave->employee->manager->last_name : 'N/A' }}</p>
                                                <p><strong>Manager Status:</strong> 
                                                    @if($leave->manager_status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @endif
                                                </p>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                    <textarea name="hr_remarks" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No pending HR requests found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$leaves" />
    </div>
</div>
@endsection