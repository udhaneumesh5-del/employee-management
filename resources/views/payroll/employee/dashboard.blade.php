@extends('layouts.dashboard')

@section('page-title', 'My Payroll')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5><i class="fas fa-file-invoice"></i> Total Payrolls</h5>
                <h2>{{ $stats['total'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5><i class="fas fa-check-circle"></i> Paid</h5>
                <h2>{{ $stats['paid'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5><i class="fas fa-clock"></i> Pending</h5>
                <h2>{{ $stats['pending'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5><i class="fas fa-money-bill-wave"></i> Total Earned</h5>
                <h2>₹{{ number_format($stats['total_earned'] ?? 0, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5><i class="fas fa-list"></i> My Payroll History</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>Year</th>
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payroll->month_name }}</td>
                        <td>{{ $payroll->payroll_year }}</td>
                        <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                        <td>₹{{ number_format($payroll->total_deductions, 2) }}</td>
                        <td><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        <td><span class="badge {{ $payroll->status_badge_class }}">{{ $payroll->status }}</span></td>
                        <td>
                            <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($payroll->status == 'Paid')
                                <a href="{{ route('payroll.payslip', $payroll->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">No payroll records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payrolls->links() }}
    </div>
</div>
@endsection