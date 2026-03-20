@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <!-- Page content -->
    <div class="container-fluid mt--4 mb-4">
        <!-- Calendar Section -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title" id="viewBookingModalLabel">
                    <i class="fas fa-eye mr-2"></i>Booking Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <!-- Booking details will be loaded here -->
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
        height: 600,
        aspectRatio: 1.8,
        contentHeight: 'auto',
        expandRows: true,
        events: {
            url: '{{ route("admin.calendar.bookings") }}',
            failure: function(error) {
                console.error('Error loading events:', error);
                alert('Failed to load calendar events');
            }
        },
        selectable: true,
        selectMirror: true,
        editable: false,
        dayMaxEvents: true,
        
        // Responsive behavior
        windowResizeDelay: 100,
        
        // Better styling
        themeSystem: 'bootstrap4',
        
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
    document.getElementById('booking_date').value = selectedDate;
    
    // Set default start datetime to selected date at current time
    const now = new Date();
    const startDateTime = selectedDate + 'T' + now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    document.getElementById('bStart_datetime').value = startDateTime;
    
    document.getElementById('bookingForm').reset();
    document.getElementById('booking_date').value = selectedDate; // Reset after form reset
    document.getElementById('bStart_datetime').value = startDateTime;
    
    $('#bookingModal').modal('show');
}

function submitBooking() {
    console.log('Submitting booking...');
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
    fetch('{{ route("admin.calendar.bookings.show", ":id") }}'.replace(':id', bookingId))
    .then(response => response.json())
    .then(booking => {
        const statusBadge = getStatusBadge(booking.status);
        const typeBadge = booking.type === 'event' ? 
            '<span class="badge badge-primary">Event</span>' : 
            '<span class="badge badge-info">Session</span>';
            
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

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge badge-warning">Pending</span>',
        'approved': '<span class="badge badge-success">Approved</span>',
        'rejected': '<span class="badge badge-danger">Rejected</span>'
    };
    return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
}

function loadStats() {
    fetch('{{ route("admin.calendar.stats") }}')
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
if (document.getElementById('duration_hours')) {
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
}

// Auto-calculate duration based on end time
if (document.getElementById('end_time')) {
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
</style>
@endpush
@endsection
