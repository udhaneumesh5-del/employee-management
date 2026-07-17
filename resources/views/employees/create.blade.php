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
                <div class="col-md-6">
                    <!-- Input Component -->
                    <x-input name="employee_code" label="Employee Code" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="email" label="Email" type="email" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="first_name" label="First Name" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="last_name" label="Last Name" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="mobile_number" label="Mobile Number" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="designation" label="Designation" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="salary" label="Salary" type="number" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="joining_date" label="Joining Date" type="date" required="true" />
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <!-- Button Component -->
                <x-button type="submit" class="btn-primary" text="Create Employee" icon="save" />
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection