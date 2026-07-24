@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>
            <i class="fas fa-history"></i> Activity Logs
        </h5>
    </div>
    <div class="card-body">
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
                        <td>{{ $log->employee_name }}</td>
                        <td>
                            @if($log->action == 'Created')
                                <span class="badge bg-success">Created</span>
                            @elseif($log->action == 'Updated')
                                <span class="badge bg-primary">Updated</span>
                            @elseif($log->action == 'Deleted')
                                <span class="badge bg-danger">Deleted</span>
                            @elseif($log->action == 'Restored')
                                <span class="badge bg-info">Restored</span>
                            @endif
                        </td>
                        <td>{{ $log->performed_by }}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($log->performed_at)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <i class="fas fa-history fa-2x text-muted d-block mb-2"></i>
                            <span class="text-muted">No activity logs found</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!--  Pagination Component -->
        <x-pagination :items="$logs" />
    </div>
</div>
@endsection