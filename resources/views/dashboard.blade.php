@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Welcome Message -->
    <div class="col-12 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h4>Welcome, {{ Auth::user()->name }}!</h4>
                <p>Role: <span class="badge bg-primary">{{ Auth::user()->role }}</span></p>
            </div>
        </div>
    </div>

    <!-- Cards -->
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

<!-- Recent Employees Table -->
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
@endsection