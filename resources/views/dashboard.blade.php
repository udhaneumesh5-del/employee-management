@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('content')

<div class="dashboard-wrapper">

    <!-- PAGE HEADER -->
    <div class="dashboard-page-header">
    <div>
        <h2>Welcome, {{ Auth::user()->name }}!</h2>

        <div class="dashboard-role">
            Role: <span>{{ Auth::user()->role }}</span>
        </div>
    </div>
</div>
    <!-- TOP STAT CARDS -->
    <div class="top-stat-grid">
        <div class="top-stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <span>Total Employees</span>
                <strong>{{ $totalEmployees }}</strong>
            </div>
            <a href="{{ route('employees.index') }}">View Employees <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="top-stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-info">
                <span>Departments</span>
                <strong>{{ $totalDepartments }}</strong>
            </div>
            <a href="{{ route('departments.index') }}">View Departments <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="top-stat-card stat-cyan">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-info">
                <span>Active Employees</span>
                <strong>{{ $activeEmployees }}</strong>
            </div>
            <a href="{{ route('employees.index', ['status' => 'Active']) }}">View Active <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="top-stat-card stat-orange">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <span>Today's Present</span>
                <strong>{{ $todayPresent }}</strong>
            </div>
            <a href="{{ route('attendance.index') }}">View Attendance <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="top-stat-card stat-red">
            <div class="stat-icon"><i class="fas fa-calendar-xmark"></i></div>
            <div class="stat-info">
                <span>Today's Absent</span>
                <strong>{{ $todayAbsent }}</strong>
            </div>
            <a href="{{ route('attendance.index') }}">View Attendance <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="top-stat-card stat-purple">
            <div class="stat-icon"><i class="fas fa-plane"></i></div>
            <div class="stat-info">
                <span>On Leave Today</span>
                <strong>{{ $onLeaveToday }}</strong>
            </div>
            <a href="{{ route('leave.balance') }}">View Leaves <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

   
    <!-- FIRST CONTENT ROW -->
   
    <div class="dashboard-grid-4">

        <!-- Employee Overview -->
        <div class="dashboard-panel">
            <div class="panel-title"><h5>Employee Overview</h5></div>
            <div class="employee-overview">
                @php
                    $total = max($totalEmployees, 1);
                    $activePercent = ($activeEmployees / $total) * 100;
                    $inactivePercent = ($inactiveEmployees / $total) * 100;
                    $leavePercent = ($onLeaveToday / $total) * 100;
                @endphp
                <div class="employee-donut" style="background: conic-gradient(#20b26b 0 {{ $activePercent }}%, #ef4444 {{ $activePercent }}% {{ $activePercent + $inactivePercent }}%, #2379e8 {{ $activePercent + $inactivePercent }}% 100%);">
                    <div class="donut-inner"></div>
                </div>
                <div class="donut-legend">
                    <div><span class="legend-dot dot-green"></span> Active ({{ $activeEmployees }})</div>
                    <div><span class="legend-dot dot-red"></span> Inactive ({{ $inactiveEmployees }})</div>
                    <div><span class="legend-dot dot-blue"></span> On Leave ({{ $onLeaveToday }})</div>
                </div>
            </div>
        </div>

        <!-- Department Wise -->
        <div class="dashboard-panel">
            <div class="panel-title"><h5>Department Wise Employees</h5></div>
            <div class="department-chart">
                @php $maxDepartment = max($departmentWise->max('total') ?? 1, 1); @endphp
                @forelse($departmentWise as $dept)
                    <div class="bar-column">
                        <div class="bar-value">{{ $dept->total }}</div>
                        <div class="department-bar" style="height: {{ ($dept->total / $maxDepartment) * 140 }}px;"></div>
                        <div class="bar-label">{{ $dept->department_name ?? 'N/A' }}</div>
                    </div>
                @empty
                    <div class="text-muted">No departments found</div>
                @endforelse
            </div>
        </div>

        <!-- Leave Requests -->
        <div class="dashboard-panel">
            <div class="panel-title"><h5>Leave Requests</h5></div>
            <div class="leave-request-list">
                <div class="leave-request-row"><span>Pending Manager Approval</span><b class="leave-badge badge-orange">{{ $pendingManager }}</b></div>
                <div class="leave-request-row"><span>Pending HR Approval</span><b class="leave-badge badge-blue">{{ $pendingHR }}</b></div>
                <div class="leave-request-row"><span>Approved (This Month)</span><b class="leave-badge badge-green">{{ $approvedThisMonth }}</b></div>
                <div class="leave-request-row"><span>Rejected (This Month)</span><b class="leave-badge badge-red">{{ $rejectedThisMonth }}</b></div>
                <a href="{{ route('leave.hr.all') }}" class="view-all-link">View All Leaves <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="dashboard-panel">
            <div class="panel-title"><h5>Recent Activity</h5></div>
            <div class="activity-list">
                @forelse($recentActivity as $activity)
                    <div class="activity-row">
                        <div class="activity-dot"></div>
                        <div class="activity-details">
                            <div><strong>{{ $activity->employee_name ?? 'System' }}</strong> <span>{{ $activity->action ?? 'Activity' }}</span></div>
                            <small>{{ \Carbon\Carbon::parse($activity->created_at)->format('h:i A') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">No recent activity</div>
                @endforelse
                <a href="{{ route('activity-logs.index') }}" class="view-all-link">View All Activity <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
   
    <!-- SECOND CONTENT ROW -->
   
    <div class="dashboard-grid-bottom">

        <!-- Recent Employees -->
        <div class="dashboard-panel recent-employees-panel">
            <div class="panel-title panel-title-flex"><h5>Recent Employees</h5></div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr><th>#</th><th>Employee Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Joining Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentEmployees as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->employee_code }}</td>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td>{{ $employee->department_name ?? 'N/A' }}</td>
                                <td>{{ $employee->designation }}</td>
                                <td>{{ date('d-m-Y', strtotime($employee->joining_date)) }}</td>
                                <td>
                                    @if($employee->status == 'Active')
                                        <span class="status-active">Active</span>
                                    @else
                                        <span class="status-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No employees found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer-link"><a href="{{ route('employees.index') }}">View All Employees <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <!-- Asset Summary -->
        <div class="dashboard-panel">
            <div class="panel-title"><h5>Asset Summary</h5></div>
            <div class="asset-summary-list">
                <div class="asset-summary-row"><span>Total Assets</span><strong class="asset-blue">{{ $totalAssets }}</strong></div>
                <div class="asset-summary-row"><span>Available</span><strong class="asset-green">{{ $availableAssets }}</strong></div>
                <div class="asset-summary-row"><span>Issued</span><strong class="asset-orange">{{ $issuedAssets }}</strong></div>
                <div class="asset-summary-row"><span>Returns</span><strong class="asset-red">{{ $returnedAssets }}</strong></div>
            </div>
            <div class="panel-footer-link"><a href="{{ route('asset-master.index') }}">View All Assets <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <!-- Attendance Summary -->
        <div class="dashboard-panel">
            <div class="panel-title"><h5>Attendance Summary <small>(This Month)</small></h5></div>
            @php
                $attendanceTotal = max($monthPresent + $monthAbsent + $monthLeave, 1);
                $presentPercent = ($monthPresent / $attendanceTotal) * 100;
                $absentPercent = ($monthAbsent / $attendanceTotal) * 100;
            @endphp
            <div class="attendance-overview">
                <div class="attendance-donut" style="background: conic-gradient(#20b26b 0 {{ $presentPercent }}%, #ef4444 {{ $presentPercent }}% {{ $presentPercent + $absentPercent }}%, #2379e8 {{ $presentPercent + $absentPercent }}% 100%);">
                    <div class="donut-inner"></div>
                </div>
                <div class="attendance-legend">
                    <div><span class="legend-dot dot-green"></span> Present ({{ $monthPresent }})</div>
                    <div><span class="legend-dot dot-red"></span> Absent ({{ $monthAbsent }})</div>
                    <div><span class="legend-dot dot-blue"></span> Leave ({{ $monthLeave }})</div>
                </div>
            </div>
            <div class="panel-footer-link"><a href="{{ route('attendance.index') }}">View Attendance <i class="fas fa-arrow-right"></i></a></div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="dashboard-footer-new">
        <span>© {{ date('Y') }} Employee Management System. All rights reserved.</span>
        <span>Version 1.0.0</span>
    </div>

</div>

@endsection