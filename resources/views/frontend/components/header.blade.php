  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-end">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{ asset('front-assets/img/logo.png') }}" alt="">
        {{-- <h1 class="sitename">Chroma By BPC</h1> --}}
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="active">Home</a></li>
          <li><a href="{{ route('classes') }}">Classes</a></li>
          <li><a href="news.html">Sessions</a></li>
          <li><a href="{{ route('events') }}">Events</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>