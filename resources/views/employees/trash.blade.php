@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>
            <i class="fas fa-trash"></i> Trash
            <span class="badge bg-secondary">{{ $employees->total() }}</span>
        </h5>
        <a href="{{ route('employees.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Employees
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>Deleted Date</th>
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
                        <td>{{ date('d-m-Y H:i:s', strtotime($employee->deleted_at)) }}</td>
                        <td>
                            <!-- Restore Button -->
                            <form action="{{ route('employees.restore', $employee->id) }}" 
                                  method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>
                            
                            <!-- Permanent Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="confirmPermanentDelete({{ $employee->id }})">
                                <i class="fas fa-trash-alt"></i> Permanent
                            </button>
                            
                            <form id="force-delete-form-{{ $employee->id }}" 
                                  action="{{ route('employees.force-delete', $employee->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="fas fa-trash fa-2x text-muted d-block mb-2"></i>
                            <span class="text-muted">Trash is empty</span>
                        </td>
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
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmPermanentDelete(id) {
        if (confirm('Are you sure you want to permanently delete this employee? This action cannot be undone.')) {
            document.getElementById('force-delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection