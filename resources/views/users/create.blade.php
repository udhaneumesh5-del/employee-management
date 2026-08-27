@extends('layouts.dashboard')

@section('page-title', 'Add User')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-user-plus"></i> Add User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST" id="userForm">
            @csrf
            <div class="row">
                <!-- Full Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Full Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Email Address <span class="text-danger">*</span>
                    </label>

                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Password <span class="text-danger">*</span>
                    </label>

                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Confirm Password <span class="text-danger">*</span>
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-control"
                           required>
                </div>
                <!-- Role -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Role <span class="text-danger">*</span>
                    </label>

                    <select name="role"
                            id="role"
                            class="form-control @error('role') is-invalid @enderror"
                            required>

                        <option value="">Select Role</option>

                        @foreach($allowedRoles as $role)
                            <option value="{{ $role }}"
                                {{ old('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach

                    </select>

                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Status <span class="text-danger">*</span>
                    </label>

                    <select name="status"
                            class="form-control @error('status') is-invalid @enderror"
                            required>

                        <option value="Active"
                            {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Inactive"
                            {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Link to Employee -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        Link to Employee
                        <span class="text-muted">
                            (Optional - Auto-fills Name & Email)
                        </span>
                    </label>

                    <select name="employee_id"
                            id="employee_id"
                            class="form-control @error('employee_id') is-invalid @enderror">

                        <option value="">
                            -- Select Employee (Auto-fill Name & Email) --
                        </option>

                        @foreach($employees as $employee)

                            <option value="{{ $employee->id }}"
                                data-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                data-email="{{ $employee->email }}"
                                data-code="{{ $employee->employee_code }}"
                                data-designation="{{ $employee->designation ?? '' }}"
                                data-department="{{ $employee->department->name ?? 'N/A' }}"
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>

                                {{ $employee->employee_code }}
                                -
                                {{ $employee->first_name }}
                                {{ $employee->last_name }}

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Select an employee to auto-fill name and email,
                        or leave blank to enter manually.
                    </small>

                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Employee Details -->
                <div class="col-md-12 mb-3"
                     id="employee-details"
                     style="display: none;">

                    <div class="card bg-light">

                        <div class="card-body">

                            <h6 class="mb-3">
                                <i class="fas fa-user"></i>
                                Employee Details
                            </h6>

                            <div class="row">

                                <div class="col-md-3">
                                    <strong>Employee Code:</strong>
                                    <span id="emp-code"></span>
                                </div>

                                <div class="col-md-3">
                                    <strong>Name:</strong>
                                    <span id="emp-name"></span>
                                </div>

                                <div class="col-md-3">
                                    <strong>Department:</strong>
                                    <span id="emp-dept"></span>
                                </div>

                                <div class="col-md-3">
                                    <strong>Designation:</strong>
                                    <span id="emp-designation"></span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Clear Auto-fill -->
                <div class="col-md-12 mb-3">

                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            id="clearAutoFill">

                        <i class="fas fa-undo"></i>
                        Clear Auto-fill (Manual Entry)

                    </button>

                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-3">

                <button type="submit" class="btn btn-primary">

                    <i class="fas fa-save"></i>
                    Create User

                </button>

                <a href="{{ route('users.index') }}"
                   class="btn btn-secondary">

                    <i class="fas fa-arrow-left"></i>
                    Back

                </a>

            </div>

        </form>
    </div>
</div>


@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function () {

    /*
    Employee Selection
    */
    $('#employee_id').on('change', function () {

        var selectedOption = $(this).find('option:selected');

        var employeeId = $(this).val();

        if (employeeId) {

            /*
            | Get values from data attributes
            */

            var name = selectedOption.attr('data-name') || '';
            var email = selectedOption.attr('data-email') || '';
            var code = selectedOption.attr('data-code') || '';
            var designation = selectedOption.attr('data-designation') || 'N/A';
            var department = selectedOption.attr('data-department') || 'N/A';


            /*
            
            | Auto-fill Name
            
            */

            $('#name').val(name);


            /*

             Auto-fill Email
            */

            $('#email').val(email);

            /*
            | Show Employee Details
            */

            $('#employee-details').show();

            $('#emp-code').text(code);

            $('#emp-name').text(name);

            $('#emp-dept').text(department);

            $('#emp-designation').text(designation);


            /*
             Debug - Check Browser Console
            */

            console.log('Employee ID:', employeeId);
            console.log('Employee Name:', name);
            console.log('Employee Email:', email);

        } else {

            /*
             No Employee Selected
            */

            $('#name').val('');
            $('#email').val('');

            $('#employee-details').hide();

        }

    });


    /*
     Clear Auto-fill
    */

    $('#clearAutoFill').on('click', function () {

        $('#employee_id').val('');

        $('#name').val('');

        $('#email').val('');

        $('#employee-details').hide();

        $('#name').focus();

    });


    /*
    Trigger Change When Validation Fails
    */

    @if(old('employee_id'))

        $('#employee_id').trigger('change');

    @endif

});

</script>

@endpush

@endsection