<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display the frontend calendar page
     */
    public function frontendIndex()
    {
        return view('frontend.calendar');
    }

    /**
     * Display the backend calendar page
     */
    public function backendIndex()
    {
        return view('backend.calendar');
    }

    /**
     * Get all bookings for the calendar
     */
    public function getBookings(Request $request)
    {
        try {
            $query = Booking::query();

            // Check if this is the public frontend request based on route name
            $routeName = $request->route()->getName();
            $isFrontend = $routeName === 'Appointment.bookings';

            // Filter bookings by date range if provided
            $start = $request->get('start');
            $end = $request->get('end');
            $search = trim((string) $request->get('search', ''));

            if ($start && $end) {
                $query->whereBetween('booking_date', [$start, $end]);
            }

            // For frontend, only show approved bookings
            if ($isFrontend) {
                $query->where('bStatus', 'approved');
            } elseif ($search !== '') {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('booking_ID', 'like', "%{$search}%")
                        ->orWhere('bTitle', 'like', "%{$search}%")
                        ->orWhere('bName', 'like', "%{$search}%")
                        ->orWhere('bEmail', 'like', "%{$search}%")
                        ->orWhere('bPhone', 'like', "%{$search}%")
                        ->orWhere('bStatus', 'like', "%{$search}%");
                });
            }

            $bookings = $query->get();

            // Format for FullCalendar
            $formattedBookings = $bookings->map(function($booking) use ($isFrontend) {

                // Determine color based on status
                $color = '#ffc107'; // default yellow for pending
                if ($booking->bStatus === 'approved') {
                    $color = '#28a745'; // green
                } elseif ($booking->bStatus === 'rejected') {
                    $color = '#dc3545'; // red
                }

                $extendedProps = [
                    'type' => $booking->bEvent_type,
                    'customer_name' => $booking->bName,
                    'phone_number' => $booking->bPhone,
                    'email' => $booking->bEmail,
                    'description' => $booking->bDescription ?? '',
                    'status' => $booking->bStatus
                ];

                // Add additional fields for backend only
                if (!$isFrontend) {
                    $extendedProps = array_merge($extendedProps, [
                        'price' => $booking->bPrice,
                        'payment_status' => $booking->bPayment_status,
                        'rejection_reason' => $booking->bRejection_reason,
                        'pubprievent' => $booking->pubprievent
                    ]);
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
                    'extendedProps' => $extendedProps
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
                'booking_date' => 'required|date|after:today',
                'bStart_datetime' => 'required|date|after:today',
                'bEnd_datetime' => 'nullable|date|after:bStart_datetime',
                'bName' => 'required|string|max:50',
                'bPhone' => 'required|string|max:10',
                'bEmail' => 'required|email|max:50',
                'bDescription' => 'nullable|string|max:500',
                'bStatus' => 'nullable|in:pending,approved,rejected',
                'bPrice' => 'nullable|numeric|min:0',
                'bPayment_status' => 'nullable|in:pending,paid,refunded',
                'bRejection_reason' => 'nullable|string|max:500'
            ], [
                'booking_date.after' => 'Booking date must be tomorrow or later.',
                'bStart_datetime.after' => 'Start date and time must be tomorrow or later.'
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
                // 'bEvent_Category' => 0,
                'bStatus' => $request->bStatus ?? 'pending',
                'bPrice' => $request->bPrice ?? 0,
                'bPayment_status' => $request->bPayment_status ?? 'pending',
                'bApproved_by' => null,
                'bApproved_at' => null,
                'bReject_by' => null,
                'bReject_at' => null,
                'bRejection_reason' => $request->bRejection_reason ?? '',
                'pubprievent' => Booking::EVENT_PRIVATE // Default to private
            ]);

            // Log the creation
            BookingLog::logBookingChange(
                $booking,
                'created',
                null,
                $booking->toArray(),
                'New booking created'
            );

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

        // Calculate duration if both times exist
        $durationHours = null;
        if ($booking->bStart_datetime && $booking->bEnd_datetime) {
            $durationMinutes = $booking->bStart_datetime->diffInMinutes($booking->bEnd_datetime);
            $durationHours = round($durationMinutes / 60, 1);
        }

        return response()->json([
            'id' => $booking->booking_ID,
            'title' => $booking->bTitle,
            'type' => $booking->bEvent_type,
            'booking_date' => $booking->booking_date->format('Y-m-d'),
            'start_datetime' => $booking->bStart_datetime->format('Y-m-d H:i:s'),
            'start_time' => $booking->bStart_datetime->format('H:i'),
            'end_datetime' => $booking->bEnd_datetime ? $booking->bEnd_datetime->format('Y-m-d H:i:s') : null,
            'end_time' => $booking->bEnd_datetime ? $booking->bEnd_datetime->format('H:i') : null,
            'duration_hours' => $durationHours,
            'customer_name' => $booking->bName,
            'phone_number' => $booking->bPhone,
            'email' => $booking->bEmail,
            'description' => $booking->bDescription,
            'status' => $booking->bStatus,
            'price' => $booking->bPrice,
            'payment_status' => $booking->bPayment_status,
            'rejection_reason' => $booking->bRejection_reason,
            'pubprievent' => $booking->pubprievent
        ]);
    }

    /**
     * Update booking status with logging
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

        // Store old data for logging
        $oldData = $booking->toArray();
        
        $user = Auth::user();
        $updateData = [
            'bStatus' => $request->status,
            'bRejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ];
        
        if ($request->status === 'approved') {
            $updateData['bApproved_at'] = now();
            $updateData['bApproved_by'] = $user ? $user->id : null;
        } else if ($request->status === 'rejected') {
            $updateData['bReject_at'] = now();
            $updateData['bReject_by'] = $user ? $user->id : null;
        }
        
        $booking->update($updateData);
        
        // Log the status change
        BookingLog::logBookingChange(
            $booking,
            $request->status,
            $oldData,
            $booking->fresh()->toArray(),
            "Booking status changed to {$request->status}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'booking' => $booking
        ]);
    }

    /**
     * Delete a booking with logging
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

        // Log before deletion
        BookingLog::logBookingChange(
            $booking,
            'deleted',
            $booking->toArray(),
            null,
            'Booking deleted by admin'
        );

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

    /**
     * Admin: Approve booking
     */
    public function approveBooking(Request $request, $id)
    {
        try {
            $booking = Booking::find($id);
            
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            if ($booking->bStatus === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking is already approved'
                ]);
            }

            // Store old data for logging
            $oldData = $booking->toArray();
            
            $user = Auth::user();
            $booking->update([
                'bStatus' => 'approved',
                'bApproved_at' => now(),
                'bApproved_by' => $user ? $user->id : null,
                'bRejection_reason' => null // Clear any previous rejection reason
            ]);

            // Log the approval
            BookingLog::logBookingChange(
                $booking,
                'approved',
                $oldData,
                $booking->fresh()->toArray(),
                'Booking approved by admin'
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking approved successfully!',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Reject booking
     */
    public function rejectBooking(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500'
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

            if ($booking->bStatus === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking is already rejected'
                ]);
            }

            // Store old data for logging
            $oldData = $booking->toArray();
            
            $user = Auth::user();
            $booking->update([
                'bStatus' => 'rejected',
                'bReject_at' => now(),
                'bReject_by' => $user ? $user->id : null,
                'bRejection_reason' => $request->rejection_reason
            ]);

            // Log the rejection
            BookingLog::logBookingChange(
                $booking,
                'rejected',
                $oldData,
                $booking->fresh()->toArray(),
                'Booking rejected by admin: ' . $request->rejection_reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking rejected successfully!',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Update booking details
     */
    public function updateBooking(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bTitle' => 'required|string|max:50',
                'bEvent_type' => 'required|in:event,session',
                'booking_date' => 'required|date|after:today',
                'bStart_datetime' => 'required|date|after:today',
                'bEnd_datetime' => 'nullable|date|after:bStart_datetime',
                'bName' => 'required|string|max:50',
                'bPhone' => 'required|string|max:10',
                'bEmail' => 'nullable|email|max:50',
                'bDescription' => 'nullable|string|max:500',
                'bStatus' => 'nullable|in:pending,approved,rejected',
                'bPrice' => 'nullable|numeric|min:0',
                'bPayment_status' => 'nullable|in:pending,paid,refunded'
            ], [
                'booking_date.after' => 'Booking date must be tomorrow or later.',
                'bStart_datetime.after' => 'Start date and time must be tomorrow or later.'
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

            // Store old data for logging
            $oldData = $booking->toArray();
            
            $user = Auth::user();
            $updateData = $request->only([
                'bTitle', 'bEvent_type', 'booking_date', 'bStart_datetime', 
                'bEnd_datetime', 'bName', 'bPhone', 'bEmail', 'bDescription', 
                'bStatus', 'bPrice', 'bPayment_status'
            ]);

            $booking->update($updateData);

            // Log the update
            BookingLog::logBookingChange(
                $booking,
                'updated',
                $oldData,
                $booking->fresh()->toArray(),
                'Booking details updated by admin'
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully!',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking history/logs
     */
    public function getBookingLogs($id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // For now, return basic booking info as history since the log table might not have data
            // In the future, this should pull from actual audit logs
            $logs = [];

            // Add booking creation record
            $logs[] = [
                'id' => 1,
                'action' => 'CREATED',
                'description' => 'Booking created',
                'changes_summary' => 'Initial booking created',
                'user_name' => 'System',
                'user_role' => 'System',
                'logged_at' => $booking->created_at ? $booking->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                'logged_at_human' => $booking->created_at ? $booking->created_at->diffForHumans() : now()->diffForHumans()
            ];

            // If there are updates, show them
            if ($booking->updated_at && $booking->updated_at->ne($booking->created_at)) {
                $logs[] = [
                    'id' => 2,
                    'action' => 'UPDATED',
                    'description' => 'Booking updated',
                    'changes_summary' => 'Booking status: ' . ucfirst($booking->bStatus),
                    'user_name' => $booking->bApproved_by ? 'Admin' : 'System',
                    'user_role' => 'Admin',
                    'logged_at' => $booking->updated_at->format('Y-m-d H:i:s'),
                    'logged_at_human' => $booking->updated_at->diffForHumans()
                ];
            }

            // If approved, show approval
            if ($booking->bStatus === 'approved' && $booking->bApproved_at) {
                $logs[] = [
                    'id' => 3,
                    'action' => 'APPROVED',
                    'description' => 'Booking approved',
                    'changes_summary' => 'Booking was approved',
                    'user_name' => $booking->bApproved_by ?? 'Admin',
                    'user_role' => 'Admin',
                    'logged_at' => $booking->bApproved_at->format('Y-m-d H:i:s'),
                    'logged_at_human' => $booking->bApproved_at->diffForHumans()
                ];
            }

            // If rejected, show rejection
            if ($booking->bStatus === 'rejected' && $booking->bReject_at) {
                $logs[] = [
                    'id' => 4,
                    'action' => 'REJECTED',
                    'description' => 'Booking rejected: ' . ($booking->bRejection_reason ?? 'No reason provided'),
                    'changes_summary' => 'Booking was rejected',
                    'user_name' => $booking->bReject_by ?? 'Admin',
                    'user_role' => 'Admin',
                    'logged_at' => $booking->bReject_at->format('Y-m-d H:i:s'),
                    'logged_at_human' => $booking->bReject_at->diffForHumans()
                ];
            }

            return response()->json([
                'success' => true,
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            \Log::error('Error retrieving booking logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving booking logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking public/private status
     */
    public function updatePubPrivateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pubprievent' => 'required|in:PUB,PRI'
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

            // Store old data for logging
            $oldData = $booking->toArray();

            $user = Auth::user();
            $booking->update([
                'pubprievent' => $request->pubprievent
            ]);

            // Log the status change
            $statusLabel = $request->pubprievent === 'PUB' ? 'Public' : 'Private';
            BookingLog::logBookingChange(
                $booking,
                'visibility_updated',
                $oldData,
                $booking->fresh()->toArray(),
                "Event visibility changed to {$statusLabel}"
            );

            return response()->json([
                'success' => true,
                'message' => "Event visibility updated to {$statusLabel} successfully!",
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating event visibility: ' . $e->getMessage()
            ], 500);
        }
    }
}