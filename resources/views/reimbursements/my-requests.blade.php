@extends('layouts.dashboard')

@section('page-title', 'My Reimbursements')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-file-invoice"></i> My Reimbursement Requests</h5>
        @if(!Auth::user()->isAdmin())
            <a href="{{ route('reimbursements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Request
            </a>
        @endif
    </div>
    <div class="card-body">
        <form action="{{ route('reimbursements.my-requests') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending_manager" {{ request('status') == 'pending_manager' ? 'selected' : '' }}>Pending Manager</option>
                        <option value="pending_hr" {{ request('status') == 'pending_hr' ? 'selected' : '' }}>Pending HR</option>
                        <option value="pending_admin" {{ request('status') == 'pending_admin' ? 'selected' : '' }}>Pending Admin</option>
                        <option value="approved_final" {{ request('status') == 'approved_final' ? 'selected' : '' }}>Approved</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Expense Type</label>
                    <select name="expense_type_id" class="form-control">
                        <option value="">All Types</option>
                        @foreach($expenseTypes as $type)
                            <option value="{{ $type->id }}" {{ request('expense_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('reimbursements.my-requests') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Expense Type</th>
                        <th>Expense Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Approval Flow</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reimbursements as $reimbursement)
                    <tr>
                        <td>{{ $loop->iteration + ($reimbursements->currentPage() - 1) * $reimbursements->perPage() }}</td>
                        <td><span class="badge bg-info">{{ $reimbursement->expenseType->name ?? 'N/A' }}</span></td>
                        <td>{{ $reimbursement->expense_date->format('d-m-Y') }}</td>
                        <td><strong>₹{{ number_format($reimbursement->amount, 2) }}</strong></td>
                        <td><span class="badge {{ $reimbursement->status_badge_class }}">{{ $reimbursement->status_text }}</span></td>
                        <td><small>{{ $reimbursement->approval_flow_text }}</small></td>
                        <td>
                            <a href="{{ route('reimbursements.show', $reimbursement->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($reimbursement->canBeEdited())
                                <a href="{{ route('reimbursements.edit', $reimbursement->id) }}" class="btn btn-warning btn-sm text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            @if($reimbursement->canBeCancelled())
                                <form action="{{ route('reimbursements.cancel', $reimbursement->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No reimbursement requests found.
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
@endsection