@extends("frontend.components.layout")
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
                <a href="{{ route('Appointment.index') }}" class="btn-primary">Book an Appointment</a>
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
          @forelse(($latestEvents ?? collect()) as $latestEvent)
            <div class="event-content">
              <div class="event-date">
                <span class="day">{{ optional($latestEvent->dateFrom)->format('d') ?? '--' }}</span>
                <span class="month">{{ optional($latestEvent->dateFrom)->format('M') ?? '---' }}</span>
              </div>
              <div class="event-info">
                <h3>{{ $latestEvent->eName }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($latestEvent->eDescription), 120) }}</p>
              </div>
              <div class="event-action">
                <a href="{{ route('frontend.events') }}" class="btn-event">View More Events</a>
              </div>
            </div>
          @empty
            <div class="event-content">
              <div class="event-date">
                <span class="day">--</span>
                <span class="month">---</span>
              </div>
              <div class="event-info">
                <h3>No Upcoming Events</h3>
                <p>Stay tuned for our next exciting event.</p>
              </div>
              <div class="event-action">
                <a href="{{ route('frontend.events') }}" class="btn-event">Event Details</a>
              </div>
            </div>
          @endforelse
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
              <h2>At Chroma Lifestyle & Concept Store, </h2>
              <p>creativity isn’t just something we offer, it’s something you experience.</p>

              <div class="timeline">
                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    {{-- <h4>1965</h4> --}}
                    <p>We are a space where art, expression and learning come together for all ages. From calming pottery sessions to expressive sip & paint experiences, we create moments that allow you to unwind, explore and connect with your creative side. For those who want the best of both worlds our combined pottery + sip & paint sessions offer a unique, hands-on artistic journey.</p>
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    {{-- <h4>1982</h4> --}}
                    <p>Beyond experiences, Chroma is also a place to grow. We offer art, karate, vocal training and elocution classes for both kids and adults helping individuals build confidence, discipline and creativity in a fun and supportive environment.</p>
                    {{-- <p>Donec dignissim, odio ac imperdiet luctus, ante nisl accumsan justo, nec tempus augue mi in nulla.</p> --}}
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    {{-- <h4>1998</h4> --}}
                    <p>We also bring people together through engaging workshops and events, designed to inspire, learn and create lasting memories.</p>
                    {{-- <p>Suspendisse potenti. Nullam lacinia dictum auctor. Phasellus euismod sem at dui imperdiet, ac tincidunt mi placerat.</p> --}}
                    {{-- <p>Nulla porttitor accumsan tincidunt. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula sed magna dictum porta.</p> --}}
                  </div>
                </div>

                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    {{-- <h4>2010</h4> --}}
                    <p>At Chroma, it’s not just about creating art it’s about creating experiences, building skills and celebrating self-expression in every form.</p>
                    {{-- <p>Vestibulum ultrices magna ut faucibus sollicitudin. Sed eget venenatis enim, nec imperdiet ex.</p> --}}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="about-image" data-aos="zoom-in" data-aos-delay="300">
              <video class="img-fluid rounded w-100" autoplay muted loop playsinline>
                <source src="{{ asset('front-assets/img/home/about.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
              </video>

              {{-- <div class="mission-vision" data-aos="fade-up" data-aos-delay="400">
                <div class="mission">
                  <h3>Our Mission</h3>
                  <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula.</p>
                </div>

                <div class="vision">
                  <h3>Our Vision</h3>
                  <p>Nulla porttitor accumsan tincidunt. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula sed magna dictum porta.</p>
                </div>
              </div> --}}
            </div>
          </div>
        </div>

       
    </section><!-- /About Section -->

    
  </main>

@endsection
