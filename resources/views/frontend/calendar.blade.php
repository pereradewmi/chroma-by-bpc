@extends('frontend.components.layout')

@push('head-scripts')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('title', 'Book Appointment - Chroma Calendar')

@section('main')

<main class="main">

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-4 text-primary mb-3">Book Your Appointment</h1>
                <p class="lead text-muted">Select a date and time to schedule your session or event</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Calendar</h5>
                </div>
                <div class="card-body p-0">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Legend</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge" style="background-color: #ffc107; color: #000;">■</span>
                        <small class="ms-2">Pending Approval</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge" style="background-color: #28a745;">■</span>
                        <small class="ms-2">Approved</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge" style="background-color: #dc3545;">■</span>
                        <small class="ms-2">Rejected</small>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary mb-1" id="totalBookings">-</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-warning mb-1" id="pendingBookings">-</h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success mb-1" id="approvedBookings">-</h4>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-0">
                                <h4 class="text-info mb-1" id="todayBookings">-</h4>
                                <small class="text-muted">Today</small>
                            </div>
                        </div>
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
                            <label for="title" class="form-label">Title*</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type*</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="event">Event</option>
                                <option value="session">Session</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="booking_date" class="form-label">Date*</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label">Start Time*</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration_hours" class="form-label">Duration (Hours)</label>
                            <select class="form-select" id="duration_hours" name="duration_hours">
                                <option value="">Select Duration</option>
                                <option value="1">1 Hour</option>
                                <option value="2">2 Hours</option>
                                <option value="3">3 Hours</option>
                                <option value="4">4 Hours</option>
                                <option value="6">6 Hours</option>
                                <option value="8">8 Hours</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="number_of_people" class="form-label">Number of People*</label>
                            <input type="number" class="form-control" id="number_of_people" name="number_of_people" min="1" max="100" value="1" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Your Name*</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number*</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price ($)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Additional details about your booking..."></textarea>
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
        // Temporarily disable events loading to test basic calendar
        // events: '{{ route("calendar.bookings") }}',
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
        }
    });
    
    console.log('Rendering calendar...');
    calendar.render();
    console.log('Calendar rendered successfully');
    
    // Load statistics
    loadStats();
    
    // Auto-refresh calendar every 30 seconds
    setInterval(function() {
        calendar.refetchEvents();
        loadStats();
    }, 30000);
});

function showBookingModal(selectedDate) {
    document.getElementById('booking_date').value = selectedDate;
    document.getElementById('bookingForm').reset();
    document.getElementById('booking_date').value = selectedDate; // Reset after form reset
    
    const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
    modal.show();
}

function submitBooking() {
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
    
    fetch('{{ route("calendar.bookings.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
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
            
            // Close modal and refresh calendar
            bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
            location.reload(); // Refresh to update calendar
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
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An unexpected error occurred'
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