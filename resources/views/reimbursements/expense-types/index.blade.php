@extends('layouts.dashboard')

@section('page-title', 'Expense Types')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-tags"></i> Expense Types</h5>
        <a href="{{ route('reimbursements.expense-types.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Expense Type
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

        <!-- Search & Filter -->
        <form action="{{ route('reimbursements.expense-types.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('reimbursements.expense-types.index') }}" class="btn btn-secondary">
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
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenseTypes as $type)
                    <tr>
                        <td>{{ $loop->iteration + ($expenseTypes->currentPage() - 1) * $expenseTypes->perPage() }}</td>
                        <td><strong>{{ $type->name }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $type->code }}</span></td>
                        <td>{{ $type->description ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $type->is_active ? 'success' : 'danger' }}">
                                {{ $type->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reimbursements.expense-types.edit', $type->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('reimbursements.expense-types.toggle', $type->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-{{ $type->is_active ? 'secondary' : 'success' }} btn-sm">
                                    <i class="fas fa-{{ $type->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('reimbursements.expense-types.destroy', $type->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this expense type?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No expense types found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $expenseTypes->firstItem() ?? 0 }} to {{ $expenseTypes->lastItem() ?? 0 }} 
                of {{ $expenseTypes->total() }} entries
            </div>
            <div>
                {{ $expenseTypes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection