<nav class="navbar navbar-top navbar-expand navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>   
        <ul class="navbar-nav align-items-center ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle position-relative">
                            <i class="fas fa-user-circle fa-2x text-white"></i>
                            <span class="avatar-indicator bg-success" style="bottom: 2px; right: 2px;"></span>
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm font-weight-bold text-white">{{ session('user_name') ?? 'Admin' }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <a href="#" class="dropdown-item text-danger" onclick="return confirmLogout();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

