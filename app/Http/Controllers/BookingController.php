<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Booking;

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
     * Get all bookings for the calendar
     */
    public function getBookings(Request $request)
    {
        try {
            $query = Booking::query();
            
            // Filter bookings by date range if provided
            $start = $request->get('start');
            $end = $request->get('end');
            
            if ($start && $end) {
                $query->whereBetween('booking_date', [$start, $end]);
            }
            
            $bookings = $query->get();
            
            // Format for FullCalendar
            $formattedBookings = $bookings->map(function($booking) {
                
                // Determine color based on status
                $color = '#ffc107'; // default yellow for pending
                if ($booking->bStatus === 'approved') {
                    $color = '#28a745'; // green
                } elseif ($booking->bStatus === 'rejected') {
                    $color = '#dc3545'; // red
                }
                
                return [
                    'id' => $booking->booking_ID,
                    'title' => $booking->bTitle . ' - ' . $booking->bName,
                    'start' => $booking->bStart_datetime->format('Y-m-d\TH:i:s'),
                    'end' => $booking->bEnd_datetime ? 
                            $booking->bEnd_datetime->format('Y-m-d\TH:i:s') : null,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => $booking->bEvent_type,
                        'customer_name' => $booking->bName,
                        'phone_number' => $booking->bPhone,
                        'email' => $booking->bEmail,
                        'status' => $booking->bStatus,
                        'description' => $booking->bDescription ?? '',
                        'price' => $booking->bPrice,
                        'payment_status' => $booking->bPayment_status
                    ]
                ];
            });

            return response()->json($formattedBookings->values());
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load bookings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bTitle' => 'required|string|max:50',
                'bEvent_type' => 'required|in:event,session',
                'booking_date' => 'required|date',
                'bStart_datetime' => 'required|date',
                'bEnd_datetime' => 'nullable|date|after:bStart_datetime',
                'bName' => 'required|string|max:50',
                'bPhone' => 'required|string|max:10',
                'bEmail' => 'required|email|max:50',
                'bDescription' => 'nullable|string|max:500',
                'bStatus' => 'nullable|in:pending,approved,rejected',
                'bPrice' => 'nullable|numeric|min:0',
                'bPayment_status' => 'nullable|in:pending,paid,refunded',
                'bRejection_reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create new booking
            $booking = Booking::create([
                'bName' => $request->bName,
                'bEmail' => $request->bEmail,
                'bPhone' => $request->bPhone,
                'booking_date' => $request->booking_date,
                'bStart_datetime' => $request->bStart_datetime,
                'bEnd_datetime' => $request->bEnd_datetime,
                'bTitle' => $request->bTitle,
                'bDescription' => $request->bDescription ?? '',
                'bEvent_type' => $request->bEvent_type,
                'bStatus' => $request->bStatus ?? 'pending',
                'bPrice' => $request->bPrice ?? 0,
                'bPayment_status' => $request->bPayment_status ?? 'pending',
                'bApproved_by' => null,
                'bApproved_at' => null,
                'bReject_by' => null,
                'bReject_at' => null,
                'bRejection_reason' => $request->bRejection_reason ?? ''
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully!',
                'booking' => $booking
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking details
     */
    public function show($id)
    {
        $booking = Booking::find($id);
        
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json([
            'id' => $booking->booking_ID,
            'title' => $booking->bTitle,
            'type' => $booking->bEvent_type,
            'booking_date' => $booking->booking_date->format('Y-m-d'),
            'start_datetime' => $booking->bStart_datetime->format('Y-m-d H:i:s'),
            'end_datetime' => $booking->bEnd_datetime ? $booking->bEnd_datetime->format('Y-m-d H:i:s') : null,
            'customer_name' => $booking->bName,
            'phone_number' => $booking->bPhone,
            'email' => $booking->bEmail,
            'description' => $booking->bDescription,
            'status' => $booking->bStatus,
            'price' => $booking->bPrice,
            'payment_status' => $booking->bPayment_status,
            'rejection_reason' => $booking->bRejection_reason
        ]);
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::find($id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->update([
            'bStatus' => $request->status,
            'bRejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            'bApproved_at' => $request->status === 'approved' ? now() : null,
            'bReject_at' => $request->status === 'rejected' ? now() : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'booking' => $booking
        ]);
    }

    /**
     * Delete a booking
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }

    /**
     * Get booking statistics
     */
    public function getStats()
    {
        $total = Booking::count();
        $pending = Booking::where('bStatus', 'pending')->count();
        $approved = Booking::where('bStatus', 'approved')->count();
        $today = Booking::whereDate('booking_date', today())->count();

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'today' => $today
        ]);
    }
}