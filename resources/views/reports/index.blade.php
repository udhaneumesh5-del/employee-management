@extends('layouts.dashboard')

@section('page-title', 'Reports')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-chart-column"></i> Reports Dashboard</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="{{ route('reports.employee') }}" class="text-decoration-none">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-users"></i> Employee Report</h5>
                            <p class="mb-0">View employee data</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('reports.attendance') }}" class="text-decoration-none">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-calendar-check"></i> Attendance Report</h5>
                            <p class="mb-0">View attendance data</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('reports.asset') }}" class="text-decoration-none">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-boxes"></i> Asset Report</h5>
                            <p class="mb-0">View asset data</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('reports.leave') }}" class="text-decoration-none">
                    <div class="card text-white bg-danger">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-calendar-alt"></i> Leave Report</h5>
                            <p class="mb-0">View leave data</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @if(isset($data))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6>Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(isset($data['totalEmployees']))
                            <div class="col-md-3">
                                <div class="alert alert-primary">Total Employees: <strong>{{ $data['totalEmployees'] }}</strong></div>
                            </div>
                            @endif
                            @if(isset($data['totalDepartments']))
                            <div class="col-md-3">
                                <div class="alert alert-success">Departments: <strong>{{ $data['totalDepartments'] }}</strong></div>
                            </div>
                            @endif
                            @if(isset($data['teamSize']))
                            <div class="col-md-3">
                                <div class="alert alert-info">Team Size: <strong>{{ $data['teamSize'] }}</strong></div>
                            </div>
                            @endif
                            @if(isset($data['myAttendance']))
                            <div class="col-md-3">
                                <div class="alert alert-secondary">My Attendance: <strong>{{ $data['myAttendance'] }}</strong></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection