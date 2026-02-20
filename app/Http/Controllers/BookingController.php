<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display the calendar page
     */
    public function index()
    {
        return view('frontend.calendar');
    }

    /**
     * Get all bookings for the calendar (using session storage)
     */
    public function getBookings(Request $request)
    {
        $bookings = session('bookings', []);
        
        // Filter bookings by date range if provided
        $start = $request->get('start');
        $end = $request->get('end');
        
        if ($start && $end) {
            $bookings = array_filter($bookings, function($booking) use ($start, $end) {
                return $booking['booking_date'] >= $start && $booking['booking_date'] <= $end;
            });
        }
        
        // Format for FullCalendar
        $formattedBookings = array_map(function($booking) {
            return [
                'id' => $booking['id'],
                'title' => $booking['title'],
                'start' => $booking['booking_date'] . 'T' . $booking['start_time'],
                'end' => !empty($booking['end_time']) ? 
                        $booking['booking_date'] . 'T' . $booking['end_time'] : null,
                'backgroundColor' => $booking['color'],
                'borderColor' => $booking['color'],
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => $booking['type'],
                    'customer_name' => $booking['customer_name'],
                    'phone_number' => $booking['phone_number'],
                    'number_of_people' => $booking['number_of_people'],
                    'status' => $booking['status'],
                    'description' => $booking['description'] ?? '',
                    'duration_hours' => $booking['duration_hours'] ?? null
                ]
            ];
        }, $bookings);

        return response()->json(array_values($formattedBookings));
    }

    /**
     * Store a new booking (using session storage)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:event,session',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'duration_hours' => 'nullable|integer|min:1|max:24',
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'number_of_people' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get existing bookings from session
        $bookings = session('bookings', []);
        
        // Check for time conflicts
        $hasConflict = false;
        foreach ($bookings as $booking) {
            if ($booking['booking_date'] === $request->booking_date && 
                $booking['status'] !== 'rejected') {
                
                $existingStart = $booking['start_time'];
                $existingEnd = $booking['end_time'] ?? $booking['start_time'];
                $newStart = $request->start_time;
                $newEnd = $request->end_time ?? $request->start_time;
                
                // Simple time overlap check
                if (($newStart >= $existingStart && $newStart <= $existingEnd) ||
                    ($newEnd >= $existingStart && $newEnd <= $existingEnd) ||
                    ($newStart <= $existingStart && $newEnd >= $existingEnd)) {
                    $hasConflict = true;
                    break;
                }
            }
        }

        if ($hasConflict) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is already booked. Please choose a different time.'
            ], 409);
        }

        // Create new booking
        $newBooking = [
            'id' => uniqid(),
            'title' => $request->title,
            'type' => $request->type,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_hours' => $request->duration_hours,
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'number_of_people' => $request->number_of_people,
            'description' => $request->description,
            'status' => 'pending',
            'color' => '#ffc107', // Yellow for pending
            'price' => $request->price,
            'created_at' => now()->toDateTimeString()
        ];

        // Add to bookings array
        $bookings[] = $newBooking;
        
        // Save to session
        session(['bookings' => $bookings]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully!',
            'booking' => $newBooking
        ]);
    }

    /**
     * Get booking details (using session storage)
     */
    public function show($id)
    {
        $bookings = session('bookings', []);
        
        $booking = collect($bookings)->firstWhere('id', $id);
        
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json($booking);
    }

    /**
     * Update booking status (using session storage)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $bookings = session('bookings', []);
        
        $bookingIndex = null;
        foreach ($bookings as $index => $booking) {
            if ($booking['id'] === $id) {
                $bookingIndex = $index;
                break;
            }
        }
        
        if ($bookingIndex === null) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        // Update status and color
        $bookings[$bookingIndex]['status'] = $request->status;
        $bookings[$bookingIndex]['color'] = match($request->status) {
            'pending' => '#ffc107',  // Yellow
            'approved' => '#28a745', // Green
            'rejected' => '#dc3545', // Red
            default => '#ffc107'
        };

        // Save to session
        session(['bookings' => $bookings]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully!',
            'booking' => $bookings[$bookingIndex]
        ]);
    }

    /**
     * Delete a booking (using session storage)
     */
    public function destroy($id)
    {
        $bookings = session('bookings', []);
        
        $bookings = array_filter($bookings, function($booking) use ($id) {
            return $booking['id'] !== $id;
        });
        
        // Save to session
        session(['bookings' => array_values($bookings)]);

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully!'
        ]);
    }

    /**
     * Get booking statistics (using session storage)
     */
    public function getStats()
    {
        $bookings = session('bookings', []);
        
        $stats = [
            'total' => count($bookings),
            'pending' => count(array_filter($bookings, fn($b) => $b['status'] === 'pending')),
            'approved' => count(array_filter($bookings, fn($b) => $b['status'] === 'approved')),
            'rejected' => count(array_filter($bookings, fn($b) => $b['status'] === 'rejected')),
            'today' => count(array_filter($bookings, fn($b) => $b['booking_date'] === today()->format('Y-m-d')))
        ];

        return response()->json($stats);
    }
}
