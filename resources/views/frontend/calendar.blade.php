@extends('frontend.components.layout')

@push('head-scripts')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('title', 'Book Appointment | Chroma By BPC')

@section('main')

<main class="main">

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="calendar-wrapper">
                <div class="card shadow border-0" style="border-radius: 10px;">
                    <div class="card-header bg-primary text-white" style="border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0" style="color: #ffffff;"><i class="fas fa-calendar-alt me-2" style="color: #ffffff;"></i><span style="color: #ffffff;">Book Your Appointment</span></h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="calendar" style="padding: 20px;"></div>
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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bookingModalLabel">
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
                                   min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bStart_datetime" class="form-label">Start Date & Time*</label>
                            <input type="datetime-local" class="form-control" id="bStart_datetime" name="bStart_datetime" 
                                   min="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bEnd_datetime" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="bEnd_datetime" name="bEnd_datetime"
                                   min="{{ date('Y-m-d\TH:i') }}">
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
                <button type="button" class="btn btn-primary" id="submitBooking" onclick="submitBooking()">
                    <i class="fas fa-check me-2"></i>Book Appointment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewBookingModalLabel">
                    <i class="fas fa-eye me-2"></i>Booking Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <!-- Booking details will be loaded here -->
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
            url: '{{ route("calendar.bookings") }}',
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
    
    // Load statistics
    loadStats();
    
    // Auto-refresh calendar every 30 seconds
    setInterval(function() {
        calendar.refetchEvents();
        loadStats();
    }, 30000);
});

function showBookingModal(selectedDate) {
    // Check if selected date is in the past
    const today = new Date().toISOString().split('T')[0];
    if (selectedDate < today) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'You cannot book appointments for past dates. Please select today or a future date.'
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
    
    const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
    modal.show();
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
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Booking...';
    submitButton.disabled = true;
    
    fetch('{{ route("calendar.bookings.store") }}', {
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
                title: 'Booking Submitted Successfully!',
                text: 'Thank you for your booking request. Our admin or support person will contact you shortly to confirm your appointment.',
                timer: 4000,
                showConfirmButton: true,
                confirmButtonText: 'Okay'
            });
            
            // Close modal and refresh calendar events
            bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
            
            // Refresh calendar events instead of full page reload
            if (window.calendarInstance) {
                window.calendarInstance.refetchEvents();
                console.log('Calendar events refreshed');
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

function showBookingDetails(bookingId) {
    fetch(`{{ route("calendar.bookings.show", ":id") }}`.replace(':id', bookingId))
    .then(response => response.json())
    .then(booking => {
        const statusBadge = getStatusBadge(booking.status);
        const typeBadge = booking.type === 'event' ? 
            '<span class="badge bg-primary">Event</span>' : 
            '<span class="badge bg-info">Session</span>';
            
        document.getElementById('bookingDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Title:</strong> ${booking.title}</p>
                    <p><strong>Type:</strong> ${typeBadge}</p>
                    <p><strong>Date:</strong> ${new Date(booking.booking_date).toLocaleDateString()}</p>
                    <p><strong>Time:</strong> ${booking.start_time} ${booking.end_time ? '- ' + booking.end_time : ''}</p>
                    <p><strong>Duration:</strong> ${booking.duration_hours ? booking.duration_hours + ' hours' : 'Not specified'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Customer:</strong> ${booking.customer_name}</p>
                    <p><strong>Phone:</strong> ${booking.phone_number}</p>
                    <p><strong>Email:</strong> ${booking.email || 'Not provided'}</p>
                    <p><strong>People:</strong> ${booking.number_of_people}</p>
                    <p><strong>Status:</strong> ${statusBadge}</p>
                </div>
            </div>
            ${booking.description ? `<div class="mt-3"><strong>Description:</strong><br>${booking.description}</div>` : ''}
            ${booking.price ? `<div class="mt-2"><strong>Price:</strong> $${booking.price}</div>` : ''}
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('viewBookingModal'));
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
    fetch('{{ route("calendar.stats") }}')
    .then(response => response.json())
    .then(stats => {
        document.getElementById('totalBookings').textContent = stats.total;
        document.getElementById('pendingBookings').textContent = stats.pending;
        document.getElementById('approvedBookings').textContent = stats.approved;
        document.getElementById('todayBookings').textContent = stats.today;
    })
    .catch(error => {
        console.error('Error loading stats:', error);
    });
}

// Auto-calculate end time based on duration
document.getElementById('duration_hours').addEventListener('change', function() {
    const startTime = document.getElementById('start_time').value;
    const duration = parseInt(this.value);
    
    if (startTime && duration) {
        const start = new Date('2000-01-01T' + startTime);
        start.setHours(start.getHours() + duration);
        
        const endTime = start.toTimeString().substr(0, 5);
        document.getElementById('end_time').value = endTime;
    }
});

// Auto-calculate duration based on end time
document.getElementById('end_time').addEventListener('change', function() {
    const startTime = document.getElementById('start_time').value;
    const endTime = this.value;
    
    if (startTime && endTime) {
        const start = new Date('2000-01-01T' + startTime);
        const end = new Date('2000-01-01T' + endTime);
        
        if (end > start) {
            const duration = (end - start) / (1000 * 60 * 60);
            document.getElementById('duration_hours').value = Math.round(duration);
        }
    }
});
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
</style>
@endsection