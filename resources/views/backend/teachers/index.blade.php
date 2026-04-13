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
                                <h3 class="mb-0">Teachers</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <form id="teachers-search-form" class="d-flex align-items-center" role="search" method="GET" action="{{ route('teachers.index') }}">
                                    <input class="form-control form-control-sm" style="width: 230px;" type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-sm btn-primary ml-3" type="submit" title="Search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('teachers.form') }}" class="btn btn-sm btn-primary" title="Add Teacher">
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

                    <div class="px-3 pb-3"></div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile Number</th>
                                    <th scope="col">Teacher Type</th>
                                    {{-- <th scope="col">Status</th> --}}
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teachers-table-body">
                                @forelse($teachers as $teacher)
                                    <tr>
                                        <td>{{ ($teachers->firstItem() ?? 1) + $loop->index }}</td>
                                        <td>
                                            <a
                                                href="javascript:void(0)"
                                                class="teacher-name-link font-weight-600"
                                                data-name="{{ trim(($teacher->tFName ?? '') . ' ' . ($teacher->tLName ?? '')) }}"
                                                data-id="{{ $teacher->T_ID }}"
                                                data-mobile="{{ $teacher->tMobileNo }}"
                                                data-address="{{ $teacher->tAddress }}"
                                                data-type="{{ $teacher->teacherType === 'instructor' ? 'Instructor' : 'Class Teacher' }}"
                                                data-status="{{ (int) $teacher->Active === 1 ? 'Active' : ((int) $teacher->Active === 0 ? 'Inactive' : 'Deleted') }}"
                                            >
                                                {{ $teacher->tFName }} {{ $teacher->tLName }}
                                            </a>
                                        </td>
                                        <td>{{ $teacher->tMobileNo }}</td>
                                        <td>{{ $teacher->teacherType === 'instructor' ? 'Instructor' : 'Class Teacher' }}</td>
                                        {{-- <td>
                                            @if((int) $teacher->Active === 1)
                                                <span class="badge badge-success">Active</span>
                                            @elseif((int) $teacher->Active === 0)
                                                <span class="badge badge-secondary">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            <a href="{{ route('teachers.form', $teacher->T_ID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            @if((int) $teacher->Active !== 2)
                                                <form action="{{ route('teachers.status', $teacher->T_ID) }}" method="POST" class="btn btn-sm text-primary d-inline-block align-middle ml-1" title="Toggle Active/Inactive">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ (int) $teacher->Active === 1 ? 1 : 0 }}">
                                                    <label class="status-switch mb-0" for="teacher-status-{{ $teacher->T_ID }}">
                                                        <input
                                                            type="checkbox"
                                                            id="teacher-status-{{ $teacher->T_ID }}"
                                                            {{ (int) $teacher->Active === 1 ? 'checked' : '' }}
                                                            onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                        >
                                                        <span class="status-slider"></span>
                                                    </label>
                                                </form>
                                            @endif
                                            <form action="{{ route('teachers.status', $teacher->T_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                                @csrf
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-sm text-primary bg-white border-0" title="Delete" aria-label="Delete">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No teachers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer py-4" id="teachers-pagination-wrapper">
                        <nav class="d-flex justify-content-end" aria-label="..." id="teachers-pagination">
                            @if($teachers->hasPages())
                                {{ $teachers->links() }}
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

    <div class="modal fade" id="teacherDetailsModal" tabindex="-1" role="dialog" aria-labelledby="teacherDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherDetailsModalLabel">Teacher Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Name:</strong> <span id="teacher-detail-name">-</span></div>
                        <div class="col-md-6 mb-2"><strong>Teacher ID:</strong> <span id="teacher-detail-id">-</span></div>
                        <div class="col-md-6 mb-2"><strong>Teacher Type:</strong> <span id="teacher-detail-type">-</span></div>
                        <div class="col-md-6 mb-2"><strong>Status:</strong> <span id="teacher-detail-status">-</span></div>
                        <div class="col-md-6 mb-2"><strong>Mobile Number:</strong> <span id="teacher-detail-mobile">-</span></div>
                        <div class="col-12 mb-2"><strong>Address:</strong> <span id="teacher-detail-address">-</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
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

        .teacher-name-link {
            cursor: pointer;
        }
    </style>

    <script>
        (function () {
            const form = document.getElementById('teachers-search-form');
            const input = form ? form.querySelector('input[name="search"]') : null;
            const tableBody = document.getElementById('teachers-table-body');
            const pagination = document.getElementById('teachers-pagination');
            let searchTimer = null;

            if (!form || !input || !tableBody) {
                return;
            }

            const loadTeachers = function (url, pushState = true) {
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
                        const nextTableBody = doc.getElementById('teachers-table-body');
                        const nextPagination = doc.getElementById('teachers-pagination');

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
                        console.error('Failed to load teachers list:', error);
                    });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const searchValue = (input.value || '').trim();
                const targetUrl = new URL(form.action, window.location.origin);

                if (searchValue !== '') {
                    targetUrl.searchParams.set('search', searchValue);
                }

                loadTeachers(targetUrl.toString());
            });

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                }, 300);
            });

            document.addEventListener('click', function (event) {
                const pageLink = event.target.closest('#teachers-pagination a.page-link');

                if (!pageLink || !pageLink.href) {
                    return;
                }

                event.preventDefault();
                loadTeachers(pageLink.href);
            });

            window.addEventListener('popstate', function () {
                loadTeachers(window.location.href, false);
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('.teacher-name-link');

                if (!link) {
                    return;
                }

                event.preventDefault();

                const teacher = {
                    name: link.getAttribute('data-name') || '',
                    id: link.getAttribute('data-id') || '',
                    mobile: link.getAttribute('data-mobile') || '',
                    address: link.getAttribute('data-address') || '',
                    type: link.getAttribute('data-type') || '',
                    status: link.getAttribute('data-status') || ''
                };

                const setText = function (id, value) {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = value ? String(value) : '-';
                    }
                };

                setText('teacher-detail-name', teacher.name);
                setText('teacher-detail-id', teacher.id);
                setText('teacher-detail-type', teacher.type);
                setText('teacher-detail-status', teacher.status);
                setText('teacher-detail-mobile', teacher.mobile);
                setText('teacher-detail-address', teacher.address);

                $('#teacherDetailsModal').modal('show');
            });
        })();
    </script>
@endsection
