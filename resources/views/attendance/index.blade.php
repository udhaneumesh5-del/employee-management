@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>
            <i class="fas fa-calendar-check"></i> Attendance
            <span class="badge bg-secondary">{{ now()->format('d-m-Y') }}</span>
        </h5>
        <div>
            <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Mark Attendance
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('attendance.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <x-input name="date" label="Date" type="date" value="{{ request('date', now()->toDateString()) }}" />
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                            <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <x-button type="submit" class="btn-primary" text="Filter" icon="search" style="margin-top: 30px;" />
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary" style="margin-top: 30px;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Attendance Table -->
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
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                        <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
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
                        <td>{{ $attendance->remarks ?? '-' }}</td>
                        <td>
                            <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $attendance->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $attendance->id }}" 
                                  action="{{ route('attendance.destroy', $attendance->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No attendance records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-pagination :items="$attendances" />
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this attendance record?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection