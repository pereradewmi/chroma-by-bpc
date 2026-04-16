<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            $formattedBookings = $bookings->map(function ($booking) use ($isFrontend) {

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
                    'status' => $booking->bStatus,
                ];

                // Add additional fields for backend only
                if (! $isFrontend) {
                    $extendedProps = array_merge($extendedProps, [
                        'price' => $booking->bPrice,
                        'payment_status' => $booking->bPayment_status,
                        'rejection_reason' => $booking->bRejection_reason,
                    ]);
                }

                return [
                    'id' => $booking->booking_ID,
                    'title' => $booking->bTitle.' - '.$booking->bName,
                    'start' => $booking->bStart_datetime->format('Y-m-d\TH:i:s'),
                    'end' => $booking->bEnd_datetime ?
                            $booking->bEnd_datetime->format('Y-m-d\TH:i:s') : null,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'extendedProps' => $extendedProps,
                ];
            });

            return response()->json($formattedBookings->values());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load bookings: '.$e->getMessage(),
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
                'bRejection_reason' => 'nullable|string|max:500',
            ], [
                'booking_date.after' => 'Booking date must be tomorrow or later.',
                'bStart_datetime.after' => 'Start date and time must be tomorrow or later.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
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
                'booking' => $booking,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get booking details
     */
    public function show($id)
    {
        $booking = Booking::find($id);

        if (! $booking) {
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
            'today' => $today,
        ]);
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
                // Allow editing of existing past bookings; only validate format here
                'booking_date' => 'required|date',
                'bStart_datetime' => 'required|date',
                'bEnd_datetime' => 'nullable|date|after:bStart_datetime',
                'bName' => 'required|string|max:50',
                'bPhone' => 'required|string|max:10',
                'bEmail' => 'nullable|email|max:50',
                'bDescription' => 'nullable|string|max:500',
                'bStatus' => 'nullable|in:pending,approved,rejected',
                'bRejection_reason' => 'nullable|string|max:500|required_if:bStatus,rejected',
                'bPrice' => 'nullable|numeric|min:0',
                'bPayment_status' => 'nullable|in:pending,paid,refunded',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $booking = Booking::find($id);

            if (! $booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                ], 404);
            }

            // Store old data for logging
            $oldData = $booking->toArray();

            $user = Auth::user();
            $updateData = $request->only([
                'bTitle', 'bEvent_type', 'booking_date', 'bStart_datetime',
                'bEnd_datetime', 'bName', 'bPhone', 'bEmail', 'bDescription',
                'bStatus', 'bPrice', 'bPayment_status', 'bRejection_reason',
            ]);

            $status = $updateData['bStatus'] ?? $booking->bStatus;
            if ($status === 'approved') {
                if ($booking->bStatus !== 'approved') {
                    $updateData['bApproved_at'] = now();
                    $updateData['bApproved_by'] = $user ? $user->id : null;
                }
                $updateData['bReject_at'] = null;
                $updateData['bReject_by'] = null;
                $updateData['bRejection_reason'] = null;
            } elseif ($status === 'rejected') {
                if ($booking->bStatus !== 'rejected') {
                    $updateData['bReject_at'] = now();
                    $updateData['bReject_by'] = $user ? $user->id : null;
                }
                $updateData['bApproved_at'] = null;
                $updateData['bApproved_by'] = null;
            } else {
                $updateData['bApproved_at'] = null;
                $updateData['bApproved_by'] = null;
                $updateData['bReject_at'] = null;
                $updateData['bReject_by'] = null;
                $updateData['bRejection_reason'] = null;
            }

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
                'booking' => $booking->fresh(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking: '.$e->getMessage(),
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

            if (! $booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                ], 404);
            }

            $logsResponse = [];

            // Try to pull detailed audit logs; if anything goes wrong, fall back gracefully
            try {
                // Each row in bookingdetailsdeleted is a snapshot of a previous state
                $logs = BookingLog::where('booking_ID', $booking->booking_ID)
                    ->orderByDesc('created_at')
                    ->get();

                if ($logs->isNotEmpty()) {
                    $logsResponse = $logs->map(function ($log, $index) {
                        // Derive an action label from status for display purposes
                        $status = strtolower((string) $log->bStatus);
                        if ($status === 'approved') {
                            $action = 'APPROVED';
                            $description = 'Booking approved';
                        } elseif ($status === 'rejected') {
                            $action = 'REJECTED';
                            $description = 'Booking rejected';
                        } else {
                            $action = 'UPDATED';
                            $description = 'Booking updated';
                        }

                        // Build a concise snapshot summary
                        $parts = [];
                        if ($log->booking_date) {
                            $parts[] = 'Date: '.$log->booking_date->format('Y-m-d');
                        }
                        if ($log->bStart_datetime) {
                            $parts[] = 'Start: '.$log->bStart_datetime->format('Y-m-d H:i');
                        }
                        if ($log->bEnd_datetime) {
                            $parts[] = 'End: '.$log->bEnd_datetime->format('Y-m-d H:i');
                        }
                        if ($log->bStatus) {
                            $parts[] = 'Status: '.ucfirst($log->bStatus);
                        }
                        if ($log->bPayment_status) {
                            $parts[] = 'Payment: '.ucfirst($log->bPayment_status);
                        }
                        if ($log->bPrice !== null) {
                            $parts[] = 'Price: '.$log->bPrice;
                        }

                        $changesSummary = implode(', ', $parts);

                        return [
                            'id' => $index + 1,
                            'action' => $action,
                            'description' => $description,
                            'changes_summary' => $changesSummary ?: 'Snapshot of booking at this time',
                            'user_name' => 'Admin',
                            'user_role' => 'Admin',
                            'logged_at' => $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                            'logged_at_human' => $log->created_at ? $log->created_at->diffForHumans() : now()->diffForHumans(),
                            // Full snapshot details for history display
                            'title' => $log->bTitle,
                            'customer_name' => $log->bName,
                            'phone' => $log->bPhone,
                            'email' => $log->bEmail,
                            'description_full' => $log->bDescription,
                            'rejection_reason' => $log->bRejection_reason,
                        ];
                    })->values()->all();
                }
            } catch (\Exception $e) {
                \Log::warning('Booking audit logs unavailable, falling back to basic history: '.$e->getMessage());
            }

            // Fallback to basic history (similar to original implementation) if no audit logs
            if (empty($logsResponse)) {
                $basicLogs = [];

                // Creation record
                $basicLogs[] = [
                    'id' => 1,
                    'action' => 'CREATED',
                    'description' => 'Booking created',
                    'changes_summary' => 'Initial booking created',
                    'user_name' => 'System',
                    'user_role' => 'System',
                    'logged_at' => $booking->created_at ? $booking->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                    'logged_at_human' => $booking->created_at ? $booking->created_at->diffForHumans() : now()->diffForHumans(),
                ];

                // Updated
                if ($booking->updated_at && $booking->updated_at->ne($booking->created_at)) {
                    $status = $booking->bStatus ? ucfirst($booking->bStatus) : 'N/A';
                    $paymentStatus = $booking->bPayment_status ? ucfirst($booking->bPayment_status) : 'N/A';
                    $price = $booking->bPrice !== null ? $booking->bPrice : 'N/A';
                    $date = $booking->booking_date ? $booking->booking_date->format('Y-m-d') : 'N/A';

                    $basicLogs[] = [
                        'id' => 2,
                        'action' => 'UPDATED',
                        'description' => 'Booking updated',
                        'changes_summary' => "Current values → Status: {$status}, Payment: {$paymentStatus}, Date: {$date}, Price: {$price}",
                        'user_name' => 'Admin',
                        'user_role' => 'Admin',
                        'logged_at' => $booking->updated_at->format('Y-m-d H:i:s'),
                        'logged_at_human' => $booking->updated_at->diffForHumans(),
                    ];
                }

                // Approved
                if ($booking->bStatus === 'approved' && $booking->bApproved_at) {
                    $basicLogs[] = [
                        'id' => 3,
                        'action' => 'APPROVED',
                        'description' => 'Booking approved',
                        'changes_summary' => 'Booking was approved',
                        'user_name' => $booking->bApproved_by ?? 'Admin',
                        'user_role' => 'Admin',
                        'logged_at' => $booking->bApproved_at->format('Y-m-d H:i:s'),
                        'logged_at_human' => $booking->bApproved_at->diffForHumans(),
                    ];
                }

                // Rejected
                if ($booking->bStatus === 'rejected' && $booking->bReject_at) {
                    $basicLogs[] = [
                        'id' => 4,
                        'action' => 'REJECTED',
                        'description' => 'Booking rejected: '.($booking->bRejection_reason ?? 'No reason provided'),
                        'changes_summary' => 'Booking was rejected',
                        'user_name' => $booking->bReject_by ?? 'Admin',
                        'user_role' => 'Admin',
                        'logged_at' => $booking->bReject_at->format('Y-m-d H:i:s'),
                        'logged_at_human' => $booking->bReject_at->diffForHumans(),
                    ];
                }

                // Sort basic logs latest-first by logged_at
                usort($basicLogs, function ($a, $b) {
                    return strcmp($b['logged_at'], $a['logged_at']);
                });

                $logsResponse = $basicLogs;
            }

            return response()->json([
                'success' => true,
                'logs' => $logsResponse,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error retrieving booking logs: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving booking logs: '.$e->getMessage(),
            ], 500);
        }
    }
}
