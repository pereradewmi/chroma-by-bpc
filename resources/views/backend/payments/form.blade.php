@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-10 offset-xl-1 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white border-0 d-flex justify-content-between align-items-center" style="padding: 1.5rem;">
                        <h3 class="mb-0">Record Payment Details</h3>
                        <a href="{{ route('payments.index') }}" class="btn btn-sm btn-light">Back to List</a>
                    </div>

                    <div class="card-body p-4">
                        <!-- Single Form (Simplified) -->
                        <form id="payment_form">
                            <h6 class="heading-small text-muted mb-3">
                                Payment Information
                            </h6>

                            <!-- Step 1: Student Search -->
                            <div id="step1" class="payment-step mb-4 pb-4 border-bottom">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-600">Search Student</label>
                                    <input type="text" id="student_search" name="student_search"
                                        class="form-control form-control-lg"
                                        placeholder="Type student name or ID..."
                                        autocomplete="off" style="border-radius: 8px;">
                                    <div id="search_results" class="mt-2"></div>
                                    <input type="hidden" id="selected_student_id" name="studentID" value="">
                                </div>

                                <!-- Selected Student Info -->
                                <div class="alert alert-secondary mt-3 p-3" id="selected_student_info" style="display: none; border-radius: 8px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="alert-heading mb-2">
                                                <i class="fas fa-check-circle"></i> Student Selected
                                            </h6>
                                            <div id="student_details_content" class="small"></div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-3" onclick="changeStudent()">
                                            <i class="fas fa-edit"></i> Change
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Payment Details (Only shows after student selection) -->
                            <div id="step2" style="display: none;">
                                <h6 class="heading-small text-muted mb-3">
                                    <i class="fas fa-file-invoice"></i> Payment Details
                                </h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-600" for="paymentTypeDropdown">Payment Type</label>
                                            <select id="paymentTypeDropdown" name="payment_type" class="form-control form-control-lg" style="border-radius: 8px;">
                                                <option value="class_fee">Class Fee</option>
                                                <option value="admission">Admission</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-600" for="classID">Select Class</label>
                                            <select id="classID" name="classID" class="form-control form-control-lg" required style="border-radius: 8px;">
                                                <option value="">Choose a class...</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->cID }}" data-fee="{{ $class->classfee ?? 0 }}" data-admission="{{ $class->admission_amount ?? 0 }}">
                                                        {{ $class->cName }} - Rs. {{ number_format($class->classfee ?? 0, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-600" for="month">Select Month</label>
                                            <select id="month" name="month" class="form-control form-control-lg" required style="border-radius: 8px;">
                                                <option value="">Choose a month...</option>
                                                @foreach($months as $code => $name)
                                                    <option value="{{ $code }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-lg btn-light" onclick="resetForm()" style="border-radius: 8px; padding: 0.7rem 2rem;">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                    <button type="button" id="submit_payment_btn" class="btn btn-lg btn-primary" onclick="submitPayment()" style="border-radius: 8px; padding: 0.7rem 2rem;">
                                        <i class="fas fa-check-circle"></i> Record Payment
                                    </button>
                                </div>

                                <!-- Loading Spinner -->
                                <div id="loading" style="display: none;" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Processing payment...</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control-lg {
            font-size: 1rem;
            padding: 0.75rem 1rem;
        }
        .btn-lg {
            font-size: 1rem;
        }
        .list-group-item {
            border-radius: 8px !important;
            margin-bottom: 0.5rem;
        }
        #search_results .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>

    <script>
        let searchTimeout;
        let selectedStudent = null;

        // Student search functionality
        document.getElementById('student_search').addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                document.getElementById('search_results').innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                searchStudents(query);
            }, 300);
        });

        function searchStudents(query) {
            fetch(`{{ route('payments.search-student') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(students => {
                    displaySearchResults(students);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    document.getElementById('search_results').innerHTML =
                        '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Search failed. Please try again.</div>';
                });
        }

        function displaySearchResults(students) {
            const resultsDiv = document.getElementById('search_results');

            if (students.length === 0) {
                resultsDiv.innerHTML = '<div class="alert alert-warning"><i class="fas fa-search"></i> No students found.</div>';
                return;
            }

            let html = '<div class="list-group">';
            students.forEach(student => {
                html += `
                    <button type="button" class="list-group-item list-group-item-action text-start" onclick="selectStudent(${student.id}); return false;">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 font-weight-600">${student.name}</h6>
                                <small class="text-muted">${student.mobile} | ${student.email}</small>
                            </div>
                            <span class="badge bg-primary">ID: ${student.id}</span>
                        </div>
                    </button>
                `;
            });
            html += '</div>';

            resultsDiv.innerHTML = html;
        }

        function selectStudent(studentId) {
            selectedStudent = studentId;

            fetch(`{{ route('payments.student-details', ':id') }}`.replace(':id', studentId))
                .then(response => response.json())
                .then(student => {
                    document.getElementById('selected_student_id').value = student.id;

                    const detailsHtml = `
                        <p class="mb-1"><strong>${student.name}</strong></p>
                        <p class="mb-0 small text-muted">${student.mobile} | ${student.email}</p>
                    `;

                    document.getElementById('student_details_content').innerHTML = detailsHtml;
                    document.getElementById('selected_student_info').style.display = 'block';
                    document.getElementById('search_results').innerHTML = '';

                    // Show step 2
                    document.getElementById('step2').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching student details:', error);
                    alert('Failed to load student details. Please try again.');
                });
        }

        function changeStudent() {
            document.getElementById('student_search').value = '';
            document.getElementById('search_results').innerHTML = '';
            document.getElementById('selected_student_info').style.display = 'none';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('selected_student_id').value = '';
            document.getElementById('payment_form').reset();
        }

        function resetForm() {
            document.getElementById('payment_form').reset();
            document.getElementById('student_search').focus();
        }

        function submitPayment() {
            const studentID = document.getElementById('selected_student_id').value;
            const classID = document.getElementById('classID').value;
            const month = document.getElementById('month').value;
            const paymentType = document.getElementById('paymentTypeDropdown').value;

            if (!studentID || !classID || !month) {
                alert('Please fill in all required fields.');
                return;
            }

            document.getElementById('loading').style.display = 'block';
            document.getElementById('submit_payment_btn').disabled = true;

            fetch('{{ route("payments.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    studentID: studentID,
                    classID: classID,
                    month: month,
                    payment_type: paymentType
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('submit_payment_btn').disabled = false;

                if (data.success) {
                    alert('Payment recorded successfully!');
                    changeStudent();
                } else {
                    if (data.errors) {
                        let errorMsg = 'Validation errors:\n';
                        Object.keys(data.errors).forEach(key => {
                            errorMsg += '- ' + data.errors[key].join(', ') + '\n';
                        });
                        alert(errorMsg);
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                }
            })
            .catch(error => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('submit_payment_btn').disabled = false;
                console.error('Payment error:', error);
                alert('Failed to record payment. Please try again.');
            });
        }
    </script>

@endsection
