  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="{{ route('home') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('front-assets/img/logo.png') }}" alt="">
            {{-- <span class="sitename">College</span> --}}
          </a>
          <div class="footer-contact pt-3">
            <p></p>
            <p>Chroma By BPC</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+76 661 3376</span></p>
            <p><strong>Email:</strong> <span>chromabybpc@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            {{-- <a href=""><i class="bi bi-twitter-x"></i></a> --}}
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('frontend.classes') }}">About us</a></li>
            <li><a href="{{ route('frontend.sessions') }}">Services</a></li>
            <li><a href="{{ route('frontend.contact') }}">Contact</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="{{ route('frontend.classes') }}">Classes</a></li>
            <li><a href="{{ route('frontend.sessions') }}">Sessions</a></li>
            <li><a href="{{ route('frontend.events') }}">Events</a></li>
            <li><a href="{{ route('frontend.register') }}">Registration</a></li>
            {{-- <li><a href="{{ route('frontend.blogs') }}">Blogs</a></li> --}}
          </ul>
        </div>

        {{-- <div class="col-lg-2 col-md-3 footer-links">
          <h4>Hic solutasetp</h4>
          <ul>
            <li><a href="#">Molestiae accusamus iure</a></li>
            <li><a href="#">Excepturi dignissimos</a></li>
            <li><a href="#">Suscipit distinctio</a></li>
            <li><a href="#">Dilecta</a></li>
            <li><a href="#">Sit quas consectetur</a></li>
          </ul>
        </div> --}}

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            {{-- <li><a href="{{ route('frontend.about') }}">About Us</a></li>
            <li><a href="{{ route('frontend.courses') }}">Courses</a></li> --}}
            <li><a href="{{ route('frontend.events') }}">Events</a></li>
            <li><a href="{{ route('frontend.contact') }}">Contact Us</a></li>
          </ul>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Chroma By BPC</strong> <span>All Rights Reserved</span></p>
      {{-- <div class="credits"> --}}
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        {{-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> | <a href="https://bootstrapmade.com/tools/">DevTools</a> --}}
      {{-- </div> --}}
    </div>

  </footer>