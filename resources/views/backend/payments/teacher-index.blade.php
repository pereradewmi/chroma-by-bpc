@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-4 d-flex align-items-center">
                                <h3 class="mb-0">Teacher Payments</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <form id="teacher-payments-search-form" class="d-flex align-items-center" role="search" method="GET" action="{{ route('teacher-payments.index') }}">
                                    <input class="form-control form-control-sm" style="width: 230px;" type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-sm btn-primary ml-3" type="submit" title="Search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('teacher-payments.form') }}" class="btn btn-sm btn-primary" title="Add Payment">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
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
                                    <th scope="col">Teacher</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Month</th>
                                    <th scope="col">Payment Date</th>
                                    <th scope="col" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teacher-payments-table-body">
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->paymentID }}</td>
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <span class="mb-0 text-sm font-weight-bold">
                                                        {{ $payment->teacher->tFName }} {{ $payment->teacher->tLName }}
                                                    </span>
                                                    <br>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>Rs. {{ number_format($payment->amount, 2) }}</strong>
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
                                        <td class="text-right">
                                            <a href="{{ route('teacher-payments.receipt', $payment->paymentID) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-invoice"></i> Receipt
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-center">
                                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                                <h4>No Payment Records Found</h4>
                                                <p class="text-muted">There are no teacher payment records in the system yet.</p>
                                                <a href="{{ route('teacher-payments.form') }}" class="btn btn-primary">Add First Payment</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer py-4" id="teacher-payments-pagination-wrapper">
                        <nav class="d-flex justify-content-end" aria-label="..." id="teacher-payments-pagination">
                            @if($payments->hasPages())
                                {{ $payments->links() }}
                            @else
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled"><span class="page-link" aria-label="Previous"><i class="fas fa-chevron-left" aria-hidden="true"></i></span></li>
                                    <li class="page-item active btn-primary"><span class="btn-primary page-link">1</span></li>
                                    <li class="page-item disabled"><span class="page-link" aria-label="Next"><i class="fas fa-chevron-right" aria-hidden="true"></i></span></li>
                                </ul>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('teacher-payments-search-form');
            const input = form ? form.querySelector('input[name="search"]') : null;
            const tableBody = document.getElementById('teacher-payments-table-body');
            const pagination = document.getElementById('teacher-payments-pagination');
            let searchTimer = null;

            if (!form || !input || !tableBody) {
                return;
            }

            const loadTeacherPayments = function (url, pushState = true) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) {
                        return response.text();
                    })
                    .then(function (html) {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const nextTableBody = doc.getElementById('teacher-payments-table-body');
                        const nextPagination = doc.getElementById('teacher-payments-pagination');

                        if (nextTableBody) {
                            tableBody.innerHTML = nextTableBody.innerHTML;
                        }

                        if (pagination && nextPagination) {
                            pagination.innerHTML = nextPagination.innerHTML;
                        }

                        if (pushState) {
                            window.history.pushState({}, '', url);
                        }
                    })
                    .catch(function (error) {
                        console.error('Failed to load teacher payments list:', error);
                    });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const searchValue = (input.value || '').trim();
                const targetUrl = new URL(form.action, window.location.origin);

                if (searchValue !== '') {
                    targetUrl.searchParams.set('search', searchValue);
                }

                loadTeacherPayments(targetUrl.toString());
            });

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                }, 300);
            });

            document.addEventListener('click', function (event) {
                const pageLink = event.target.closest('#teacher-payments-pagination a.page-link');

                if (!pageLink || !pageLink.href) {
                    return;
                }

                event.preventDefault();
                loadTeacherPayments(pageLink.href);
            });

            window.addEventListener('popstate', function () {
                loadTeacherPayments(window.location.href, false);
            });
        })();
    </script>
@endsection
