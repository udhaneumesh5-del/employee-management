@extends('layouts.dashboard')

@section('page-title', 'Payroll Adjustments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-sliders-h"></i> Payroll Adjustments</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Adjustment
        </button>
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

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Added By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adj)
                    <tr>
                        <td>{{ $loop->iteration + ($adjustments->currentPage() - 1) * $adjustments->perPage() }}</td>
                        <td>
                            {{ $adj->employee->first_name ?? 'N/A' }} 
                            {{ $adj->employee->last_name ?? '' }}
                            <br>
                            <small class="text-muted">{{ $adj->employee->employee_code ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $adj->adjustment_type == 'Earning' ? 'bg-success' : 'bg-danger' }}">
                                {{ $adj->adjustment_type }}
                            </span>
                        </td>
                        <td>{{ $adj->category }}</td>
                        <td><strong>₹{{ number_format($adj->amount, 2) }}</strong></td>
                        <td>{{ $adj->description }}</td>
                        <td>{{ $adj->addedBy->name ?? 'N/A' }}</td>
                        <td>{{ $adj->created_at->format('d-m-Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No adjustments found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $adjustments->firstItem() ?? 0 }} to {{ $adjustments->lastItem() ?? 0 }} 
                of {{ $adjustments->total() }} entries
            </div>
            <div>
                {{ $adjustments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payroll.adjustments.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees ?? [] as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Earning">Earning (Bonus/Incentive)</option>
                            <option value="Deduction">Deduction (Penalty/Loan)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="Bonus">Bonus</option>
                            <option value="Incentive">Incentive</option>
                            <option value="Penalty">Penalty</option>
                            <option value="Loan">Loan</option>
                            <option value="Advance">Advance</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection