@extends('layouts.dashboard')

@section('page-title', 'Edit Reimbursement Request')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-edit"></i> Edit Reimbursement Request</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('reimbursements.update', $reimbursement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Requester Info -->
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-user"></i>
                        <strong>Requester:</strong> {{ $reimbursement->requester->first_name }} {{ $reimbursement->requester->last_name }}
                        <span class="badge bg-primary ms-2">{{ $reimbursement->requester_role }}</span>
                        <span class="badge bg-warning ms-2 text-dark">{{ $reimbursement->approval_flow_text }}</span>
                    </div>
                </div>

                <!-- Expense Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                    <select name="expense_type_id" id="expense_type_id" class="form-control @error('expense_type_id') is-invalid @enderror" required>
                        <option value="">Select Expense Type</option>
                        @foreach($expenseTypes as $type)
                            <option value="{{ $type->id }}" {{ old('expense_type_id', $reimbursement->expense_type_id) == $type->id ? 'selected' : '' }}>
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
                    <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $reimbursement->expense_date->format('Y-m-d')) }}" required>
                    @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $reimbursement->amount) }}" step="0.01" min="0.01" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Policy Info -->
                <div class="col-md-6 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6><i class="fas fa-gavel"></i> Policy Applied at Submission</h6>
                            @if($reimbursement->policy_name_at_submission)
                                <p><strong>Policy:</strong> {{ $reimbursement->policy_name_at_submission }}</p>
                                <p><strong>Limit:</strong> ₹{{ number_format($reimbursement->policy_limit_at_submission ?? 0, 2) }}</p>
                                <p><strong>Receipt Required:</strong> {{ $reimbursement->receipt_required_at_submission ? 'Yes' : 'No' }}</p>
                            @else
                                <p class="text-muted">No policy applied at submission.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $reimbursement->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Receipt -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Receipt/Bill</label>
                    @if($reimbursement->receipt)
                        <div class="mb-2">
                            <a href="{{ $reimbursement->receipt_url }}" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Current Receipt
                            </a>
                        </div>
                    @endif
                    <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-muted">Supported: JPG, JPEG, PNG, PDF (Max: 2MB). Leave empty to keep current receipt.</small>
                    @error('receipt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Remarks (Optional)</label>
                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $reimbursement->remarks) }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Request
                </button>
                <a href="{{ route('reimbursements.my-requests') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection