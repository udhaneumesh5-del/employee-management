@extends('layouts.dashboard')

@section('page-title', 'Create Reimbursement Request')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-file-invoice-dollar"></i> Create Reimbursement Request</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('reimbursements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Requester Info -->
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-user"></i>
                        <strong>Requester:</strong> {{ $employee->first_name }} {{ $employee->last_name }}
                        <span class="badge bg-primary ms-2">{{ auth()->user()->role }}</span>
                        @if(auth()->user()->isEmployee())
                            <span class="badge bg-warning ms-2 text-dark">→ Manager → HR Final</span>
                        @elseif(auth()->user()->isManager())
                            <span class="badge bg-warning ms-2 text-dark">→ HR Final</span>
                        @elseif(auth()->user()->isHR())
                            <span class="badge bg-warning ms-2 text-dark">→ Admin Final</span>
                        @endif
                        <input type="hidden" name="requester_id" value="{{ $employee->id }}">
                    </div>
                </div>

                <!-- Expense Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                    <select name="expense_type_id" id="expense_type_id" class="form-select @error('expense_type_id') is-invalid @enderror" required>
                        <option value="">Select Expense Type</option>
                        @foreach($expenseTypes as $type)
                            <option value="{{ $type->id }}" {{ old('expense_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('expense_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Expense Date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date') }}" required>
                    @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" step="0.01" min="0.01" required>
                    <small class="text-muted" id="policy_limit_info"></small>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Policy Info -->
                <div class="col-md-6 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6><i class="fas fa-gavel"></i> Policy Information</h6>
                            <div id="policy_info">
                                <p class="text-muted">Select an expense type to view policy details.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Receipt -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Receipt/Bill</label>
                    <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-muted">Supported: JPG, JPEG, PNG, PDF (Max: 2MB)</small>
                    <div id="receipt_required_info" class="text-muted small"></div>
                    @error('receipt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Remarks (Optional)</label>
                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Submit Request
                </button>
                <a href="{{ route('reimbursements.my-requests') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const expenseTypeSelect = document.getElementById('expense_type_id');
        const policyInfoDiv = document.getElementById('policy_info');
        const amountInput = document.getElementById('amount');
        const policyLimitInfo = document.getElementById('policy_limit_info');
        const receiptRequiredInfo = document.getElementById('receipt_required_info');

        expenseTypeSelect.addEventListener('change', function() {
            const expenseTypeId = this.value;
            if (expenseTypeId) {
                fetch(`/reimbursements/policy/${expenseTypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.policy) {
                            let policyHtml = `
                                <p><strong>Policy:</strong> ${data.policy.name}</p>
                                <p><strong>Max Amount:</strong> ₹${data.policy.maximum_amount}</p>
                                <p><strong>Limit Type:</strong> ${data.policy.limit_type.replace('_', ' ')}</p>
                                <p><strong>Receipt Required:</strong> ${data.policy.receipt_required ? 'Yes' : 'No'}</p>
                                <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                            `;
                            policyInfoDiv.innerHTML = policyHtml;
                            policyLimitInfo.textContent = `Policy limit: ₹${data.policy.maximum_amount}`;
                            receiptRequiredInfo.textContent = `Receipt ${data.policy.receipt_required ? 'is required' : 'is optional'} as per policy.`;
                        } else {
                            policyInfoDiv.innerHTML = `<p class="text-warning">No active policy found for this expense type.</p>`;
                            policyLimitInfo.textContent = '';
                            receiptRequiredInfo.textContent = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                policyInfoDiv.innerHTML = `<p class="text-muted">Select an expense type to view policy details.</p>`;
                policyLimitInfo.textContent = '';
                receiptRequiredInfo.textContent = '';
            }
        });
    });
</script>
@endpush
@endsection