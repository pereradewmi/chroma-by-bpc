@extends("frontend.components.layout")
@section("title", "Chroma By BPC")
@section("description", "{{ $event->eDescription }}")
@section("keywords", "Chroma By BPC, events, {{ $event->eName }}, event details, workshops, community gatherings, activities")
@section("main")

  <main class="main">

    <!-- Event Section -->
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

              <!-- End event content -->
            </div>
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

    </section><!-- /Event Section -->

  </main>
@endsection