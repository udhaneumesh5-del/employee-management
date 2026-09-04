@extends('layouts.dashboard')

@section('page-title', 'Pending HR Approvals')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Pending HR Approvals</h5>
        <a href="{{ route('reimbursements.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        @if($reimbursements->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Requester</th>
                            <th>Role</th>
                            <th>Expense Type</th>
                            <th>Amount</th>
                            <th>Current Status</th>
                            <th>Approval Flow</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reimbursements as $reimbursement)
                        <tr>
                            <td>{{ $loop->iteration + ($reimbursements->currentPage() - 1) * $reimbursements->perPage() }}</td>
                            <td>
                                {{ $reimbursement->requester->first_name }} {{ $reimbursement->requester->last_name }}
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $reimbursement->requester_role }}</span>
                            </td>
                            <td>{{ $reimbursement->expenseType->name ?? 'N/A' }}</td>
                            <td><strong>₹{{ number_format($reimbursement->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge {{ $reimbursement->status_badge_class }}">
                                    {{ $reimbursement->status_text }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $reimbursement->approval_flow_text }}</small>
                            </td>
                            <td>
                                <!-- View Button -->
                                <a href="{{ route('reimbursements.show', $reimbursement->id) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Approve Form -->
                                <form action="{{ route('reimbursements.hr.approve', $reimbursement->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this request? This will be the final approval.')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $reimbursement->id }}">
                                    <i class="fas fa-times"></i>
                                </button>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $reimbursement->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('reimbursements.hr.approve', $reimbursement->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="reject" value="1">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Remarks <span class="text-danger">*</span></label>
                                                        <textarea name="remarks" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $reimbursements->firstItem() ?? 0 }} to {{ $reimbursements->lastItem() ?? 0 }} 
                    of {{ $reimbursements->total() }} entries
                </div>
                <div>
                    {{ $reimbursements->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No pending approvals for HR.
            </div>
        @endif
    </div>
</div>
@endsection