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
        <!-- Search and Sorting Form -->
        <form action="{{ route('employees.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or email..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(request('search') || request('status'))
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

                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
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