@extends('layouts.dashboard')

@section('page-title', 'Employee Profile - ' . $employee->first_name . ' ' . $employee->last_name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
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
                <div class="position-relative d-inline-block">
                    <img src="{{ $employee->profile_image_url }}" 
                         alt="{{ $employee->first_name }}" 
                         class="img-fluid rounded-circle"
                         style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #0d6efd; padding: 3px;">
                    @if($employee->status == 'Active')
                        <span class="position-absolute badge bg-success rounded-pill" 
                              style="bottom: 15px; right: 15px; padding: 6px 14px; font-size: 11px; border: 2px solid #fff;">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                    @else
                        <span class="position-absolute badge bg-danger rounded-pill" 
                              style="bottom: 15px; right: 15px; padding: 6px 14px; font-size: 11px; border: 2px solid #fff;">
                            <i class="fas fa-times-circle"></i> Inactive
                        </span>
                    @endif
                </div>
                <h4 class="mt-3">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                <p class="text-muted">{{ $employee->designation }}</p>
                <p class="text-muted small">
                    <i class="fas fa-code"></i> {{ $employee->employee_code }}
                </p>
            </div>

            <!-- Employee Details Column -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Employee Code -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Employee Code</label>
                        <p class="border-bottom pb-1 mb-0">{{ $employee->employee_code }}</p>
                    </div>

                    <!-- Department -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Department</label>
                        <p class="border-bottom pb-1 mb-0">{{ $employee->department ? $employee->department->department_name : 'N/A' }}</p>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Email</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-envelope text-secondary"></i> {{ $employee->email }}
                        </p>
                    </div>

                    <!-- Mobile Number -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Mobile Number</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-phone text-secondary"></i> {{ $employee->mobile_number }}
                        </p>
                    </div>

                    <!-- Designation -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Designation</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-briefcase text-secondary"></i> {{ $employee->designation }}
                        </p>
                    </div>

                    <!-- Salary -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Salary</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-money-bill-wave text-secondary"></i> ₹{{ number_format($employee->salary, 2) }}
                        </p>
                    </div>

                    <!-- Joining Date -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Joining Date</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-calendar-plus text-secondary"></i> {{ date('d-m-Y', strtotime($employee->joining_date)) }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Status</label>
                        <p class="border-bottom pb-1 mb-0">
                            @if($employee->status == 'Active')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inactive</span>
                            @endif
                        </p>
                    </div>

                    <!-- Created Date -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Created Date</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-calendar-alt text-secondary"></i> {{ date('d-m-Y H:i:s', strtotime($employee->created_at)) }}
                        </p>
                    </div>

                    <!-- Last Updated -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small fw-bold">Last Updated</label>
                        <p class="border-bottom pb-1 mb-0">
                            <i class="fas fa-clock text-secondary"></i> {{ date('d-m-Y H:i:s', strtotime($employee->updated_at)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection