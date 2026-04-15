  <footer id="footer" class="footer position-relative light-background" style="padding: 5px 0;">
    <div class="container footer-top">
      <div class="row">
        <div class="col-lg-4 footer-about">
          <a href="{{ route('home') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('front-assets/img/logo.png') }}" alt="CHROMA LIFESTYLE AND CONCEPT STORE" >
          </a>
          <div class="footer-contact pt-3">
            <p><strong>Phone :</strong> <span>+94 76 661 3376</span></p>
            <p><strong>Email :</strong> <span>chromabybpc@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="" ><i class="bi bi-youtube"></i></a> 
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
          </div>
        </div>
        <div class="col-lg-2  footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('frontend.contact') }}">Contact Us</a></li>
          </ul>
        </div>
        <div class="col-lg-2  footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="{{ route('frontend.classes') }}">Classes</a></li>
            <li><a href="{{ route('frontend.sessions') }}">Sessions</a></li>
            <li><a href="{{ route('frontend.events') }}">Events</a></li>
          </ul>
        </div>
        <div class="col-lg-2 footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="{{ route('frontend.gallery') }}">Gallery</a></li>
            <li><a href="{{ route('Appointment.index') }}">Appointment</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-3">
      <p style="margin: 0;">© <span>Copyright</span> <strong class="px-1 sitename">CHROMA</strong> <span>All Rights Reserved</span></p>
    </div>
</footer>