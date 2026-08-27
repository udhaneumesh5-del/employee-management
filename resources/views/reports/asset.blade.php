@extends('layouts.dashboard')

@section('page-title', 'Asset Report')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-boxes"></i> Asset Report</h5>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection