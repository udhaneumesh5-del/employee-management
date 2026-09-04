@extends('layouts.dashboard')

@section('page-title', 'Reimbursement Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-file-invoice"></i> Reimbursement Details</h5>
                <span class="badge {{ $reimbursement->status_badge_class }}">{{ $reimbursement->status_text }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Request ID</dt>
                            <dd class="col-sm-7">#{{ $reimbursement->id }}</dd>

                            <dt class="col-sm-5">Requester</dt>
                            <dd class="col-sm-7">
                                {{ $reimbursement->requester->first_name ?? 'N/A' }} 
                                {{ $reimbursement->requester->last_name ?? '' }}
                                <br>
                                <small class="text-muted">{{ $reimbursement->requester_role }}</small>
                            </dd>

                            <dt class="col-sm-5">Expense Type</dt>
                            <dd class="col-sm-7">{{ $reimbursement->expenseType->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">Expense Date</dt>
                            <dd class="col-sm-7">{{ $reimbursement->expense_date->format('d-m-Y') }}</dd>

                            <dt class="col-sm-5">Amount</dt>
                            <dd class="col-sm-7"><strong>₹{{ number_format($reimbursement->amount, 2) }}</strong></dd>

                            <dt class="col-sm-5">Description</dt>
                            <dd class="col-sm-7">{{ $reimbursement->description }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Approval Flow</dt>
                            <dd class="col-sm-7">{{ $reimbursement->approval_flow_text }}</dd>

                            <dt class="col-sm-5">Current Status</dt>
                            <dd class="col-sm-7">
                                <span class="badge {{ $reimbursement->status_badge_class }}">
                                    {{ $reimbursement->status_text }}
                                </span>
                            </dd>

                            @if($reimbursement->remarks)
                                <dt class="col-sm-5">Remarks</dt>
                                <dd class="col-sm-7">{{ $reimbursement->remarks }}</dd>
                            @endif

                            @if($reimbursement->receipt)
                                <dt class="col-sm-5">Receipt</dt>
                                <dd class="col-sm-7">
                                    <a href="{{ $reimbursement->receipt_url }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> View Receipt
                                    </a>
                                </dd>
                            @endif

                            @if($reimbursement->policy_name_at_submission)
                                <dt class="col-sm-5">Policy Applied</dt>
                                <dd class="col-sm-7">
                                    {{ $reimbursement->policy_name_at_submission }}
                                    <br>
                                    <small>Limit: ₹{{ number_format($reimbursement->policy_limit_at_submission ?? 0, 2) }}</small>
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Flow Status -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-route"></i> Approval Flow Status</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($reimbursement->requester_role == 'Employee')
                        <div class="col-md-4 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'pending_manager' ? 'bg-warning' : ($reimbursement->manager_approved_at ? 'bg-success text-white' : '') }}">
                                <i class="fas fa-user-check fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>Manager</strong>
                                    @if($reimbursement->manager_approved_at)
                                        <br><small>Approved: {{ $reimbursement->manager_approved_at->format('d-m-Y H:i') }}</small>
                                    @elseif($reimbursement->status == 'pending_manager')
                                        <br><small>Pending...</small>
                                    @elseif($reimbursement->status == 'manager_rejected')
                                        <br><small class="text-danger">Rejected</small>
                                    @else
                                        <br><small>Not Started</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'pending_hr' ? 'bg-warning' : ($reimbursement->status == 'approved_final' || $reimbursement->status == 'paid' ? 'bg-success text-white' : '') }}">
                                <i class="fas fa-user-check fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>HR (Final)</strong>
                                    @if($reimbursement->hr_approved_at)
                                        <br><small>Approved: {{ $reimbursement->hr_approved_at->format('d-m-Y H:i') }}</small>
                                    @elseif($reimbursement->status == 'pending_hr')
                                        <br><small>Pending...</small>
                                    @elseif($reimbursement->status == 'hr_rejected')
                                        <br><small class="text-danger">Rejected</small>
                                    @else
                                        <br><small>Not Started</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'paid' ? 'bg-success text-white' : '' }}">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>Paid</strong>
                                    @if($reimbursement->payment_date)
                                        <br><small>Paid: {{ $reimbursement->payment_date->format('d-m-Y') }}</small>
                                    @else
                                        <br><small>Pending</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @elseif($reimbursement->requester_role == 'Manager')
                        <div class="col-md-6 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'pending_hr' ? 'bg-warning' : ($reimbursement->status == 'approved_final' || $reimbursement->status == 'paid' ? 'bg-success text-white' : '') }}">
                                <i class="fas fa-user-check fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>HR (Final)</strong>
                                    @if($reimbursement->hr_approved_at)
                                        <br><small>Approved: {{ $reimbursement->hr_approved_at->format('d-m-Y H:i') }}</small>
                                    @elseif($reimbursement->status == 'pending_hr')
                                        <br><small>Pending...</small>
                                    @elseif($reimbursement->status == 'hr_rejected')
                                        <br><small class="text-danger">Rejected</small>
                                    @else
                                        <br><small>Not Started</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'paid' ? 'bg-success text-white' : '' }}">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>Paid</strong>
                                    @if($reimbursement->payment_date)
                                        <br><small>Paid: {{ $reimbursement->payment_date->format('d-m-Y') }}</small>
                                    @else
                                        <br><small>Pending</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @elseif($reimbursement->requester_role == 'HR')
                        <div class="col-md-6 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'pending_admin' ? 'bg-warning' : ($reimbursement->status == 'approved_final' || $reimbursement->status == 'paid' ? 'bg-success text-white' : '') }}">
                                <i class="fas fa-user-check fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>Admin (Final)</strong>
                                    @if($reimbursement->admin_approved_at)
                                        <br><small>Approved: {{ $reimbursement->admin_approved_at->format('d-m-Y H:i') }}</small>
                                    @elseif($reimbursement->status == 'pending_admin')
                                        <br><small>Pending...</small>
                                    @elseif($reimbursement->status == 'admin_rejected')
                                        <br><small class="text-danger">Rejected</small>
                                    @else
                                        <br><small>Not Started</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="p-3 border rounded {{ $reimbursement->status == 'paid' ? 'bg-success text-white' : '' }}">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                                <p class="mt-2 mb-0">
                                    <strong>Paid</strong>
                                    @if($reimbursement->payment_date)
                                        <br><small>Paid: {{ $reimbursement->payment_date->format('d-m-Y') }}</small>
                                    @else
                                        <br><small>Pending</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Approval History -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-history"></i> Approval History</h6>
            </div>
            <div class="card-body">
                @if($reimbursement->approvals->count() > 0)
                    <div class="timeline">
                        @foreach($reimbursement->approvals as $approval)
                            <div class="timeline-item">
                                <div class="timeline-badge {{ $approval->action == 'approved' ? 'bg-success' : ($approval->action == 'rejected' ? 'bg-danger' : ($approval->action == 'paid' ? 'bg-primary' : 'bg-warning')) }}">
                                    <i class="fas fa-{{ $approval->action == 'approved' ? 'check' : ($approval->action == 'rejected' ? 'times' : ($approval->action == 'paid' ? 'money-bill-wave' : 'clock')) }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $approval->user->name }}</strong>
                                            <span class="badge bg-primary">{{ $approval->role }}</span>
                                        </div>
                                        <small class="text-muted">{{ $approval->created_at->format('d-m-Y H:i') }}</small>
                                    </div>
                                    <div>
                                        <span class="badge {{ $approval->action == 'approved' ? 'bg-success' : ($approval->action == 'rejected' ? 'bg-danger' : ($approval->action == 'paid' ? 'bg-primary' : 'bg-warning')) }}">
                                            {{ ucfirst($approval->action) }}
                                        </span>
                                        @if($approval->is_final_approval)
                                            <span class="badge bg-success"><i class="fas fa-star"></i> Final Approval</span>
                                        @endif
                                    </div>
                                    <div>
                                        <small>
                                            Status: {{ $approval->previous_status ? ' from ' . ucfirst(str_replace('_', ' ', $approval->previous_status)) : '' }}
                                            to {{ ucfirst(str_replace('_', ' ', $approval->new_status)) }}
                                        </small>
                                    </div>
                                    @if($approval->remarks)
                                        <div class="mt-1">
                                            <small><strong>Remarks:</strong> {{ $approval->remarks }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No approval history available.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Payment Details -->
        @if($reimbursement->status == 'paid')
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6><i class="fas fa-money-bill-wave"></i> Payment Details</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Paid Amount</dt>
                        <dd class="col-sm-6">₹{{ number_format($reimbursement->paid_amount, 2) }}</dd>

                        <dt class="col-sm-6">Payment Date</dt>
                        <dd class="col-sm-6">{{ $reimbursement->payment_date ? $reimbursement->payment_date->format('d-m-Y') : 'N/A' }}</dd>

                        <dt class="col-sm-6">Payment Method</dt>
                        <dd class="col-sm-6">{{ $reimbursement->payment_method ?? 'N/A' }}</dd>

                        <dt class="col-sm-6">Reference</dt>
                        <dd class="col-sm-6">{{ $reimbursement->payment_reference ?? 'N/A' }}</dd>

                        {{-- ✅ FIXED: Paid By --}}
                        <dt class="col-sm-6">Paid By</dt>
                        <dd class="col-sm-6">
                            @if($reimbursement->paidBy)
                                {{ $reimbursement->paidBy->name }}
                                <br>
                                <small class="text-muted">({{ $reimbursement->paidBy->role }})</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-tasks"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('reimbursements.my-requests') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fas fa-arrow-left"></i> Back to My Requests
                </a>
                
                @if($reimbursement->canBeEdited())
                    <a href="{{ route('reimbursements.edit', $reimbursement->id) }}" class="btn btn-warning btn-sm w-100 mb-2 text-white">
                        <i class="fas fa-edit"></i> Edit Request
                    </a>
                @endif
                
                @if($reimbursement->canBeCancelled())
                    <form action="{{ route('reimbursements.cancel', $reimbursement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-times"></i> Cancel Request
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 40px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 20px;
    border-left: 2px solid #ddd;
}
.timeline-badge {
    position: absolute;
    left: -32px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
}
</style>
@endsection