@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>
            <i class="fas fa-user-circle"></i> Employee Profile
        </h5>
        <div>
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Profile Image Column -->
            <div class="col-md-3 text-center">
                <img src="{{ $employee->profile_image_url }}" 
                     alt="{{ $employee->first_name }}" 
                     class="img-fluid rounded-circle"
                     style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #0d6efd;">
                <h4 class="mt-3">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                <p class="text-muted">{{ $employee->designation }}</p>
            </div>

            <!-- Employee Details Column -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Employee Code -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Employee Code</label>
                        <p class="form-control-static">{{ $employee->employee_code }}</p>
                    </div>

                    <!-- Department -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Department</label>
                        <p class="form-control-static">{{ $employee->department ? $employee->department->department_name : 'N/A' }}</p>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Email</label>
                        <p class="form-control-static">{{ $employee->email }}</p>
                    </div>

                    <!-- Mobile Number -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Mobile Number</label>
                        <p class="form-control-static">{{ $employee->mobile_number }}</p>
                    </div>

                    <!-- Designation -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Designation</label>
                        <p class="form-control-static">{{ $employee->designation }}</p>
                    </div>

                    <!-- Salary -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Salary</label>
                        <p class="form-control-static">₹{{ number_format($employee->salary, 2) }}</p>
                    </div>

                    <!-- Joining Date -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Joining Date</label>
                        <p class="form-control-static">{{ date('d-m-Y', strtotime($employee->joining_date)) }}</p>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Status</label>
                        <p class="form-control-static">
                            @if($employee->status == 'Active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <!-- Created Date -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Created Date</label>
                        <p class="form-control-static">{{ date('d-m-Y H:i:s', strtotime($employee->created_at)) }}</p>
                    </div>

                    <!-- Last Updated -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Last Updated</label>
                        <p class="form-control-static">{{ date('d-m-Y H:i:s', strtotime($employee->updated_at)) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection