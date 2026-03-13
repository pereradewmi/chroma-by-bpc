<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white d-flex flex-column" id="sidenav-main">
    <div class="container-fluid d-flex flex-column h-100 p-0">
        <!-- Sidebar Header with Toggle -->
        <div class="navbar-brand-wrapper d-flex align-items-center justify-content-between p-3">
            <!-- Brand -->
            <a class="navbar-brand pt-0 m-0 d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('front-assets') }}/img/logo.png" class="navbar-brand-img" alt="Chroma Logo" style="max-height: 40px;">
                <span class="ms-2 text-white font-weight-bold" style="font-size: 0.9rem;">Chroma</span>
            </a>
            <!-- Sidebar Toggle Button -->
            <button class="btn btn-sm btn-outline-primary d-none d-md-block" type="button" id="sidebar-toggle" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Mobile Toggler -->
        <button class="navbar-toggler d-md-none mx-3 mb-2" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible content -->
        <div class="collapse navbar-collapse flex-column h-100" id="sidenav-collapse-main">
            <!-- Navigation -->
            <ul class="navbar-nav px-3 flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'students') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <i class="fas fa-user-graduate text-primary"></i>
                        {{ __('Students') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'teachers') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                        <i class="fas fa-chalkboard-teacher text-warning"></i>
                        {{ __('Teachers') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'classes') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                        <i class="fas fa-school text-info"></i>
                        {{ __('Classes') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'sessions') ? 'active' : '' }}" href="{{ route('sessions.index') }}">
                        <i class="fas fa-calendar-alt text-danger"></i>
                        {{ __('Sessions') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'admin.calendar') ? 'active' : '' }}" href="{{ route('admin.calendar.index') }}">
                        <i class="fas fa-calendar text-success"></i>
                        {{ __('Calendar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'admin.images') ? 'active' : '' }}" href="{{ route('admin.images.index') }}">
                        <i class="fas fa-images text-info"></i>
                        {{ __('Gallery') }}
                    </a>
                </li>
            </ul>
            
            <!-- Logout Section at bottom -->
            <div class="mt-auto px-3 pb-3">
                <hr class="my-2">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="return confirmLogout();">
                            <i class="fas fa-sign-out-alt text-danger"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
