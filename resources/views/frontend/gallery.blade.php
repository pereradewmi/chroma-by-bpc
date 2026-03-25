@extends("frontend.components.layout")
@section("title", "Chroma By BPC")
@section("description", "Discover our modern campus facilities at Chroma By BPC. Explore academic buildings, student services, and recreational spaces.")
@section("keywords", "Chroma By BPC, campus facilities, academic buildings, student services, recreational spaces")
@section("main")

<main class="main">

    <!-- Students Life Section -->
    <section id="students-life" class="students-life section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div id="gallery-dynamic-section" class="student-gallery mt-5 pt-3" data-aos="fade-up" data-aos-delay="200">
          <h3 class="text-center mb-4">Student Life Gallery</h3>

          <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
            <a href="{{ route('frontend.gallery') }}"
               class="btn btn-sm gallery-filter-link {{ empty($selectedCategoryId) ? 'btn-primary' : 'btn-outline-primary' }}"
               style="border-radius: 20px; box-shadow: 0 4px 12px rgba(4, 65, 95, 0.25); background-color: #04415f; border-color: #04415f; color: #fff; padding: 0.5rem 1rem;">
              All
            </a>
            @foreach($categories as $category)
              <a href="{{ route('frontend.gallery', ['category' => $category->id]) }}"
                 class="btn btn-sm gallery-filter-link {{ (string) $selectedCategoryId === (string) $category->id ? 'btn-primary' : 'btn-outline-primary' }}"
                 style="border-radius: 20px; box-shadow: 0 4px 12px rgba(4, 65, 95, 0.25); background-color: #04415f; border-color: #04415f; color: #fff; padding: 0.5rem 1rem;">
                {{ $category->name }}
              </a>
            @endforeach
          </div>

          <div id="gallery-grid" class="row g-3">
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const section = document.getElementById('gallery-dynamic-section');
      if (!section) {
        return;
      }

      async function loadGallery(url, pushState = true) {
        section.style.opacity = '0.6';

        try {
          const response = await fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (!response.ok) {
            throw new Error('Failed to load gallery data');
          }

          const html = await response.text();
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newSection = doc.getElementById('gallery-dynamic-section');

          if (newSection) {
            section.innerHTML = newSection.innerHTML;
            bindFilterLinks();

            if (pushState) {
              window.history.pushState({}, '', url);
            }
          }
        } catch (error) {
          window.location.href = url;
        } finally {
          section.style.opacity = '1';
        }
      }

      function bindFilterLinks() {
        const links = section.querySelectorAll('.gallery-filter-link');

        links.forEach(function (link) {
          link.addEventListener('click', function (event) {
            event.preventDefault();
            loadGallery(link.href);
          });
        });
      }

      window.addEventListener('popstate', function () {
        loadGallery(window.location.href, false);
      });

      bindFilterLinks();
    });
  </script>
@endsection