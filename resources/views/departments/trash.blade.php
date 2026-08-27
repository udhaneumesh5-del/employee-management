@extends('layouts.dashboard')

@section('page-title', 'Trash - Departments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-trash"></i> Department Trash
            <span class="badge bg-secondary ms-2">{{ $departments->total() }}</span>
        </h5>
        <a href="{{ route('departments.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Departments
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
                        <th>Deleted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}</td>
                        <td><strong>{{ $department->department_name }}</strong></td>
                        <td><span class="badge bg-info">{{ $department->department_code }}</span></td>
                        <td>
                            <span class="badge bg-warning">
                                <i class="far fa-clock"></i> {{ date('d-m-Y H:i:s', strtotime($department->deleted_at)) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Restore Button -->
                                <form action="{{ route('departments.restore', $department->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Restore">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                                
                                <!-- Permanent Delete Button -->
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmPermanentDelete({{ $department->id }})" title="Permanently Delete">
                                    <i class="fas fa-trash-alt"></i> Permanent
                                </button>
                            </div>
                            
                            <form id="force-delete-form-{{ $department->id }}" 
                                  action="{{ route('departments.force-delete', $department->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-trash fa-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Trash is empty</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Component -->
        <x-pagination :items="$departments" />
    </div>
</div>

@push('scripts')
<script>
    function confirmPermanentDelete(id) {
        if (confirm('⚠️ Are you sure you want to permanently delete this department? This action cannot be undone.')) {
            document.getElementById('force-delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection