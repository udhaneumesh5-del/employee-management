@extends('layouts.dashboard')

@section('page-title', 'Leave Types')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-list"></i> Leave Types</h5>
        <a href="{{ route('leave-types.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Leave Type
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('leave-types.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <x-input name="search" label="Search" placeholder="Search by name or code..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('leave-types.index') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Annual Limit</th>
                        <th>Max Consecutive</th>
                        <th>Carry Forward</th>
                        <th>Paid</th>
                        <th>Requires Document</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $type)
                    <tr>
                        <td>{{ $loop->iteration + ($leaveTypes->currentPage() - 1) * $leaveTypes->perPage() }}</td>
                        <td>{{ $type->name }}</td>
                        <td><span class="badge bg-info">{{ $type->code }}</span></td>
                        <td>{{ $type->annual_limit }}</td>
                        <td>{{ $type->max_consecutive_days ?? '∞' }}</td>
                        <td>
                            @if($type->carry_forward)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($type->is_paid)
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-secondary">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            @if($type->requires_document)
                                <span class="badge bg-warning">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($type->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('leave-types.edit', $type->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('leave-types.toggle', $type->id) }}" class="btn btn-{{ $type->is_active ? 'secondary' : 'success' }} btn-sm">
                                <i class="fas fa-{{ $type->is_active ? 'times' : 'check' }}"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $type->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $type->id }}" 
                                  action="{{ route('leave-types.destroy', $type->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No leave types found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$leaveTypes" />
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this leave type?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection