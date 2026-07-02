@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-user-plus me-2"></i>Add New Employee
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="employee_code" class="form-label fw-semibold">
                        <i class="fas fa-id-card me-1 text-primary"></i>Employee Code <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="employee_code" id="employee_code" 
                           class="form-control @error('employee_code') is-invalid @enderror" 
                           value="{{ old('employee_code') }}" required>
                    @error('employee_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">
                        <i class="fas fa-envelope me-1 text-primary"></i>Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" name="email" id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="first_name" class="form-label fw-semibold">
                        <i class="fas fa-user me-1 text-primary"></i>First Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="first_name" id="first_name" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="last_name" class="form-label fw-semibold">
                        <i class="fas fa-user me-1 text-primary"></i>Last Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="last_name" id="last_name" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="mobile_number" class="form-label fw-semibold">
                        <i class="fas fa-phone me-1 text-primary"></i>Mobile Number <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="mobile_number" id="mobile_number" 
                           class="form-control @error('mobile_number') is-invalid @enderror" 
                           value="{{ old('mobile_number') }}" required>
                    @error('mobile_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="designation" class="form-label fw-semibold">
                        <i class="fas fa-briefcase me-1 text-primary"></i>Designation <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="designation" id="designation" 
                           class="form-control @error('designation') is-invalid @enderror" 
                           value="{{ old('designation') }}" required>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="salary" class="form-label fw-semibold">
                        <i class="fas fa-money-bill-wave me-1 text-primary"></i>Salary <span class="text-danger">*</span>
                    </label>
                    <input type="number" step="0.01" name="salary" id="salary" 
                           class="form-control @error('salary') is-invalid @enderror" 
                           value="{{ old('salary') }}" required>
                    @error('salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="joining_date" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>Joining Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="joining_date" id="joining_date" 
                           class="form-control @error('joining_date') is-invalid @enderror" 
                           value="{{ old('joining_date') }}" required>
                    @error('joining_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Department Dropdown -->
                <div class="col-md-6">
                    <label for="department_id" class="form-label fw-semibold">
                        <i class="fas fa-building me-1 text-primary"></i>Department
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-chevron-down text-muted"></i>
                        </span>
                        <select name="department_id" id="department_id" 
                                class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" 
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    <i class="fas fa-building me-1"></i>
                                    {{ $department->department_name }} ({{ $department->department_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Status Dropdown -->
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
                
                <!-- Profile Image -->
                <div class="col-md-6">
                    <label for="profile_image" class="form-label fw-semibold">
                        <i class="fas fa-image me-1 text-primary"></i>Profile Image
                    </label>
                    <input type="file" name="profile_image" id="profile_image" 
                           class="form-control @error('profile_image') is-invalid @enderror" 
                           accept="image/*">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Supported: jpeg, png, jpg, gif (Max: 2MB)
                    </small>
                    @error('profile_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Employee
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection