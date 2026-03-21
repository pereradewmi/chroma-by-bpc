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
                                <h3 class="mb-0">Record Payment Details</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Step 1: Student Search -->
                        <div id="step1" class="payment-step">
                            <h6 class="heading-small text-muted mb-4">Step 1: Search Student</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label class="form-control-label" for="student_search">Search Student by Name or ID</label>
                                            <input type="text" id="student_search" name="student_search"
                                                class="form-control form-control-alternative"
                                                placeholder="Enter student name or ID..."
                                                autocomplete="off">
                                            <div id="search_results" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Student Details & Payment Info -->
                        <div id="step2" class="payment-step" style="display: none;">
                            <h6 class="heading-small text-muted mb-4">Step 2: Payment Details</h6>

                            <!-- Selected Student Details -->
                            <div class="pl-lg-4">
                                <div class="alert alert-info" id="selected_student_info" style="display: none;">
                                    <h5 class="alert-heading">
                                        <i class="fas fa-user"></i> Selected Student
                                    </h5>
                                    <div id="student_details_content"></div>
                                    <hr>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="changeStudent()">
                                        <i class="fas fa-edit"></i> Change Student
                                    </button>
                                </div>

                                <form id="payment_form">
                                    <input type="hidden" id="selected_student_id" name="studentID" value="">

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="classID">Select Class</label>
                                                <select id="classID" name="classID" class="form-control form-control-alternative" required>
                                                    <option value="">Choose a class...</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->cID }}" data-fee="{{ $class->classfee ?? 0 }}">
                                                            {{ $class->cName }} - Rs. {{ number_format($class->classfee ?? 0, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="month">Select Month</label>
                                                <select id="month" name="month" class="form-control form-control-alternative" required>
                                                    <option value="">Choose a month...</option>
                                                    @foreach($months as $code => $name)
                                                        <option value="{{ $code }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="button" id="confirm_payment_btn" class="btn btn-warning" onclick="confirmPayment()">
                                                <i class="fas fa-check"></i> Confirm Payment Details
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Step 3: Final Confirmation -->
                        <div id="step3" class="payment-step" style="display: none;">
                            <h6 class="heading-small text-muted mb-4">Step 3: Final Confirmation</h6>

            <div class="pl-lg-4">
                                <div class="alert alert-warning">
                                    <h5 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle"></i> Confirm Payment
                                    </h5>
                                    <p>Please review the payment details below and confirm to record the payment:</p>
                                    <div id="final_confirmation_details"></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-success" onclick="finalizePayment()">
                                            <i class="fas fa-credit-card"></i> Record Payment
                                        </button>
                                        <button type="button" class="btn btn-secondary ml-2" onclick="goBackToStep2()">
                                            <i class="fas fa-arrow-left"></i> Back
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading Spinner -->
                        <div id="loading" style="display: none;">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Processing...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            fetch(`/backend/payments/search-student?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(students => {
                    displaySearchResults(students);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    document.getElementById('search_results').innerHTML =
                        '<div class="alert alert-danger">Search failed. Please try again.</div>';
                });
        }

        function displaySearchResults(students) {
            const resultsDiv = document.getElementById('search_results');

            if (students.length === 0) {
                resultsDiv.innerHTML = '<div class="alert alert-warning">No students found.</div>';
                return;
            }

            let html = '<div class="list-group">';
            students.forEach(student => {
                html += `
                    <a href="#" class="list-group-item list-group-item-action" onclick="selectStudent(${student.id})">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${student.name}</h6>
                            <small>ID: ${student.id}</small>
                        </div>
                        <p class="mb-1">${student.mobile} | ${student.email}</p>
                        <small>Age: ${student.age} | ${student.address}</small>
                    </a>
                `;
            });
            html += '</div>';

            resultsDiv.innerHTML = html;
        }

        function selectStudent(studentId) {
            selectedStudent = studentId;

            fetch(`/backend/payments/student-details/${studentId}`)
                .then(response => response.json())
                .then(student => {
                    document.getElementById('selected_student_id').value = student.id;

                    const detailsHtml = `
                        <strong>Name:</strong> ${student.name}<br>
                        <strong>ID:</strong> ${student.id}<br>
                        <strong>Mobile:</strong> ${student.mobile}<br>
                        <strong>Email:</strong> ${student.email}<br>
                        <strong>Age:</strong> ${student.age}<br>
                        <strong>Address:</strong> ${student.address}
                    `;

                    document.getElementById('student_details_content').innerHTML = detailsHtml;
                    document.getElementById('selected_student_info').style.display = 'block';

                    // Move to step 2
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step2').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching student details:', error);
                    alert('Failed to load student details. Please try again.');
                });
        }

        function changeStudent() {
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step3').style.display = 'none';
            document.getElementById('student_search').value = '';
            document.getElementById('search_results').innerHTML = '';
            document.getElementById('payment_form').reset();
        }

        function confirmPayment() {
            const form = document.getElementById('payment_form');
            const formData = new FormData(form);

            if (!formData.get('studentID') || !formData.get('classID') || !formData.get('month')) {
                alert('Please fill in all required fields.');
                return;
            }

            document.getElementById('loading').style.display = 'block';
            document.getElementById('step2').style.display = 'none';

            fetch('/backend/payments/confirm', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';

                if (data.success) {
                    const confirmationHtml = `
                        <strong>Student:</strong> ${data.data.student.name} (ID: ${data.data.student.id})<br>
                        <strong>Mobile:</strong> ${data.data.student.mobile}<br>
                        <strong>Email:</strong> ${data.data.student.email}<br>
                        <strong>Class:</strong> ${data.data.class.name}<br>
                        <strong>Class Fee:</strong> Rs. ${parseFloat(data.data.class.fee).toFixed(2)}<br>
                        <strong>Month:</strong> ${data.data.month.name}
                    `;

                    document.getElementById('final_confirmation_details').innerHTML = confirmationHtml;
                    document.getElementById('step3').style.display = 'block';
                } else {
                    document.getElementById('step2').style.display = 'block';

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
                document.getElementById('step2').style.display = 'block';
                console.error('Confirmation error:', error);
                alert('Failed to confirm payment. Please try again.');
            });
        }

        function goBackToStep2() {
            document.getElementById('step2').style.display = 'block';
            document.getElementById('step3').style.display = 'none';
        }

        function finalizePayment() {
            const form = document.getElementById('payment_form');
            const formData = new FormData(form);

            document.getElementById('loading').style.display = 'block';
            document.getElementById('step3').style.display = 'none';

            fetch('/backend/payments/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';

                if (data.success) {
                    alert(data.message);
                    // Redirect to payments index
                    window.location.href = '{{ route("payments.index") }}';
                } else {
                    document.getElementById('step3').style.display = 'block';

                    if (data.errors) {
                        let errorMsg = 'Validation errors:\n';
                        Object.keys(data.errors).forEach(key => {
                            errorMsg += '- ' + data.errors[key].join(', ') + '\n';
                        });
                        alert(errorMsg);
                    } else {
                        alert(data.message || 'Failed to record payment. Please try again.');
                    }
                }
            })
            .catch(error => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('step3').style.display = 'block';
                console.error('Payment error:', error);
                alert('Failed to record payment. Please try again.');
            });
        }
    </script>
@endsection
