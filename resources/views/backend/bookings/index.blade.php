@extends('backend.layout.app')

@section('title', 'Booking Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Booking Management</h6>
                    <p class="text-sm mb-0">Manage all calendar bookings</p>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="bookingsTableBody">
                                <!-- Bookings will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <!-- Booking details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="updateBookingStatus(currentBookingId, 'pending')">Mark Pending</button>
                <button type="button" class="btn btn-success" onclick="updateBookingStatus(currentBookingId, 'approved')">Approve</button>
                <button type="button" class="btn btn-danger" onclick="updateBookingStatus(currentBookingId, 'rejected')">Reject</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentBookingId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadBookings();
    
    // Auto-refresh every 30 seconds
    setInterval(loadBookings, 30000);
});

function loadBookings() {
    fetch('{{ route("calendar.bookings") }}')
        .then(response => response.json())
        .then(bookings => {
            displayBookings(bookings);
        })
        .catch(error => {
            console.error('Error loading bookings:', error);
        });
}

function displayBookings(bookings) {
    const tbody = document.getElementById('bookingsTableBody');
    
    if (bookings.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <p class="text-muted">No bookings found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = bookings.map(booking => {
        const statusBadge = getStatusBadge(booking.extendedProps.status);
        const typeBadge = booking.extendedProps.type === 'event' ? 
            '<span class="badge badge-sm bg-gradient-primary">Event</span>' : 
            '<span class="badge badge-sm bg-gradient-info">Session</span>';
            
        const startDate = new Date(booking.start);
        const endTime = booking.end ? new Date(booking.end).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
        
        return `
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">${booking.title}</h6>
                            <p class="text-xs text-secondary mb-0">${typeBadge}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">${booking.extendedProps.customer_name}</p>
                    <p class="text-xs text-secondary mb-0">${booking.extendedProps.phone_number}</p>
                </td>
                <td class="align-middle text-center text-sm">
                    <span class="text-secondary text-xs font-weight-bold">${startDate.toLocaleDateString()}</span><br>
                    <span class="text-secondary text-xs">${startDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} ${endTime ? '- ' + endTime : ''}</span>
                </td>
                <td class="align-middle text-center">
                    ${statusBadge}
                </td>
                <td class="align-middle text-center">
                    <button class="btn btn-link text-dark px-3 mb-0" onclick="viewBookingDetails('${booking.id}')" title="View" aria-label="View">
                        <i class="fas fa-eye text-dark" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-link text-danger px-3 mb-0" onclick="deleteBooking('${booking.id}')" title="Delete" aria-label="Delete">
                        <i class="fas fa-trash text-danger" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge badge-sm bg-gradient-warning">Pending</span>',
        'approved': '<span class="badge badge-sm bg-gradient-success">Approved</span>',
        'rejected': '<span class="badge badge-sm bg-gradient-danger">Rejected</span>'
    };
    return badges[status] || '<span class="badge badge-sm bg-gradient-secondary">Unknown</span>';
}

function viewBookingDetails(bookingId) {
    currentBookingId = bookingId;
    
    fetch(`{{ route("calendar.bookings.show", ":id") }}`.replace(':id', bookingId))
        .then(response => response.json())
        .then(booking => {
            const statusBadge = getStatusBadge(booking.status);
            const typeBadge = booking.type === 'event' ? 
                '<span class="badge bg-primary">Event</span>' : 
                '<span class="badge bg-info">Session</span>';
                
            document.getElementById('bookingDetailsContent').innerHTML = `
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
                <div class="mt-2"><strong>Created:</strong> ${new Date(booking.created_at).toLocaleString()}</div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Could not load booking details');
        });
}

function updateBookingStatus(bookingId, status) {
    fetch(`{{ route("calendar.bookings.status", ":id") }}`.replace(':id', bookingId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and refresh bookings
            bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
            loadBookings();
            
            alert(data.message);
        } else {
            alert('Error updating booking status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating booking status');
    });
}

function deleteBooking(bookingId) {
    if (!confirm('Are you sure you want to delete this booking?')) {
        return;
    }
    
    fetch(`{{ route("calendar.bookings.destroy", ":id") }}`.replace(':id', bookingId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadBookings();
            alert(data.message);
        } else {
            alert('Error deleting booking');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting booking');
    });
}
</script>
@endsection