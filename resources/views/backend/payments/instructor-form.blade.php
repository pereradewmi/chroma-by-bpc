@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white border-0 d-flex justify-content-between align-items-center" style="padding: 1.5rem;">
                        <h3 class="mb-0">Record Instructor Payment</h3>
                        <a href="{{ route('instructor-payments.index') }}" class="btn btn-sm btn-light">Back to List</a>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('instructor-payments.store') }}">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">
                                <i class="fas fa-money-bill-wave"></i> Payment Information
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-600" for="instructor_id">Select Instructor</label>
                                        <select id="instructor_id" name="instructor_id" class="form-control form-control-lg" required style="border-radius: 8px;">
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
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-600" for="amount">Amount (Rs.)</label>
                                        <input type="number" id="amount" name="amount" step="0.01" min="0" class="form-control form-control-lg"
                                            placeholder="Enter payment amount" required style="border-radius: 8px;"
                                            value="{{ old('amount') }}">
                                        @error('amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
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
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-600" for="sessions_count">Number of Sessions</label>
                                        <input type="number" id="sessions_count" name="sessions_count" min="1" class="form-control form-control-lg"
                                            placeholder="Enter number of sessions" required style="border-radius: 8px;"
                                            value="{{ old('sessions_count') }}">
                                        @error('sessions_count')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-600" for="description">Description</label>
                                        <textarea id="description" name="description" rows="3" class="form-control form-control-lg"
                                            placeholder="Enter payment description (optional)" style="border-radius: 8px;">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('instructor-payments.index') }}" class="btn btn-lg btn-light" style="border-radius: 8px; padding: 0.7rem 2rem;">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary" style="border-radius: 8px; padding: 0.7rem 2rem;">
                                    <i class="fas fa-check-circle"></i> Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
