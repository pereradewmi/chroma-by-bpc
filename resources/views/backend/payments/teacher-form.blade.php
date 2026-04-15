@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white border-0 d-flex justify-content-between align-items-center" style="padding: 1.25rem 1.5rem;">
                        <h3 class="mb-0">Record Teacher Payment</h3>
                        <a href="{{ route('teacher-payments.index') }}" class="btn btn-sm btn-light">Back to List</a>
                    </div>

                    <div class="card-body p-4 teacher-payment-form-compact">
                        <form id="teacher_payment_form" method="POST" action="{{ route('teacher-payments.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-2">
                                        <label class="form-control-label font-weight-600" for="teacher_id">Select Teacher</label>
                                        <select id="teacher_id" name="teacher_id" class="form-control form-control-alternative" required>
                                            <option value="">Choose a teacher...</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->T_ID }}" {{ old('teacher_id') == $teacher->T_ID ? 'selected' : '' }}>
                                                    {{ $teacher->tFName }} {{ $teacher->tLName }} - {{ $teacher->tMobileNo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
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

                            <div class="row">
                                <div class="col-md-6 mb-0">
                                    <div class="form-group">
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
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-4 d-flex justify-content-end">
                                <a href="{{ route('teacher-payments.index') }}" class="btn btn-secondary mr-2">
                                    Cancel
                                </a>
                                <button type="button" class="btn btn-primary" onclick="openTeacherPaymentConfirmModal()">
                                    Record Payment
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Payment Confirm Modal -->
    <div class="modal fade" id="teacherPaymentConfirmModal" tabindex="-1" role="dialog" aria-labelledby="teacherPaymentConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherPaymentConfirmModalLabel">Confirm Teacher Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to record this teacher payment? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('teacher_payment_form').submit();">Yes, record payment</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .teacher-payment-form-compact .form-group {
            margin-bottom: 0.75rem;
        }

        .teacher-payment-form-compact .heading-small {
            margin-bottom: 1rem !important;
        }
    </style>

    <script>
        function openTeacherPaymentConfirmModal() {
            $('#teacherPaymentConfirmModal').modal('show');
        }
    </script>
@endsection
