<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AccessCare Connect - Medical Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-black: #000000;
            --accent-yellow: #DFFF00;
            --primary-blue: #000080;
            --primary-green: #008000;
        }

        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: var(--primary-black) !important;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background-color: #000066;
            border-color: #000066;
        }

        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .btn-success:hover {
            background-color: #006600;
            border-color: #006600;
        }

        .badge.bg-success {
            background-color: var(--primary-green) !important;
        }

        .badge.bg-warning {
            background-color: var(--accent-yellow) !important;
            color: var(--primary-black) !important;
        }

        .badge.bg-info {
            background-color: var(--primary-blue) !important;
            color: white !important;
        }

        .card-header {
            background-color: var(--primary-black);
            color: white;
        }

        .table {
            border-color: var(--primary-black);
        }

        .table thead {
            background-color: var(--primary-black);
            color: white;
        }

        .modal-header {
            background-color: var(--primary-black);
            color: white;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .alert-success {
            background-color: var(--primary-green);
            color: white;
            border: none;
        }

        .alert-info {
            background-color: var(--primary-blue);
            color: white;
            border: none;
        }

        .nav-link {
            color: var(--accent-yellow) !important;
        }

        .nav-link:hover {
            color: white !important;
        }

        .navbar-brand {
            color: var(--accent-yellow) !important;
        }

        .navbar-brand:hover {
            color: white !important;
        }

        /* Custom button styles */
        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-blue);
            color: white;
        }

        /* Status badges */
        .status-pending {
            background-color: var(--accent-yellow);
            color: var(--primary-black);
        }

        .status-approved {
            background-color: var(--primary-green);
            color: white;
        }

        .status-suggested {
            background-color: var(--primary-blue);
            color: white;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <span class="fw-bold">AccessCare</span>
                <span class="fw-light">Connect</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Logout</button>
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>