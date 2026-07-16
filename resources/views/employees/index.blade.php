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
                <i class="fas fa-plus"></i> Add New Employee
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Advanced Search Form -->
        <form action="{{ route('employees.index') }}" method="GET" class="mb-3">
            <div class="row">
                <!-- Employee Name -->
                <div class="col-md-3 mb-2">
                    <label class="form-label">
                        <i class="fas fa-user"></i> Employee Name
                    </label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="Search by name..." 
                           value="{{ request('name') }}">
                </div>

                <!-- Email -->
                <div class="col-md-3 mb-2">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="text" name="email" class="form-control" 
                           placeholder="Search by email..." 
                           value="{{ request('email') }}">
                </div>

                <!-- Department -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-building"></i> Department
                    </label>
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
                    <label class="form-label">
                        <i class="fas fa-circle"></i> Status
                    </label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Joining Date From -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-calendar-start"></i> From
                    </label>
                    <input type="date" name="joining_from" class="form-control" 
                           value="{{ request('joining_from') }}">
                </div>

                <!-- Joining Date To -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-calendar-end"></i> To
                    </label>
                    <input type="date" name="joining_to" class="form-control" 
                           value="{{ request('joining_to') }}">
                </div>

                <!-- Salary Min -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-money-bill"></i> Min
                    </label>
                    <input type="number" name="salary_min" class="form-control" 
                           placeholder="Min" value="{{ request('salary_min') }}">
                </div>

                <!-- Salary Max -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-money-bill"></i> Max
                    </label>
                    <input type="number" name="salary_max" class="form-control" 
                           placeholder="Max" value="{{ request('salary_max') }}">
                </div>

                <!-- Sort By Dropdown -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-sort"></i> Sort By
                    </label>
                    <select name="sort_by" class="form-control" onchange="this.form.submit()">
                        <option value="">Default</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="department" {{ request('sort_by') == 'department' ? 'selected' : '' }}>Department</option>
                        <option value="joining_date" {{ request('sort_by') == 'joining_date' ? 'selected' : '' }}>Joining Date</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>

                <!-- Sort Order Dropdown -->
                <div class="col-md-2 mb-2">
                    <label class="form-label">
                        <i class="fas fa-arrow-up-arrow-down"></i> Order
                    </label>
                    <select name="sort_order" class="form-control" onchange="this.form.submit()">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>▲ Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>▼ Descending</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-12 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear All
                    </a>
                    
                    @if(request()->anyFilled(['name', 'email', 'department_id', 'status', 'joining_from', 'joining_to', 'salary_min', 'salary_max', 'sort_by', 'sort_order']))
                        <span class="badge bg-info ms-2">
                            <i class="fas fa-filter"></i> Filters Applied
                        </span>
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
                        <th>
                            Name
                            @if(request('sort_by') == 'name')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>
                            Email
                            @if(request('sort_by') == 'email')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>Mobile</th>
                        <th>
                            Department
                            @if(request('sort_by') == 'department')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>
                            Joining Date
                            @if(request('sort_by') == 'joining_date')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>
                            Status
                            @if(request('sort_by') == 'status')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
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
                        <td colspan="13" class="text-center">No employees found</td>
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