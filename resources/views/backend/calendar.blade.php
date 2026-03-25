@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt-4 mb-4">
        
        <!-- Calendar Section -->
            <div class="row mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 border-left-primary shadow-sm" style="border-left: 4px solid #04415f !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Bookings</h5>
                            <span class="h2 font-weight-bold mb-0" id="totalBookings">-</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 border-left-warning shadow-sm" style="border-left: 4px solid #fb6340 !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Pending</h5>
                            <span class="h2 font-weight-bold mb-0 text-warning" id="pendingBookings">-</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 border-left-success shadow-sm" style="border-left: 4px solid #2dce89 !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Approved</h5>
                            <span class="h2 font-weight-bold mb-0 text-success" id="approvedBookings">-</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 border-left-info shadow-sm" style="border-left: 4px solid #11cdef !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Today</h5>
                            <span class="h2 font-weight-bold mb-0 text-info" id="todayBookings">-</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card shadow">
                    <!-- <div class="card-header bg-primary">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0 text-white">
                                    <i class="fas fa-calendar-alt mr-2"></i>Booking Calendar
                                </h3>
                                <p class="text-white-50 mb-0">Click on a date to create new booking or click on existing events to view details</p>
                            </div>
                        </div>
                    </div> -->
                    <div class="card-body p-0">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card shadow mb-4">
                    <div class="card-header bg-gradient-warning">
                        <h6 class="mb-0 text-white">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Pending Review
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="pendingBookingsList">
                            <p class="text-muted mb-0">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-gradient-dark">
                        <h6 class="mb-0 text-white">
                            <i class="fas fa-cogs mr-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-sm btn-primary btn-block mb-2" onclick="showBookingModal()">
                            <i class="fas fa-plus mr-2"></i>New Booking
                        </button>
                        <button class="btn btn-sm btn-info btn-block mb-2" onclick="refreshCalendar()">
                            <i class="fas fa-sync mr-2"></i>Refresh Calendar
                        </button>
                        <button class="btn btn-sm btn-success btn-block" onclick="loadStats()">
                            <i class="fas fa-chart-line mr-2"></i>Refresh Stats
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="bookingModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Book Appointment
                </h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bTitle" class="form-label">Title*</label>
                            <input type="text" class="form-control" id="bTitle" name="bTitle" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bEvent_type" class="form-label">Event Type*</label>
                            <select class="form-control" id="bEvent_type" name="bEvent_type" required>
                                <option value="">Select Type</option>
                                <option value="event">Event</option>
                                <option value="session">Session</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3 d-none">
                            <label for="booking_date" class="form-label">Date*</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bStart_datetime" class="form-label">Start Date & Time*</label>
                            <input type="datetime-local" class="form-control" id="bStart_datetime" name="bStart_datetime" min="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bEnd_datetime" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="bEnd_datetime" name="bEnd_datetime" min="{{ date('Y-m-d\TH:i') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bName" class="form-label">Customer Name*</label>
                            <input type="text" class="form-control" id="bName" name="bName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bPhone" class="form-label">Phone Number*</label>
                            <input type="tel" class="form-control" id="bPhone" name="bPhone" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="bEmail" name="bEmail">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="bDescription" name="bDescription" rows="1" placeholder="Additional details about your booking..."></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pubprievent" class="form-label">Event Visibility</label>
                            <select class="form-control" id="pubprievent" name="pubprievent">
                                <option value="PRI" selected>Private (Admin Only)</option>
                                <option value="PUB">Public (Visible to All)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bStatus" class="form-label">Status</label>
                            <select class="form-control" id="bStatus" name="bStatus">
                                <option value="pending" selected>Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bPayment_status" class="form-label">Payment Status</label>
                            <select class="form-control" id="bPayment_status" name="bPayment_status">
                                <option value="pending" selected>Pending</option>
                                <option value="paid">Paid</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bPrice" class="form-label">Price (Rs.)</label>
                            <input type="number" class="form-control" id="bPrice" name="bPrice" min="0" step="0.01">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="bRejection_reason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="bRejection_reason" name="bRejection_reason" rows="2" placeholder="Reason for rejection (if applicable)..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="submitBooking" onclick="submitBooking()">
                    <i class="fas fa-check mr-2"></i>Book Appointment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="viewBookingModalLabel">
                    <i class="fas fa-eye mr-2"></i>Booking Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <!-- Booking details will be loaded here -->
            </div>
            <div class="modal-footer" style="gap: 8px;">
                <button type="button" class="btn btn-warning" id="viewLogsBtn" onclick="viewBookingLogs()" style="flex-shrink: 1;">
                    <i class="fas fa-history mr-2"></i>View History
                </button>
                <button type="button" class="btn btn-danger" id="rejectBookingBtn" onclick="rejectCurrentBooking()" style="flex-shrink: 1;">
                    <i class="fas fa-times mr-2"></i>Reject
                </button>
                <button type="button" class="btn btn-info" id="editBookingBtn" onclick="editCurrentBooking()" style="flex-shrink: 1; min-width: 120px;">
                    <i class="fas fa-pencil-alt mr-2"></i>Edit
                </button>
                <button type="button" class="btn btn-success" id="approveBookingBtn" onclick="approveCurrentBooking()" style="flex-shrink: 1;">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="editBookingModalLabel">
                    <i class="fas fa-edit mr-2"></i>Edit Booking
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editBookingForm">
                    <input type="hidden" id="editBookingId" name="booking_id">  
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editTitle" class="form-label">Title*</label>
                            <input type="text" class="form-control" id="editTitle" name="bTitle" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editEvent_type" class="form-label">Event Type*</label>
                            <select class="form-control" id="editEvent_type" name="bEvent_type" required>
                                <option value="">Select Type</option>
                                <option value="event">Event</option>
                                <option value="session">Session</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="editBooking_date" class="form-label">Date*</label>
                            <input type="date" class="form-control" id="editBooking_date" name="booking_date"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editStart_datetime" class="form-label">Start DateTime*</label>
                            <input type="datetime-local" class="form-control" id="editStart_datetime" name="bStart_datetime"
                                min="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editEnd_datetime" class="form-label">End DateTime</label>
                            <input type="datetime-local" class="form-control" id="editEnd_datetime" name="bEnd_datetime"
                                min="{{ date('Y-m-d\TH:i') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="editName" class="form-label">Customer Name*</label>
                            <input type="text" class="form-control" id="editName" name="bName" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editPhone" class="form-label">Phone Number*</label>
                            <input type="tel" class="form-control" id="editPhone" name="bPhone" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="bEmail">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="bDescription" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPubprievent" class="form-label">Event Visibility</label>
                            <select class="form-control" id="editPubprievent" name="pubprievent">
                                <option value="PRI">Private (Admin Only)</option>
                                <option value="PUB">Public (Visible to All)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control" id="editStatus" name="bStatus">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitEditBooking()">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Booking Modal -->
<div class="modal fade" id="rejectBookingModal" tabindex="-1" aria-labelledby="rejectBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="rejectBookingModalLabel">
                    <i class="fas fa-times-circle mr-2"></i>Reject Booking
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectBookingForm">
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Rejection Reason*</label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="4" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        This action will reject the booking and notify the customer.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitRejectBooking()">
                    <i class="fas fa-times mr-2"></i>Reject Booking
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Booking Logs Modal -->
<div class="modal fade" id="bookingLogsModal" tabindex="-1" aria-labelledby="bookingLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="bookingLogsModalLabel">
                    <i class="fas fa-history mr-2"></i>Booking History
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bookingLogsContent">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Loading history...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<!-- SweetAlert2 for better alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentBookingId = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing calendar...');
    const calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) {
        console.error('Calendar element not found!');
        return;
    }
    
    console.log('Calendar element found, creating calendar...');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        events: {
            url: '{{ route("admin.calendar.bookings") }}',
            failure: function(error) {
                console.error('Error loading events:', error);
                Swal.fire('Error!', 'Failed to load calendar events', 'error');
            }
        },
        selectable: true,
        selectMirror: true,
        editable: false,
        dayMaxEvents: true,
        
        // Handle date selection
        select: function(info) {
            console.log('Date selected:', info.startStr);
            showBookingModal(info.startStr);
        },
        
        // Handle event click
        eventClick: function(info) {
            console.log('Event clicked:', info.event.id);
            showBookingDetails(info.event.id);
        },
        
        // Event styling
        eventDidMount: function(info) {
            info.el.setAttribute('title', info.event.title + ' - ' + info.event.extendedProps.customer_name);
        },
        
        // Add success callback for events loading
        eventSourceSuccess: function(events) {
            console.log('Events loaded:', events);
        }
    });
    
    console.log('Rendering calendar...');
    calendar.render();
    console.log('Calendar rendered successfully');
    
    // Make calendar globally accessible for refreshing
    window.calendarInstance = calendar;
    
    // Load statistics and pending bookings
    loadStats();
    loadPendingBookings();

    // Auto-refresh calendar every 60 seconds
    setInterval(function() {
        calendar.refetchEvents();
        loadStats();
        loadPendingBookings();
    }, 60000);
});

