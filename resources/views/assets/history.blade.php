@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-history"></i> Employee-wise Asset History</h5>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.history') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <x-input name="search" label="Search" placeholder="Search by Employee Code or Name..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('assets.history') }}" class="btn btn-secondary" style="margin-top: 30px;">
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
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Asset Type</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Condition</th>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$assets" />
    </div>
</div>
@endsection