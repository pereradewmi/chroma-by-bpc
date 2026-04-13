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
                                <h3 class="mb-0">Student Payments</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <form id="payments-search-form" class="d-flex align-items-center" role="search" method="GET" action="{{ route('payments.index') }}">
                                    <input class="form-control form-control-sm" style="width: 230px;" type="search" name="search" value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-sm btn-primary ml-3" type="submit" title="Search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('payments.form') }}" class="btn btn-sm btn-primary" title="Add Payment">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @php
                        $inlineSuccess = request()->has('success') && request()->success == 1;
                    @endphp

                    @if(session('success') || $inlineSuccess)
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text">
                                {{ session('success') ?? 'Payment recorded successfully.' }}
                            </span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="px-4 pb-3"></div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Payment ID</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Month</th>
                                    <th scope="col">Class Fee</th>
                                    <th scope="col">Payment Date</th>
                                    <th scope="col" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="payments-table-body">
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->paymentID }}</td>
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <span class="mb-0 text-sm font-weight-bold">
                                                        {{ $payment->student->fName }} {{ $payment->student->lName }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $payment->student->AutoID }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-dot mr-4">
                                                <span class="status"></span>
                                                <span class="text-dark">{{ $payment->classRoom->cName }}</span>
                                            </span>
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
                                        <td>Rs. {{ number_format($payment->classRoom->classfee ?? 0, 2) }}</td>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('payments.receipt', $payment->paymentID) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-invoice"></i> Receipt
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-center">
                                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                                <h4>No Payment Records Found</h4>
                                                <p class="text-muted">There are no payment records in the system yet.</p>
                                                <a href="{{ route('payments.form') }}" class="btn btn-primary">Add First Payment</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer py-4" id="payments-pagination-wrapper">
                        <nav class="d-flex justify-content-end" aria-label="..." id="payments-pagination">
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
            const form = document.getElementById('payments-search-form');
            const input = form ? form.querySelector('input[name="search"]') : null;
            const tableBody = document.getElementById('payments-table-body');
            const pagination = document.getElementById('payments-pagination');
            let searchTimer = null;

            if (!form || !input || !tableBody) {
                return;
            }

            const loadPayments = function (url, pushState = true) {
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
                        const nextTableBody = doc.getElementById('payments-table-body');
                        const nextPagination = doc.getElementById('payments-pagination');

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
                        console.error('Failed to load student payments list:', error);
                    });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const searchValue = (input.value || '').trim();
                const targetUrl = new URL(form.action, window.location.origin);

                if (searchValue !== '') {
                    targetUrl.searchParams.set('search', searchValue);
                }

                loadPayments(targetUrl.toString());
            });

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.dispatchEvent(new Event('submit', { cancelable: true }));
                }, 300);
            });

            document.addEventListener('click', function (event) {
                const pageLink = event.target.closest('#payments-pagination a.page-link');

                if (!pageLink || !pageLink.href) {
                    return;
                }

                event.preventDefault();
                loadPayments(pageLink.href);
            });

            window.addEventListener('popstate', function () {
                loadPayments(window.location.href, false);
            });
        })();
    </script>
@endsection
