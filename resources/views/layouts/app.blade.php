<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Management</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
   
    <!-- Navbar -->
   
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid px-4">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-laptop"></i> Employee Management
            </a>
            
            <!-- Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                   
                    <!-- 1. Dashboard - All Roles -->
                   
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                    </li>

                   
                    <!-- 2. Departments - Admin & Manager -->
                   
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" 
                                   href="{{ route('departments.index') }}">
                                    <i class="fas fa-building"></i> Departments
                                </a>
                            </li>
                        @endif
                    @endauth

                   
                    <!-- 3. Employees - All Roles -->
                   
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" 
                           href="{{ route('employees.index') }}">
                            <i class="fas fa-users"></i> Employees
                        </a>
                    </li>

                   
                    <!-- 4. Attendance - All Roles -->
                   
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" 
                           href="{{ route('attendance.index') }}">
                            <i class="fas fa-calendar-check"></i> Attendance
                        </a>
                    </li>

                   
                    <!-- 5. ASSETS DROPDOWN - Admin & HR -->
                   
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('asset-master.*') || request()->routeIs('asset-issue.*') || request()->routeIs('asset-return.*') ? 'active' : '' }}" 
                                   href="#" id="assetsDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-boxes"></i> Assets
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-master.index') }}">
                                            <i class="fas fa-box"></i> Asset Master
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-issue.index') }}">
                                            <i class="fas fa-arrow-right"></i> Issue Asset
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-return.index') }}">
                                            <i class="fas fa-arrow-left"></i> Return Asset
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-issue.report') }}">
                                            <i class="fas fa-file-alt"></i> Issued Report
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-return.report') }}">
                                            <i class="fas fa-file-alt"></i> Returned Report
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-issue.export-csv') }}">
                                            <i class="fas fa-file-csv"></i> Export Issued CSV
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('asset-return.export-csv') }}">
                                            <i class="fas fa-file-csv"></i> Export Returned CSV
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endauth

                   
                    <!-- 6. LEAVE DROPDOWN - All Roles -->
                   
                    @auth
                        @php
                            $user = Auth::user();
                            $isEmployee = \App\Models\Employee::where('email', $user->email)->exists();
                        @endphp
                        
                        @if($isEmployee || $user->isAdmin() || $user->isHR() || $user->isManager())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('leave.*') || request()->routeIs('leave-types.*') ? 'active' : '' }}" 
                                   href="#" id="leaveDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-calendar-alt"></i> Leave
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- Employee Section -->
                                    @if($isEmployee || $user->isAdmin() || $user->isHR() || $user->isManager())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.apply-form') }}">
                                                <i class="fas fa-pen"></i> Apply Leave
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.my-leaves') }}">
                                                <i class="fas fa-list"></i> My Leaves
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.balance') }}">
                                                <i class="fas fa-balance-scale"></i> Leave Balance
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif

                                    <!-- Manager Section -->
                                    @if($user->isManager() || $user->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.manager.dashboard') }}">
                                                <i class="fas fa-chart-bar"></i> Manager Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.manager.pending') }}">
                                                <i class="fas fa-clock"></i> Pending Requests
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif

                                    <!-- HR Section -->
                                    @if($user->isHR() || $user->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.hr.dashboard') }}">
                                                <i class="fas fa-chart-line"></i> HR Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.hr.pending') }}">
                                                <i class="fas fa-hourglass-half"></i> Pending HR
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.hr.all') }}">
                                                <i class="fas fa-list-ul"></i> All Requests
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif

                                    <!-- Admin Section -->
                                    @if($user->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave-types.index') }}">
                                                <i class="fas fa-tags"></i> Leave Types
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leave.admin.balances') }}">
                                                <i class="fas fa-calculator"></i> Leave Balances
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endauth

                   
                    <!-- 7. Activity Logs - Only Admin -->
                   
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" 
                                   href="{{ route('activity-logs.index') }}">
                                    <i class="fas fa-history"></i> Activity Logs
                                </a>
                            </li>
                        @endif
                    @endauth

                </ul>
                
               
                <!-- User Dropdown - Right Side -->
               
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <span class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="role-badge">{{ Auth::user()->role }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog"></i> Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

   
    <!-- Main Content -->
   
    <div class="container-fluid px-4 mt-4">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>