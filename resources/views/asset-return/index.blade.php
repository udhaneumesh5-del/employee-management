@extends('layouts.dashboard')

@section('page-title', 'Returned Assets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-arrow-left"></i> Returned Assets</h5>
        <div>
            <a href="{{ route('asset-return.export-csv') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('asset-return.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Return Asset
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('asset-return.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-input name="search" label="Search" placeholder="Search by Employee or Asset..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Return Reason</label>
                        <select name="return_reason" class="form-control" onchange="this.form.submit()">
                            <option value="">All Reasons</option>
                            @foreach($returnReasons ?? [] as $reason)
                                <option value="{{ $reason }}" {{ request('return_reason') == $reason ? 'selected' : '' }}>
                                    {{ $reason }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
                        <th>Employee</th>
                        <th>Asset Code</th>
                        <th>Asset Type</th>
                        <th>Company</th>
                        <th>Model</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>{{ $loop->iteration + ($returns->currentPage() - 1) * $returns->perPage() }}</td>
                        <td>{{ $return->employee_name }}</td>
                        <td>{{ $return->asset_code }}</td>
                        <td>{{ $return->asset_type }}</td>
                        <td>{{ $return->company_name }}</td>
                        <td>{{ $return->model }}</td>
                        <td>{{ date('d-m-Y', strtotime($return->issue_date)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($return->return_date)) }}</td>
                        <td>
                            @if($return->return_reason == 'Repair Required')
                                <span class="badge bg-warning">Repair Required</span>
                            @elseif($return->return_reason == 'Exchange')
                                <span class="badge bg-info">Exchange</span>
                            @elseif($return->return_reason == 'Employee Resigned')
                                <span class="badge bg-danger">Employee Resigned</span>
                            @elseif($return->return_reason == 'Hardware Problem')
                                <span class="badge bg-danger">Hardware Problem</span>
                            @else
                                <span class="badge bg-secondary">{{ $return->return_reason }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No returned assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$returns" />
    </div>
</div>
@endsection