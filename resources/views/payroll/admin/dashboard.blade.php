@extends('layouts.dashboard')

@section('page-title', 'Admin Payroll Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5>Pending HR Payrolls</h5>
                <h2>{{ $stats['pending_hr_payroll'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Total HR Payroll</h5>
                <h2>₹{{ number_format($stats['total_hr_payroll'] ?? 0, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Paid HR Payrolls</h5>
                <h2>{{ $stats['paid_hr_payroll'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5><i class="fas fa-list"></i> HR Payrolls (Admin Managed)</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>HR Name</th>
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
                        <td>{{ $payroll->month_name }} {{ $payroll->payroll_year }}</td>
                        <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                        <td><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        <td><span class="badge {{ $payroll->status_badge_class }}">{{ $payroll->status }}</span></td>
                        <td>
                            <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i></a>
                            @if($payroll->status == 'Pending')
                                <form action="{{ route('payroll.approve', $payroll->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
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
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>No payroll records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payrolls->links() }}
    </div>
</div>

{{-- PAYMENT MODALS --}}
@foreach($payrolls as $payroll)
    @if($payroll->status == 'Processed')
    <div class="modal fade" id="payModal{{ $payroll->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payroll.mark-paid', $payroll->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Mark HR Payroll as Paid</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>HR:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
                        <p><strong>Net Salary:</strong> ₹{{ number_format($payroll->net_salary, 2) }}</p>
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reference</label>
                            <input type="text" name="payment_reference" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Mark as Paid</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection