@extends('layouts.app')

@section('content')
<!-- Welcome Message -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h4>Welcome, {{ Auth::user()->name }}!</h4>
                <p>Role: <span class="badge bg-primary">{{ Auth::user()->role }}</span></p>
            </div>
        </div>
    </div>
</div>

<!-- Employee Stats Cards -->
<div class="row">
    <div class="col-md-3 mb-3">
        <a href="{{ route('employees.index') }}" class="text-decoration-none">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Employees</h5>
                    <h2 class="card-text">{{ $totalEmployees }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('departments.index') }}" class="text-decoration-none">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Departments</h5>
                    <h2 class="card-text">{{ $totalDepartments }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('employees.index', ['status' => 'Active']) }}" class="text-decoration-none">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Active Employees</h5>
                    <h2 class="card-text">{{ $activeEmployees }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('employees.index', ['status' => 'Inactive']) }}" class="text-decoration-none">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Inactive Employees</h5>
                    <h2 class="card-text">{{ $inactiveEmployees }}</h2>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ✅ Today's Attendance Cards -->
<div class="row">
    <div class="col-12 mb-3">
        <h5>Today's Attendance ({{ date('d-m-Y') }})</h5>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Present</h5>
                <h2 class="card-text">{{ $todayPresent }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Absent</h5>
                <h2 class="card-text">{{ $todayAbsent }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">On Leave</h5>
                <h2 class="card-text">{{ $todayLeave }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Total Marked</h5>
                <h2 class="card-text">{{ $todayTotal }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Recent Employees -->
<div class="card mt-3">
    <div class="card-header">
        <h5>Recent Employees</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Joining Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEmployees as $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>{{ $employee->department ? $employee->department->department_name : 'N/A' }}</td>
                        <td>{{ date('d-m-Y', strtotime($employee->joining_date)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No employees found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ Recent Attendance -->
<div class="card mt-3">
    <div class="card-header">
        <h5>Recent Attendance</h5>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendance as $attendance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No attendance records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection