@extends('frontend.components.layout')

@push('head-scripts')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .calendar-page {
        background: linear-gradient(180deg, #f4f8fb 0%, #eef4f8 100%);
        min-height: calc(100vh - 140px);
    }

    .calendar-page .calendar-wrapper .card {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 14px 36px rgba(4, 65, 95, 0.14);
    }

    .calendar-page .calendar-wrapper .card-header {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        border-bottom: 0;
        padding: 1rem 1.25rem;
    }

    .calendar-page .calendar-title {
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .calendar-page #calendar {
        padding: 18px;
        background: #ffffff;
    }

    .calendar-page .fc-header-toolbar {
        margin-bottom: 1rem;
        gap: 0.5rem;
    }

    .calendar-page .fc-toolbar-title {
        color: #001f3f;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .calendar-page .fc-button-primary {
        background: #001f3f;
        border-color: #001f3f;
        box-shadow: 0 4px 12px rgba(0, 31, 63, 0.2);
    }

    .calendar-page .fc-button-primary:hover,
    .calendar-page .fc-button-primary:focus,
    .calendar-page .fc-button-primary:active {
        background: #000a1f;
        border-color: #000a1f;
    }

    .calendar-page .fc-daygrid-event,
    .calendar-page .fc-timegrid-event {
        border: 0;
        border-radius: 8px;
        padding: 2px 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    .calendar-page .fc-event:hover {
        opacity: 0.88;
    }

    .calendar-page .modal-content {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 16px 36px rgba(4, 65, 95, 0.18);
    }

    .calendar-page .modal-header {
        border-bottom: 0;
    }

    .calendar-page .modal-footer {
        border-top: 1px solid #edf2f7;
    }

    .calendar-page .form-control,
    .calendar-page .form-select {
        border-radius: 10px;
    }

    .calendar-page .badge {
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.38rem 0.58rem;
    }

    @media (max-width: 767.98px) {
        .calendar-page #calendar {
            padding: 12px;
        }

        .calendar-page .fc-header-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .calendar-page .fc-toolbar-title {
            font-size: 1rem;
        }
    }

    /* Navy Blue Button Styling */
    .calendar-page .btn-primary {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        border: none;
        color: #ffffff;
    }

    .calendar-page .btn-primary:hover,
    .calendar-page .btn-primary:focus,
    .calendar-page .btn-primary:active {
        background: linear-gradient(135deg, #000a1f 0%, #002855 100%);
        border: none;
        color: #ffffff;
    }

    /* Modal Styling */
    .calendar-page .modal-header {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        border: none;
    }

    .calendar-page .modal-content {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 16px 36px rgba(0, 31, 63, 0.18);
    }

    .calendar-page .modal-body {
        background: #ffffff;
        color: #333333;
    }

    .calendar-page .modal-body .form-label {
        color: #333333;
        font-weight: 600;
    }

    .calendar-page .modal-body .form-control,
    .calendar-page .modal-body .form-select {
        border-color: #ddd;
        color: #333333;
        background-color: #ffffff;
    }

    .calendar-page .modal-body .form-control:focus,
    .calendar-page .modal-body .form-select:focus {
        border-color: #001f3f;
        box-shadow: 0 0 0 0.2rem rgba(0, 31, 63, 0.25);
        color: #333333;
    }

    .calendar-page .modal-body p,
    .calendar-page .modal-body label {
        color: #333333;
    }
</style>
@endpush

@section('main')

<main class="main calendar-page">

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="calendar-wrapper">
                <div class="card shadow border-0">
                    <div class="card-header text-white">
                        <h5 class="mb-0 calendar-title"><i class="fas fa-calendar-alt me-2"></i>Book Your Appointment</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);">
                <h5 class="modal-title text-white" id="bookingModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Book Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <select class="form-select" id="bEvent_type" name="bEvent_type" required>
                                <option value="">Select Type</option>
                                <option value="event">Event</option>
                                <option value="session">Session</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3  d-none" >
                            <label for="booking_date" class="form-label">Date*</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bStart_datetime" class="form-label">Start Date & Time*</label>
                            <input type="datetime-local" class="form-control" id="bStart_datetime" name="bStart_datetime"
                                   min="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bEnd_datetime" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="bEnd_datetime" name="bEnd_datetime"
                                   min="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bName" class="form-label">Your Name*</label>
                            <input type="text" class="form-control" id="bName" name="bName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bPhone" class="form-label">Phone Number*</label>
                            <input type="tel" class="form-control" id="bPhone" name="bPhone" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="bEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="bEmail" name="bEmail">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="bDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="bDescription" name="bDescription" rows="3" placeholder="Additional details about your booking..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn text-white" id="submitBooking" onclick="submitBooking()" style="background: linear-gradient(135deg, #001f3f 0%, #003d82 100%); border: none;">
                    <i class="fas fa-check me-2"></i>Book Appointment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);">
                <h5 class="modal-title text-white" id="viewBookingModalLabel">
                    <i class="fas fa-calendar-check me-2"></i>All Appointments
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-spinner fa-spin me-2"></i>Loading appointments...
                </div>
            </div>
        </div>
    </div>
</div>

</main>
@endsection

@section('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<!-- SweetAlert2 for better alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const bookingModalEl = document.getElementById('bookingModal');
    const viewBookingModalEl = document.getElementById('viewBookingModal');
    
    if (!calendarEl) {
        console.error('Calendar element not found!');
        return;
    }
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
                right: 'dayGridMonth'
                // right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        events: {
            url: '{{ route("Appointment.bookings") }}',
            failure: function(error) {
                console.error('Error loading events:', error);
                alert('Failed to load calendar events');
            }
        },
        selectable: true,
        selectMirror: true,
        editable: false,
        dayMaxEvents: true,
        
        // Handle date selection
        select: function(info) {
            showBookingModal(info.startStr);
        },

        // Mobile-friendly single tap on a date cell
        dateClick: function(info) {
            showBookingModal(info.dateStr);
        },
        
        // Handle event click
        eventClick: function(info) {
            showAllAppointmentsModal(info.event.startStr || info.event.start);
        },
        
        // Event styling
        eventDidMount: function(info) {
            info.el.setAttribute('title', info.event.title + ' - ' + info.event.extendedProps.customer_name);
        },
        
        // Add success callback for events loading
        eventSourceSuccess: function(events) {
        }
    });
    
    calendar.render();

    // Keep one instance per modal and clean any stale backdrop/body lock state on close.
    if (bookingModalEl) {
        window.bookingModalInstance = bootstrap.Modal.getOrCreateInstance(bookingModalEl);
        bookingModalEl.addEventListener('hidden.bs.modal', cleanupModalArtifacts);
    }

    if (viewBookingModalEl) {
        window.viewBookingModalInstance = bootstrap.Modal.getOrCreateInstance(viewBookingModalEl);
        viewBookingModalEl.addEventListener('hidden.bs.modal', cleanupModalArtifacts);
    }
    
    // Make calendar globally accessible for refreshing
    window.calendarInstance = calendar;
    
    // Load statistics
    loadStats();
    
    // Auto-refresh calendar every 30 seconds
    setInterval(function() {
        calendar.refetchEvents();
        loadStats();
    }, 30000);
});

function cleanupModalArtifacts() {
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
    document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
        backdrop.remove();
    });
}

function showBookingModal(selectedDate) {
    // Check if selected date is in the past or today
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    if (selectedDate < tomorrow) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'You cannot book appointments for today or past dates. Please select a future date.'
        });
        return;
    }
    
    document.getElementById('booking_date').value = selectedDate;
    
    // Set default start datetime to selected date at current time or 9 AM if date is in future
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
    
    document.getElementById('bookingForm').reset();
    document.getElementById('booking_date').value = selectedDate; // Reset after form reset
    document.getElementById('bStart_datetime').value = startDateTime;
    
    const bookingModalEl = document.getElementById('bookingModal');
    if (!bookingModalEl) {
        return;
    }

    const modal = bootstrap.Modal.getOrCreateInstance(bookingModalEl);
    modal.show();
}

function submitBooking() {

    // Validate dates before submitting
    const bookingDate = document.getElementById('booking_date').value;
    const startDateTime = document.getElementById('bStart_datetime').value;
    const endDateTime = document.getElementById('bEnd_datetime').value;

    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    const now = new Date().toISOString();

    // Check booking date - must be in the future (tomorrow or later)
    if (bookingDate && bookingDate <= today) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'Booking date must be a future date (tomorrow or later).'
        });
        return;
    }

    // Check start datetime - must be in the future (tomorrow or later)
    if (startDateTime && startDateTime.split('T')[0] <= today) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date/Time',
            text: 'Start date must be a future date (tomorrow or later).'
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
    
    // Show loading state
    const submitButton = document.getElementById('submitBooking');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Booking...';
    submitButton.disabled = true;
    
    fetch('{{ route("Appointment.bookings.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Booking Submitted Successfully!',
                text: 'Thank you for your booking request. Our admin or support person will contact you shortly to confirm your Appointment.',
                showConfirmButton: true,
                confirmButtonText: 'Okay'
            });
            
            // Close modal and refresh calendar events
            const bookingModalEl = document.getElementById('bookingModal');
            const bookingModal = bookingModalEl ? bootstrap.Modal.getOrCreateInstance(bookingModalEl) : null;
            if (bookingModal) {
                bookingModal.hide();
            }
            
            // Refresh calendar events instead of full page reload
            if (window.calendarInstance) {
                window.calendarInstance.refetchEvents();
            }
            
            // Also refresh statistics
            loadStats();
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

