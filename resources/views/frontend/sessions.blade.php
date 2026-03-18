@extends("frontend.components.layout")
@section("title", "Sessions | Chroma By BPC")
@section("description", "Discover our modern campus facilities at Chroma By BPC. Explore academic buildings, student services, and recreational spaces.")
@section("keywords", "Chroma By BPC, campus facilities, academic buildings, student services, recreational spaces")
@section("main")

  <main class="main">

        <!-- News Hero Section -->
    <section id="news-hero" class="news-hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">
          <!-- Main Content Area -->
          <div class="col-lg-8">
            <!-- Featured Article -->
            @if(!empty($featuredSession))
              <article class="featured-post position-relative mb-4" data-aos="fade-up">
                <img src="{{ $featuredSession->getSessionImage() }}" alt="{{ $featuredSession->sName }}" class="img-fluid">
                <div class="post-overlay">
                  <div class="post-content">
                    <div class="post-meta">
                      <span class="category">Session</span>
                      {{-- <span class="date">{{ optional($featuredSession->created_at)->format('m/d/Y') ?? now()->format('m/d/Y') }}</span> --}}
                    </div>
                    <h2 class="post-title">
                      <a href="#">{{ $featuredSession->sName }}</a>
                    </h2>
                    <p class="post-excerpt">{{ \Illuminate\Support\Str::limit($featuredSession->sDescription, 180) }}</p>
                    <div class="post-author">
                      <span>by</span>
                      <a href="#">Chroma By BPC</a>
                    </div>
                  </div>
                </div>
              </article>
            @else
              <article class="featured-post position-relative mb-4" data-aos="fade-up">
                <img src="{{ asset('front-assets/img/logo.png') }}" alt="No session available" class="img-fluid">
                <div class="post-overlay">
                  <div class="post-content">
                    <div class="post-meta">
                      <span class="category">Session</span>
                    </div>
                    <h2 class="post-title">
                      <a href="#">No sessions available yet</a>
                    </h2>
                  </div>
                </div>
              </article>
            @endif

            <!-- Secondary Articles -->
            {{-- <div class="row g-4">
              <div class="col-md-6">
                <article class="secondary-post" data-aos="fade-up">
                  <div class="post-image">
                    <img src="{{ asset('front-assets/img/blog/blog-post-1.webp') }}" alt="Post" class="img-fluid">
                  </div>
                  <div class="post-content">
                    <div class="post-meta">
                      <span class="category">Politics</span>
                      <span class="date">03/21/2024</span>
                    </div>
                    <h3 class="post-title">
                      <a href="#">Implementing Agile Methodologies for Enhanced Business Performance</a>
                    </h3>
                    <div class="post-author">
                      <span>by</span>
                      <a href="#">Robert Anderson</a>
                    </div>
                  </div>
                </article>
              </div>
              <div class="col-md-6">
                <article class="secondary-post" data-aos="fade-up" data-aos-delay="100">
                  <div class="post-image">
                    <img src="{{ asset('front-assets/img/blog/blog-post-2.webp') }}" alt="Post" class="img-fluid">
                  </div>
                  <div class="post-content">
                    <div class="post-meta">
                      <span class="category">Business</span>
                      <span class="date">01/30/2024</span>
                    </div>
                    <h3 class="post-title">
                      <a href="#">Streamlining Operations Through Digital Transformation Solutions</a>
                    </h3>
                    <div class="post-author">
                      <span>by</span>
                      <a href="#">Sarah Thompson</a>
                    </div>
                  </div>
                </article>
              </div>
            </div> --}}
          </div><!-- End Main Content Area -->

          <!-- Sidebar with Tabs -->
          <div class="col-lg-4">
            <div class="news-tabs" data-aos="fade-up" data-aos-delay="200">
              {{-- <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#top-stories" type="button">Top stories</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#trending" type="button">Trending News</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#latest" type="button">Latest News</button>
                </li>
              </ul> --}}

              <div class="tab-content">
                <!-- Top Stories Tab -->
                <div class="tab-pane fade show active" id="top-stories">
                  @forelse(($topStoriesSessions ?? collect()) as $topStorySession)
                    <article class="tab-post">
                      <div class="row g-0 align-items-center">
                        <div class="col-4">
                          <img src="{{ $topStorySession->getSessionImage() }}" alt="{{ $topStorySession->sName }}" class="img-fluid">
                        </div>
                        <div class="col-8">
                          <div class="post-content">
                            <span class="category">Session</span>
                            <h4 class="post-title">
                              <a href="#">{{ \Illuminate\Support\Str::limit($topStorySession->sName, 65) }}</a><br><br>
                               <p class="post-date">{{ \Illuminate\Support\Str::limit($topStorySession->sDescription, 180) }}</p>
                            </h4>
                            <div class="post-author">by <a href="#">Chroma By BPC</a></div>
                          </div>
                        </div>
                      </div>
                    </article>
                  @empty
                    <article class="tab-post">
                      <div class="row g-0 align-items-center">
                        <div class="col-4">
                          <img src="{{ asset('front-assets/img/logo.png') }}" alt="No session available" class="img-fluid">
                        </div>
                        <div class="col-8">
                          <div class="post-content">
                            <span class="category">Session</span>
                            <h4 class="post-title"><a href="#">No top stories available</a></h4>
                            <div class="post-author">by <a href="#">Chroma By BPC</a></div>
                          </div>
                        </div>
                      </div>
                    </article>
                  @endforelse

                  {{-- <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-4.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Technology</span>
                          <h4 class="post-title"><a href="#">Transforming Business Models Through Digital Innovation</a></h4>
                          <div class="post-author">by <a href="#">Rachel Stevens</a></div>
                        </div>
                      </div>
                    </div>
                  </article> --}}

                  {{-- <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-5.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Finance</span>
                          <h4 class="post-title"><a href="#">Strategic Investment Planning for Sustainable Growth</a></h4>
                          <div class="post-author">by <a href="#">Andrew Phillips</a></div>
                        </div>
                      </div>
                    </div>
                  </article> --}}
                </div>

                <!-- Trending News Tab -->
                {{-- <div class="tab-pane fade" id="trending">
                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-4.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Science</span>
                          <h4 class="post-title"><a href="#">Implementing Sustainable Business Practices for Long-term Growth</a></h4>
                          <div class="post-author">by <a href="#">Alexandra Foster</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-5.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Style</span>
                          <h4 class="post-title"><a href="#">Optimizing Supply Chain Management Through Technology Integration</a></h4>
                          <div class="post-author">by <a href="#">Christopher Wells</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-6.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Politics</span>
                          <h4 class="post-title"><a href="#">Developing Strategic Partnerships for Market Expansion</a></h4>
                          <div class="post-author">by <a href="#">Victoria Palmer</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-7.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Marketing</span>
                          <h4 class="post-title"><a href="#">Enhancing Brand Value Through Customer-Centric Strategies</a></h4>
                          <div class="post-author">by <a href="#">Sophia Rodriguez</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-8.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Leadership</span>
                          <h4 class="post-title"><a href="#">Building High-Performance Teams in Dynamic Environments</a></h4>
                          <div class="post-author">by <a href="#">Nathan Brooks</a></div>
                        </div>
                      </div>
                    </div>
                  </article>
                </div> --}}

                <!-- Latest News Tab -->
                {{-- <div class="tab-pane fade" id="latest">
                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-7.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Health</span>
                          <h4 class="post-title"><a href="#">Accelerating Innovation Through Cross-functional Collaboration</a></h4>
                          <div class="post-author">by <a href="#">Benjamin Carter</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-8.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Business</span>
                          <h4 class="post-title"><a href="#">Driving Business Growth Through Strategic Digital Initiatives</a></h4>
                          <div class="post-author">by <a href="#">Olivia Martinez</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-9.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Sports</span>
                          <h4 class="post-title"><a href="#">Maximizing Operational Efficiency Through Process Optimization</a></h4>
                          <div class="post-author">by <a href="#">William Turner</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-10.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Innovation</span>
                          <h4 class="post-title"><a href="#">Leveraging AI Solutions for Business Process Automation</a></h4>
                          <div class="post-author">by <a href="#">Isabella Clark</a></div>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-4">
                        <img src="{{ asset('front-assets/img/blog/blog-post-square-6.webp') }}" alt="Post" class="img-fluid">
                      </div>
                      <div class="col-8">
                        <div class="post-content">
                          <span class="category">Strategy</span>
                          <h4 class="post-title"><a href="#">Implementing Agile Framework for Project Management Excellence</a></h4>
                          <div class="post-author">by <a href="#">Marcus Henderson</a></div>
                        </div>
                      </div>
                    </div>
                  </article>
                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /News Hero Section -->

    <!-- News Posts Section -->
    <section id="news-posts" class="news-posts section">

      <div class="container">

        <div class="row gy-4">
          @forelse(($newsPostSessions ?? collect()) as $newsPostSession)
            <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3 + 1) * 100 }}">
              <article>

                <div class="post-img">
                  <img src="{{ $newsPostSession->getSessionImage() }}" alt="{{ $newsPostSession->sName }}" class="img-fluid">
                </div>

                <p class="post-category">Session</p>

                <h2 class="title">
                  <a href="#">{{ \Illuminate\Support\Str::limit($newsPostSession->sName, 70) }}</a> <br><br>
                   <p class="post-date">{{ \Illuminate\Support\Str::limit($newsPostSession->sDescription, 180) }}</p>
                </h2>

                <div class="d-flex align-items-center">
                  <img src="{{ asset('front-assets/img/logo.png') }}" alt="Author" class="img-fluid post-author-img flex-shrink-0">
                  <div class="post-meta">
                    <p class="post-author">Chroma By BPC</p>
                    <p class="post-date">
                      <time datetime="{{ optional($newsPostSession->created_at)->format('Y-m-d') ?? now()->format('Y-m-d') }}">{{ optional($newsPostSession->created_at)->format('M j, Y') ?? now()->format('M j, Y') }}</time>
                    </p>
                  </div>
                </div>

              </article>
            </div><!-- End post list item -->
          @empty
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
              <article>
                <h2 class="title mb-0">
                  <a href="#">No sessions available yet</a>
                </h2>
              </article>
            </div>
          @endforelse

        </div><!-- End recent posts list -->

      </div>

    </section><!-- /News Posts Section -->

  </main>
@endsection