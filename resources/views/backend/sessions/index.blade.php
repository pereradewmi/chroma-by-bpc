@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Sessions</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('sessions.form') }}" class="btn btn-sm btn-primary">Add New Session</a>
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

                    <div class="px-3 pb-3 d-flex justify-content-end">
                        <form id="sessions-search-form" class="m-2 d-flex align-items-center flex-nowrap justify-content-end" role="search" method="GET" action="{{ route('sessions.index') }}">
                            <input class="form-control form-control-sm mr-2" style="width: 220px;" type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                            <button class="btn btn-sm btn-primary mr-2" type="submit">Search</button>
                        </form>
                    </div>

                    <div class="table-responsive sessions-table-responsive" style="width: 100%; overflow-x: auto !important; overflow-y: hidden; display: block; -webkit-overflow-scrolling: touch;">
                        <table class="table align-items-center table-flush sessions-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    {{-- <th scope="col">Session Image</th> --}}
                                    <th scope="col">Session Name</th>
                                    <th scope="col">Description</th>
                                    {{-- <th scope="col">Status</th> --}}
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sessions-table-body">
                                @forelse($sessions as $session)
                                    <tr>
                                        <td>{{ ($sessions->firstItem() ?? 1) + $loop->index }}</td>
                                        {{-- <td>
                                            <img src="{{ $session->getSessionImage() }}" alt="Session Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td> --}}
                                        <td>
                                            <a
                                                href="javascript:void(0)"
                                                class="session-name-link font-weight-600"
                                                data-name="{{ $session->sName }}"
                                                data-id="{{ $session->sID }}"
                                                data-description="{{ $session->sDescription }}"
                                                data-status="{{ (int) ($session->status ?? 1) === 1 ? 'Active' : ((int) ($session->status ?? 1) === 0 ? 'Inactive' : 'Deleted') }}"
                                                data-image="{{ $session->sImage ? $session->getSessionImage() : '' }}"
                                            >
                                                <strong>{{ $session->sName }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($session->sDescription, 50) }}</small>
                                        </td>
                                        {{-- <td>
                                            @if((int) ($session->status ?? 1) === 1)
                                                <span class="badge badge-success">Active</span>
                                            @elseif((int) ($session->status ?? 1) === 0)
                                                <span class="badge badge-secondary">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td> --}}
                                        {{-- <td>{{ $session->created_at->format('M d, Y') }}</td> --}}
                                        <td class="session-actions-cell">
                                            <div class="session-action-group">
                                                <a href="{{ route('sessions.form', $session->sID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @if((int) ($session->status ?? 1) !== 2)
                                                    <form action="{{ route('sessions.status', $session->sID) }}" method="POST" class="btn btn-sm text-primary session-toggle-form" title="Toggle Active/Inactive">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ (int) ($session->status ?? 1) === 1 ? 1 : 0 }}">
                                                        <label class="status-switch mb-0" for="session-status-{{ $session->sID }}">
                                                            <input
                                                                type="checkbox"
                                                                id="session-status-{{ $session->sID }}"
                                                                {{ (int) ($session->status ?? 1) === 1 ? 'checked' : '' }}
                                                                onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                            >
                                                            <span class="status-slider"></span>
                                                        </label>
                                                    </form>
                                                @endif
                                                <form action="{{ route('sessions.status', $session->sID) }}" method="POST" class="session-delete-form" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                                    @csrf
                                                    <input type="hidden" name="status" value="2">
                                                    <button type="submit" class="btn btn-sm text-primary bg-white border-0" title="Delete" aria-label="Delete">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No sessions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer py-4" id="sessions-pagination-wrapper">
                        <nav class="d-flex justify-content-end" aria-label="..." id="sessions-pagination">
                            @if($sessions->hasPages())
                                {{ $sessions->links() }}
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

    <div class="modal fade" id="sessionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="sessionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sessionDetailsModalLabel">Session Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img id="session-detail-image" src="" alt="Session Image" class="rounded border" style="width: 120px; height: 140px; object-fit: cover; display: none;">
                            <div id="session-detail-image-placeholder" class="rounded bg-secondary text-white align-items-center justify-content-center" style="width: 140px; height: 140px; font-size: 2rem; display: inline-flex;">
                                -
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-2"><strong>Name:</strong> <span id="session-detail-name">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Session ID:</strong> <span id="session-detail-id">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Status:</strong> <span id="session-detail-status">-</span></div>
                                <div class="col-12 mb-2"><strong>Description:</strong> <span id="session-detail-description">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sessions-table {
            min-width: 1250px;
            width: 100%;
            white-space: nowrap;
            table-layout: auto;
        }

        .sessions-table-responsive {
            overflow-x: auto !important;
            overflow-y: hidden;
            width: 100%;
            display: block;
        }

        .sessions-table th,
        .sessions-table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .session-actions-cell {
            white-space: nowrap;
            min-width: 220px;
        }

        .session-action-group {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 4px;
            min-width: max-content;
        }

        .session-toggle-form {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .session-delete-form {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .status-switch {
            position: relative;
            display: inline-block;
            width: 24px;
            height: 14px;
            vertical-align: middle;
        }

        .status-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .status-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #e9ecf3;
            transition: 0.2s;
            border-radius: 22px;
            border: 1px solid #d2d8e5;
        }

        .status-slider:before {
            position: absolute;
            content: "";
            height: 10px;
            width: 10px;
            left: 2px;
            top: 2px;
            background-color: #fff;
            transition: 0.2s;
            border-radius: 50%;
        }

        .status-switch input:checked + .status-slider {
            background-color: #04415f;
            border-color: #04415f;
        }

        .status-switch input:checked + .status-slider:before {
            transform: translateX(10px);
        }

        .session-name-link {
            cursor: pointer;
        }
    </style>

    <script>
        (function () {
            const form = document.getElementById('sessions-search-form');
            const input = form ? form.querySelector('input[name="search"]') : null;
            const tableBody = document.getElementById('sessions-table-body');
            const pagination = document.getElementById('sessions-pagination');
            let searchTimer = null;

            if (!form || !input || !tableBody) {
                return;
            }

            const loadSessions = function (url, pushState = true) {
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
                        const nextTableBody = doc.getElementById('sessions-table-body');
                        const nextPagination = doc.getElementById('sessions-pagination');

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
                        console.error('Failed to load sessions list:', error);
                    });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const searchValue = (input.value || '').trim();
                const targetUrl = new URL(form.action, window.location.origin);

                if (searchValue !== '') {
                    targetUrl.searchParams.set('search', searchValue);
                }

                loadSessions(targetUrl.toString());
            });

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                }, 300);
            });

            document.addEventListener('click', function (event) {
                const pageLink = event.target.closest('#sessions-pagination a.page-link');

                if (!pageLink || !pageLink.href) {
                    return;
                }

                event.preventDefault();
                loadSessions(pageLink.href);
            });

            window.addEventListener('popstate', function () {
                loadSessions(window.location.href, false);
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('.session-name-link');

                if (!link) {
                    return;
                }

                event.preventDefault();

                const session = {
                    name: link.getAttribute('data-name') || '',
                    id: link.getAttribute('data-id') || '',
                    description: link.getAttribute('data-description') || '',
                    status: link.getAttribute('data-status') || '',
                    image: link.getAttribute('data-image') || ''
                };

                const setText = function (id, value) {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = value ? String(value) : '-';
                    }
                };

                setText('session-detail-name', session.name);
                setText('session-detail-id', session.id);
                setText('session-detail-status', session.status);
                setText('session-detail-description', session.description);

                const image = document.getElementById('session-detail-image');
                const imagePlaceholder = document.getElementById('session-detail-image-placeholder');

                if (image && imagePlaceholder) {
                    if (session.image) {
                        image.src = session.image;
                        image.style.display = 'inline-block';
                        imagePlaceholder.style.display = 'none';
                    } else {
                        image.removeAttribute('src');
                        image.style.display = 'none';
                        imagePlaceholder.style.display = 'inline-flex';
                    }
                }

                $('#sessionDetailsModal').modal('show');
            });
        })();
    </script>
@endsection
