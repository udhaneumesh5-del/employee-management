<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="ems-dashboard">

    <!-- Sidebar Overlay (Mobile) -->
    <div class="ems-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <aside class="ems-sidebar" id="sidebar">
        <div class="ems-logo d-flex align-items-center">
            <div class="logo-icon me-3">
                <i class="fas fa-users"></i>
            </div>
            <div class="logo-text">
                <strong class="text-white">EMPLOYEE</strong>
                <span>MANAGEMENT SYSTEM</span>
            </div>
        </div>

        <!-- SIDEBAR MENU -->
       
        <ul class="ems-menu">
            
            <!-- Dashboard -->
            <li class="ems-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @auth
               
                <!-- USER MANAGEMENT - Admin & HR Only -->
               
                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                    <li class="ems-menu-title ems-dropdown">
                        <a href="#" class="ems-dropdown-toggle">
                            <span><i class="fas fa-users-cog"></i> User Management</span>
                            <i class="fas fa-chevron-down ems-arrow"></i>
                        </a>
                        <ul class="ems-dropdown-menu">
                            <li class="ems-submenu"><a href="{{ route('users.index') }}">All Users</a></li>
                            <li class="ems-submenu"><a href="{{ route('users.create') }}">Add User</a></li>
                        </ul>
                    </li>
                @endif

               
                <!-- EMPLOYEE MANAGEMENT - All Roles -->
               
                <li class="ems-menu-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <a href="{{ route('employees.index') }}">
                        <i class="fas fa-user-group"></i>
                        <span>Employee Management</span>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </a>
                </li>

               
                <!-- DEPARTMENT MANAGEMENT - All Roles -->
               
                <li class="ems-menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}">
                        <i class="fas fa-building"></i>
                        <span>Department Management</span>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </a>
                </li>

               
                <!-- ATTENDANCE MANAGEMENT - All Roles -->
               
                <li class="ems-menu-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.index') }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance Management</span>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </a>
                </li>

               
                <!-- ASSET MANAGEMENT - Role Based -->
               
                @if(Auth::user()->isAdmin() || Auth::user()->isHR() || Auth::user()->isManager() || Auth::user()->isEmployee())
                    <li class="ems-menu-item ems-dropdown">
                        <a href="#" class="ems-dropdown-toggle">
                            <span><i class="fas fa-briefcase"></i> Asset Management</span>
                            <i class="fas fa-chevron-down ems-arrow"></i>
                        </a>
                        <ul class="ems-dropdown-menu">
                            @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                                <li class="ems-submenu"><a href="{{ route('asset-master.index') }}">Asset Master</a></li>
                                <li class="ems-submenu"><a href="{{ route('asset-issue.index') }}">Issue Asset</a></li>
                                <li class="ems-submenu"><a href="{{ route('asset-return.index') }}">Return Asset</a></li>
                                <li class="ems-submenu"><a href="{{ route('asset-issue.report') }}">Issued Report</a></li>
                                <li class="ems-submenu"><a href="{{ route('asset-return.report') }}">Returned Report</a></li>
                            @else
                                <li class="ems-submenu"><a href="{{ route('asset-master.index') }}">My Assets</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- LEAVE MANAGEMENT - Role Based -->
                <!-- Employee/Manager/HR Options - Hide from Admin -->
                @if(!Auth::user()->isAdmin())
                    <li class="ems-menu-item ems-dropdown">
                        <a href="#" class="ems-dropdown-toggle">
                            <span><i class="fas fa-calendar-days"></i> Leave Management</span>
                            <i class="fas fa-chevron-down ems-arrow"></i>
                        </a>
                        <ul class="ems-dropdown-menu">
                            <!-- Employee Options -->
                            @if(Auth::user()->isEmployee() || Auth::user()->isManager() || Auth::user()->isHR())
                                <li class="ems-submenu"><a href="{{ route('leave.apply-form') }}">Apply Leave</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave.my-leaves') }}">My Leaves</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave.balance') }}">Leave Balance</a></li>
                            @endif

                            <!-- Manager Options -->
                            @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                                <li class="ems-submenu"><a href="{{ route('leave.manager.dashboard') }}">Manager Dashboard</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave.manager.pending') }}">Pending Requests</a></li>
                            @endif

                            <!-- HR Options -->
                            @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                                <li class="ems-submenu"><a href="{{ route('leave.hr.dashboard') }}">HR Dashboard</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave.hr.pending') }}">Pending HR</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave.hr.all') }}">All Requests</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave-types.index') }}">Leave Types</a></li>
                            @endif

                            <!-- Admin Options -->
                            @if(Auth::user()->isAdmin())
                                <li class="ems-submenu"><a href="{{ route('leave.admin.dashboard') }}">Admin Dashboard</a></li>
                                <li class="ems-submenu"><a href="{{ route('leave.admin.pending') }}">Pending Admin</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

               
                <!-- ADMIN ONLY - Leave Management -->
               
                @if(Auth::user()->isAdmin())
                    <li class="ems-menu-item ems-dropdown">
                        <a href="#" class="ems-dropdown-toggle">
                            <span><i class="fas fa-calendar-days"></i> Leave Management</span>
                            <i class="fas fa-chevron-down ems-arrow"></i>
                        </a>
                        <ul class="ems-dropdown-menu">
                            <!-- Admin Only -->
                            <li class="ems-submenu"><a href="{{ route('leave.admin.dashboard') }}">Admin Dashboard</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave.admin.pending') }}">Pending Admin</a></li>
                            
                            <!-- Leave Types (Admin) -->
                            <li class="ems-submenu"><a href="{{ route('leave-types.index') }}">Leave Types</a></li>
                            
                            <!-- Leave Balance Management (Admin) -->
                            <li class="ems-submenu"><a href="{{ route('leave.admin.balances') }}">Leave Balances</a></li>
                        </ul>
                    </li>
                @endif

               
                <!-- ACTIVITY LOGS - Admin & HR -->
               
                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                    <li class="ems-menu-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        <a href="{{ route('activity-logs.index') }}">
                            <i class="fas fa-clock-rotate-left"></i>
                            <span>Activity Logs</span>
                        </a>
                    </li>
                @endif

               
                <!-- REPORTS - All Roles -->
               
                <li class="ems-menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <a href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-column"></i>
                        <span>Reports</span>
                    </a>
                </li>

               
                <!-- SETTINGS - Admin & HR -->
               
                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                    <li class="ems-menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}">
                            <i class="fas fa-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="ems-main">

       
        <!-- TOP BAR -->
       
        <header class="ems-topbar d-flex align-items-center justify-content-between">
            <button class="ems-sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="d-flex align-items-center gap-3">
                <!-- Notification -->
                <div class="ems-notification">
                    <i class="far fa-bell"></i>
                    @auth
                        @php
                            $pendingCount = 0;
                            if(isset($pendingManager)) {
                                $pendingCount += $pendingManager;
                            }
                            if(isset($pendingHR)) {
                                $pendingCount += $pendingHR;
                            }
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-danger rounded-circle">{{ $pendingCount }}</span>
                        @endif
                    @endauth
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    @auth
                        <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" role="button" style="cursor:pointer;">
                            <span class="ems-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <span class="role-badge d-none d-md-inline">{{ Auth::user()->role }}</span>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </header>

       
        <!-- PAGE CONTENT -->
       
        <div class="ems-content">

            <!-- Page Header -->
            <div class="page-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="date">
                    <i class="far fa-calendar"></i>
                    {{ now()->format('d F, Y (l)') }}
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Sidebar Toggle (Mobile)
      
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            });
        }

        // Dropdown Toggle
        const dropdownToggles = document.querySelectorAll('.ems-dropdown-toggle');

        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();

                const parent = this.closest('.ems-dropdown');

                // Close other dropdowns
                document.querySelectorAll('.ems-dropdown.open').forEach(function(item) {
                    if (item !== parent) {
                        item.classList.remove('open');
                        const arrow = item.querySelector('.ems-arrow');
                        if (arrow) {
                            arrow.classList.remove('rotated');
                        }
                    }
                });

                parent.classList.toggle('open');

                const arrow = parent.querySelector('.ems-arrow');
                if (arrow) {
                    arrow.classList.toggle('rotated');
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.ems-dropdown')) {
                document.querySelectorAll('.ems-dropdown.open').forEach(function(item) {
                    item.classList.remove('open');
                    const arrow = item.querySelector('.ems-arrow');
                    if (arrow) {
                        arrow.classList.remove('rotated');
                    }
                });
            }
        });

        // Active Link Highlight
        const currentUrl = window.location.href;

        document.querySelectorAll('.ems-sidebar .nav-link').forEach(function(link) {
            if (link.href === currentUrl || link.href === currentUrl + '/') {
                link.classList.add('active');
                const parentLi = link.closest('li');
                if (parentLi) {
                    parentLi.classList.add('active');
                }
            }
        });

    });
</script>

@stack('scripts')
</body>
</html>