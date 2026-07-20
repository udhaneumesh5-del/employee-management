@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Employee</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <!-- Input Component -->
                    <x-input name="employee_code" label="Employee Code" type="text" :value="$employee->employee_code" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="email" label="Email" type="email" :value="$employee->email" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="first_name" label="First Name" type="text" :value="$employee->first_name" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="last_name" label="Last Name" type="text" :value="$employee->last_name" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="mobile_number" label="Mobile Number" type="text" :value="$employee->mobile_number" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="designation" label="Designation" type="text" :value="$employee->designation" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="salary" label="Salary" type="number" :value="$employee->salary" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="joining_date" label="Joining Date" type="date" :value="$employee->joining_date" required="true" />
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $employee->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $employee->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-select @error('profile_image') is-invalid @enderror" accept="image/*">
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($employee->profile_image)
                            <div class="mt-2">
                                <img src="{{ $employee->profile_image_url }}" 
                                     alt="{{ $employee->first_name }}" 
                                     width="80" height="80" 
                                     style="border-radius: 50%; object-fit: cover;">
                                <p class="text-muted small mt-1">Current Image</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <!-- Button Component -->
                <x-button type="submit" class="btn-primary" text="Update Employee" icon="save" />
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection