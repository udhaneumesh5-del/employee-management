@extends('layouts.dashboard')

@section('page-title', 'Trash - Employees')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-trash"></i> Trash
            <span class="badge bg-secondary ms-2">{{ $employees->total() }}</span>
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
                                 style="border-radius: 50%; object-fit: cover; border: 2px solid #e9ecef;">
                        </td>
                        <td><strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></td>
                        <td>{{ $employee->department ? $employee->department->department_name : 'N/A' }}</td>
                        <td>{{ $employee->designation }}</td>
                        <td><span class="badge bg-info">₹ {{ number_format($employee->salary, 2) }}</span></td>
                        <td>
                            <span class="badge bg-warning">
                                <i class="far fa-clock"></i> {{ date('d-m-Y H:i:s', strtotime($employee->deleted_at)) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Restore Button -->
                                <form action="{{ route('employees.restore', $employee->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Restore">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                                
                                <!-- Permanent Delete Button -->
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmPermanentDelete({{ $employee->id }})" title="Permanently Delete">
                                    <i class="fas fa-trash-alt"></i> Permanent
                                </button>
                            </div>
                            
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
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-trash fa-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Trash is empty</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Component -->
        <x-pagination :items="$employees" />
    </div>
</div>

@push('scripts')
<script>
    function confirmPermanentDelete(id) {
        if (confirm('⚠️ Are you sure you want to permanently delete this employee? This action cannot be undone.')) {
            document.getElementById('force-delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection