@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Payment Management</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="card-header bg-light border-bottom">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payments.index') }}" role="tab">
                                    <i class="fas fa-graduation-cap"></i> Student Payments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher-payments.index') }}" role="tab">
                                    <i class="fas fa-chalkboard-user"></i> Teacher Payments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('instructor-payments.index') }}" role="tab">
                                    <i class="fas fa-person-chalkboard"></i> Instructor Payments
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="row align-items-center">
                        <div class="col ml-auto">
                            <a href="{{ route('instructor-payments.form') }}" class="btn btn-sm btn-primary mt-3 mr-3">Add New Payment</a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Payment ID</th>
                                    <th scope="col">Instructor</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Sessions</th>
                                    <th scope="col">Month</th>
                                    <th scope="col">Payment Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->paymentID }}</td>
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <span class="mb-0 text-sm font-weight-bold">
                                                        {{ $payment->instructor->tFName }} {{ $payment->instructor->tLName }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $payment->instructor->T_ID }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>Rs. {{ number_format($payment->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $payment->sessions_count }} Sessions</span>
                                        </td>
                                        <td>
                                            @php
                                                $months = [
                                                    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                                                    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                                                    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                                                ];
                                            @endphp
                                            {{ $months[$payment->month] ?? 'Unknown' }}
                                        </td>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('instructor-payments.destroy', $payment->paymentID) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-center">
                                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                                <h4>No Payment Records Found</h4>
                                                <p class="text-muted">There are no instructor payment records in the system yet.</p>
                                                <a href="{{ route('instructor-payments.form') }}" class="btn btn-primary">Add First Payment</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($payments->hasPages())
                        <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $payments->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
