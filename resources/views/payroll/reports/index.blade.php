@extends('layouts.dashboard')

@section('page-title', 'Payroll Reports')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-chart-bar"></i> Payroll Reports</h5>
        <div>
            <a href="{{ route('payroll.reports.export-csv', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('payroll.reports.export-pdf', request()->query()) }}" class="btn btn-danger btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('payroll.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form action="{{ route('payroll.reports') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-control">
                        <option value="">All</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-control">
                        <option value="">All</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Processed" {{ request('status') == 'Processed' ? 'selected' : '' }}>Processed</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-control">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('payroll.reports') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6>Total Payrolls</h6>
                        <h3>{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h6>Total Gross</h6>
                        <h3>₹{{ number_format($stats['total_gross'] ?? 0, 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h6>Total Deductions</h6>
                        <h3>₹{{ number_format($stats['total_deductions'] ?? 0, 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Total Net Salary</h6>
                        <h3>₹{{ number_format($stats['total_net'] ?? 0, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Month/Year</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                    <tr>
                        <td>{{ $loop->iteration + ($payrolls->currentPage() - 1) * $payrolls->perPage() }}</td>
                        <td>
                            {{ $payroll->employee->first_name ?? 'N/A' }} 
                            {{ $payroll->employee->last_name ?? '' }}
                            <br>
                            <small class="text-muted">{{ $payroll->employee->employee_code ?? '' }}</small>
                        </td>
                        <td>{{ $payroll->employee->department->department_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $payroll->employee->user->role ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $payroll->month_name }} {{ $payroll->payroll_year }}</td>
                        <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                        <td class="text-danger">₹{{ number_format($payroll->total_deductions, 2) }}</td>
                        <td><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        <td>
                            <span class="badge {{ $payroll->status_badge_class }}">
                                {{ $payroll->status }}
                            </span>
                        </td>
                        <td>{{ $payroll->paid_at?->format('d-m-Y') ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No payroll records found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $payrolls->firstItem() ?? 0 }} to {{ $payrolls->lastItem() ?? 0 }} 
                of {{ $payrolls->total() }} entries
            </div>
            <div>
                {{ $payrolls->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection