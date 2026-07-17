@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>
            <i class="fas fa-trash"></i> Department Trash
            <span class="badge bg-secondary">{{ $departments->total() }}</span>
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
                        <td>{{ $department->department_name }}</td>
                        <td>{{ $department->department_code }}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($department->deleted_at)) }}</td>
                        <td>
                            <!-- Restore Button -->
                            <form action="{{ route('departments.restore', $department->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <x-button type="submit" class="btn-success btn-sm" text="Restore" icon="undo" />
                            </form>
                            
                            <!-- Permanent Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmPermanentDelete({{ $department->id }})">
                                <i class="fas fa-trash-alt"></i> Permanent
                            </button>
                            
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
                        <td colspan="5" class="text-center">
                            <i class="fas fa-trash fa-2x text-muted d-block mb-2"></i>
                            <span class="text-muted">Trash is empty</span>
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
        if (confirm('Are you sure you want to permanently delete this department? This action cannot be undone.')) {
            document.getElementById('force-delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection