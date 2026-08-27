@extends('layouts.dashboard')

@section('page-title', 'Attendance Report')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-calendar-check"></i> Attendance Report</h5>
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
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attendance->first_name }} {{ $attendance->last_name }}</td>
                        <td>{{ date('d-m-Y', strtotime($attendance->date)) }}</td>
                        <td>
                            @if($attendance->status == 'Present')
                                <span class="badge bg-success">Present</span>
                            @elseif($attendance->status == 'Absent')
                                <span class="badge bg-danger">Absent</span>
                            @else
                                <span class="badge bg-warning">Leave</span>
                            @endif
                        </td>
                        <td>{{ $attendance->check_in ?? '-' }}</td>
                        <td>{{ $attendance->check_out ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No attendance records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection