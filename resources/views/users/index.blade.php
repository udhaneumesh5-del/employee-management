@extends('layouts.dashboard')

@section('page-title', 'User Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-users-cog"></i> Users</h5>
        @if(auth()->user()->isAdmin() || auth()->user()->isHR())
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add User
            </a>
        @endif
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form action="{{ route('users.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- User Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Employee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleColors = [
                                    'Admin' => 'bg-danger',
                                    'HR' => 'bg-primary',
                                    'Manager' => 'bg-warning',
                                    'Employee' => 'bg-success'
                                ];
                            @endphp
                            <span class="badge {{ $roleColors[$user->role] ?? 'bg-secondary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td>
                            @if(!empty($user->employee_code))
                                {{ $user->employee_code }} - {{ $user->employee_first_name ?? '' }} {{ $user->employee_last_name ?? '' }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $currentUser = auth()->user();
                                $canManage = $currentUser->isAdmin() || 
                                            ($currentUser->isHR() && isset($user->role) && $user->role != 'Admin');
                            @endphp
                            
                            @if($canManage && $user->id !== auth()->id())
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $user->status == 'Active' ? 'secondary' : 'success' }} btn-sm" 
                                            title="{{ $user->status == 'Active' ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $user->status == 'Active' ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                
                                @if($currentUser->isAdmin())
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" 
                                          action="{{ route('users.destroy', $user->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                                
                            @elseif($user->id === auth()->id())
                                <span class="text-muted"><i class="fas fa-user"></i> You</span>
                            @else
                                <span class="text-muted">Protected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No users found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} 
                of {{ $users->total() }} entries
            </div>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection