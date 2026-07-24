@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock"></i> Issued Assets Report</h5>
        <div>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('assets.reports') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
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
                        <th>Condition</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $asset->employee_code }}</td>
                        <td>{{ $asset->employee_name }}</td>
                        <td>{{ $asset->department }}</td>
                        <td>{{ $asset->asset_type }}</td>
                        <td>{{ date('d-m-Y', strtotime($asset->issue_date)) }}</td>
                        <td>{{ $asset->return_date ? date('d-m-Y', strtotime($asset->return_date)) : '-' }}</td>
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
                        <td colspan="8" class="text-center">No issued assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection