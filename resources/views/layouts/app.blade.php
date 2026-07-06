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
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-briefcase"></i> Employee Management
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <div class="navbar-nav ms-auto">

                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </a>

                    <a class="nav-link {{ request()->routeIs('employees.*') ? 'active fw-bold' : '' }}"
                        href="{{ route('employees.index') }}">
                        <i class="fas fa-users"></i> Employees
                    </a>

                    <a class="nav-link {{ request()->routeIs('departments.*') ? 'active fw-bold' : '' }}"
                        href="{{ route('departments.index') }}">
                        <i class="fas fa-building"></i> Departments
                    </a>

                </div>

            </div>

        </div>
    </nav>

    <div class="container mt-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif

        @yield('content')

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>