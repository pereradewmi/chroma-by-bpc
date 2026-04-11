<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ 'Chroma By BPC ' }}</title>
        <!-- Favicon -->
        <link href="{{ asset('front-assets/img/logo.png') }}" rel="icon" type="image/png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <!-- Extra details for Live View on GitHub Pages -->

        <!-- Icons -->
        <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        
        <!-- Custom Sidebar Styles -->
                <style>
            /* Main content adjustment for fixed footer */
            .main-content {
                margin-left: 250px;
                height: 100vh;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .content-wrapper {
                flex-grow: 1;
                overflow-y: auto;
                overflow-x: hidden;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .main-content {
                    margin-left: 0 !important;
                }
            }

            /* Logout section styling */
            .navbar-nav hr {
                border-color: #e9ecef;
                width: 80%;
                margin: 1rem auto;
            }

            /* Brand wrapper styling */
            .navbar-brand-wrapper {
                padding: 1rem;
                border-bottom: 2px solid #04415f;
                margin-bottom: 1rem;
                background: #f8f9fa;
            }

            /* Online status indicator */
            .avatar-indicator {
                position: absolute;
                bottom: 0;
                right: 0;
                width: 12px;
                height: 12px;
                border: 2px solid #fff;
                border-radius: 50%;
            }

            /* Improved dropdown styling */
            .dropdown-item.text-danger:hover {
                background-color: #f8d7da;
                color: #721c24;
            }
            
            /* Navigation link hover effects */
            .nav-link:hover {
                background-color: rgba(0, 0, 0, 0.05);
                border-radius: 0.375rem;
                transition: all 0.3s ease;
            }

            /*--------------------------------------------------------------
            # Chroma By BPC - Navy Blue Theme
            =================================================================*/

            /* Primary Color Override - Navy Blue */
            :root {
                --bs-primary: #04415f !important;
                --bs-primary-rgb: 4, 65, 95 !important;
            }

            /* Sidebar styling */
            #sidenav-main {
                background: #ffffff !important;
                border-right: 2px solid #e9ecef;
            }

            #sidenav-main .navbar-brand {
                color: #04415f !important;
                font-weight: 700;
                font-size: 1.2rem;
            }

            #sidenav-main .nav-link {
                color: #04415f !important;
                transition: all 0.3s ease;
            }

            #sidenav-main .nav-link:hover {
                color: #04415f !important;
                background: rgba(4, 65, 95, 0.08) !important;
                border-left: 3px solid #04415f !important;
            }

            #sidenav-main .nav-link.active {
                background: rgba(4, 65, 95, 0.1) !important;
                color: #04415f !important;
                border-left: 3px solid #04415f !important;
                font-weight: 600;
            }

            /* Top Navbar */
            .navbar.navbar-main {
                background: #ffffff;
                box-shadow: 0 2px 8px rgba(4, 65, 95, 0.1);
            }

            /* Top navbar styling */
            .navbar.navbar-top {
                background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important;
                z-index: 1050 !important;
                position: relative !important;
            }

            .navbar.navbar-top .navbar-brand {
                color: #ffffff !important;
            }

            .navbar.navbar-top .nav-link {
                color: #ffffff !important;
            }

            .navbar.navbar-horizontal {
                background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important;
            }

            /* Buttons */
            .btn {
                padding: 0.7rem 1.3rem !important;
                border-radius: 12px !important;
                font-weight: 600;
                font-size: 0.95rem;
                line-height: 1.2;
                box-shadow: 0 6px 16px rgba(4, 65, 95, 0.14);
                transition: all 0.25s ease;
            }

            .btn:hover,
            .btn:focus {
                transform: translateY(-1px);
                box-shadow: 0 10px 22px rgba(4, 65, 95, 0.2) !important;
            }

            .btn-sm {
                padding: 0.55rem 1rem !important;
                border-radius: 10px !important;
            }

            .btn-lg {
                padding: 0.9rem 1.65rem !important;
                border-radius: 14px !important;
            }

            .btn-primary {
                background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important;
                border: none !important;
                box-shadow: 0 2px 8px rgba(4, 65, 95, 0.2) !important;
                color: #ffffff !important;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #032d42 0%, #052a5c 100%) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 16px rgba(4, 65, 95, 0.3) !important;
            }

            .btn-primary:focus {
                box-shadow: 0 0 0 0.2rem rgba(4, 65, 95, 0.25) !important;
            }

            .btn-primary:active {
                background: linear-gradient(135deg, #021d2c 0%, #041c3f 100%) !important;
            }

            /* Badge styling */
            .badge.bg-primary {
                background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important;
            }

            /* Card styling */
            .card {
                border: 1px solid #e9ecef;
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 4px 16px rgba(4, 65, 95, 0.1) !important;
                border-color: #04415f;
            }

            .card-header {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                border-bottom: 2px solid #04415f !important;
            }

            /* Form controls */
            .form-control:focus {
                border-color: #04415f !important;
                box-shadow: 0 0 0 0.2rem rgba(4, 65, 95, 0.15) !important;
            }

            .form-check-input:checked {
                background-color: #04415f !important;
                border-color: #04415f !important;
            }

            .form-check-input:focus {
                border-color: #04415f !important;
                box-shadow: 0 0 0 0.2rem rgba(4, 65, 95, 0.15) !important;
            }

            /* Tables */
            .table-hover tbody tr:hover {
                background: rgba(4, 65, 95, 0.05) !important;
            }

            .table thead th {
                background: #f8f9fa;
                color: #04415f;
                font-weight: 600;
                border-color: #dee2e6;
            }

            /* Rounded + shadow tables (backend-wide) */
            .main-content .table-responsive,
            .main-content table.table {
                border-radius: 12px;
                background: #ffffff;
                box-shadow: 0 8px 24px rgba(4, 65, 95, 0.12);
            }

            .main-content .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
            }

            .main-content .table-responsive > table.table {
                margin-bottom: 0;
                min-width: 100%;
            }

            .main-content .table-responsive table.table {
                border-radius: 0;
                box-shadow: none;
            }

            /* Pagination */
            .card-footer .pagination {
                margin-bottom: 0;
                gap: 4px;
            }

            .card-footer .pagination .page-link {
                color: #0d6efd;
                background-color: #ffffff;
                border: 1px solid #dee2e6;
                border-radius: 4px !important;
                padding: 0.35rem 0.7rem;
                line-height: 1.2;
                box-shadow: none !important;
            }

            .card-footer .pagination .page-item.active .page-link {
                color: #ffffff;
                background-color: #0d6efd;
                border-color: #0d6efd;
                font-weight: 600;
            }

            .card-footer .pagination .page-item.disabled .page-link {
                color: #6c757d;
                background-color: #ffffff;
                border-color: #dee2e6;
                opacity: 1;
            }

            .card-footer .pagination .page-link:hover {
                color: #0d6efd;
                border-color: #0d6efd;
            }

            .card-footer .pagination .page-link:focus {
                box-shadow: none;
            }

            /* Alerts */
            .alert-primary {
                background: linear-gradient(135deg, #e8f1f6 0%, #f0f5f9 100%) !important;
                border: 1px solid #04415f !important;
                color: #032d42 !important;
            }

            /* Progress bar */
            .progress-bar {
                background: linear-gradient(90deg, #04415f 0%, #064d7a 100%) !important;
            }

            /* Dropdown */
            .dropdown-menu {
                border: 1px solid #e9ecef;
                box-shadow: 0 4px 12px rgba(4, 65, 95, 0.1) !important;
            }

            .dropdown-item.active,
            .dropdown-item:active {
                background-color: #04415f !important;
            }

            .dropdown-item:hover {
                background-color: rgba(4, 65, 95, 0.1) !important;
            }

            /* Nav pills */
            .nav-pills .nav-link.active {
                background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important;
            }

            /* List group */
            .list-group-item.active {
                background-color: #04415f !important;
                border-color: #04415f !important;
            }

            /* Background colors and gradients */
            .bg-primary {
                background-color: #04415f !important;
            }

            .bg-gradient-primary {
                background: linear-gradient(14deg, #272727 0%, #00568c 100%) !important;
                /* background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important; */
            }

            /* Text utilities */
            .text-primary {
                color: #04415f !important;
            }

            .text-primary-emphasis {
                color: #032d42 !important;
            }

            /* Links */
            a {
                color: #04415f;
                transition: all 0.3s ease;
            }

            a:hover {
                color: #032d42;
            }

            /* Headers */
            h1, h2, h3, h4, h5, h6 {
                color: #011e2c;
            }
</style>
    </head>
    <body class="{{ $class ?? '' }}">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @include('backend.layouts.navbars.sidebar')
        
        <div class="main-content">
            <div class="content-wrapper">
                @include('backend.layouts.navbars.navbar')
                @yield('content')
            </div>
            
            <div class="container-fluid mt-auto mb-3">
                @include('backend.layouts.footers.auth')
            </div>
        </div>

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- SweetAlert2 for better alerts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        @stack('js')
        
        <!-- Argon JS -->
        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>

        <script>
            function confirmLogout() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will be logged out of your account.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#04415f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
                return false;
            }
        </script>

        </body>
</html>



