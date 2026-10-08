@extends('layouts.dashboard')

@section('page-title', 'HR Payroll Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Employees</h5>
                <h2>{{ $stats['total_employees'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>This Month Payroll</h5>
                <h2>₹{{ number_format($stats['this_month_payroll'] ?? 0, 0) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5>Pending Payrolls</h5>
                <h2>{{ $stats['total_pending'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Paid Payrolls</h5>
                <h2>{{ $stats['total_paid'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('payroll.generate') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Generate Payroll
        </a>
        <a href="{{ route('payroll.salary-structure') }}" class="btn btn-info">
            <i class="fas fa-cog"></i> Salary Structure
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5><i class="fas fa-list"></i> Employee & Manager Payrolls</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Role</th>
                        <th>Month/Year</th>
                        <th>Gross</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        <td><span class="badge bg-primary">{{ $payroll->employee->user->role ?? 'N/A' }}</span></td>
                        <td>{{ $payroll->month_name }} {{ $payroll->payroll_year }}</td>
                        <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                        <td><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        <td><span class="badge {{ $payroll->status_badge_class }}">{{ $payroll->status }}</span></td>
                        <td>
                            <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i></a>
                            @if($payroll->status == 'Pending')
                                <form action="{{ route('payroll.approve', $payroll->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Approve?')"><i class="fas fa-check"></i></button>
                                </form>
                            @endif
                            @if($payroll->status == 'Approved')
                                <form action="{{ route('payroll.process', $payroll->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-cogs"></i></button>
                                </form>
                            @endif
                            @if($payroll->status == 'Processed')
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#payModal{{ $payroll->id }}">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $payrolls->links() }}
    </div>
</div>
@endsection