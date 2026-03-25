@extends("frontend.components.layout")
@section("title", "Chroma By BPC")
@section("description", "Discover upcoming events at Chroma By BPC. Join us for exciting activities, workshops, and community gatherings.")
@section("keywords", "Chroma By BPC, events, workshops, community gatherings, activities")
@section("main")

  <main class="main">

    <!-- Events Extended Section -->
    <section id="events-extended" class="events-extended section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-8">
            <!-- Events List -->
            <div class="events-list">
              @forelse($events as $index => $event)
              <!-- Event Item -->
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
              </div><!-- End Event Item -->
              @empty
              <div class="alert alert-info">
                <p>No events found.</p>
              </div>
              @endforelse

              <!-- Pagination -->
              {{-- <div class="events-pagination" data-aos="fade-up" data-aos-delay="100">
                <ul class="pagination justify-content-center">
                  <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-arrow-left"></i></a></li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#"><i class="bi bi-arrow-right"></i></a></li>
                </ul>
              </div> --}}
            </div>
            <!-- End Events List -->
          </div>

          <div class="col-lg-4">
            <!-- Sidebar -->
            <div class="events-sidebar">
              <!-- Search Form -->
              {{-- <div class="sidebar-item search-form" data-aos="fade-up">
                <h4>Search Events</h4>
                <form action="">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search Events...">
                    <button class="btn" type="submit"><i class="bi bi-search"></i></button>
                  </div>
                </form>
              </div> --}}
              <!-- End Search Form -->

              <!-- Categories -->
              {{-- <div class="sidebar-item categories" data-aos="fade-up" data-aos-delay="100">
                <h4>Event Categories</h4>
                <ul class="list-unstyled">
                  <li><a href="#">Academic <span>(12)</span></a></li>
                  <li><a href="#">Sports <span>(7)</span></a></li>
                  <li><a href="#">Arts &amp; Culture <span>(9)</span></a></li>
                  <li><a href="#">Workshops <span>(5)</span></a></li>
                  <li><a href="#">Seminars <span>(8)</span></a></li>
                  <li><a href="#">Competitions <span>(6)</span></a></li>
                </ul>
              </div> --}}
              <!-- End Categories -->

              <!-- Upcoming Events -->
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
              </div><!-- End Upcoming Events -->

              <!-- Event Calendar -->
              {{-- <div class="sidebar-item event-calendar" data-aos="fade-up" data-aos-delay="300">
                <h4>Event Calendar</h4>
                <div class="calendar-widget">
                  <div class="calendar-header">
                    <h5>May 2023</h5>
                    <div class="calendar-nav">
                      <a href="#" class="prev-month"><i class="bi bi-chevron-left"></i></a>
                      <a href="#" class="next-month"><i class="bi bi-chevron-right"></i></a>
                    </div>
                  </div>
                  <table class="calendar-table">
                    <thead>
                      <tr>
                        <th>S</th>
                        <th>M</th>
                        <th>T</th>
                        <th>W</th>
                        <th>T</th>
                        <th>F</th>
                        <th>S</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>6</td>
                      </tr>
                      <tr>
                        <td>7</td>
                        <td>8</td>
                        <td>9</td>
                        <td>10</td>
                        <td>11</td>
                        <td>12</td>
                        <td>13</td>
                      </tr>
                      <tr>
                        <td>14</td>
                        <td class="has-event">15</td>
                        <td>16</td>
                        <td>17</td>
                        <td>18</td>
                        <td>19</td>
                        <td>20</td>
                      </tr>
                      <tr>
                        <td>21</td>
                        <td class="has-event">22</td>
                        <td>23</td>
                        <td>24</td>
                        <td>25</td>
                        <td>26</td>
                        <td>27</td>
                      </tr>
                      <tr>
                        <td>28</td>
                        <td>29</td>
                        <td>30</td>
                        <td>31</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div> --}}
              <!-- End Event Calendar -->
            </div><!-- End Sidebar -->
          </div>
        </div>

      </div>

    </section><!-- /Events Extended Section -->

  </main>
  
@endsection