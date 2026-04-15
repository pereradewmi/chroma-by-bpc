@extends("frontend.components.layout")
@section("description", "{{ $event->eDescription }}")
@section("keywords", "CHROMA LIFESTYLE AND CONCEPT STORE, events, {{ $event->eName }}, event details, workshops, community gatherings, activities")
@section("main")

  <main class="main">

    <section id="event" class="event section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row">
          <div class="col-lg-8">
            <div class="event-image mb-4" data-aos="fade-up">
              <img src="{{ $event->getEventImage() }}" alt="Event" class="img-fluid rounded">
            </div>
            <div class="event-meta mb-4" data-aos="fade-up" data-aos-delay="100">
              <div class="row g-3">
                @if($event->dateFrom)
                <div class="col-md-6">
                  <div class="meta-item">
                    <i class="bi bi-calendar-date"></i>
                    <span>
                      {{ $event->dateFrom->format('m/d/Y') }}
                      @if($event->dateTo)
                        - {{ $event->dateTo->format('m/d/Y') }}
                      @endif
                    </span>
                  </div>
                </div>
                @endif
              </div>
            </div>
            <div class="event-content" data-aos="fade-up" data-aos-delay="200">
              <h2>{{ $event->eName }}</h2>
              @if($event->eDescription)
              <p>
                {{ $event->eDescription }}
              </p>
              @endif
            </div>
          </div>

          <div class="col-lg-4">
            <div class="events-sidebar">  
              <div class="sidebar-item upcoming-events" data-aos="fade-up" data-aos-delay="200">
                <h4 style="font-size: 1.3rem; font-weight: 600; margin-bottom: 1.5rem; color: #000d23; border-bottom: 3px solid #002f64; padding-bottom: 0.8rem;">Upcoming Featured Events</h4>
                @if($latestEvent)
                <div class="featured-event" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); transition: all 0.3s ease;">
                  <div style="position: relative; overflow: hidden; max-height: 200px;">
                    <img src="{{ $latestEvent->getEventImage() }}" alt="Event" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover; transition: transform 0.3s ease;">
                  </div>
                  <div class="featured-event-details" style="padding: 1.5rem; position: relative; z-index: 1;">
                    <h5 style="font-size: 1.1rem; font-weight: 600; color: #1f4788; margin-bottom: 0.8rem; line-height: 1.4;">{{ $latestEvent->eName }}</h5>
                    @if($latestEvent->dateFrom && $latestEvent->dateTo)
                      <span class="event-date" style="display: flex; align-items: center; gap: 0.5rem; color: #0d66cc; font-size: 0.9rem; margin-bottom: 1.2rem;">
                        <i class="bi bi-calendar" style="font-size: 1rem; color: #0d66cc;"></i> 
                        <span>{{ $latestEvent->dateFrom->format('M d, Y') }} - {{ $latestEvent->dateTo->format('M d, Y') }}</span>
                      </span>
                    @endif
                    <a href="{{ route('frontend.event-details', $latestEvent->eID) }}" class="btn-sm btn-register" style="display: inline-block; background: linear-gradient(135deg, #01152b 0%, #0b3d6e 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: all 0.3s ease; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(13, 102, 204, 0.3);">Learn More</a>
                  </div>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>
@endsection