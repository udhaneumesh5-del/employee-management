@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-building me-2"></i>Add New Department
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="department_name" class="form-label fw-semibold">
                        <i class="fas fa-building me-1 text-primary"></i>Department Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="department_name" id="department_name" 
                           class="form-control @error('department_name') is-invalid @enderror" 
                           value="{{ old('department_name') }}" required>
                    @error('department_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="department_code" class="form-label fw-semibold">
                        <i class="fas fa-code me-1 text-primary"></i>Department Code <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="department_code" id="department_code" 
                           class="form-control @error('department_code') is-invalid @enderror" 
                           value="{{ old('department_code') }}" required>
                    @error('department_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">
                        <i class="fas fa-toggle-on me-1 text-primary"></i>Status <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-chevron-down text-muted"></i>
                        </span>
                        <select name="status" id="status" 
                                class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Select Status --</option>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle text-success me-1"></i> Active
                            </option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle text-danger me-1"></i> Inactive
                            </option>
                        </select>
                    </div>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Department
                </button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection