<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white d-flex flex-column" id="sidenav-main">
    <div class="container-fluid d-flex flex-column h-100 p-0">

        <div class="navbar-brand-wrapper d-flex align-items-center justify-content-between p-3">
            <a class="navbar-brand pt-0 m-0 d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('front-assets') }}/img/logo.png" class="navbar-brand-img" alt="Chroma Logo" style="max-height: 40px;">  
            </a>
            
        </div>
        
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
                        <i class="fas fa-chalkboard-teacher text-primary"></i>
                        {{ __('Teachers') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'classes') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                        <i class="fas fa-school text-primary"></i>
                        {{ __('Classes') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'sessions') ? 'active' : '' }}" href="{{ route('sessions.index') }}">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        {{ __('Sessions') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'events') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="fas fa-star text-primary"></i>
                        {{ __('Events') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'admin.calendar') ? 'active' : '' }}" href="{{ route('admin.calendar.index') }}">
                        <i class="fas fa-calendar text-primary"></i>
                        {{ __('Calendar') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'admin.images') ? 'active' : '' }}" href="{{ route('admin.images.index') }}">
                        <i class="fas fa-images text-primary"></i>
                        {{ __('Gallery') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(Route::currentRouteName(), 'admin.image-categories') ? 'active' : '' }}" href="{{ route('admin.image-categories.index') }}">
                        <i class="fas fa-tags text-primary"></i>
                        {{ __('Category') }}
                    </a>
                </li>
                @php
                    $paymentsOpen = str_contains(Route::currentRouteName(), 'payments.')
                        || str_contains(Route::currentRouteName(), 'teacher-payments.')
                        || str_contains(Route::currentRouteName(), 'instructor-payments.');
                @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $paymentsOpen ? 'active' : '' }}" href="#paymentsMenu" data-toggle="collapse" role="button" aria-expanded="{{ $paymentsOpen ? 'true' : 'false' }}" aria-controls="paymentsMenu">
                        <span>
                            <i class="fas fa-money-bill-wave text-primary"></i>
                            {{ __('Payments') }}
                        </span>
                        <i class="fas fa-chevron-right small {{ $paymentsOpen ? 'rotate-90' : '' }}"></i>
                    </a>
                </li>
                <div class="collapse {{ $paymentsOpen ? 'show' : '' }}" id="paymentsMenu">
                    <ul class="navbar-nav px-3">
                        <li class="nav-item pl-4 m-1">
                            <a class="nav-link {{ str_contains(Route::currentRouteName(), 'payments.') && !str_contains(Route::currentRouteName(), 'teacher-') && !str_contains(Route::currentRouteName(), 'instructor-') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                {{ __('Student') }}
                            </a>
                        </li>
                        <li class="nav-item pl-4 m-1">
                            <a class="nav-link {{ str_contains(Route::currentRouteName(), 'teacher-payments.') ? 'active' : '' }}" href="{{ route('teacher-payments.index') }}">
                                {{ __('Teacher') }}
                            </a>
                        </li>
                        <li class="nav-item pl-4 m-1">
                            <a class="nav-link {{ str_contains(Route::currentRouteName(), 'instructor-payments.') ? 'active' : '' }}" href="{{ route('instructor-payments.index') }}">
                                {{ __('Instructor') }}
                            </a>
                        </li>
                    </ul>
                </div>
                @php
                    $reportsOpen = str_contains(Route::currentRouteName(), 'reports.index')
                        || str_contains(Route::currentRouteName(), 'reports.user-payments');
                @endphp
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $reportsOpen ? 'active' : '' }}" href="#reportsMenu" data-toggle="collapse" role="button" aria-expanded="{{ $reportsOpen ? 'true' : 'false' }}" aria-controls="reportsMenu">
                        <span>
                            <i class="fas fa-chart-bar text-primary"></i>
                            {{ __('Reports') }}
                        </span>
                        <i class="fas fa-chevron-right small {{ $reportsOpen ? 'rotate-90' : '' }}"></i>
                    </a>
                </li>
                <div class="collapse {{ $reportsOpen ? 'show' : '' }}" id="reportsMenu">
                    <ul class="navbar-nav px-3">
                        <li class="nav-item pl-4 m-1">
                            <a class="nav-link {{ Route::currentRouteName() === 'reports.index' ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                {{ __('Booking') }}
                            </a>
                        </li>
                        <li class="nav-item pl-4 m-1">
                            <a class="nav-link {{ Route::currentRouteName() === 'reports.user-payments' ? 'active' : '' }}" href="{{ route('reports.user-payments') }}">
                                {{ __('Users') }}
                            </a>
                        </li>
                    </ul>
                </div>

            </ul>
            
            <!-- Logout Section at bottom -->
            <div class="mt-auto px-3 pb-3">
                <hr class="my-2">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="return confirmLogout();">
                            <i class="fas fa-sign-out-alt text-primary"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

