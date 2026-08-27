@extends('layouts.dashboard')

@section('page-title', 'Asset Master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-box"></i> Asset Master</h5>
        <a href="{{ route('asset-master.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Asset
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('asset-master.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <x-input name="search" label="Search" placeholder="Search by Code, Type, Company..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Asset Code</th>
                        <th>Asset Type</th>
                        <th>Company</th>
                        <th>Model</th>
                        <th>Condition</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td>{{ $loop->iteration + ($assets->currentPage() - 1) * $assets->perPage() }}</td>
                        <td>{{ $asset->asset_code }}</td>
                        <td>{{ $asset->asset_type }}</td>
                        <td>{{ $asset->company_name }}</td>
                        <td>{{ $asset->model }}</td>
                        <td>
                            @if($asset->condition == 'Good')
                                <span class="badge bg-success">Good</span>
                            @else
                                <span class="badge bg-danger">Damaged</span>
                            @endif
                        </td>
                        <td>
                            @if($asset->status == 'Available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-warning">Issued</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('asset-master.edit', $asset->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $asset->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $asset->id }}" 
                                  action="{{ route('asset-master.destroy', $asset->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No assets found</td>
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
        if (confirm('Are you sure you want to delete this asset?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection