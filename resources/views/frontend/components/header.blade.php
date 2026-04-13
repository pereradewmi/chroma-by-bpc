  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-end">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
        <img src="{{ asset('front-assets/img/logo.png') }}" alt="Chroma Logo">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ route('frontend.classes') }}" class="{{ request()->routeIs('frontend.classes*') ? 'active' : '' }}">Classes</a></li>
          <li><a href="{{ route('frontend.sessions') }}" class="{{ request()->routeIs('frontend.sessions*') ? 'active' : '' }}">Sessions</a></li>
          <li><a href="{{ route('frontend.events') }}" class="{{ request()->routeIs('frontend.events*') ? 'active' : '' }}">Events</a></li>
          <li><a href="{{ route('frontend.gallery') }}" class="{{ request()->routeIs('frontend.gallery*') ? 'active' : '' }}">Gallery</a></li>
          <li><a href="{{ route('Appointment.index') }}" class="{{ request()->routeIs('Appointment.*') ? 'active' : '' }}">Appointment</a></li>
          <li><a href="{{ route('frontend.contact') }}" class="{{ request()->routeIs('frontend.contact*') ? 'active' : '' }}">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>