@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-boxes"></i> Asset List</h5>
        <div>
            <a href="{{ route('assets.history') }}" class="btn btn-info btn-sm me-2">
                <i class="fas fa-history"></i> History
            </a>
            <a href="{{ route('assets.reports') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-file-alt"></i> Reports
            </a>
            <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Assign Assets
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('assets.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-input name="search" label="Search" placeholder="Search by Employee Code or Name..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="Issued" {{ request('status') == 'Issued' ? 'selected' : '' }}>Issued</option>
                            <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                    </div>
                </div>
                <!-- Asset Type Filter Dropdown (updated) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Asset Type</label>
                        <select name="asset_type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Assets</option>
                            @foreach($assetTypes as $type)
                                <option value="{{ $type }}" {{ request('asset_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('assets.index') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Asset Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Asset Type</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td>{{ $loop->iteration + ($assets->currentPage() - 1) * $assets->perPage() }}</td>
                        <td>{{ $asset->employee_code }}</td>
                        <td>{{ $asset->employee_name }}</td>
                        <td>{{ $asset->department }}</td>
                        <td>{{ $asset->asset_type }}</td>
                        <td>{{ date('d-m-Y', strtotime($asset->issue_date)) }}</td>
                        <td>{{ $asset->return_date ? date('d-m-Y', strtotime($asset->return_date)) : '-' }}</td>
                        <td>
                            @if($asset->status == 'Issued')
                                <span class="badge bg-warning">Issued</span>
                            @else
                                <span class="badge bg-success">Returned</span>
                            @endif
                        </td>
                        <td>
                            @if($asset->condition == 'Good')
                                <span class="badge bg-success">Good</span>
                            @else
                                <span class="badge bg-danger">Damaged</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $asset->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $asset->id }}" 
                                  action="{{ route('assets.destroy', $asset->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$assets" />
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this asset record?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection