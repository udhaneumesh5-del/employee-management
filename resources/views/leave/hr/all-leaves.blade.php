@extends('layouts.dashboard')

@section('page-title', 'All Leave Requests')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-list"></i> All Leave Requests</h5>
        <a href="{{ route('leave.hr.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('leave.hr.all') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Employee Name</label>
                        <input type="text" name="employee_name" class="form-control" 
                               placeholder="Search by name..." value="{{ request('employee_name') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending_manager" {{ request('status') == 'pending_manager' ? 'selected' : '' }}>Pending Manager</option>
                            <option value="pending_hr" {{ request('status') == 'pending_hr' ? 'selected' : '' }}>Pending HR</option>
                            <option value="pending_admin" {{ request('status') == 'pending_admin' ? 'selected' : '' }}>Pending Admin</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="manager_rejected" {{ request('status') == 'manager_rejected' ? 'selected' : '' }}>Manager Rejected</option>
                            <option value="hr_rejected" {{ request('status') == 'hr_rejected' ? 'selected' : '' }}>HR Rejected</option>
                            <option value="admin_rejected" {{ request('status') == 'admin_rejected' ? 'selected' : '' }}>Admin Rejected</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('leave.hr.all') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Leaves Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Email</th>
                        <th>Leave Type</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration + ($leaves->currentPage() - 1) * $leaves->perPage() }}</td>
                        <td>
                            <strong>{{ $leave->first_name }} {{ $leave->last_name }}</strong>
                        </td>
                        <td>{{ $leave->employee_email }}</td>
                        <td>
                            <span class="badge bg-info">{{ $leave->leave_type_name }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $leave->total_days }}</span>
                        </td>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending_manager' => 'Pending Manager',
                                    'pending_hr' => 'Pending HR',
                                    'pending_admin' => 'Pending Admin',
                                    'approved' => 'Approved',
                                    'manager_rejected' => 'Manager Rejected',
                                    'hr_rejected' => 'HR Rejected',
                                    'admin_rejected' => 'Admin Rejected',
                                    'cancelled' => 'Cancelled'
                                ];
                                $statusClasses = [
                                    'pending_manager' => 'bg-warning text-dark',
                                    'pending_hr' => 'bg-warning text-dark',
                                    'pending_admin' => 'bg-warning text-dark',
                                    'approved' => 'bg-success',
                                    'manager_rejected' => 'bg-danger',
                                    'hr_rejected' => 'bg-danger',
                                    'admin_rejected' => 'bg-danger',
                                    'cancelled' => 'bg-secondary'
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$leave->status] ?? 'bg-secondary' }}">
                                {{ $statusLabels[$leave->status] ?? $leave->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('leave.view', $leave->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No leave requests found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $leaves->firstItem() ?? 0 }} to {{ $leaves->lastItem() ?? 0 }} 
                of {{ $leaves->total() }} entries
            </div>
            <div>
                {{ $leaves->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection