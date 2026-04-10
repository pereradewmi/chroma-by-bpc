@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Student' : 'Register New Student' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="student_id" value="{{ $student->AutoID }}">
                            @endif

                            <div class="pl-lg-4 student-form-compact">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="fName">First Name <span class="text-danger">*</span></label>
                                            <input type="text" id="fName" name="fName" 
                                                class="form-control form-control-alternative @error('fName') is-invalid @enderror" 
                                                placeholder="First Name" 
                                                value="{{ old('fName', $student->fName) }}" required>
                                            @error('fName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="lName">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" id="lName" name="lName" 
                                                class="form-control form-control-alternative @error('lName') is-invalid @enderror" 
                                                placeholder="Last Name" 
                                                value="{{ old('lName', $student->lName) }}" required>
                                            @error('lName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="mobileNo">Mobile Number <span class="text-danger">*</span></label>
                                            <input type="text" id="mobileNo" name="mobileNo"
                                                class="form-control form-control-alternative @error('mobileNo') is-invalid @enderror"
                                                placeholder="Mobile Number"
                                                value="{{ old('mobileNo', $student->mobileNo) }}" required>
                                            @error('mobileNo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="studentemail">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" id="studentemail" name="studentemail"
                                                class="form-control form-control-alternative @error('studentemail') is-invalid @enderror"
                                                placeholder="Email Address"
                                                value="{{ old('studentemail', $student->studentemail) }}" required>
                                            @error('studentemail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="Age">Age <span class="text-danger">*</span></label>
                                            <input type="number" id="Age" name="Age"
                                                class="form-control form-control-alternative @error('Age') is-invalid @enderror"
                                                placeholder="Age" min="1" max="100"
                                                value="{{ old('Age', $student->Age) }}" required>
                                            @error('Age')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="Address">Address <span class="text-danger">*</span></label>
                                            <textarea id="Address" name="Address" rows="2"
                                                class="form-control form-control-alternative @error('Address') is-invalid @enderror" 
                                                placeholder="Address" required>{{ old('Address', $student->Address) }}</textarea>
                                            @error('Address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="studentpic">Student Picture</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('studentpic') is-invalid @enderror"
                                                    id="studentpic" name="studentpic" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <label class="custom-file-label" for="studentpic">Choose image...</label>
                                            </div>
                                            @if($isEdit && $student->studentpic)
                                                <small class="form-text text-muted d-block mt-2">
                                                    <strong>Current Picture:</strong> {{ $student->studentpic }}
                                                </small>
                                            @endif
                                            @error('studentpic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                                            <input type="text" id="guardian_name" name="guardian_name"
                                                class="form-control form-control-alternative @error('guardian_name') is-invalid @enderror"
                                                placeholder="Guardian's Full Name"
                                                value="{{ old('guardian_name', $student->guardian_name) }}" required>
                                            @error('guardian_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="guardian_phone">Guardian Phone <span class="text-danger">*</span></label>
                                            <input type="text" id="guardian_phone" name="guardian_phone"
                                                class="form-control form-control-alternative @error('guardian_phone') is-invalid @enderror"
                                                placeholder="Guardian's Phone Number"
                                                value="{{ old('guardian_phone', $student->guardian_phone) }}" required>
                                            @error('guardian_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="classDropdownToggle">Assign Classes</label>
                                            @php
                                                use App\Models\ClassRoom;
                                                $classes = ClassRoom::orderBy('cName')->get();
                                                $selectedClasses = $isEdit ? json_decode($student->class_ids ?? '[]', true) : [];
                                            @endphp
                                            <div class="dropdown-multiselect">
                                                <button type="button" class="form-control form-control-alternative d-flex justify-content-between align-items-center" id="classDropdownToggle">
                                                    <span class="selected-text">Select classes</span>
                                                    <span class="text-muted"><i class="fas fa-chevron-down"></i></span>
                                                </button>
                                                <div class="dropdown-menu w-100 mt-1" id="classDropdownMenu">
                                                    @foreach($classes as $class)
                                                        <div class="custom-control custom-checkbox px-3 py-1">
                                                            <input type="checkbox" class="custom-control-input" id="class_{{ $class->cID }}" name="class_ids[]" value="{{ $class->cID }}" {{ in_array($class->cID, $selectedClasses) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="class_{{ $class->cID }}">
                                                                {{ $class->cName }} - Rs. {{ number_format($class->classfee ?? 0, 2) }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @error('class_ids')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4 align-items-center">
                                    <div class="col-lg-4">
                                        <div class="form-group mb-0">
                                            <label class="form-control-label">Status</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="Active" value="0">
                                                <input type="checkbox" id="Active" name="Active" value="1"
                                                    class="custom-control-input"
                                                    {{ old('Active', $isEdit ? $student->Active : 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="Active">Active Student</label>
                                            </div>
                                            @error('Active')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-8 text-right">
                                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            {{ $isEdit ? 'Update Student' : 'Register Student' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .student-form-compact .form-group {
            margin-bottom: 0.75rem;
        }

        .student-form-compact .heading-small {
            margin-bottom: 1rem !important;
        }

        .dropdown-multiselect {
            position: relative;
        }

        .dropdown-multiselect .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            max-height: 240px;
            overflow-y: auto;
            padding: 0.25rem 0.75rem;
            border: 1px solid rgba(0,0,0,.15);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
            background-color: #fff;
        }

        .dropdown-multiselect .dropdown-menu.show {
            display: block;
        }

        .dropdown-multiselect .custom-control {
            padding-left: 2.25rem;
        }

        .dropdown-multiselect .custom-control-label {
            padding-left: 0.25rem;
        }

        .dropdown-multiselect .custom-control-label::before,
        .dropdown-multiselect .custom-control-label::after {
            left: -1.35rem;
        }

        #classDropdownToggle {
            cursor: pointer;
        }
    </style>

    <script>
        // Handle file input label update
        document.getElementById('studentpic')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            const label = document.querySelector('.custom-file-label');
            if (label) {
                label.textContent = fileName;
            }
        });

        // Handle class multi-select dropdown with checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggle = document.getElementById('classDropdownToggle');
            const dropdownMenu = document.getElementById('classDropdownMenu');

            if (!dropdownToggle || !dropdownMenu) {
                return;
            }

            const updateSelectedText = () => {
                const checkboxes = dropdownMenu.querySelectorAll('input[type="checkbox"]');
                const selectedCheckboxes = Array.from(checkboxes).filter(c => c.checked);
                const textSpan = dropdownToggle.querySelector('.selected-text');
                if (!textSpan) return;

                if (selectedCheckboxes.length === 0) {
                    textSpan.textContent = 'Select classes';
                    return;
                }

                const labels = selectedCheckboxes.map(c => {
                    const label = dropdownMenu.querySelector('label[for="' + c.id + '"]');
                    return label ? label.innerText.trim() : '';
                }).filter(Boolean);

                textSpan.textContent = labels.join(', ');
            };

            dropdownToggle.addEventListener('click', function (e) {
                e.preventDefault();
                dropdownMenu.classList.toggle('show');
            });

            dropdownMenu.addEventListener('click', function (e) {
                // Prevent closing when clicking inside the menu
                e.stopPropagation();
            });

            document.addEventListener('click', function (e) {
                if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });

            dropdownMenu.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    updateSelectedText();
                });
            });

            // Initialize selected text on page load
            updateSelectedText();
        });
    </script>
@endsection
