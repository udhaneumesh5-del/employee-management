@extends('layouts.dashboard')

@section('page-title', 'Leave Balances')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-balance-scale"></i> Leave Balances</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignBalanceModal">
            <i class="fas fa-plus"></i> Assign Balance
        </button>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('leave.admin.balances') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Employee Name</label>
                        <input type="text" name="employee_name" class="form-control" 
                               placeholder="Search by name..." value="{{ request('employee_name') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-control">
                            <option value="">All Years</option>
                            @foreach($years ?? [] as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('leave.admin.balances') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Balances Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Year</th>
                        <th>Allocated</th>
                        <th>Used</th>
                        <th>Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                    <tr>
                        <td>{{ $loop->iteration + ($balances->currentPage() - 1) * $balances->perPage() }}</td>
                        <td>
                            {{ $balance->first_name }} {{ $balance->last_name }}
                            <br>
                            <small class="text-muted">{{ $balance->email }}</small>
                        </td>
                        <td>
                            @if($balance->leave_type_name)
                                <span class="badge bg-info">{{ $balance->leave_type_name }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $balance->year ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $balance->allocated_days ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-danger">{{ $balance->used_days ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $balance->remaining_days ?? 0 }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No balances found
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
                Showing {{ $balances->firstItem() ?? 0 }} to {{ $balances->lastItem() ?? 0 }} 
                of {{ $balances->total() }} entries
            </div>
            <div>
                {{ $balances->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Assign Balance Modal -->
<div class="modal fade" id="assignBalanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('leave.admin.balances.assign') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Leave Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach($allEmployees ?? [] as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-control" required>
                            <option value="">Select Leave Type</option>
                            @foreach($leaveTypes ?? [] as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input type="number" name="year" class="form-control" value="{{ now()->year }}" required min="2000" max="{{ now()->year + 1 }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Allocated Days <span class="text-danger">*</span></label>
                        <input type="number" name="allocated_days" class="form-control" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Assign Balance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection