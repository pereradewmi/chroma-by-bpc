@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white border-0 d-flex justify-content-between align-items-center" style="padding: 1.25rem 1.5rem;">
                        <h3 class="mb-0">Record Instructor Payment</h3>
                        <a href="{{ route('instructor-payments.index') }}" class="btn btn-sm btn-light">Back to List</a>
                    </div>

                    <div class="card-body p-4 instructor-payment-form-compact">
                        <form id="instructor_payment_form" method="POST" action="{{ route('instructor-payments.store') }}">
                            @csrf

                            <!-- First row: Instructor and Session -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="instructor_id">Select Instructor</label>
                                        <select id="instructor_id" name="instructor_id" class="form-control form-control-alternative" required>
                                            <option value="">Choose an instructor...</option>
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->T_ID }}" {{ old('instructor_id') == $instructor->T_ID ? 'selected' : '' }}>
                                                    {{ $instructor->tFName }} {{ $instructor->tLName }} - {{ $instructor->tMobileNo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('instructor_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="session_id">Select Session</label>
                                        <select id="session_id" name="session_id" class="form-control form-control-alternative" required>
                                            <option value="">Choose a session...</option>
                                            @foreach($sessions as $session)
                                                <option value="{{ $session->sID }}" {{ old('session_id') == $session->sID ? 'selected' : '' }}>
                                                    {{ $session->sName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('session_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Second row: Month and Amount -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="month">Select Month</label>
                                        <select id="month" name="month" class="form-control form-control-alternative" required>
                                            <option value="">Choose a month...</option>
                                            @foreach($months as $code => $name)
                                                <option value="{{ $code }}" {{ old('month') == $code ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('month')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="amount">Amount (Rs.)</label>
                                        <input type="number" id="amount" name="amount" step="0.01" min="0" class="form-control form-control-alternative"
                                            placeholder="Enter payment amount" required
                                            value="{{ old('amount') }}">
                                        @error('amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Third row: Number of Sessions and Description (equal width) -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="sessions_count">No. of Sessions</label>
                                        <input type="number" id="sessions_count" name="sessions_count" min="1" class="form-control form-control-alternative"
                                            placeholder="Enter number of sessions" required
                                            value="{{ old('sessions_count') }}">
                                        @error('sessions_count')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="description">Description</label>
                                        <textarea id="description" name="description" rows="2" class="form-control form-control-alternative"
                                            placeholder="Optional notes">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-4 d-flex justify-content-end">
                                <a href="{{ route('instructor-payments.index') }}" class="btn btn-secondary mr-2">
                                    Cancel
                                </a>
                                <button type="button" class="btn btn-primary" onclick="openInstructorPaymentConfirmModal()">
                                    Record Payment
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructor Payment Confirm Modal -->
    <div class="modal fade" id="instructorPaymentConfirmModal" tabindex="-1" role="dialog" aria-labelledby="instructorPaymentConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instructorPaymentConfirmModalLabel">Confirm Instructor Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to record this instructor payment? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('instructor_payment_form').submit();">Yes, record payment</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .instructor-payment-form-compact .form-group {
            margin-bottom: 0.75rem;
        }

        .instructor-payment-form-compact .heading-small {
            margin-bottom: 1rem !important;
        }
    </style>

    <script>
        function openInstructorPaymentConfirmModal() {
            $('#instructorPaymentConfirmModal').modal('show');
        }
    </script>
@endsection
