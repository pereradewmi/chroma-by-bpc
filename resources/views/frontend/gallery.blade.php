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

          <!-- Category Cards View -->
          <div id="category-cards-view" class="row g-4">
            @forelse($categories as $category)
              <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ (($loop->index % 6) + 1) * 100 }}">
                <div class="category-card-item" data-category-id="{{ $category->id }}" style="cursor: pointer; position: relative; overflow: hidden; border-radius: 12px; height: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                  @php
                    $bgImage = $category->background_image 
                      ? asset('storage/' . $category->background_image) 
                      : ($category->images()->first() 
                        ? asset('storage/' . $category->images()->first()->image_path) 
                        : 'linear-gradient(135deg, #04415f 0%, #0a6fa3 100%)');
                  @endphp
                  <div style="width: 100%; height: 100%; background: url('{{ $bgImage }}') center/cover no-repeat; position: relative; display: flex; align-items: center; justify-content: center;">
                    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.3s ease;" class="category-overlay"></div>
                    <h5 class="text-white text-center fw-bold" style="position: relative; z-index: 2; opacity: 0; transition: opacity 0.3s ease; font-size: 1.5rem;">
                      {{ $category->name }}
                    </h5>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12 text-center">
                <p class="mb-0">No categories found.</p>
              </div>
            @endforelse
          </div>

          <!-- Images View -->
          <div id="images-view" style="display: none;">
            <button id="back-to-categories" class="btn btn-sm btn-outline-primary mb-4" style="border-radius: 20px; box-shadow: 0 4px 12px rgba(4, 65, 95, 0.25); border-color: #04415f; color: #04415f; padding: 0.5rem 1rem;">
              <i class="bi bi-arrow-left"></i> Back to Categories
            </button>
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

      </div>

    </section><!-- /Students Life Section -->

  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const section = document.getElementById('gallery-dynamic-section');
      const categoryCardsView = document.getElementById('category-cards-view');
      const imagesView = document.getElementById('images-view');
      const galleryGrid = document.getElementById('gallery-grid');
      const backButton = document.getElementById('back-to-categories');

      if (!section) {
        return;
      }

      // Handle category card hover effects
      function bindCategoryCards() {
        const cards = categoryCardsView.querySelectorAll('.category-card-item');

        cards.forEach(function (card) {
          const overlay = card.querySelector('.category-overlay');
          const name = card.querySelector('h5');

          card.addEventListener('mouseenter', function () {
            overlay.style.opacity = '1';
            name.style.opacity = '1';
            card.style.transform = 'scale(1.05)';
          });

          card.addEventListener('mouseleave', function () {
            overlay.style.opacity = '0';
            name.style.opacity = '0';
            card.style.transform = 'scale(1)';
          });

          card.addEventListener('click', function () {
            const categoryId = card.getAttribute('data-category-id');
            loadCategoryImages(categoryId);
          });
        });
      }

      // Load images for a specific category
      async function loadCategoryImages(categoryId, pushState = true) {
        section.style.opacity = '0.6';

        try {
          const url = `{{ route('frontend.gallery') }}?category=${encodeURIComponent(categoryId)}`;
          const response = await fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (!response.ok) {
            throw new Error('Failed to load images');
          }

          const html = await response.text();
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');

          // Extract gallery grid from response
          const newGalleryGrid = doc.getElementById('gallery-grid');
          if (newGalleryGrid) {
            galleryGrid.innerHTML = newGalleryGrid.innerHTML;

            // Switch views
            categoryCardsView.style.display = 'none';
            imagesView.style.display = 'block';

            // Re-initialize glightbox for new images
            if (typeof GLightbox !== 'undefined') {
              GLightbox({
                selector: '.gallery-item'
              });
            }

            if (pushState) {
              window.history.pushState({view: 'images', categoryId: categoryId}, '', url);
            }
          }
        } catch (error) {
          console.error('Error loading images:', error);
          alert('Failed to load images. Please try again.');
        } finally {
          section.style.opacity = '1';
        }
      }

      // Go back to categories
      function backToCategories(pushState = true) {
        categoryCardsView.style.display = '';
        imagesView.style.display = 'none';
        galleryGrid.innerHTML = '';

        if (pushState) {
          window.history.pushState({view: 'categories'}, '', '{{ route('frontend.gallery') }}');
        }
      }

      // Back button click handler
      backButton.addEventListener('click', function (event) {
        event.preventDefault();
        backToCategories();
      });

      // Handle browser back button
      window.addEventListener('popstate', function (event) {
        if (event.state && event.state.view === 'images') {
          loadCategoryImages(event.state.categoryId, false);
        } else {
          backToCategories(false);
        }
      });

      bindCategoryCards();

      @if(!empty($selectedCategoryId))
        loadCategoryImages('{{ $selectedCategoryId }}', false);
      @endif
    });
  </script>
@endsection