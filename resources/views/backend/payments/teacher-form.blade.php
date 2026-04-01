@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white border-0 d-flex justify-content-between align-items-center" style="padding: 1.5rem;">
                        <h3 class="mb-0">Record Teacher Payment</h3>
                        <a href="{{ route('teacher-payments.index') }}" class="btn btn-sm btn-light">Back to List</a>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('teacher-payments.store') }}">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">
                                <i class="fas fa-money-bill-wave"></i> Payment Information
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-600" for="teacher_id">Select Teacher</label>
                                        <select id="teacher_id" name="teacher_id" class="form-control form-control-lg" required style="border-radius: 8px;">
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
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('teacher-payments.index') }}" class="btn btn-lg btn-light" style="border-radius: 8px; padding: 0.7rem 2rem;">
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
