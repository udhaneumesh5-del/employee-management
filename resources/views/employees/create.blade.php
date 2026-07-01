@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Add New Employee</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="employee_code" class="form-label">Employee Code <span class="text-danger">*</span></label>
                    <input type="text" name="employee_code" id="employee_code" 
                           class="form-control @error('employee_code') is-invalid @enderror" 
                           value="{{ old('employee_code') }}" required>
                    @error('employee_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" id="first_name" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" id="last_name" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" name="mobile_number" id="mobile_number" 
                           class="form-control @error('mobile_number') is-invalid @enderror" 
                           value="{{ old('mobile_number') }}" required>
                    @error('mobile_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                    <input type="text" name="designation" id="designation" 
                           class="form-control @error('designation') is-invalid @enderror" 
                           value="{{ old('designation') }}" required>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="salary" id="salary" 
                           class="form-control @error('salary') is-invalid @enderror" 
                           value="{{ old('salary') }}" required>
                    @error('salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                    <input type="date" name="joining_date" id="joining_date" 
                           class="form-control @error('joining_date') is-invalid @enderror" 
                           value="{{ old('joining_date') }}" required>
                    @error('joining_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" 
                            class="form-control @error('status') is-invalid @enderror" required>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="profile_image" class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image" 
                           class="form-control @error('profile_image') is-invalid @enderror" 
                           accept="image/*">
                    <small class="text-muted">Supported: jpeg, png, jpg, gif (Max: 2MB)</small>
                    @error('profile_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create Employee</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection