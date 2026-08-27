@extends('layouts.dashboard')

@section('page-title', 'Employee Leave Balances')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-balance-scale"></i> Employee Leave Balances</h5>
        <a href="{{ route('leave.hr.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('leave.hr.balances') }}" method="GET" class="mb-3">
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
                    <a href="{{ route('leave.hr.balances') }}" class="btn btn-secondary" style="margin-top: 30px;">
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
                        <th>Email</th>
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
                            <strong>{{ $balance->first_name }} {{ $balance->last_name }}</strong>
                        </td>
                        <td>{{ $balance->email }}</td>
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
                        <td colspan="8" class="text-center">
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
@endsection