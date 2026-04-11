@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">User Payments Report</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.user-payments') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-control-label">Class</label>
                                    <select name="class_id" class="form-control">
                                        <option value="">All Classes</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->cID }}" {{ ($filters['class_id'] ?? '') == $class->cID ? 'selected' : '' }}>
                                                {{ $class->cName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-control-label">Teacher / Instructor</label>
                                    <select name="teacher_id" class="form-control">
                                        <option value="">All Teachers</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->T_ID }}" {{ ($filters['teacher_id'] ?? '') == $teacher->T_ID ? 'selected' : '' }}>
                                                {{ $teacher->tFName }} {{ $teacher->tLName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-control-label">Student</label>
                                    <select name="student_id" class="form-control">
                                        <option value="">All Students</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->AutoID }}" {{ ($filters['student_id'] ?? '') == $student->AutoID ? 'selected' : '' }}>
                                                {{ $student->fName }} {{ $student->lName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-control-label">From Date</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-control-label">To Date</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                                </div>
                                <div class="col-md-9 d-flex align-items-end justify-content-end mb-3">
                                    <button type="submit" class="btn btn-primary mr-2">Apply Filters</button>
                                    <a href="{{ route('reports.user-payments') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <h3 class="mb-0">Payments</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Teacher / Instructor</th>
                                    <th scope="col">Class / Session</th>
                                    <th scope="col">Month</th>
                                    <th scope="col">Sessions</th>
                                    <th scope="col">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ optional($payment['date'])->format('Y-m-d') }}</td>
                                        <td>{{ $payment['type'] }}</td>
                                        <td>{{ $payment['student'] ?? '-' }}</td>
                                        <td>{{ $payment['teacher'] ?? '-' }}</td>
                                        <td>{{ $payment['class'] ?? '-' }}</td>
                                        <td>{{ $payment['month'] ?? '-' }}</td>
                                        <td>{{ $payment['sessions_count'] ?? '-' }}</td>
                                        <td>Rs. {{ number_format($payment['amount'] ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No payments found for the selected filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
