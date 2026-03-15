@extends("frontend.components.layout")
@section("title", "Chroma By BPC")
@section("description", "Welcome to Chroma By BPC, your premier destination for innovative education and transformative learning experiences. Explore our diverse programs, expert faculty, and vibrant campus life.")
@section("keywords", "Chroma By BPC, education, learning, programs, faculty, campus life, innovative education, transformative learning")
@section("main")

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="hero-wrapper hero-background">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-delay="100">
              <h1>Inspiring Excellence Through Education</h1>
              <p>Discover a wide range of exciting classes and events. Browse schedules, book your sessions, and manage your activities easily through Chroma</p>
              <p>Explore a variety of engaging sessions designed to help you learn new skills, meet new people, and enjoy meaningful experiences.</p>
              <div class="action-buttons">
                <a href="{{ route('calendar.index') }}" class="btn-primary">Book an Appointment</a>
                <a href="{{ route('frontend.events') }}" class="btn-secondary">Virtual Tour</a>
              </div>
            </div>
            <div class="col-lg-6 hero-media" data-aos="zoom-in" data-aos-delay="200">
              {{-- <img src="{{ asset('front-assets/img/home/header.jpg') }}" alt="Education" class="img-fluid main-image"> --}}
              <div class="image-overlay">
                {{-- <div class="badge-accredited">
                  <i class="bi bi-patch-check-fill"></i>
                  <span>Accredited Excellence</span>
                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>

  

      <div class="upcoming-event" data-aos="fade-up" data-aos-delay="400">
        <div class="container">
          <div class="event-content">
            <div class="event-date">
              <span class="day">15</span>
              <span class="month">NOV</span>
            </div>
            <div class="event-info">
              <h3>Annual Mega Sale</h3>
              <p>Join us to explore campus facilities, meet our faculty, and learn about scholarship opportunities.</p>
            </div>
            <div class="event-action">
              <a href="{{ route('frontend.events') }}" class="btn-event">Event Details</a> 
               {{-- <span class="countdown">Starts in 3 weeks</span>  --}}
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <div class="about-content" data-aos="fade-up" data-aos-delay="200">
              <h3>Our Story</h3>
              <h2>Educating Minds, Inspiring Hearts</h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vitae odio ac nisi tristique venenatis. Nullam feugiat ipsum vitae justo finibus, in sagittis dolor malesuada. Aenean vel fringilla est, a vulputate massa.</p>

              <div class="timeline">
                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>1965</h4>
                    <p>Etiam at tincidunt arcu. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>1982</h4>
                    <p>Donec dignissim, odio ac imperdiet luctus, ante nisl accumsan justo, nec tempus augue mi in nulla.</p>
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>1998</h4>
                    <p>Suspendisse potenti. Nullam lacinia dictum auctor. Phasellus euismod sem at dui imperdiet, ac tincidunt mi placerat.</p>
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <h4>2010</h4>
                    <p>Vestibulum ultrices magna ut faucibus sollicitudin. Sed eget venenatis enim, nec imperdiet ex.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="about-image" data-aos="zoom-in" data-aos-delay="300">
              <img src="{{ asset('front-assets/img/home/chroma.jpg') }}" alt="Campus" class="img-fluid rounded">

              <div class="mission-vision" data-aos="fade-up" data-aos-delay="400">
                <div class="mission">
                  <h3>Our Mission</h3>
                  <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula.</p>
                </div>

                <div class="vision">
                  <h3>Our Vision</h3>
                  <p>Nulla porttitor accumsan tincidunt. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula sed magna dictum porta.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

       
    </section><!-- /About Section -->

    
  </main>

@endsection
