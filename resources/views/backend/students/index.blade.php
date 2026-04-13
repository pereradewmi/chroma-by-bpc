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
                                <h3 class="mb-0">Students</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <form id="students-search-form" class="d-flex align-items-center" role="search" method="GET" action="{{ route('students.index') }}">
                                    <input class="form-control form-control-sm" style="width: 230px;" type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-sm btn-primary ml-3" type="submit" title="Search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('students.form') }}" class="btn btn-sm btn-primary" title="Add Student">
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
                                    {{-- <th scope="col">Photo</th> --}}
                                    <th scope="col">Name</th>
                                    <th scope="col">Classes</th>
                                    {{-- <th scope="col">Mobile Number</th>
                                    <th scope="col">Guardian Name</th> --}}
                                    {{-- <th scope="col">Status</th> --}}
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="students-table-body">
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ ($students->firstItem() ?? 1) + $loop->index }}</td>
                                        {{-- <td>
                                            @if($student->studentpic)
                                                <img
                                                    src="{{ $student->photo_url }}"
                                                    alt="{{ $student->fName }} {{ $student->lName }}"
                                                    class="avatar rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                >
                                            @else
                                                <span class="avatar rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($student->fName, 0, 1)) }}
                                                </span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            @php
                                                // Prefer pivot table if available, fall back to JSON column
                                                $classNames = [];

                                                if ($student->relationLoaded('classes') || method_exists($student, 'classes')) {
                                                    foreach ($student->classes as $class) {
                                                        $classNames[] = $class->cName;
                                                    }
                                                }

                                                if (empty($classNames) && !empty($student->class_ids)) {
                                                    $ids = json_decode($student->class_ids, true) ?: [];
                                                    $classNames = App\Models\ClassRoom::whereIn('cID', $ids)->pluck('cName')->toArray();
                                                }

                                                $classNamesText = !empty($classNames)
                                                    ? implode(', ', $classNames)
                                                    : 'No classes assigned';
                                            @endphp
                                            <a
                                                href="javascript:void(0)"
                                                class="student-name-link font-weight-600"
                                                data-name="{{ trim(($student->fName ?? '') . ' ' . ($student->lName ?? '')) }}"
                                                data-auto-id="{{ $student->AutoID }}"
                                                data-classes="{{ $classNamesText }}"
                                                data-mobile="{{ $student->mobileNo }}"
                                                data-email="{{ $student->studentemail }}"
                                                data-age="{{ $student->Age }}"
                                                data-address="{{ $student->Address }}"
                                                data-guardian-name="{{ $student->guardian_name }}"
                                                data-guardian-phone="{{ $student->guardian_phone }}"
                                                data-status="{{ (int) $student->Active === 1 ? 'Active' : ((int) $student->Active === 0 ? 'Inactive' : 'Deleted') }}"
                                                data-photo="{{ $student->studentpic ? $student->photo_url : '' }}"
                                            >
                                                {{ $student->fName }} {{ $student->lName }}
                                            </a>
                                        </td>
                                        <td>{{ $classNamesText }}</td>
                                        {{-- <td>{{ $student->mobileNo }}</td>
                                        <td>{{ $student->guardian_name }}</td> --}}
                                        {{-- <td>
                                            @if((int) $student->Active === 1)
                                                <span class="badge badge-success">Active</span>
                                            @elseif((int) $student->Active === 0)
                                                <span class="badge badge-secondary">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td> --}}
                                        <td >
                                            <a href="{{ route('students.form', $student->AutoID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            @if((int) $student->Active !== 2)
                                                <form action="{{ route('students.status', $student->AutoID) }}" method="POST" class="btn btn-sm text-primary d-inline-block align-middle ml-1" title="Toggle Active/Inactive">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ (int) $student->Active === 1 ? 1 : 0 }}">
                                                    <label class="status-switch mb-0" for="student-status-{{ $student->AutoID }}">
                                                        <input
                                                            type="checkbox"
                                                            id="student-status-{{ $student->AutoID }}"
                                                            {{ (int) $student->Active === 1 ? 'checked' : '' }}
                                                            onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                        >
                                                        <span class="status-slider"></span>
                                                    </label>
                                                </form>
                                            @endif
                                            <form action="{{ route('students.status', $student->AutoID) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
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
                                        <td colspan="4" class="text-center">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-white border-0 py-2 d-flex justify-content-end" id="students-pagination-wrapper">
                        <nav class="mb-0" aria-label="Page navigation example" id="students-pagination">
                            @if($students->hasPages())
                                {{ $students->links() }}
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

    <div class="modal fade" id="studentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentDetailsModalLabel">Student Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img id="student-detail-photo" src="" alt="Student Photo" class="rounded-circle border" style="width: 90px; height: 90px; object-fit: cover; display: none;">
                            <div id="student-detail-photo-placeholder" class="rounded-circle bg-secondary text-white align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 1.4rem; display: inline-flex;">
                                -
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-2"><strong>Name:</strong> <span id="student-detail-name">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Student ID:</strong> <span id="student-detail-id">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Classes:</strong> <span id="student-detail-classes">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Status:</strong> <span id="student-detail-status">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Mobile:</strong> <span id="student-detail-mobile">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Email:</strong> <span id="student-detail-email">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Age:</strong> <span id="student-detail-age">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Guardian:</strong> <span id="student-detail-guardian">-</span></div>
                                <div class="col-md-6 mb-2"><strong>Guardian Phone:</strong> <span id="student-detail-guardian-phone">-</span></div>
                                <div class="col-12 mb-2"><strong>Address:</strong> <span id="student-detail-address">-</span></div>
                            </div>
                        </div>
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

        .card-footer .pagination {
            margin-bottom: 0;
        }

        #students-pagination .pagination {
            margin-bottom: 0;
            gap: 4px;
        }

        #students-pagination .page-link {
            color: #04415f;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px !important;
            padding: 0.35rem 0.7rem;
            line-height: 1.2;
            box-shadow: none !important;
        }

        #students-pagination .page-item.active .page-link {
            color: #ffffff;
            background-color: #04415f;
            border-color: #04415f;
            font-weight: 600;
        }

        #students-pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #ffffff;
            border-color: #dee2e6;
            opacity: 1;
        }

        #students-pagination .page-link:focus {
            box-shadow: none;
        }
    </style>

    <script>
        (function () {
            const form = document.getElementById('students-search-form');
            const input = form ? form.querySelector('input[name="search"]') : null;
            const tableBody = document.getElementById('students-table-body');
            const pagination = document.getElementById('students-pagination');
            const paginationWrapper = document.getElementById('students-pagination-wrapper');
            let searchTimer = null;

            if (!form || !input || !tableBody || !pagination) {
                return;
            }

            const loadStudents = function (url, pushState = true) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) {
                        return response.text();
                    })
                    .then(function (html) {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const nextTableBody = doc.getElementById('students-table-body');
                        const nextPagination = doc.getElementById('students-pagination');

                        if (nextTableBody) {
                            tableBody.innerHTML = nextTableBody.innerHTML;
                        }

                        if (nextPagination) {
                            pagination.innerHTML = nextPagination.innerHTML;
                        }

                        if (pushState) {
                            window.history.pushState({}, '', url);
                        }
                    })
                    .catch(function (error) {
                        console.error('Failed to load students list:', error);
                    });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const searchValue = (input.value || '').trim();
                const targetUrl = new URL(form.action, window.location.origin);

                if (searchValue !== '') {
                    targetUrl.searchParams.set('search', searchValue);
                }

                loadStudents(targetUrl.toString());
            });

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                }, 300);
            });

            document.addEventListener('click', function (event) {
                const pageLink = event.target.closest('#students-pagination a');

                if (!pageLink || !pageLink.href) {
                    return;
                }

                event.preventDefault();
                loadStudents(pageLink.href);
            });

            window.addEventListener('popstate', function () {
                loadStudents(window.location.href, false);
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('.student-name-link');

                if (!link) {
                    return;
                }

                event.preventDefault();

                const student = {
                    name: link.getAttribute('data-name') || '',
                    auto_id: link.getAttribute('data-auto-id') || '',
                    classes: link.getAttribute('data-classes') || '',
                    mobile: link.getAttribute('data-mobile') || '',
                    email: link.getAttribute('data-email') || '',
                    age: link.getAttribute('data-age') || '',
                    address: link.getAttribute('data-address') || '',
                    guardian_name: link.getAttribute('data-guardian-name') || '',
                    guardian_phone: link.getAttribute('data-guardian-phone') || '',
                    status: link.getAttribute('data-status') || '',
                    photo: link.getAttribute('data-photo') || ''
                };

                const setText = function (id, value) {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = value ? String(value) : '-';
                    }
                };

                setText('student-detail-name', student.name);
                setText('student-detail-id', student.auto_id);
                setText('student-detail-classes', student.classes);
                setText('student-detail-status', student.status);
                setText('student-detail-mobile', student.mobile);
                setText('student-detail-email', student.email);
                setText('student-detail-age', student.age);
                setText('student-detail-guardian', student.guardian_name);
                setText('student-detail-guardian-phone', student.guardian_phone);
                setText('student-detail-address', student.address);

                const photo = document.getElementById('student-detail-photo');
                const photoPlaceholder = document.getElementById('student-detail-photo-placeholder');

                if (photo && photoPlaceholder) {
                    if (student.photo) {
                        photo.src = student.photo;
                        photo.style.display = 'inline-block';
                        photoPlaceholder.style.display = 'none';
                    } else {
                        photo.removeAttribute('src');
                        photo.style.display = 'none';
                        photoPlaceholder.style.display = 'inline-flex';
                        photoPlaceholder.textContent = (student.name || '-').charAt(0).toUpperCase() || '-';
                    }
                }

                $('#studentDetailsModal').modal('show');
            });
        })();
    </script>
@endsection
