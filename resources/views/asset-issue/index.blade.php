@extends('layouts.dashboard')

@section('page-title', 'Issued Assets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-arrow-right"></i> Issued Assets</h5>
        <div>
            <a href="{{ route('asset-issue.export-csv') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> CSV
            </a>
            <a href="{{ route('asset-issue.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Issue Asset
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('asset-issue.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <x-input name="search" label="Search" placeholder="Search by Employee..." value="{{ request('search') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Search" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('asset-issue.index') }}" class="btn btn-secondary" style="margin-top: 30px;">
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
                        <th>Asset Code</th>
                        <th>Asset Type</th>
                        <th>Company Name</th>
                        <th>Model</th>
                        <th>Issue Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                    <tr>
                        <td>{{ $loop->iteration + ($issues->currentPage() - 1) * $issues->perPage() }}</td>
                        <td>{{ $issue->employee_code }}</td>
                        <td>{{ $issue->employee_name }}</td>
                        <td>{{ $issue->department }}</td>
                        <td>{{ $issue->asset_code }}</td>
                        <td>{{ $issue->asset_type }}</td>
                        <td>{{ $issue->company_name }}</td>
                        <td>{{ $issue->model }}</td>
                        <td>{{ date('d-m-Y', strtotime($issue->issue_date)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No issued assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$issues" />
    </div>
</div>
@endsection