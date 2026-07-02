@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Department List</h5>
        <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm">
            Add New Department
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Department Name</th>
                        <th>Department Code</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}</td>
                        <td>{{ $department->department_name }}</td>
                        <td>{{ $department->department_code }}</td>
                        <td>
                            @if($department->status == 'Active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('departments.edit', $department->id) }}" 
                               class="btn btn-warning btn-sm">Update</a>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete({{ $department->id }})">
                                Delete
                            </button>
                            <form id="delete-form-{{ $department->id }}" 
                                  action="{{ route('departments.destroy', $department->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No departments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} 
                of {{ $departments->total() }} entries
            </div>
            <div>
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this department?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection