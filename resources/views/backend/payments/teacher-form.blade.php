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
                        <form method="POST" action="{{ route('teacher-payments.store') }}">
                            @csrf

                            <h6 class="heading-small text-muted mb-3">
                                <i class="fas fa-money-bill-wave"></i> Payment Information
                            </h6>

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
                                <button type="submit" class="btn btn-primary">
                                    Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
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
@endsection
