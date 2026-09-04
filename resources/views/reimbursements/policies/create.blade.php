@extends('layouts.dashboard')

@section('page-title', 'Add Reimbursement Policy')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-plus-circle"></i> Add Reimbursement Policy</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reimbursements.policies.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Policy Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Policy Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Expense Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                    <select name="expense_type_id" class="form-select @error('expense_type_id') is-invalid @enderror" required>
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

                <!-- Maximum Amount -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Maximum Amount (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="maximum_amount" class="form-control @error('maximum_amount') is-invalid @enderror" value="{{ old('maximum_amount') }}" step="0.01" min="0" required>
                    @error('maximum_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Limit Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Limit Type <span class="text-danger">*</span></label>
                    <select name="limit_type" class="form-select @error('limit_type') is-invalid @enderror" required>
                        <option value="">Select Limit Type</option>
                        @foreach($limitTypes as $type)
                            <option value="{{ $type }}" {{ old('limit_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('limit_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Limit Period -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Limit Period (Days/Months/Years)</label>
                    <input type="number" name="limit_period" class="form-control @error('limit_period') is-invalid @enderror" value="{{ old('limit_period') }}" min="1">
                    @error('limit_period')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Maximum Claims -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Maximum Claims</label>
                    <input type="number" name="maximum_claims" class="form-control @error('maximum_claims') is-invalid @enderror" value="{{ old('maximum_claims') }}" min="1">
                    @error('maximum_claims')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Receipt Required -->
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="receipt_required" class="form-check-input" id="receipt_required" value="1" {{ old('receipt_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="receipt_required">Receipt Required</label>
                    </div>
                </div>

                <!-- Exception Allowed -->
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="exception_allowed" class="form-check-input" id="exception_allowed" value="1" {{ old('exception_allowed') ? 'checked' : '' }}>
                        <label class="form-check-label" for="exception_allowed">Exception Allowed</label>
                    </div>
                </div>

                <!-- Effective From -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Effective From <span class="text-danger">*</span></label>
                    <input type="date" name="effective_from" class="form-control @error('effective_from') is-invalid @enderror" value="{{ old('effective_from') }}" required>
                    @error('effective_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Effective To -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Effective To</label>
                    <input type="date" name="effective_to" class="form-control @error('effective_to') is-invalid @enderror" value="{{ old('effective_to') }}">
                    @error('effective_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Policy
                </button>
                <a href="{{ route('reimbursements.policies.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection