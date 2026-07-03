@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Employee List</h5>
        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
            Add New Employee
        </a>
    </div>
    <div class="card-body">
        <!-- Search and Sorting -->
        <form action="{{ route('employees.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or email..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(request('search'))
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Clear</a>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="sort_by" class="form-control" onchange="this.form.submit()">
                        <option value="">Sort By</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="joining_date" {{ request('sort_by') == 'joining_date' ? 'selected' : '' }}>Joining Date</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select name="sort_order" class="form-control" onchange="this.form.submit()">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
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
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-white text-decoration-none">
                                Employee Name
                                @if(request('sort_by') == 'name')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'joining_date', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-white text-decoration-none">
                                Joining Date
                                @if(request('sort_by') == 'joining_date')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
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
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
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
                            <a href="{{ route('employees.edit', $employee->id) }}" 
                               class="btn btn-warning btn-sm">Update</a>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete({{ $employee->id }})">
                                Delete
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
                        <td colspan="9" class="text-center">No employees found</td>
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