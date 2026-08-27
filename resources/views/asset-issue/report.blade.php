@extends('layouts.dashboard')

@section('page-title', 'Issued Asset Report')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-arrow-right"></i> Issued Asset Report</h5>
        <div>
            <a href="{{ route('asset-issue.export-csv', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> CSV
            </a>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('asset-issue.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter Form -->
        <form action="{{ route('asset-issue.report') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-input name="search" label="Search" placeholder="Search by Employee or Asset..." 
                             value="{{ request('search') }}" />
                </div>
                <div class="col-md-3">
                    <x-input name="from_date" label="From Date" type="date" 
                             value="{{ request('from_date') }}" />
                </div>
                <div class="col-md-3">
                    <x-input name="to_date" label="To Date" type="date" 
                             value="{{ request('to_date') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Filter" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('asset-issue.report') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Report Table -->
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
                        <th>Condition</th>
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
                        <td>
                            @if($issue->condition == 'Good')
                                <span class="badge bg-success">Good</span>
                            @elseif($issue->condition == 'Damaged')
                                <span class="badge bg-danger">Damaged</span>
                            @else
                                <span class="badge bg-warning">{{ $issue->condition }}</span>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y', strtotime($issue->issue_date)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No issued assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $issues->firstItem() ?? 0 }} to {{ $issues->lastItem() ?? 0 }} 
                of {{ $issues->total() }} entries
            </div>
            <div>
                {{ $issues->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit form on date change
    document.addEventListener('DOMContentLoaded', function() {
        var dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    });
</script>
@endpush
@endsection