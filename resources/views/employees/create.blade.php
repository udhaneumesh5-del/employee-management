@extends('layouts.dashboard')

@section('page-title', 'Add New Employee')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Add New Employee</h5>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-input name="employee_code" label="Employee Code" type="text" 
                             :value="old('employee_code')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="email" label="Email" type="email" 
                             :value="old('email')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="first_name" label="First Name" type="text" 
                             :value="old('first_name')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="last_name" label="Last Name" type="text" 
                             :value="old('last_name')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="mobile_number" label="Mobile Number" type="text" 
                             :value="old('mobile_number')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="designation" label="Designation" type="text" 
                             :value="old('designation')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="salary" label="Salary" type="number" step="0.01"
                             :value="old('salary')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="joining_date" label="Joining Date" type="date" 
                             :value="old('joining_date')" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ✅ MANAGER DROPDOWN - ADDED -->
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Manager</label>
                        <select name="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
                            <option value="">Select Manager</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->first_name }} {{ $manager->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select reporting manager for this employee</small>
                        @error('manager_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Supported formats: JPG, PNG, GIF (Max: 2MB)</small>
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Create Employee" icon="save" />
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection