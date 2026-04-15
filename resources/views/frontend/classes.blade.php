@extends("frontend.components.layout")
@section("description", "Discover our modern campus facilities at CHROMA LIFESTYLE AND CONCEPT STORE. Explore academic buildings, student services, and recreational spaces.")
@section("keywords", "CHROMA LIFESTYLE AND CONCEPT STORE, campus facilities, academic buildings, student services, recreational spaces")
@section("main")

  <main class="main">

    <section id="campus-facilities" class="campus-facilities section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="d-flex justify-content-end mb-4" data-aos="fade-up" data-aos-delay="150">
          <a href="{{ route('frontend.register') }}" style="display:inline-block; padding:10px 16px; border-radius:8px; background: linear-gradient(135deg, #001f3f 0%, #003d82 100%); color:#fff; font-weight:600; text-decoration:none;">Student Registration</a>
        </div>

        <div class="facilities-grid" data-aos="fade-up" data-aos-delay="200">
          @forelse($classes as $index => $class)
          <div class="category-card academic" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
            <div class="card-header">
              <h3>{{ strtoupper($class->cName) }}</h3>
            </div>
            <div class="card-content">
              <div class="facility-image">
                <video class="img-fluid rounded w-100" style="height: 240px; object-fit: cover;" autoplay muted loop playsinline>
                <source src="{{ $class->getClassVideo() }}" type="video/mp4">
                Your browser does not support the video tag.
              </video>

              </div>
              <div class="facility-list">
                @if($class->cDescription)
                  <div class="facility-item">

                    <span>{!! $class->cDescription !!}</span>
                  </div>
                @else
                  <div class="facility-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>{{ $class->cName }} - Fee: Rs. {{ number_format($class->classfee, 2) }}</span>
                  </div>
                @endif
              </div>
            </div>
            <div class="card-footer">
            </div>
          </div>
          @empty
          <div class="col-12">
            <div class="alert alert-info" role="alert">
              <p>No classes available at the moment.</p>
            </div>
          </div>
          @endforelse
        </div>
      </div>
    </section>

  </main>
@endsection