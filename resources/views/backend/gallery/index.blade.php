@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-4 d-flex align-items-center">
                                <h3 class="mb-0">Gallery Images</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <form id="gallery-search-form" class="d-flex align-items-center" role="search" method="GET" action="{{ route('admin.images.index') }}">
                                    <input class="form-control form-control-sm" style="width: 230px;" type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-sm btn-primary ml-3" type="submit" title="Search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.images.form') }}" class="btn btn-sm btn-primary" title="Add Image">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gallery-table-body">
                                @forelse($images as $image)
                                    <tr>
                                        <td>{{ ($images->firstItem() ?? 1) + $loop->index }}</td>
                                        <td>
                                            <img src="{{ Storage::url($image->image_path) }}" alt="Gallery Image" class="img-fluid" style="max-height: 50px;">
                                        </td>
                                        <td>{{ optional($image->category)->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.images.form', $image->id) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No gallery images found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer py-4" id="gallery-pagination-wrapper">
                        <nav class="d-flex justify-content-end" aria-label="..." id="gallery-pagination">
                            @if($images->hasPages())
                                {{ $images->links() }}
                            @else
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled"><span class="page-link" aria-label="Previous"><i class="fas fa-chevron-left" aria-hidden="true"></i></span></li>
                                    <li class="page-item active btn-primary"><span class="btn-primary page-link">1</span></li>
                                    <li class="page-item disabled"><span class="page-link" aria-label="Next"><i class="fas fa-chevron-right" aria-hidden="true"></i></span></li>
                                </ul>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('gallery-search-form');
            const input = form ? form.querySelector('input[name="search"]') : null;
            const tableBody = document.getElementById('gallery-table-body');
            const pagination = document.getElementById('gallery-pagination');
            let searchTimer = null;

            if (!form || !input || !tableBody) {
                return;
            }

            const loadGallery = function (url, pushState = true) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) {
                        return response.text();
                    })
                    .then(function (html) {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const nextTableBody = doc.getElementById('gallery-table-body');
                        const nextPagination = doc.getElementById('gallery-pagination');

                        if (nextTableBody) {
                            tableBody.innerHTML = nextTableBody.innerHTML;
                        }

                        if (pagination && nextPagination) {
                            pagination.innerHTML = nextPagination.innerHTML;
                        }

                        if (pushState) {
                            window.history.pushState({}, '', url);
                        }
                    })
                    .catch(function (error) {
                        console.error('Failed to load gallery list:', error);
                    });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const searchValue = (input.value || '').trim();
                const targetUrl = new URL(form.action, window.location.origin);

                if (searchValue !== '') {
                    targetUrl.searchParams.set('search', searchValue);
                }

                loadGallery(targetUrl.toString());
            });

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                }, 300);
            });

            document.addEventListener('click', function (event) {
                const pageLink = event.target.closest('#gallery-pagination .page-link');

                if (!pageLink || !pageLink.href) {
                    return;
                }

                event.preventDefault();
                loadGallery(pageLink.href);
            });

            window.addEventListener('popstate', function () {
                loadGallery(window.location.href, false);
            });
        })();
    </script>
@endsection
