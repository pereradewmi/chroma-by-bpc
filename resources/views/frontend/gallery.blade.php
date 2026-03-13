@extends("frontend.components.layout")
@section("title", "Gallery | Chroma By BPC")
@section("description", "Discover our modern campus facilities at Chroma By BPC. Explore academic buildings, student services, and recreational spaces.")
@section("keywords", "Chroma By BPC, campus facilities, academic buildings, student services, recreational spaces")
@section("main")

<main class="main">

    <!-- Students Life Section -->
    <section id="students-life" class="students-life section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="student-gallery mt-5 pt-3" data-aos="fade-up" data-aos-delay="200">
          <h3 class="text-center mb-4">Student Life Gallery</h3>

          <div class="row g-3">
            @forelse($images as $image)
              <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ (($loop->index % 6) + 1) * 100 }}">
                <a href="{{ asset('storage/' . $image->image_path) }}" class="gallery-item glightbox">
                  <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid" loading="lazy" alt="Gallery Image">
                  <div class="gallery-overlay">
                    <i class="bi bi-plus-circle"></i>
                  </div>
                </a>
              </div>
            @empty
              <div class="col-12 text-center">
                <p class="mb-0">No gallery images found.</p>
              </div>
            @endforelse

          </div>
        </div>

      </div>

    </section><!-- /Students Life Section -->

  </main>
@endsection