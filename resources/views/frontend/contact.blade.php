@extends("frontend.components.layout")
@section("title", "Contact Us | Chroma By BPC")
@section("description", "Discover our modern campus facilities at Chroma By BPC. Explore academic buildings, student services, and recreational spaces.")
@section("keywords", "Chroma By BPC, campus facilities, academic buildings, student services, recreational spaces")
@section("main")
  <main class="main">

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="contact-main-wrapper">
          <div class="map-wrapper">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d506986.52351414727!2d79.59664412906386!3d6.910656146308238!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2ef1004efe67b%3A0xa41b8c76433a2690!2sCHROMA%20BY%20BANDULA%20PAINT%20CENTRE!5e0!3m2!1sen!2slk!4v1772434622445!5m2!1sen!2slk" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>

          <div class="contact-content">
            <div class="contact-cards-container" data-aos="fade-up" data-aos-delay="300">
              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="contact-text">
                  <h4>Location</h4>
                  <p>357 Negombo - Colombo Main Rd, Negombo</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="contact-text">
                  <h4>Email</h4>
                  <p>chromabybpc@gmail.com</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="contact-text">
                  <h4>Call</h4>
                  <p>+1 (212) 555-7890</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-clock"></i>
                </div>
                <div class="contact-text">
                  <h4>Open Hours</h4>
                  <p>Monday-Friday: 9AM - 6PM</p>
                </div>
              </div>
            </div>

            <div class="contact-form-container" data-aos="fade-up" data-aos-delay="400">
              <h3>Get in Touch</h3>
              {{-- <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua consectetur adipiscing.</p> --}}

              <form action="{{ route('frontend.contact.send') }}" method="post">
                @csrf
                <div class="row php-email-form">
                  <div class="col-md-6 form-group">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" value="{{ old('name') }}" required="">
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" value="{{ old('email') }}" required="">
                  </div>
                </div>
                <div class="form-group mt-3 php-email-form">
                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{ old('subject') }}" required="">
                </div>
                <div class="form-group mt-3 php-email-form">
                  <textarea class="form-control" name="message" rows="5" placeholder="Message" required="">{{ old('message') }}</textarea>
                </div>

                <div class="my-3">
                  @if (session('success'))
                    <div class="sent-message d-block">{{ session('success') }}</div>
                  @endif

                  @if (session('error'))
                    <div class="error-message d-block">{{ session('error') }}</div>
                  @endif

                  @if (session('error_detail'))
                    <div class="error-message d-block" style="white-space: pre-wrap;">{{ session('error_detail') }}</div>
                  @endif

                  @if ($errors->any())
                    <div class="error-message d-block">
                      @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                      @endforeach
                    </div>
                  @endif
                </div>

                <div class="form-submit">
                  <button type="submit">Send Message</button>
                  {{-- <div class="social-links">
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                  </div> --}}
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->

  </main>

@endsection