@extends('layouts.dashboard')

@section('page-title', 'Payment Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-money-bill-wave"></i> Payment Management</h5>
        <a href="{{ route('reimbursements.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Requester</th>
                        <th>Role</th>
                        <th>Expense Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Paid By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reimbursements as $reimbursement)
                    <tr>
                        <td>{{ $loop->iteration + ($reimbursements->currentPage() - 1) * $reimbursements->perPage() }}</td>
                        <td>
                            {{ $reimbursement->requester->first_name ?? 'N/A' }} 
                            {{ $reimbursement->requester->last_name ?? '' }}
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
                            @if($reimbursement->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                                <br>
                                <small>₹{{ number_format($reimbursement->paid_amount, 2) }}</small>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($reimbursement->paidBy)
                                {{ $reimbursement->paidBy->name }}
                                <br>
                                <small class="text-muted">{{ $reimbursement->paidBy->role }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('reimbursements.show', $reimbursement->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($reimbursement->status == 'approved_final')
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $reimbursement->id }}">
                                    <i class="fas fa-money-bill-wave"></i> Pay
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No reimbursements found for payment.
                            </div>
                        </td>
                    </tr>
                    @endforelse
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
    </div>
</div>

<!-- Payment Modals -->
@foreach($reimbursements as $reimbursement)
    @if($reimbursement->status == 'approved_final')
    <div class="modal fade" id="paymentModal{{ $reimbursement->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('reimbursements.payments.process', $reimbursement->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Process Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="paid_amount" class="form-control" value="{{ $reimbursement->amount }}" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transaction Reference</label>
                            <input type="text" name="payment_reference" class="form-control" placeholder="TXN-XXX-XXX">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="payment_remarks" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Process Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection