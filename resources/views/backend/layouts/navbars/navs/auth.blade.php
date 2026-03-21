<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
        
        <!-- User -->
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
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') ?? '#' }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-danger" onclick="return confirmLogout();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

