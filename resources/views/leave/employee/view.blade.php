@extends('layouts.dashboard')

@section('page-title', 'Leave Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-eye"></i> Leave Details #{{ $leave->id }}</h5>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Employee</th>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ $leave->employee->department ? $leave->employee->department->department_name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            @php
                                $user = \App\Models\User::where('email', $leave->employee->email)->first();
                            @endphp
                            {{ $user ? $user->role : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Manager</th>
                        <td>{{ $leave->employee->manager ? $leave->employee->manager->first_name . ' ' . $leave->employee->manager->last_name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Leave Type</th>
                        <td>{{ $leave->leaveType->name }}</td>
                    </tr>
                    <tr>
                        <th>From Date</th>
                        <td>{{ date('d-m-Y', strtotime($leave->from_date)) }}</td>
                    </tr>
                    <tr>
                        <th>To Date</th>
                        <td>{{ date('d-m-Y', strtotime($leave->to_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Total Days</th>
                        <td>{{ $leave->total_days }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $leave->getStatusBadgeClassAttribute() }}">
                                {{ $leave->getStatusTextAttribute() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Reason</th>
                        <td>{{ $leave->reason }}</td>
                    </tr>
                    @if($leave->document)
                    <tr>
                        <th>Document</th>
                        <td>
                            <a href="{{ asset('storage/' . $leave->document) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-file"></i> View Document
                            </a>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>

           
            <!-- Approval Timeline - Enhanced -->
           
            <div class="col-md-6">
                <h6>Approval Timeline</h6>
                <div class="timeline">
                    <!-- Leave Applied -->
                    <div class="timeline-item">
                        <div class="timeline-badge bg-primary">✓</div>
                        <div class="timeline-content">
                            <p><strong>Leave Applied</strong></p>
                            <small>{{ date('d-m-Y H:i:s', strtotime($leave->created_at)) }}</small>
                        </div>
                    </div>

                    <!-- Manager Approval - ONLY for Employee Leaves -->
                    @if($leave->manager_status && $leave->employee->user && $leave->employee->user->role == 'Employee')
                    <div class="timeline-item">
                        <div class="timeline-badge {{ $leave->manager_status == 'approved' ? 'bg-success' : ($leave->manager_status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ $leave->manager_status == 'approved' ? '✓' : ($leave->manager_status == 'rejected' ? '✗' : '●') }}
                        </div>
                        <div class="timeline-content">
                            <p><strong>Manager {{ ucfirst($leave->manager_status) }}</strong></p>
                            @if($leave->manager_remarks)
                                <p class="text-muted">{{ $leave->manager_remarks }}</p>
                            @endif
                            @if($leave->manager_approved_at)
                                <small>{{ date('d-m-Y H:i:s', strtotime($leave->manager_approved_at)) }}</small>
                            @endif
                            @if($leave->manager_rejected_at)
                                <small>{{ date('d-m-Y H:i:s', strtotime($leave->manager_rejected_at)) }}</small>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- HR Approval -->
                    @if($leave->hr_status)
                    <div class="timeline-item">
                        <div class="timeline-badge {{ $leave->hr_status == 'approved' ? 'bg-success' : ($leave->hr_status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ $leave->hr_status == 'approved' ? '✓' : ($leave->hr_status == 'rejected' ? '✗' : '●') }}
                        </div>
                        <div class="timeline-content">
                            <p><strong>HR {{ ucfirst($leave->hr_status) }}</strong></p>
                            @if($leave->hr_remarks)
                                <p class="text-muted">{{ $leave->hr_remarks }}</p>
                            @endif
                            @if($leave->hr_approved_at)
                                <small>{{ date('d-m-Y H:i:s', strtotime($leave->hr_approved_at)) }}</small>
                            @endif
                            @if($leave->hr_rejected_at)
                                <small>{{ date('d-m-Y H:i:s', strtotime($leave->hr_rejected_at)) }}</small>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Admin Approval - Only for HR Leaves -->
                    @if($leave->admin_status && $leave->employee->user && $leave->employee->user->role == 'HR')
                    <div class="timeline-item">
                        <div class="timeline-badge {{ $leave->admin_status == 'approved' ? 'bg-success' : ($leave->admin_status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ $leave->admin_status == 'approved' ? '✓' : ($leave->admin_status == 'rejected' ? '✗' : '●') }}
                        </div>
                        <div class="timeline-content">
                            <p><strong>Admin {{ ucfirst($leave->admin_status) }}</strong></p>
                            @if($leave->admin_remarks)
                                <p class="text-muted">{{ $leave->admin_remarks }}</p>
                            @endif
                            @if($leave->admin_approved_at)
                                <small>{{ date('d-m-Y H:i:s', strtotime($leave->admin_approved_at)) }}</small>
                            @endif
                            @if($leave->admin_rejected_at)
                                <small>{{ date('d-m-Y H:i:s', strtotime($leave->admin_rejected_at)) }}</small>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Final Status -->
                    @if($leave->final_status)
                    <div class="timeline-item">
                        <div class="timeline-badge {{ $leave->final_status == 'approved' ? 'bg-success' : ($leave->final_status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ $leave->final_status == 'approved' ? '✓' : ($leave->final_status == 'rejected' ? '✗' : '○') }}
                        </div>
                        <div class="timeline-content">
                            <p><strong>Final {{ ucfirst($leave->final_status) }}</strong></p>
                            @if($leave->final_status == 'approved')
                                <small>Leave approved successfully!</small>
                            @elseif($leave->final_status == 'rejected')
                                <small>Leave rejected.</small>
                            @else
                                <small>Waiting for approval.</small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

       
        <!-- Manager Approve/Reject - Only for Employee Leaves -->
       
        @if($leave->manager_status == 'pending' && Auth::user()->isManager() && $leave->employee->manager_id == Auth::user()->employee->id)
            <div class="mt-4 p-3 border rounded bg-light">
                <h6>Manager Actions</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('leave.manager.approve', $leave->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this leave?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#managerRejectModal">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </div>

            <!-- Manager Reject Modal -->
            <div class="modal fade" id="managerRejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('leave.manager.reject', $leave->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Leave</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Employee:</strong> {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                <p><strong>Leave Type:</strong> {{ $leave->leaveType->name }}</p>
                                <p><strong>Days:</strong> {{ $leave->total_days }}</p>
                                
                                <div class="mb-3">
                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="manager_remarks" class="form-control" rows="3" required></textarea>
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
        @endif

       
        <!-- HR Approve/Reject - For Employee & Manager Leaves -->
       
        @if($leave->hr_status == 'pending' && Auth::user()->isHR() && 
            ($leave->manager_status == 'approved' || $leave->employee->user->role == 'Manager'))
            <div class="mt-4 p-3 border rounded bg-light">
                <h6>HR Actions</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('leave.hr.approve', $leave->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this leave?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hrRejectModal">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </div>

            <!-- HR Reject Modal -->
            <div class="modal fade" id="hrRejectModal" tabindex="-1">
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
        @endif
        <!-- Admin Approve/Reject (HR Leave Only) -->
    
        @if($leave->admin_status == 'pending' && Auth::user()->isAdmin() && $leave->employee->user && $leave->employee->user->role == 'HR')
            <div class="mt-4 p-3 border rounded bg-light">
                <h6>Admin Actions</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('leave.admin.approve', $leave->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this HR leave?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#adminRejectModal">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </div>

            <!-- Admin Reject Modal -->
            <div class="modal fade" id="adminRejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('leave.admin.reject', $leave->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reject HR Leave</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Employee:</strong> {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                <p><strong>Leave Type:</strong> {{ $leave->leaveType->name }}</p>
                                <p><strong>Days:</strong> {{ $leave->total_days }}</p>
                                
                                <div class="mb-3">
                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="admin_remarks" class="form-control" rows="3" required></textarea>
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
        @endif
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-item:last-child {
    margin-bottom: 0;
}
.timeline-badge {
    position: absolute;
    left: -30px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    color: white;
    text-align: center;
    line-height: 24px;
    font-size: 12px;
}
.timeline-content {
    padding-left: 10px;
    border-left: 2px solid #e9ecef;
    padding-left: 15px;
}
.timeline-content p {
    margin-bottom: 2px;
}
.timeline-content small {
    color: #6c757d;
}
</style>
@endsection