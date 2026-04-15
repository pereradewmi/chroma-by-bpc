<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingLog extends Model
{
    use HasFactory;

    // Archive table that stores a full snapshot of the booking
    protected $table = 'bookingdetailsdeleted';
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'booking_ID',
        'bName',
        'bEmail',
        'bPhone',
        'booking_date',
        'bStart_datetime',
        'bEnd_datetime',
        'bTitle',
        'bDescription',
        'bEvent_type',
        'bStatus',
        'bPrice',
        'bPayment_status',
        'bApproved_by',
        'bApproved_at',
        'bReject_by',
        'bReject_at',
        'bRejection_reason',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'bStart_datetime' => 'datetime',
        'bEnd_datetime' => 'datetime',
        'bApproved_at' => 'datetime',
        'bReject_at' => 'datetime',
        'bPrice' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Log a booking change by storing the previous state in bookingdetailsdeleted.
     */
    public static function logBookingChange($booking, $action = null, $oldData = null, $newData = null, $description = null)
    {
        try {
            // Prefer the explicit old data snapshot if provided; otherwise use current booking state
            $data = is_array($oldData) ? $oldData : $booking->toArray();

            return self::create([
                'booking_ID' => $data['booking_ID'] ?? $booking->booking_ID,
                'bName' => $data['bName'] ?? null,
                'bEmail' => $data['bEmail'] ?? null,
                'bPhone' => $data['bPhone'] ?? null,
                'booking_date' => $data['booking_date'] ?? null,
                'bStart_datetime' => $data['bStart_datetime'] ?? null,
                'bEnd_datetime' => $data['bEnd_datetime'] ?? null,
                'bTitle' => $data['bTitle'] ?? null,
                'bDescription' => $data['bDescription'] ?? null,
                'bEvent_type' => $data['bEvent_type'] ?? null,
                'bStatus' => $data['bStatus'] ?? null,
                'bPrice' => $data['bPrice'] ?? null,
                'bPayment_status' => $data['bPayment_status'] ?? null,
                'bApproved_by' => $data['bApproved_by'] ?? null,
                'bApproved_at' => $data['bApproved_at'] ?? null,
                'bReject_by' => $data['bReject_by'] ?? null,
                'bReject_at' => $data['bReject_at'] ?? null,
                'bRejection_reason' => $data['bRejection_reason'] ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log booking change: ' . $e->getMessage());
            return null;
        }
    }
}