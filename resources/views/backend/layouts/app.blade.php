<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CROMA Dashboard') }}</title>
        <!-- Favicon -->
        <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
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
            /* Sidebar Collapse Styles */
            #sidenav-main {
                transition: all 0.3s ease-in-out;
                width: 250px;
            }
            
            #sidenav-main.collapsed {
                width: 80px;
            }
            
            #sidenav-main.collapsed .navbar-brand-img,
            #sidenav-main.collapsed .nav-link span,
            #sidenav-main.collapsed .navbar-brand {
                opacity: 0;
                visibility: hidden;
            }
            
            #sidenav-main.collapsed .nav-link {
                text-align: center;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            #sidenav-main.collapsed .nav-link i {
                margin-right: 0 !important;
            }
            
            /* Main content adjustment */
            .main-content {
                transition: margin-left 0.3s ease-in-out;
                margin-left: 250px;
            }
            
            .main-content.sidebar-collapsed {
                margin-left: 80px;
            }
            
            /* Toggle button styles */
            #sidebar-toggle {
                border: none;
                background: transparent;
                color: #6c757d;
                transition: all 0.3s ease;
            }
            
            #sidebar-toggle:hover {
                color: #007bff;
                background: rgba(0, 123, 255, 0.1);
            }
            
            /* Responsive adjustments */
            @media (max-width: 768px) {
                .main-content {
                    margin-left: 0 !important;
                }
                #sidenav-main {
                    width: 250px;
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
                border-bottom: 1px solid #e9ecef;
                margin-bottom: 1rem;
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
        </style>
    </head>
    <body class="{{ $class ?? '' }}">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @include('backend.layouts.navbars.sidebar')
        
        <div class="main-content">
            @include('backend.layouts.navbars.navbar')
            @yield('content')
        </div>

        @include('backend.layouts.footers.auth')

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- SweetAlert2 for better alerts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        @stack('js')
        
        <!-- Argon JS -->
        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
        
        <!-- Custom Sidebar Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebar = document.getElementById('sidenav-main');
                const mainContent = document.querySelector('.main-content');
                
                // Check for saved sidebar state
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('sidebar-collapsed');
                    sidebarToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
                }
                
                // Toggle sidebar on button click
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function() {
                        sidebar.classList.toggle('collapsed');
                        mainContent.classList.toggle('sidebar-collapsed');
                        
                        // Save state to localStorage
                        const isCollapsed = sidebar.classList.contains('collapsed');
                        localStorage.setItem('sidebarCollapsed', isCollapsed);
                        
                        // Change toggle icon
                        if (isCollapsed) {
                            sidebarToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
                            sidebarToggle.title = 'Expand Sidebar';
                        } else {
                            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
                            sidebarToggle.title = 'Collapse Sidebar';
                        }
                    });
                }
                
                // Add hover effect for collapsed sidebar
                sidebar.addEventListener('mouseenter', function() {
                    if (sidebar.classList.contains('collapsed')) {
                        sidebar.style.width = '250px';
                        sidebar.querySelector('.navbar-brand-img').style.opacity = '1';
                        sidebar.querySelector('.navbar-brand-img').style.visibility = 'visible';
                        
                        // Show nav text
                        const navLinks = sidebar.querySelectorAll('.nav-link span, .navbar-brand');
                        navLinks.forEach(link => {
                            link.style.opacity = '1';
                            link.style.visibility = 'visible';
                        });
                    }
                });
                
                sidebar.addEventListener('mouseleave', function() {
                    if (sidebar.classList.contains('collapsed')) {
                        sidebar.style.width = '80px';
                        sidebar.querySelector('.navbar-brand-img').style.opacity = '0';
                        sidebar.querySelector('.navbar-brand-img').style.visibility = 'hidden';
                        
                        // Hide nav text
                        const navLinks = sidebar.querySelectorAll('.nav-link span, .navbar-brand');
                        navLinks.forEach(link => {
                            link.style.opacity = '0';
                            link.style.visibility = 'hidden';
                        });
                    }
                });
            });
            
            
            // Professional logout confirmation with SweetAlert2
            function confirmLogout() {
                Swal.fire({
                    title: 'Are you sure you want to logout?',
                    text: "You will be redirected to the login page.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Yes, Logout',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    reverseButtons: true,
                    allowOutsideClick: false,
                    allowEscapeKey: true,
                    focusCancel: true,
                    customClass: {
                        popup: 'animated fadeInDown',
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading while processing logout
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait while we sign you out.',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'animated fadeIn'
                            },
                            didOpen: () => {
                                Swal.showLoading();
                                // Submit logout form after short delay for user feedback
                                setTimeout(() => {
                                    document.getElementById('logout-form').submit();
                                }, 400);
                            }
                        });
                    }
                });
                
                return false;
            }
        </script>
    </body>
</html>