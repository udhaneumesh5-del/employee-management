@extends('layouts.dashboard')

@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-user-edit"></i> Edit User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-input name="name" label="Full Name" type="text" :value="$user->name" required="true" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="email" label="Email Address" type="email" :value="$user->email" required="true" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="password" label="New Password" type="password" placeholder="Leave blank to keep current" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="password_confirmation" label="Confirm Password" type="password" placeholder="Leave blank to keep current" />
                </div>

                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach($allowedRoles as $role)
                                <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $user->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $user->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Link to Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $user->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->employee_code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Link this user to an employee record</small>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Employee Details (AJAX) -->
                <div class="col-md-12 mb-3" id="employee-details" style="display: {{ $user->employee_id ? 'block' : 'none' }};">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Employee Details</h6>
                            <div class="row">
                                <div class="col-md-3"><strong>Employee Code:</strong> <span id="emp-code">{{ $user->employee->employee_code ?? '' }}</span></div>
                                <div class="col-md-3"><strong>Name:</strong> <span id="emp-name">{{ $user->employee->first_name ?? '' }} {{ $user->employee->last_name ?? '' }}</span></div>
                                <div class="col-md-3"><strong>Department:</strong> <span id="emp-dept">{{ $user->employee->department->department_name ?? 'N/A' }}</span></div>
                                <div class="col-md-3"><strong>Designation:</strong> <span id="emp-designation">{{ $user->employee->designation ?? 'N/A' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Update User" icon="save" />
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#employee_id').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: "{{ url('users/get-employee') }}/" + id,
                    type: "GET",
                    success: function(data) {
                        if (data) {
                            $('#employee-details').show();
                            $('#emp-code').text(data.employee_code);
                            $('#emp-name').text(data.first_name + ' ' + data.last_name);
                            $('#emp-dept').text(data.department_name || 'N/A');
                            $('#emp-designation').text(data.designation || 'N/A');
                        }
                    }
                });
            } else {
                $('#employee-details').hide();
            }
        });
    });
</script>
@endpush
@endsection