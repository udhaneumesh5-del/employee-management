<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Management System')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body>

<div class="ems-dashboard">

    <!-- Sidebar Overlay (Mobile) -->
    <div class="ems-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <aside class="ems-sidebar" id="sidebar">
        
        <!-- Logo Section -->
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

            {{-- = MAIN = --}}
            <li class="ems-menu-title">MAIN</li>

            <!-- Dashboard -->
            <li class="ems-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @auth

            {{-- = MANAGEMENT = --}}
            <li class="ems-menu-title">MANAGEMENT</li>

            <!-- USER MANAGEMENT - Admin & HR Only -->
            @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                <li class="ems-menu-item ems-dropdown {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="#" class="ems-dropdown-toggle">
                        <span><i class="fas fa-users-cog"></i> User Management</span>
                        <i class="fas fa-chevron-down ems-arrow"></i>
                    </a>
                    <ul class="ems-dropdown-menu">
                        <li class="ems-submenu">
                            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="fas fa-list"></i> All Users
                            </a>
                        </li>
                        <li class="ems-submenu">
                            <a href="{{ route('users.create') }}" class="{{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="fas fa-user-plus"></i> Add User
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- EMPLOYEE MANAGEMENT - All Roles -->
            <li class="ems-menu-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <a href="{{ route('employees.index') }}">
                    <i class="fas fa-user-group"></i>
                    <span>Employees</span>
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
            </li>

            <!-- DEPARTMENT MANAGEMENT - All Roles -->
            <li class="ems-menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <a href="{{ route('departments.index') }}">
                    <i class="fas fa-building"></i>
                    <span>Departments</span>
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
            </li>

            <!-- ATTENDANCE MANAGEMENT - All Roles -->
            <li class="ems-menu-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <a href="{{ route('attendance.index') }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
            </li>

            {{-- = MODULES = --}}
            <li class="ems-menu-title">MODULES</li>

            <!-- ASSET MANAGEMENT -->
            @if(Auth::user()->isAdmin() || Auth::user()->isHR() || Auth::user()->isManager() || Auth::user()->isEmployee())
                <li class="ems-menu-item ems-dropdown {{ request()->routeIs('asset-*') ? 'active' : '' }}">
                    <a href="#" class="ems-dropdown-toggle">
                        <span><i class="fas fa-briefcase"></i> Assets</span>
                        <i class="fas fa-chevron-down ems-arrow"></i>
                    </a>
                    <ul class="ems-dropdown-menu">
                        @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                            <li class="ems-submenu"><a href="{{ route('asset-master.index') }}"><i class="fas fa-box"></i> Asset Master</a></li>
                            <li class="ems-submenu"><a href="{{ route('asset-issue.index') }}"><i class="fas fa-share"></i> Issue Asset</a></li>
                            <li class="ems-submenu"><a href="{{ route('asset-return.index') }}"><i class="fas fa-undo"></i> Return Asset</a></li>
                            <li class="ems-divider"></li>
                            <li class="ems-submenu"><a href="{{ route('asset-issue.report') }}"><i class="fas fa-chart-line"></i> Issued Report</a></li>
                            <li class="ems-submenu"><a href="{{ route('asset-return.report') }}"><i class="fas fa-chart-line"></i> Returned Report</a></li>
                        @else
                            <li class="ems-submenu"><a href="{{ route('asset-master.index') }}"><i class="fas fa-box"></i> My Assets</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- LEAVE MANAGEMENT - Non-Admin -->
            @if(!Auth::user()->isAdmin())
                <li class="ems-menu-item ems-dropdown {{ request()->routeIs('leave.*') || request()->routeIs('leave-types.*') ? 'active' : '' }}">
                    <a href="#" class="ems-dropdown-toggle">
                        <span><i class="fas fa-calendar-days"></i> Leaves</span>
                        <i class="fas fa-chevron-down ems-arrow"></i>
                    </a>
                    <ul class="ems-dropdown-menu">
                        @if(Auth::user()->isEmployee() || Auth::user()->isManager() || Auth::user()->isHR())
                            <li class="ems-submenu"><a href="{{ route('leave.apply-form') }}"><i class="fas fa-plus"></i> Apply Leave</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave.my-leaves') }}"><i class="fas fa-list"></i> My Leaves</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave.balance') }}"><i class="fas fa-balance-scale"></i> Leave Balance</a></li>
                        @endif

                        @if(Auth::user()->isManager())
                            <li class="ems-divider"></li>
                            <li class="ems-submenu"><a href="{{ route('leave.manager.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Manager Dashboard</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave.manager.pending') }}"><i class="fas fa-clock"></i> Pending Requests</a></li>
                        @endif

                        @if(Auth::user()->isHR())
                            <li class="ems-divider"></li>
                            <li class="ems-submenu"><a href="{{ route('leave.hr.dashboard') }}"><i class="fas fa-tachometer-alt"></i> HR Dashboard</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave.hr.pending') }}"><i class="fas fa-clock"></i> Pending HR</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave.hr.all') }}"><i class="fas fa-list"></i> All Requests</a></li>
                            <li class="ems-submenu"><a href="{{ route('leave-types.index') }}"><i class="fas fa-tags"></i> Leave Types</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- ADMIN LEAVE MANAGEMENT -->
            @if(Auth::user()->isAdmin())
                <li class="ems-menu-item ems-dropdown {{ request()->routeIs('leave.*') || request()->routeIs('leave-types.*') ? 'active' : '' }}">
                    <a href="#" class="ems-dropdown-toggle">
                        <span><i class="fas fa-calendar-days"></i> Leaves</span>
                        <i class="fas fa-chevron-down ems-arrow"></i>
                    </a>
                    <ul class="ems-dropdown-menu">
                        <li class="ems-submenu"><a href="{{ route('leave.admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a></li>
                        <li class="ems-submenu"><a href="{{ route('leave.admin.pending') }}"><i class="fas fa-clock"></i> Pending Approvals</a></li>
                        <li class="ems-submenu"><a href="{{ route('leave-types.index') }}"><i class="fas fa-tags"></i> Leave Types</a></li>
                        <li class="ems-submenu"><a href="{{ route('leave.admin.balances') }}"><i class="fas fa-balance-scale"></i> Leave Balances</a></li>
                    </ul>
                </li>
            @endif

            <!-- REIMBURSEMENT MANAGEMENT -->
            <li class="ems-menu-item ems-dropdown {{ request()->routeIs('reimbursements.*') ? 'active' : '' }}">
                <a href="#" class="ems-dropdown-toggle">
                    <span><i class="fas fa-file-invoice-dollar"></i> Reimbursement</span>
                    <i class="fas fa-chevron-down ems-arrow"></i>
                </a>
                <ul class="ems-dropdown-menu">
                    <li class="ems-submenu">
                        <a href="{{ route('reimbursements.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>

                    @if(Auth::user()->isEmployee() || Auth::user()->isManager() || Auth::user()->isHR())
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.my-requests') }}">
                                <i class="fas fa-list"></i> My Requests
                            </a>
                        </li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.create') }}">
                                <i class="fas fa-plus"></i> New Request
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isManager())
                        <li class="ems-divider"></li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.manager.pending') }}">
                                <i class="fas fa-clock"></i> Pending Approvals
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isHR())
                        <li class="ems-divider"></li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.hr.pending') }}">
                                <i class="fas fa-clock"></i> Pending HR
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isAdmin())
                        <li class="ems-divider"></li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.admin.pending') }}">
                                <i class="fas fa-clock"></i> Pending Admin
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                        <li class="ems-divider"></li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.policies.index') }}">
                                <i class="fas fa-gavel"></i> Policies
                            </a>
                        </li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.expense-types.index') }}">
                                <i class="fas fa-tags"></i> Expense Types
                            </a>
                        </li>
                        <li class="ems-divider"></li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.reports.index') }}">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                        </li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.payments.index') }}">
                                <i class="fas fa-money-bill-wave"></i> Payments
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isAdmin())
                        <li class="ems-divider"></li>
                        <li class="ems-submenu">
                            <a href="{{ route('reimbursements.all-requests') }}">
                                <i class="fas fa-list-ul"></i> All Requests
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <!-- PAYROLL MANAGEMENT -->
    <li class="ems-menu-item ems-dropdown {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
        <a href="#" class="ems-dropdown-toggle">
            <span>
                <i class="fas fa-money-check-alt"></i> Payroll
            </span>
            <i class="fas fa-chevron-down ems-arrow"></i>
        </a>

        <ul class="ems-dropdown-menu">

            <!-- Dashboard - All Roles -->
            <li class="ems-submenu">
                <a href="{{ route('payroll.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>


            <!-- My Payroll - Employee / Manager / HR -->
            @if(Auth::user()->isEmployee() || Auth::user()->isManager() || Auth::user()->isHR())
                <li class="ems-submenu">
                    <a href="{{ route('payroll.index') }}">
                        <i class="fas fa-list"></i> My Payroll
                    </a>
                </li>
            @endif


            <!-- HR PAYROLL MANAGEMENT -->
            @if(Auth::user()->isHR())

                <li class="ems-divider"></li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.salary-structure') }}">
                        <i class="fas fa-cog"></i> Salary Structure
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.generate') }}">
                        <i class="fas fa-plus"></i> Generate Payroll
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.index') }}">
                        <i class="fas fa-list"></i> All Payrolls
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.adjustments') }}">
                        <i class="fas fa-sliders-h"></i> Adjustments
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.reports') }}">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </li>

            @endif


            <!-- ADMIN - HR PAYROLL MANAGEMENT -->
            @if(Auth::user()->isAdmin())

                <li class="ems-divider"></li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.salary-structure') }}">
                        <i class="fas fa-cog"></i> Salary Structure
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.generate') }}">
                        <i class="fas fa-plus"></i> Generate HR Payroll
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.index') }}">
                        <i class="fas fa-list"></i> HR Payrolls
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.adjustments') }}">
                        <i class="fas fa-sliders-h"></i> Adjustments
                    </a>
                </li>

                <li class="ems-submenu">
                    <a href="{{ route('payroll.reports') }}">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </li>

            @endif

        </ul>
    </li>

                {{-- = SYSTEM = --}}
                <li class="ems-menu-title">SYSTEM</li>

                <!-- ACTIVITY LOGS -->
                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                    <li class="ems-menu-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        <a href="{{ route('activity-logs.index') }}">
                            <i class="fas fa-clock-rotate-left"></i>
                            <span>Activity Logs</span>
                        </a>
                    </li>
                @endif

                <!-- REPORTS -->
                <li class="ems-menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <a href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-column"></i>
                        <span>Reports</span>
                    </a>
                </li>

                <!-- SETTINGS -->
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
                            if(isset($pendingAdmin)) {
                                $pendingCount += $pendingAdmin;
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
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
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
                    @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif
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

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
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

        // Auto-Open Dropdown if Submenu is Active
        document.querySelectorAll('.ems-dropdown').forEach(function(dropdown) {
            const activeSubmenu = dropdown.querySelector('.ems-submenu a.active');
            if (activeSubmenu) {
                dropdown.classList.add('open');
                const arrow = dropdown.querySelector('.ems-arrow');
                if (arrow) {
                    arrow.classList.add('rotated');
                }
            }
        });

        // Highlight Active Submenu Link
        const currentPath = window.location.pathname;

        document.querySelectorAll('.ems-submenu a').forEach(function(link) {
            try {
                const linkPath = new URL(link.href).pathname;
                if (linkPath === currentPath) {
                    link.classList.add('active');
                    const parentDropdown = link.closest('.ems-dropdown');
                    if (parentDropdown) {
                        parentDropdown.classList.add('open');
                        const arrow = parentDropdown.querySelector('.ems-arrow');
                        if (arrow) {
                            arrow.classList.add('rotated');
                        }
                    }
                }
            } catch (e) {
            }
        });

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }, 5000);
        });

    });
</script>

@stack('scripts')
</body>
</html>