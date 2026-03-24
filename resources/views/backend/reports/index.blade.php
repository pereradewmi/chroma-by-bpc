@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <!-- Filter Form -->
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{ __('Filter Reports') }}</h3>
                        </div>
                        <div class="col text-right">
                            <button type="button" class="btn btn-sm btn-primary" onclick="resetFilters()">
                                <i class="fas fa-refresh"></i> {{ __('Reset Filters') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filter-form" method="GET" action="{{ route('reports.index') }}">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('From Date') }}</label>
                                    <input class="form-control" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('To Date') }}</label>
                                    <input class="form-control" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Event Type') }}</label>
                                    <select class="form-control" name="event_type" id="event_type">
                                        <option value="all">{{ __('All Types') }}</option>
                                        <option value="event" {{ ($filters['event_type'] ?? '') == 'event' ? 'selected' : '' }}>{{ __('Event') }}</option>
                                        <option value="session" {{ ($filters['event_type'] ?? '') == 'session' ? 'selected' : '' }}>{{ __('Session') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Payment Status') }}</label>
                                    <select class="form-control" name="payment_status" id="payment_status">
                                        <option value="all">{{ __('All Statuses') }}</option>
                                        <option value="pending" {{ ($filters['payment_status'] ?? '') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="paid" {{ ($filters['payment_status'] ?? '') == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                        <option value="refunded" {{ ($filters['payment_status'] ?? '') == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Booking Status') }}</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="all">{{ __('All Statuses') }}</option>
                                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                        <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Event Category') }}</label>
                                    <select class="form-control" name="event_category" id="event_category">
                                        <option value="all">{{ __('All Categories') }}</option>
                                        <!-- Dynamic options will be loaded here -->
                                    </select>
                                </div>
                            </div>
                             <div class="col-md-3 col-sm-6"></div>
                            <div class="col-md-3 col-sm-6 pt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter mr-1"></i>{{ __('Apply Filters') }}
                                </button>
                                <button type="button" class="btn btn-success" onclick="downloadReport('excel')">
                                    <i class="fas fa-file-excel mr-1"></i>{{ __('Download') }}
                                </button>
                            </div>

                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row mt-4">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{ __('Booking Reports') }}</h3>
                            <p class="text-sm mb-0 text-muted">
                                {{ __('Showing') }} {{ $bookings->firstItem() ?? 0 }} {{ __('to') }} {{ $bookings->lastItem() ?? 0 }} 
                                {{ __('of') }} {{ number_format($bookings->total()) }} {{ __('results') }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">{{ __('Last updated') }}: {{ now()->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Contact') }}</th>
                                <th scope="col">{{ __('Booking Date') }}</th>
                                <th scope="col">{{ __('Event') }}</th>
                                <th scope="col">{{ __('Type') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Payment') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_ID }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="font-weight-600 mb-0">{{ $booking->bName }}</div>
                                            <small class="text-muted">{{ Str::limit($booking->bTitle, 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $booking->bEmail }}</div>
                                    <small class="text-muted">{{ $booking->bPhone }}</small>
                                </td>
                                <td>
                                    <div>{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</div>
                                    @if($booking->bStart_datetime)
                                        <small class="text-muted">{{ $booking->bStart_datetime->format('H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-600">{{ $booking->bEvent_Category }}</div>
                                    <small class="text-muted">{{ Str::limit($booking->bDescription, 40) }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-{{ $booking->bEvent_type == 'event' ? 'primary' : 'info' }}">
                                        {{ ucfirst($booking->bEvent_type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-{{ $booking->bStatus == 'approved' ? 'success' : ($booking->bStatus == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($booking->bStatus) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-{{ $booking->bPayment_status == 'paid' ? 'success' : ($booking->bPayment_status == 'refunded' ? 'info' : 'warning') }}">
                                        {{ ucfirst($booking->bPayment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="font-weight-600">${{ number_format($booking->bPrice, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="my-4">
                                        <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                                        <h4 class="text-muted mt-3">{{ __('No data found') }}</h4>
                                        <p class="text-muted">{{ __('Try adjusting your filters to see more results.') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $bookings->appends(request()->except('page'))->links() }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load filter options
        loadFilterOptions();
    });

    function loadFilterOptions() {
        fetch('{{ route("reports.filter-options") }}')
            .then(response => response.json())
            .then(data => {
                // Populate event categories
                const categorySelect = document.getElementById('event_category');
                const currentCategory = '{{ $filters["event_category"] ?? "" }}';
                
                // Clear existing options except "All Categories"
                categorySelect.innerHTML = '<option value="all">All Categories</option>';
                
                data.event_categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    if (category === currentCategory) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading filter options:', error));
    }

    function downloadReport(format) {
        const formData = new FormData(document.getElementById('filter-form'));
        formData.append('format', format);
        
        const params = new URLSearchParams();
        for (let [key, value] of formData.entries()) {
            if (value && value !== 'all') {
                params.append(key, value);
            }
        }
        
        window.location.href = '{{ route("reports.download") }}?' + params.toString();
    }

    function resetFilters() {
        document.getElementById('filter-form').reset();
        // Remove all URL parameters except the base route
        window.location.href = '{{ route("reports.index") }}';
    }

    function exportFilteredData() {
        // Show export modal or directly download with current filters
        const format = confirm('Export as Excel? (Cancel for CSV)') ? 'excel' : 'csv';
        downloadReport(format);
    }

    // Auto-submit form when filters change (optional)
    document.querySelectorAll('#filter-form select, #filter-form input[type="date"]').forEach(element => {
        element.addEventListener('change', function() {
            // Optional: Auto-submit on change
            // document.getElementById('filter-form').submit();
        });
    });
</script>
@endpush
