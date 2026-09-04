@extends('layouts.dashboard')

@section('page-title', 'Reimbursement Policies')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-gavel"></i> Reimbursement Policies</h5>
        <a href="{{ route('reimbursements.policies.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Policy
        </a>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form action="{{ route('reimbursements.policies.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-select" placeholder="Search by name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="expense_type_id" class="form-select">
                        <option value="">All Expense Types</option>
                        @foreach($expenseTypes as $type)
                            <option value="{{ $type->id }}" {{ request('expense_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('reimbursements.policies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Policies Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Policy Name</th>
                        <th>Expense Type</th>
                        <th>Max Amount</th>
                        <th>Limit Type</th>
                        <th>Receipt Required</th>
                        <th>Effective From</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($policies as $policy)
                    <tr>
                        <td>{{ $loop->iteration + ($policies->currentPage() - 1) * $policies->perPage() }}</td>
                        <td>{{ $policy->name }}</td>
                        <td>{{ $policy->expenseType->name ?? 'N/A' }}</td>
                        <td>₹{{ number_format($policy->maximum_amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $policy->limit_type)) }}</td>
                        <td>
                            <span class="badge {{ $policy->receipt_required ? 'bg-success' : 'bg-secondary' }}">
                                {{ $policy->receipt_required ? 'Required' : 'Optional' }}
                            </span>
                        </td>
                        <td>{{ $policy->effective_from->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge {{ $policy->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $policy->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reimbursements.policies.edit', $policy->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('reimbursements.policies.toggle', $policy->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-{{ $policy->status == 'Active' ? 'secondary' : 'success' }} btn-sm">
                                    <i class="fas fa-{{ $policy->status == 'Active' ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            @if($policy->reimbursements->count() == 0)
                                <form action="{{ route('reimbursements.policies.destroy', $policy->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted" title="Used in reimbursements">
                                    <i class="fas fa-lock"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No policies found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $policies->firstItem() ?? 0 }} to {{ $policies->lastItem() ?? 0 }} 
                of {{ $policies->total() }} entries
            </div>
            <div>
                {{ $policies->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection