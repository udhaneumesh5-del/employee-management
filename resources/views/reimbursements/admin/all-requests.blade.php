@extends('layouts.dashboard')

@section('page-title', 'All Reimbursement Requests')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-list"></i> All Reimbursement Requests</h5>
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

        <!-- Filters -->
        <form action="{{ route('reimbursements.all-requests') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Requester Name</label>
                    <input type="text" name="requester_name" class="form-control" placeholder="Search by name..." value="{{ request('requester_name') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Expense Type</label>
                    <select name="expense_type_id" class="form-select">
                        <option value="">All Types</option>
                        @foreach($expenseTypes as $type)
                            <option value="{{ $type->id }}" {{ request('expense_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending_manager" {{ request('status') == 'pending_manager' ? 'selected' : '' }}>Pending Manager</option>
                        <option value="pending_hr" {{ request('status') == 'pending_hr' ? 'selected' : '' }}>Pending HR</option>
                        <option value="pending_admin" {{ request('status') == 'pending_admin' ? 'selected' : '' }}>Pending Admin</option>
                        <option value="approved_final" {{ request('status') == 'approved_final' ? 'selected' : '' }}>Approved</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('reimbursements.all-requests') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Requester</th>
                        <th>Role</th>
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
                        <td>
                            {{ $reimbursement->requester->first_name ?? 'N/A' }} 
                            {{ $reimbursement->requester->last_name ?? '' }}
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $reimbursement->requester_role }}</span>
                        </td>
                        <td>{{ $reimbursement->expenseType->name ?? 'N/A' }}</td>
                        <td>{{ $reimbursement->expense_date->format('d-m-Y') }}</td>
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
                            <a href="{{ route('reimbursements.show', $reimbursement->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
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