function showAllAppointmentsModal(clickedDateTime) {
    fetch('{{ route("Appointment.bookings") }}')
    .then(response => response.json())
    .then(bookings => {
        const clickedDate = clickedDateTime ? new Date(clickedDateTime) : null;
        const clickedDateKey = clickedDate && !Number.isNaN(clickedDate.getTime())
            ? clickedDate.toISOString().split('T')[0]
            : null;

        const sortedBookings = bookings
            .slice()
            .filter((booking) => {
                if (!clickedDateKey) {
                    return true;
                }

                return String(booking.start || '').slice(0, 10) === clickedDateKey;
            })
            .sort((firstBooking, secondBooking) => new Date(firstBooking.start) - new Date(secondBooking.start));

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const formatDateTime = (dateTime) => {
            if (!dateTime) {
                return 'Not specified';
            }

            const parsedDate = new Date(dateTime);
            if (Number.isNaN(parsedDate.getTime())) {
                return escapeHtml(dateTime);
            }

            return parsedDate.toLocaleString([], {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const formatTime = (dateTime) => {
            if (!dateTime) {
                return 'Not specified';
            }

            const parsedDate = new Date(dateTime);
            if (Number.isNaN(parsedDate.getTime())) {
                return escapeHtml(dateTime);
            }

            return parsedDate.toLocaleTimeString([], {
                hour: 'numeric',
                minute: '2-digit'
            });
        };

        const highlightStyle = 'border-left: 4px solid #003d82;';

        document.getElementById('bookingDetails').innerHTML = sortedBookings.length ? `
            <div class="d-flex flex-column gap-3">
                ${sortedBookings.map((booking, index) => {
                    const typeBadge = booking.extendedProps?.type === 'event'
                        ? '<span class="badge bg-primary">Event</span>'
                        : '<span class="badge bg-info text-dark">Session</span>';

                    return `
                        <div class="card shadow-sm border-0" style="${highlightStyle}${index === 0 ? 'background-color: #f8fbff;' : ''}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <div class="text-muted small mb-1">Appointment ${index + 1}</div>
                                        <h6 class="mb-1">${escapeHtml(booking.title || 'Untitled appointment')}</h6>
                                        <div class="small text-muted">
                                            <span class="me-3"><strong>Type:</strong> ${typeBadge}</span>
                                            <span><strong>Time:</strong> ${formatTime(booking.start)} - ${formatTime(booking.end)}</span>
                                        </div>
                                        <div class="small text-muted mt-1"><strong>Status:</strong> ${escapeHtml(booking.extendedProps?.status || 'Not specified')}</div>
                                    </div>
                                    <span class="badge bg-secondary">${formatDateTime(booking.start)}</span>
                                </div>
                                ${booking.extendedProps?.description ? `<div class="mt-3"><strong>Description:</strong><br>${escapeHtml(booking.extendedProps.description)}</div>` : ''}
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>
        ` : `
            <div class="text-center text-muted py-4">No appointments found.</div>
        `;
        
        const viewBookingModalEl = document.getElementById('viewBookingModal');
        if (!viewBookingModalEl) {
            return;
        }

        const modal = bootstrap.Modal.getOrCreateInstance(viewBookingModalEl);
        modal.show();
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

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning text-dark">Pending</span>',
        'approved': '<span class="badge bg-success">Approved</span>',
        'rejected': '<span class="badge bg-danger">Rejected</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function loadStats() {
    fetch('{{ route("Appointment.stats") }}')
    .then(response => response.json())
    .then(stats => {
        const totalBookings = document.getElementById('totalBookings');
        const pendingBookings = document.getElementById('pendingBookings');
        const approvedBookings = document.getElementById('approvedBookings');
        const todayBookings = document.getElementById('todayBookings');

        if (totalBookings) totalBookings.textContent = stats.total;
        if (pendingBookings) pendingBookings.textContent = stats.pending;
        if (approvedBookings) approvedBookings.textContent = stats.approved;
        if (todayBookings) todayBookings.textContent = stats.today;
    })
    .catch(error => {
        console.error('Error loading stats:', error);
    });
}

// Auto-calculate end time based on duration
const durationHoursField = document.getElementById('duration_hours');
const startTimeField = document.getElementById('start_time');
const endTimeField = document.getElementById('end_time');

if (durationHoursField && startTimeField && endTimeField) {
    durationHoursField.addEventListener('change', function() {
        const startTime = startTimeField.value;
        const duration = parseInt(this.value);

        if (startTime && duration) {
            const start = new Date('2000-01-01T' + startTime);
            start.setHours(start.getHours() + duration);

            const endTime = start.toTimeString().substr(0, 5);
            endTimeField.value = endTime;
        }
    });

    // Auto-calculate duration based on end time
    endTimeField.addEventListener('change', function() {
        const startTime = startTimeField.value;
        const endTime = this.value;

        if (startTime && endTime) {
            const start = new Date('2000-01-01T' + startTime);
            const end = new Date('2000-01-01T' + endTime);

            if (end > start) {
                const duration = (end - start) / (1000 * 60 * 60);
                durationHoursField.value = Math.round(duration);
            }
        }
    });
}
</script>

@endsection