function showBookingModal(selectedDate = null) {
    // Check if selected date is in the past (only if date is provided)
    if (selectedDate) {
        const today = new Date().toISOString().split('T')[0];
        if (selectedDate < today) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date',
                text: 'You cannot book appointments for past dates. Please select today or a future date.'
            });
            return;
        }
    }
    
    // Reset form and set title
    document.getElementById('bookingForm').reset();
    document.getElementById('bookingModalLabel').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Book Appointment';
    document.getElementById('submitBooking').innerHTML = '<i class="fas fa-check mr-2"></i>Book Appointment';
    
    if (selectedDate) {
        document.getElementById('booking_date').value = selectedDate;
        
        // Set default start datetime to selected date at appropriate time
        const now = new Date();
        const selectedDateObj = new Date(selectedDate + 'T00:00:00');
        const todayDate = new Date().toISOString().split('T')[0];
        
        let startDateTime;
        if (selectedDate === todayDate) {
            // If selecting today, use current time but round up to next hour
            const currentHour = now.getHours();
            const nextHour = currentHour + 1;
            startDateTime = selectedDate + 'T' + nextHour.toString().padStart(2, '0') + ':00';
        } else {
            // If selecting future date, default to 9 AM
            startDateTime = selectedDate + 'T09:00';
        }
        
        document.getElementById('bStart_datetime').value = startDateTime;
    }
    
    $('#bookingModal').modal('show');
}

function submitBooking() {
    console.log('Submitting booking...');
    
    // Validate dates before submitting
    const bookingDate = document.getElementById('booking_date').value;
    const startDateTime = document.getElementById('bStart_datetime').value;
    const endDateTime = document.getElementById('bEnd_datetime').value;
    
    const today = new Date().toISOString().split('T')[0];
    const now = new Date().toISOString();
    
    // Check booking date
    if (bookingDate && bookingDate < today) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'Booking date cannot be in the past.'
        });
        return;
    }
    
    // Check start datetime
    if (startDateTime && startDateTime < now.substring(0, 16)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date/Time',
            text: 'Start date and time cannot be in the past.'
        });
        return;
    }
    
    // Check end datetime is after start datetime
    if (startDateTime && endDateTime && endDateTime <= startDateTime) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Time Range',
            text: 'End date and time must be after start date and time.'
        });
        return;
    }
    
    const form = document.getElementById('bookingForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    console.log('Form data:', data);
    
    // Show loading state
    const submitButton = document.getElementById('submitBooking');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Booking...';
    submitButton.disabled = true;
    
    fetch('{{ route("admin.calendar.bookings.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            
            // Close modal and refresh calendar events
            $('#bookingModal').modal('hide');
            refreshCalendar();
            loadStats();
            loadPendingBookings();
        } else {
            let errorMessage = data.message || 'An error occurred';
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join('\n');
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
            });
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Network error: ' + error.message
        });
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

function showBookingDetails(bookingId) {
    currentBookingId = bookingId;
    
    fetch('{{ route("admin.calendar.bookings.show", ":id") }}'.replace(':id', bookingId))
    .then(response => response.json())
    .then(booking => {
        const statusBadge = getStatusBadge(booking.status);
        const paymentBadge = getPaymentBadge(booking.payment_status);
        const visibilityBadge = getVisibilityBadge(booking.pubprievent || 'PRI');
        const typeBadge = booking.type === 'event' ?
            '<span class="badge badge-primary">Event</span>' :
            '<span class="badge badge-info">Session</span>';

        document.getElementById('bookingDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Title:</strong> ${booking.title}</p>
                    <p><strong>Type:</strong> ${typeBadge}</p>
                    <p><strong>Date:</strong> ${new Date(booking.booking_date).toLocaleDateString()}</p>
                    <p><strong>Start Time:</strong> ${booking.start_time || 'N/A'}</p>
                    <p><strong>End Time:</strong> ${booking.end_time || 'N/A'}</p>
                    <p><strong>Duration:</strong> ${booking.duration_hours ? booking.duration_hours + ' hours' : 'Not specified'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Customer:</strong> ${booking.customer_name}</p>
                    <p><strong>Phone:</strong> ${booking.phone_number}</p>
                    <p><strong>Email:</strong> ${booking.email || 'Not provided'}</p>
                    <p><strong>People:</strong> ${booking.number_of_people || 'N/A'}</p>
                    <p><strong>Status:</strong> ${statusBadge}</p>
                    <p><strong>Payment:</strong> ${paymentBadge}</p>
                    <p><strong>Visibility:</strong> ${visibilityBadge}</p>
                </div>
            </div>
            ${booking.description ? `<div class="mt-3"><strong>Description:</strong><br>${booking.description}</div>` : ''}
            ${booking.rejection_reason ? `<div class="mt-3"><strong>Rejection Reason:</strong><br>${booking.rejection_reason}</div>` : ''}
            ${booking.price ? `<div class="mt-2"><strong>Price:</strong> (Rs.) ${booking.price}</div>` : ''}
        `;
        
        $('#viewBookingModal').modal('show');
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Could not load booking details'
        });
    });
}

function editCurrentBooking() {
    if (!currentBookingId) return;
    
    // First get the current booking data
    fetch('{{ route("admin.calendar.bookings.show", ":id") }}'.replace(':id', currentBookingId))
    .then(response => response.json())
    .then(booking => {
        // Populate the edit form
        document.getElementById('editBookingId').value = booking.id;
        document.getElementById('editTitle').value = booking.title || '';
        document.getElementById('editEvent_type').value = booking.type || '';
        document.getElementById('editBooking_date').value = booking.booking_date || '';
        document.getElementById('editStart_datetime').value = booking.start_datetime ? booking.start_datetime.replace(' ', 'T') : '';
        document.getElementById('editEnd_datetime').value = booking.end_datetime ? booking.end_datetime.replace(' ', 'T') : '';
        document.getElementById('editStatus').value = booking.status || '';
        document.getElementById('editPayment_status').value = booking.payment_status || '';
        document.getElementById('editPrice').value = booking.price || '';
        document.getElementById('editName').value = booking.customer_name || '';
        document.getElementById('editPhone').value = booking.phone_number || '';
        document.getElementById('editEmail').value = booking.email || '';
        document.getElementById('editDescription').value = booking.description || '';
        document.getElementById('editPubprievent').value = booking.pubprievent || 'PRI';

        // Hide view modal and show edit modal
        $('#viewBookingModal').modal('hide');
        $('#editBookingModal').modal('show');
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Could not load booking details for editing', 'error');
    });
}

function submitEditBooking() {
    // Validate dates before submitting
    const bookingDate = document.getElementById('editBooking_date').value;
    const startDateTime = document.getElementById('editStart_datetime').value;
    const endDateTime = document.getElementById('editEnd_datetime').value;
    
    const today = new Date().toISOString().split('T')[0];
    const now = new Date().toISOString();
    
    // Check booking date
    if (bookingDate && bookingDate < today) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'Booking date cannot be in the past.'
        });
        return;
    }
    
    // Check start datetime
    if (startDateTime && startDateTime < now.substring(0, 16)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date/Time',
            text: 'Start date and time cannot be in the past.'
        });
        return;
    }
    
    // Check end datetime is after start datetime
    if (startDateTime && endDateTime && endDateTime <= startDateTime) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Time Range',
            text: 'End date and time must be after start date and time.'
        });
        return;
    }
    
    const form = document.getElementById('editBookingForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    const bookingId = document.getElementById('editBookingId').value;
    
    fetch('{{ route("admin.calendar.bookings.update", ":id") }}'.replace(':id', bookingId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
            $('#editBookingModal').modal('hide');
            refreshCalendar();
            loadStats();
        } else {
            let errorMessage = data.message || 'An error occurred';
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join('\n');
            }
            Swal.fire('Error!', errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Network error occurred', 'error');
    });
}

function deleteCurrentBooking() {
    if (!currentBookingId) return;
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("admin.calendar.bookings.destroy", ":id") }}'.replace(':id', currentBookingId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'The booking has been deleted.', 'success');
                    $('#viewBookingModal').modal('hide');
                    refreshCalendar();
                    loadStats();
                } else {
                    Swal.fire('Error!', data.message || 'Could not delete booking', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Network error occurred', 'error');
            });
        }
    });
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge badge-warning">Pending</span>',
        'approved': '<span class="badge badge-success">Approved</span>',
        'rejected': '<span class="badge badge-danger">Rejected</span>'
    };
    return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
}

function getPaymentBadge(status) {
    const badges = {
        'pending': '<span class="badge badge-warning">Pending</span>',
        'paid': '<span class="badge badge-success">Paid</span>',
        'refunded': '<span class="badge badge-info">Refunded</span>'
    };
    return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
}

function getVisibilityBadge(pubprievent) {
    const badges = {
        'PUB': '<span class="badge badge-success"><i class="fas fa-eye mr-1"></i>Public</span>',
        'PRI': '<span class="badge badge-warning"><i class="fas fa-eye-slash mr-1"></i>Private</span>'
    };
    return badges[pubprievent] || '<span class="badge badge-secondary">Unknown</span>';
}

function updateEventVisibility(bookingId, visibility) {
    const visibilityLabel = visibility === 'PUB' ? 'Public' : 'Private';

    // Show confirmation dialog
    Swal.fire({
        title: `Make Event ${visibilityLabel}?`,
        text: `Are you sure you want to make this event ${visibilityLabel.toLowerCase()}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, make it ${visibilityLabel.toLowerCase()}!`
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to update visibility
            fetch('{{ route("admin.calendar.bookings.visibility", ":id") }}'.replace(':id', bookingId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pubprievent: visibility
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Refresh calendar to show updated visibility
                    refreshCalendar();
                    loadPendingBookings();

                    // Close the booking details modal
                    $('#viewBookingModal').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to update event visibility'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Network error occurred while updating visibility'
                });
            });
        }
    });
}

function loadStats() {
    fetch('{{ route("admin.calendar.stats") }}')
    .then(response => response.json())
    .then(stats => {
        document.getElementById('totalBookings').textContent = stats.total || 0;
        document.getElementById('pendingBookings').textContent = stats.pending || 0;
        document.getElementById('approvedBookings').textContent = stats.approved || 0;
        document.getElementById('todayBookings').textContent = stats.today || 0;
    })
    .catch(error => {
        console.error('Error loading stats:', error);
    });
}

function loadPendingBookings() {
    // Load private bookings that need admin attention
    fetch('{{ route("admin.calendar.bookings") }}?private_only=true')
    .then(response => response.json())
    .then(bookings => {
        const privateBookings = bookings.filter(booking =>
            booking.extendedProps && booking.extendedProps.pubprievent === 'PRI'
        );

        const pendingList = document.getElementById('pendingBookingsList');

        if (privateBookings.length === 0) {
            pendingList.innerHTML = `
                <p class="text-muted mb-0">
                    <i class="fas fa-check-circle text-success mr-2"></i>No events pending review
                </p>
            `;
        } else {
            let html = `<small class="text-muted">Recent private events needing review:</small><br>`;

            privateBookings.slice(0, 5).forEach(booking => {
                const date = new Date(booking.start).toLocaleDateString();
                html += `
                    <div class="d-flex justify-content-between align-items-center mt-2 p-2 border rounded">
                        <div>
                            <strong class="d-block">${booking.title}</strong>
                            <small class="text-muted">${date}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="showBookingDetails(${booking.id})">
                            Review
                        </button>
                    </div>
                `;
            });

            if (privateBookings.length > 5) {
                html += `<small class="text-muted mt-2 d-block">...and ${privateBookings.length - 5} more</small>`;
            }

            pendingList.innerHTML = html;
        }
    })
    .catch(error => {
        console.error('Error loading pending bookings:', error);
        document.getElementById('pendingBookingsList').innerHTML = `
            <p class="text-danger mb-0">
                <i class="fas fa-exclamation-triangle mr-2"></i>Error loading pending events
            </p>
        `;
    });
}

function refreshCalendar() {
    if (window.calendarInstance) {
        window.calendarInstance.refetchEvents();
        console.log('Calendar events refreshed');
    }
}

function approveCurrentBooking() {
    if (!currentBookingId) return;
    
    Swal.fire({
        title: 'Approve Booking?',
        text: "This will approve the booking and notify the customer.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("admin.calendar.bookings.approve", ":id") }}'.replace(':id', currentBookingId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Approved!', 'The booking has been approved.', 'success');
                    $('#viewBookingModal').modal('hide');
                    refreshCalendar();
                    loadStats();
                } else {
                    Swal.fire('Error!', data.message || 'Could not approve booking', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Network error occurred', 'error');
            });
        }
    });
}

function rejectCurrentBooking() {
    if (!currentBookingId) return;
    
    $('#viewBookingModal').modal('hide');
    $('#rejectBookingModal').modal('show');
}

function submitRejectBooking() {
    const rejectionReason = document.getElementById('rejectionReason').value.trim();
    
    if (!rejectionReason) {
        Swal.fire('Error!', 'Please provide a rejection reason', 'error');
        return;
    }
    
    fetch('{{ route("admin.calendar.bookings.reject", ":id") }}'.replace(':id', currentBookingId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            rejection_reason: rejectionReason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Rejected!', 'The booking has been rejected.', 'success');
            $('#rejectBookingModal').modal('hide');
            document.getElementById('rejectionReason').value = ''; // Clear the form
            refreshCalendar();
            loadStats();
        } else {
            let errorMessage = data.message || 'Could not reject booking';
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join('\n');
            }
            Swal.fire('Error!', errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Network error occurred', 'error');
    });
}

function viewBookingLogs() {
    if (!currentBookingId) return;
    
    document.getElementById('bookingLogsContent').innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin"></i> Loading history...
        </div>
    `;
    
    $('#viewBookingModal').modal('hide');
    $('#bookingLogsModal').modal('show');
    
    fetch('{{ route("admin.calendar.bookings.logs", ":id") }}'.replace(':id', currentBookingId))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let logsHtml = '';
            
            if (data.logs.length === 0) {
                logsHtml = '<div class="alert alert-info">No history records found for this booking.</div>';
            } else {
                logsHtml = '<div class="timeline">';
                data.logs.forEach(log => {
                    const actionClass = {
                        'CREATED': 'success',
                        'UPDATED': 'info', 
                        'APPROVED': 'success',
                        'REJECTED': 'danger',
                        'DELETED': 'dark'
                    }[log.action] || 'secondary';
                    
                    logsHtml += `
                        <div class="timeline-item mb-3">
                            <div class="card border-left-${actionClass}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge badge-${actionClass}">${log.action}</span>
                                            <span class="text-muted ml-2">${log.logged_at_human}</span>
                                        </div>
                                        <small class="text-muted">${log.logged_at}</small>
                                    </div>
                                    <p class="mb-1 mt-2"><strong>${log.user_name}</strong> (${log.user_role})</p>
                                    <p class="mb-1">${log.description}</p>
                                    ${log.changes_summary !== 'No significant changes' ? 
                                        `<small class="text-muted">${log.changes_summary}</small>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                logsHtml += '</div>';
            }
            
            document.getElementById('bookingLogsContent').innerHTML = logsHtml;
        } else {
            document.getElementById('bookingLogsContent').innerHTML = 
                '<div class="alert alert-danger">Error loading booking history.</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('bookingLogsContent').innerHTML = 
            '<div class="alert alert-danger">Network error loading booking history.</div>';
    });
}
</script>

<style>
.fc-header-toolbar {
    margin-bottom: 1rem;
}

.fc-event {
    cursor: pointer;
    border-radius: 3px;
}

.fc-event:hover {
    opacity: 0.8;
}

.card {
    border-radius: 10px;
}

.modal-content {
    border-radius: 10px;
}

.badge {
    font-size: 0.875em;
}

#calendar {
    padding: 20px;
}

.fc-daygrid-event {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.text-primary { color: #007bff !important; }
.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }
.text-info { color: #17a2b8 !important; }

.btn-block {
    display: block;
    width: 100%;
}

/* Timeline styles for booking logs */
.timeline-item {
    position: relative;
}

.border-left-success {
    border-left: 3px solid #28a745 !important;
}

.border-left-info {
    border-left: 3px solid #17a2b8 !important;
}

.border-left-danger {
    border-left: 3px solid #dc3545 !important;
}

.border-left-warning {
    border-left: 3px solid #ffc107 !important;
}

.border-left-dark {
    border-left: 3px solid #343a40 !important;
}

.border-left-secondary {
    border-left: 3px solid #6c757d !important;
}

/* Modal improvements */
.modal-lg {
    max-width: 900px;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
    padding: 0.375em 0.5em;
}

/* Button group spacing */
.btn-group .btn + .btn {
    margin-left: -1px;
}

/* Alert improvements */
.alert {
    border-radius: 0.5rem;
}
</style>
@endpush
@endsection







