@extends('layouts.dashboard')

@section('page-title', 'Activity Logs')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-history"></i> Activity Logs
        </h5>
        <span class="badge bg-primary">{{ $logs->total() ?? 0 }} Total</span>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('activity-logs.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-input name="search" label="Search" placeholder="Search by employee or action..." 
                             value="{{ request('search') }}" />
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Action Type</label>
                        <select name="action" class="form-control">
                            <option value="">All Actions</option>
                            <option value="Created" {{ request('action') == 'Created' ? 'selected' : '' }}>Created</option>
                            <option value="Updated" {{ request('action') == 'Updated' ? 'selected' : '' }}>Updated</option>
                            <option value="Deleted" {{ request('action') == 'Deleted' ? 'selected' : '' }}>Deleted</option>
                            <option value="Restored" {{ request('action') == 'Restored' ? 'selected' : '' }}>Restored</option>
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
                <div class="col-md-1">
                    <x-button type="submit" class="btn-primary" text="Filter" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary" style="margin-top: 5px;">
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
                        <th>Employee Name</th>
                        <th>Action</th>
                        <th>Performed By</th>
                        <th>Performed At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                        <td><strong>{{ $log->employee_name }}</strong></td>
                        <td>
                            @if($log->action == 'Created')
                                <span class="badge bg-success"><i class="fas fa-plus"></i> Created</span>
                            @elseif($log->action == 'Updated')
                                <span class="badge bg-primary"><i class="fas fa-edit"></i> Updated</span>
                            @elseif($log->action == 'Deleted')
                                <span class="badge bg-danger"><i class="fas fa-trash"></i> Deleted</span>
                            @elseif($log->action == 'Restored')
                                <span class="badge bg-info"><i class="fas fa-undo"></i> Restored</span>
                            @else
                                <span class="badge bg-secondary">{{ $log->action }}</span>
                            @endif
                        </td>
                        <td>{{ $log->performed_by }}</td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="far fa-clock"></i> {{ date('d-m-Y H:i:s', strtotime($log->performed_at)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No activity logs found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Component -->
        <x-pagination :items="$logs" />
    </div>
</div>
@endsection