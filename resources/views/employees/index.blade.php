@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Employee List</h5>
        <div>
            <a href="{{ route('employees.export.csv') }}" class="btn btn-success btn-sm me-2">
                <i class="fas fa-file-export"></i> Export CSV
            </a>
            <a href="{{ route('employees.trash') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-trash"></i> Trash
            </a>
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                Add New Employee
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Advanced Search Form -->
        <form action="{{ route('employees.index') }}" method="GET" class="mb-3">
            <div class="row">
                <!-- Employee Name -->
                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee Name</label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="Search by name..." 
                           value="{{ request('name') }}">
                </div>

                <!-- Email -->
                <div class="col-md-3 mb-2">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" 
                           placeholder="Search by email..." 
                           value="{{ request('email') }}">
                </div>

                <!-- Department -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-control">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" 
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Joining Date From -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Joining From</label>
                    <input type="date" name="joining_from" class="form-control" 
                           value="{{ request('joining_from') }}">
                </div>

                <!-- Joining Date To -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Joining To</label>
                    <input type="date" name="joining_to" class="form-control" 
                           value="{{ request('joining_to') }}">
                </div>

                <!-- Salary Min -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Salary Min</label>
                    <input type="number" name="salary_min" class="form-control" 
                           placeholder="Min" value="{{ request('salary_min') }}">
                </div>

                <!-- Salary Max -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">Salary Max</label>
                    <input type="number" name="salary_max" class="form-control" 
                           placeholder="Max" value="{{ request('salary_max') }}">
                </div>

                <!-- Buttons -->
                <div class="col-md-12 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear All
                    </a>
                    
                    <!-- Show applied filters -->
                    @if(request()->anyFilled(['name', 'email', 'department_id', 'status', 'joining_from', 'joining_to', 'salary_min', 'salary_max']))
                        <span class="badge bg-info ms-2">Filters Applied</span>
                    @endif
                </div>
            </div>
        </form>

        <!-- Employee Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Employee Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                        <td>
                            <img src="{{ $employee->profile_image_url }}" 
                                 alt="{{ $employee->first_name }}" 
                                 width="50" height="50" 
                                 style="border-radius: 50%; object-fit: cover;">
                        </td>
                        <td>{{ $employee->employee_code }}</td>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->mobile_number }}</td>
                        <td>{{ $employee->department ? $employee->department->department_name : 'N/A' }}</td>
                        <td>{{ $employee->designation }}</td>
                        <td>{{ number_format($employee->salary, 2) }}</td>
                        <td>{{ date('d-m-Y', strtotime($employee->joining_date)) }}</td>
                        <td>
                            @if($employee->status == 'Active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $employee->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $employee->id }}" 
                                  action="{{ route('employees.destroy', $employee->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No employees found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} 
                of {{ $employees->total() }} entries
            </div>
            <div>
                {{ $employees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this employee?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection