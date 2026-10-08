@extends('layouts.dashboard')

@section('page-title', 'Edit Salary Structure')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-edit"></i> Edit Salary Structure</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('payroll.salary-structure.update', $structure->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee</label>
                    <input type="text" class="form-control" value="{{ $structure->employee->first_name }} {{ $structure->employee->last_name }} ({{ $structure->employee->employee_code }})" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Effective From <span class="text-danger">*</span></label>
                    <input type="date" name="effective_from" class="form-control @error('effective_from') is-invalid @enderror" 
                           value="{{ old('effective_from', $structure->effective_from->format('Y-m-d')) }}" required>
                    @error('effective_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                    <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" 
                           value="{{ old('basic_salary', $structure->basic_salary) }}" step="0.01" min="0" required>
                    @error('basic_salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">HRA</label>
                    <input type="number" name="hra" class="form-control" 
                           value="{{ old('hra', $structure->hra) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Conveyance Allowance</label>
                    <input type="number" name="conveyance_allowance" class="form-control" 
                           value="{{ old('conveyance_allowance', $structure->conveyance_allowance) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Medical Allowance</label>
                    <input type="number" name="medical_allowance" class="form-control" 
                           value="{{ old('medical_allowance', $structure->medical_allowance) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Special Allowance</label>
                    <input type="number" name="special_allowance" class="form-control" 
                           value="{{ old('special_allowance', $structure->special_allowance) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Other Allowance</label>
                    <input type="number" name="other_allowance" class="form-control" 
                           value="{{ old('other_allowance', $structure->other_allowance) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">PF Percentage</label>
                    <input type="number" name="pf_percentage" class="form-control" 
                           value="{{ old('pf_percentage', $structure->pf_percentage) }}" step="0.01" min="0" max="100">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Professional Tax</label>
                    <input type="number" name="professional_tax" class="form-control" 
                           value="{{ old('professional_tax', $structure->professional_tax) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Other Deduction</label>
                    <input type="number" name="other_deduction" class="form-control" 
                           value="{{ old('other_deduction', $structure->other_deduction) }}" step="0.01" min="0">
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="pf_enabled" class="form-check-input" value="1" 
                               id="pf_enabled" {{ old('pf_enabled', $structure->pf_enabled) ? 'checked' : '' }}>
                        <label class="form-check-label" for="pf_enabled">Enable PF Deduction</label>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('payroll.salary-structure') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection