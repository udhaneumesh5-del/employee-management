@extends('layouts.dashboard')

@section('page-title', 'Payroll Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-file-invoice"></i> Payroll Details</h5>
                <span class="badge {{ $payroll->status_badge_class }}">{{ $payroll->status }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Payroll ID</dt>
                            <dd class="col-sm-7">#{{ $payroll->id }}</dd>

                            <dt class="col-sm-5">Employee</dt>
                            <dd class="col-sm-7">
                                {{ $payroll->employee->first_name ?? 'N/A' }} {{ $payroll->employee->last_name ?? '' }}
                                <br>
                                <small class="text-muted">{{ $payroll->employee->employee_code ?? '' }}</small>
                            </dd>

                            <dt class="col-sm-5">Role</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-primary">{{ $payroll->employee->user->role ?? 'N/A' }}</span>
                            </dd>

                            <dt class="col-sm-5">Department</dt>
                            <dd class="col-sm-7">{{ $payroll->employee->department->department_name ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">Designation</dt>
                            <dd class="col-sm-7">{{ $payroll->employee->designation ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">Pay Period</dt>
                            <dd class="col-sm-7">{{ $payroll->month_name }} {{ $payroll->payroll_year }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Total Working Days</dt>
                            <dd class="col-sm-7">{{ $payroll->total_working_days }}</dd>

                            <dt class="col-sm-5">Present Days</dt>
                            <dd class="col-sm-7 text-success">{{ $payroll->present_days }}</dd>

                            <dt class="col-sm-5">Absent Days</dt>
                            <dd class="col-sm-7 text-danger">{{ $payroll->absent_days }}</dd>

                            <dt class="col-sm-5">Paid Leave</dt>
                            <dd class="col-sm-7">{{ $payroll->paid_leave_days }}</dd>

                            <dt class="col-sm-5">Unpaid Leave</dt>
                            <dd class="col-sm-7 text-warning">{{ $payroll->unpaid_leave_days }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings & Deductions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-calculator"></i> Earnings & Deductions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Earnings</h6>
                        <table class="table table-sm table-bordered">
                            <tr><td>Basic Salary</td><td class="text-end">₹{{ number_format($payroll->basic_salary, 2) }}</td></tr>
                            <tr><td>HRA</td><td class="text-end">₹{{ number_format($payroll->hra, 2) }}</td></tr>
                            <tr><td>Conveyance</td><td class="text-end">₹{{ number_format($payroll->conveyance_allowance, 2) }}</td></tr>
                            <tr><td>Medical</td><td class="text-end">₹{{ number_format($payroll->medical_allowance, 2) }}</td></tr>
                            <tr><td>Special Allowance</td><td class="text-end">₹{{ number_format($payroll->special_allowance, 2) }}</td></tr>
                            <tr><td>Other Allowance</td><td class="text-end">₹{{ number_format($payroll->other_allowance, 2) }}</td></tr>
                            @if($payroll->bonus > 0)
                            <tr><td>Bonus</td><td class="text-end">₹{{ number_format($payroll->bonus, 2) }}</td></tr>
                            @endif
                            @if($payroll->incentive > 0)
                            <tr><td>Incentive</td><td class="text-end">₹{{ number_format($payroll->incentive, 2) }}</td></tr>
                            @endif
                            <tr class="table-success">
                                <th>Gross Salary</th>
                                <th class="text-end">₹{{ number_format($payroll->gross_salary, 2) }}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger">Deductions</h6>
                        <table class="table table-sm table-bordered">
                            <tr><td>Absent Deduction</td><td class="text-end">₹{{ number_format($payroll->absent_deduction, 2) }}</td></tr>
                            <tr><td>Unpaid Leave</td><td class="text-end">₹{{ number_format($payroll->unpaid_leave_deduction, 2) }}</td></tr>
                            <tr><td>PF</td><td class="text-end">₹{{ number_format($payroll->pf_deduction, 2) }}</td></tr>
                            <tr><td>Professional Tax</td><td class="text-end">₹{{ number_format($payroll->professional_tax, 2) }}</td></tr>
                            <tr><td>Other</td><td class="text-end">₹{{ number_format($payroll->other_deduction, 2) }}</td></tr>
                            <tr class="table-danger">
                                <th>Total Deductions</th>
                                <th class="text-end">₹{{ number_format($payroll->total_deductions, 2) }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Net Salary</strong></td>
                                <td class="text-end"><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                            </tr>
                            @if($payroll->reimbursement_amount > 0)
                            <tr>
                                <td><strong>Reimbursement</strong></td>
                                <td class="text-end">₹{{ number_format($payroll->reimbursement_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <td><h5 class="mb-0"><strong>Total Payable</strong></h5></td>
                                <td class="text-end"><h5 class="mb-0"><strong>₹{{ number_format($payroll->total_payable, 2) }}</strong></h5></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Info -->
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-info-circle"></i> Quick Info</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-6">Generated By</dt>
                    <dd class="col-sm-6">{{ $payroll->generatedBy->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-6">Generated At</dt>
                    <dd class="col-sm-6">{{ $payroll->generated_at?->format('d-m-Y H:i') ?? 'N/A' }}</dd>

                    @if($payroll->approved_by)
                    <dt class="col-sm-6">Approved By</dt>
                    <dd class="col-sm-6">{{ $payroll->approvedBy->name ?? 'N/A' }}</dd>
                    @endif

                    @if($payroll->processed_by)
                    <dt class="col-sm-6">Processed By</dt>
                    <dd class="col-sm-6">{{ $payroll->processedBy->name ?? 'N/A' }}</dd>
                    @endif

                    @if($payroll->paid_by)
                    <dt class="col-sm-6">Paid By</dt>
                    <dd class="col-sm-6">{{ $payroll->paidBy->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-6">Paid At</dt>
                    <dd class="col-sm-6">{{ $payroll->paid_at?->format('d-m-Y H:i') ?? 'N/A' }}</dd>

                    <dt class="col-sm-6">Payment Method</dt>
                    <dd class="col-sm-6">{{ $payroll->payment_method ?? 'N/A' }}</dd>

                    <dt class="col-sm-6">Reference</dt>
                    <dd class="col-sm-6">{{ $payroll->payment_reference ?? 'N/A' }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-tasks"></i> Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('payroll.index') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                @if($payroll->status == 'Paid')
                    <a href="{{ route('payroll.payslip', $payroll->id) }}" class="btn btn-success btn-sm w-100" target="_blank">
                        <i class="fas fa-download"></i> Download Payslip
                    </a>
                @endif

                @if((Auth::user()->isAdmin() || Auth::user()->isHR()) && $payroll->status == 'Pending')
                    <form action="{{ route('payroll.approve', $payroll->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm w-100 mb-2">
                            <i class="fas fa-check"></i> Approve Payroll
                        </button>
                    </form>
                @endif

                @if((Auth::user()->isAdmin() || Auth::user()->isHR()) && $payroll->status == 'Approved')
                    <form action="{{ route('payroll.process', $payroll->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="fas fa-cogs"></i> Process Payroll
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection