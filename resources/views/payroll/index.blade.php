@extends('layouts.dashboard')

@section('page-title', 'Payroll List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-money-check-alt"></i> Payroll Records</h5>
        @if(Auth::user()->isAdmin() || Auth::user()->isHR())
            <a href="{{ route('payroll.generate') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Generate Payroll
            </a>
        @endif
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
        <form action="{{ route('payroll.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Processed" {{ request('status') == 'Processed' ? 'selected' : '' }}>Processed</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
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
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Role</th>
                        <th>Month/Year</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Action</th>
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
                        <td>
                            <span class="badge bg-primary">
                                {{ $payroll->employee->user->role ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            {{ $payroll->month_name }} {{ $payroll->payroll_year }}
                        </td>
                        <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                        <td class="text-danger">₹{{ number_format($payroll->total_deductions, 2) }}</td>
                        <td><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        <td>
                            <span class="badge {{ $payroll->status_badge_class }}">
                                {{ $payroll->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                                @if($payroll->status == 'Pending')
                                    <form action="{{ route('payroll.approve', $payroll->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this payroll?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($payroll->status == 'Approved')
                                    <form action="{{ route('payroll.process', $payroll->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Process this payroll?')">
                                            <i class="fas fa-cogs"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($payroll->status == 'Processed')
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#payModal{{ $payroll->id }}">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>
                                @endif
                            @endif

                            @if($payroll->status == 'Paid')
                                <a href="{{ route('payroll.payslip', $payroll->id) }}" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </td>
                    </tr>

                    <!-- Payment Modal -->
                    @if($payroll->status == 'Processed' && (Auth::user()->isAdmin() || Auth::user()->isHR()))
                    <div class="modal fade" id="payModal{{ $payroll->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('payroll.mark-paid', $payroll->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mark Payroll as Paid</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Employee:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
                                        <p><strong>Net Salary:</strong> ₹{{ number_format($payroll->net_salary, 2) }}</p>
                                        <p><strong>Total Payable:</strong> ₹{{ number_format($payroll->total_payable, 2) }}</p>
                                        <hr>
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
                                            <label class="form-label">Payment Reference</label>
                                            <input type="text" name="payment_reference" class="form-control" placeholder="TXN-XXX">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Mark as Paid
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
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