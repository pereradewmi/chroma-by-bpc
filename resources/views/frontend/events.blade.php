@extends("frontend.components.layout")
@section("description", "Discover upcoming events at CHROMA LIFESTYLE AND CONCEPT STORE. Join us for exciting activities, workshops, and community gatherings.")
@section("keywords", "CHROMA LIFESTYLE AND CONCEPT STORE, events, workshops, community gatherings, activities")
@section("main")

  <main class="main">

    <section id="events-extended" class="events-extended section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row">
          <div class="col-lg-8">
            <div class="events-list">
              @forelse($events as $index => $event)
              <div class="event-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                @if($event->dateFrom)
                <div class="event-date">
                  <span class="day">{{ $event->dateFrom->format('d') }}</span>
                  <span class="month">{{ $event->dateFrom->format('M') }}</span>
                </div>
                @endif
                <div class="event-content">
                  <h3 class="event-title">{{ $event->eName }}</h3>
                  <div class="event-meta">
                    @if($event->dateFrom && $event->dateTo)
                      <span><i class="bi bi-calendar"></i> {{ $event->dateFrom->format('M d, Y') }} - {{ $event->dateTo->format('M d, Y') }}</span>
                    @endif
                  </div>
                  <p class="event-description">{{ $event->eDescription }}</p>
                  <a href="{{ route('frontend.event-details', $event->eID) }}" class="btn-event-details">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
              @empty
              <div class="alert alert-info">
                <p>No events found.</p>
              </div>
              @endforelse
            </div>
          </div>

          <div class="col-lg-4">
            <div class="events-sidebar">
              <div class="sidebar-item upcoming-events" data-aos="fade-up" data-aos-delay="200">
                <h4>Upcoming Featured Events</h4>
                @if($latestEvent)
                <div class="featured-event">
                  <img src="{{ $latestEvent->getEventImage() }}" alt="Event" class="img-fluid">
                  <div class="featured-event-details">
                    <h5>{{ $latestEvent->eName }}</h5>
                    @if($latestEvent->dateFrom && $latestEvent->dateTo)
                      <span class="event-date"><i class="bi bi-calendar"></i> {{ $latestEvent->dateFrom->format('M d, Y') }} - {{ $latestEvent->dateTo->format('M d, Y') }}</span>
                    @endif
                    <a href="{{ route('frontend.event-details', $latestEvent->eID) }}" class="btn-sm btn-register">Learn More</a>
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