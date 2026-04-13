@extends("frontend.components.layout")
@section("description", "Discover our modern campus facilities at Chroma By BPC. Explore academic buildings, student services, and recreational spaces.")
@section("keywords", "Chroma By BPC, campus facilities, academic buildings, student services, recreational spaces")
@section("main")
  <main class="main">
    <section id="news-hero" class="news-hero section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-4">
          <div class="col-lg-8">
            @if(!empty($featuredSession))
              <article class="featured-post position-relative mb-4" data-aos="fade-up">
                <iframe width="100%" height="496" src="https://www.youtube.com/embed/6NczqPk9K7I" title="Pottery at Chroma" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                <div class="post-overlay">
                  <div class="post-content">
                    <div class="post-meta">
                      <span class="category">Session</span>
                    </div>
                    <div class="post-author">
                      <span>by</span>
                      <a href="https://www.youtube.com/@chromabybpc">Chroma By BPC</a>
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
          </div>

          <div class="col-lg-4">
            <div class="news-tabs" data-aos="fade-up" data-aos-delay="200">
              <div class="tab-content">
                <div class="tab-pane fade show active" id="top-stories">
                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-12">
                        <iframe width="100%" height="196" src="https://www.youtube.com/embed/uQaoSbVcv4Y" title="Art Piece : The Inception" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                      </div>
                    </div>
                  </article>

                  <article class="tab-post">
                    <div class="row g-0 align-items-center">
                      <div class="col-12">
                        <iframe width="100%" height="196" src="https://www.youtube.com/embed/B3Z_bDhFo3k" title="Clay Tales  Studio Pottery" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        {{-- <iframe width="100%" height="196" src="https://www.youtube.com/embed/uQaoSbVcv4Y" title="Art Piece : The Inception" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> --}}
                      </div>
                    </div>
                  </article>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

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
                  <a href="#">{{ \Illuminate\Support\Str::limit($newsPostSession->sName, 70) }}</a>
                  <br><br>
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
            </div>
          @empty
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
              <article>
                <h2 class="title mb-0">
                  <a href="#">No sessions available yet</a>
                </h2>
              </article>
            </div>
          @endforelse
        </div>
      </div>
    </section>
  </main>
@endsection