@extends('layouts.dashboard')

@section('page-title', 'Reimbursement Reports')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-chart-bar"></i> Reimbursement Reports</h5>
        <a href="{{ route('reimbursements.reports.export-csv', request()->query()) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
    </div>
    <div class="card-body">
        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6>Total</h6>
                        <h4>{{ $stats['total'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Approved</h6>
                        <h4>{{ $stats['approved'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h6>Pending</h6>
                        <h4>{{ $stats['pending'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h6>Rejected</h6>
                        <h4>{{ $stats['rejected'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h6>Paid</h6>
                        <h4>{{ $stats['paid'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <h6>Total Amount</h6>
                        <h6>₹{{ number_format($stats['total_amount'] ?? 0, 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form action="{{ route('reimbursements.reports.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Requester Name</label>
                    <input type="text" name="requester_name" class="form-control" placeholder="Search by name..." value="{{ request('requester_name') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Requester Role</label>
                    <select name="requester_role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="Employee" {{ request('requester_role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                        <option value="Manager" {{ request('requester_role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="HR" {{ request('requester_role') == 'HR' ? 'selected' : '' }}>HR</option>
                    </select>
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
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Min Amount</label>
                    <input type="number" name="amount_min" class="form-control" placeholder="0" value="{{ request('amount_min') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Max Amount</label>
                    <input type="number" name="amount_max" class="form-control" placeholder="10000" value="{{ request('amount_max') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('reimbursements.reports.index') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Reports Table -->
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
                        <th>Final Approver</th>
                        <th>Payment</th>
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
                        <td><span class="badge bg-primary">{{ $reimbursement->requester_role }}</span></td>
                        <td>{{ $reimbursement->expenseType->name ?? 'N/A' }}</td>
                        <td>{{ $reimbursement->expense_date->format('d-m-Y') }}</td>
                        <td><strong>₹{{ number_format($reimbursement->amount, 2) }}</strong></td>
                        <td>
                            <span class="badge {{ $reimbursement->status_badge_class }}">
                                {{ $reimbursement->status_text }}
                            </span>
                        </td>
                        <td><small>{{ $reimbursement->approval_flow_text }}</small></td>
                        <td>
                            @if($reimbursement->finalApprover)
                                {{ $reimbursement->finalApprover->first_name ?? '' }}
                                <br>
                                <small class="text-muted">{{ $reimbursement->final_approver_role }}</small>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($reimbursement->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                                <br>
                                <small>₹{{ number_format($reimbursement->paid_amount, 2) }}</small>
                            @else
                                <span class="badge bg-secondary">Not Paid</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No records found.
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