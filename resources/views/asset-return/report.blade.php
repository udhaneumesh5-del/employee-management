@extends('layouts.dashboard')

@section('page-title', 'Returned Asset Report')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-arrow-left"></i> Returned Asset Report</h5>
        <div>
            <a href="{{ route('asset-return.export-csv', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> CSV
            </a>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('asset-return.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter Form -->
        <form action="{{ route('asset-return.report') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <x-input name="search" label="Search" placeholder="Search by Employee or Asset..." 
                             value="{{ request('search') }}" />
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Return Reason</label>
                        <select name="return_reason" class="form-control" onchange="this.form.submit()">
                            <option value="">All Reasons</option>
                            @php
                                // Define default reasons if not passed from controller
                                $reasons = isset($returnReasons) && count($returnReasons) > 0 
                                    ? $returnReasons 
                                    : ['Repair Required', 'Exchange', 'Employee Resigned', 'Hardware Problem'];
                            @endphp
                            @foreach($reasons as $reason)
                                <option value="{{ $reason }}" {{ request('return_reason') == $reason ? 'selected' : '' }}>
                                    {{ $reason }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <x-input name="from_date" label="From Date" type="date" 
                             value="{{ request('from_date') }}" />
                </div>
                <div class="col-md-2">
                    <x-input name="to_date" label="To Date" type="date" 
                             value="{{ request('to_date') }}" />
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Filter" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('asset-return.report') }}" class="btn btn-secondary" style="margin-top: 30px;">
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
                        <th>Return Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>{{ $loop->iteration + ($returns->currentPage() - 1) * $returns->perPage() }}</td>
                        <td>{{ $return->employee_code }}</td>
                        <td>{{ $return->employee_name }}</td>
                        <td>{{ $return->department }}</td>
                        <td>{{ $return->asset_code }}</td>
                        <td>{{ $return->asset_type }}</td>
                        <td>{{ $return->company_name }}</td>
                        <td>{{ $return->model }}</td>
                        <td>
                            @if($return->condition == 'Good')
                                <span class="badge bg-success">Good</span>
                            @elseif($return->condition == 'Damaged')
                                <span class="badge bg-danger">Damaged</span>
                            @elseif($return->condition == 'Repair Required')
                                <span class="badge bg-warning">Repair Required</span>
                            @else
                                <span class="badge bg-secondary">{{ $return->condition }}</span>
                            @endif
                        </td>
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
                        <td colspan="12" class="text-center">No returned assets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $returns->firstItem() ?? 0 }} to {{ $returns->lastItem() ?? 0 }} 
                of {{ $returns->total() }} entries
            </div>
            <div>
                {{ $returns->appends(request()->query())->links() }}
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