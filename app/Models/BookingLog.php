<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingLog extends Model
{
    use HasFactory;

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
        'bRejection_reason'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'bStart_datetime' => 'datetime',
        'bEnd_datetime' => 'datetime',
        'bApproved_at' => 'datetime',
        'bReject_at' => 'datetime',
        'bPrice' => 'decimal:2'
    ];

    /**
     * Relationship to the booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_ID', 'booking_ID');
    }

    /**
     * Create a log entry by copying booking data
     */
    public static function logBookingChange($booking)
    {
        return self::create([
            'booking_ID' => $booking->booking_ID,
            'bName' => $booking->bName,
            'bEmail' => $booking->bEmail,
            'bPhone' => $booking->bPhone,
            'booking_date' => $booking->booking_date,
            'bStart_datetime' => $booking->bStart_datetime,
            'bEnd_datetime' => $booking->bEnd_datetime,
            'bTitle' => $booking->bTitle,
            'bDescription' => $booking->bDescription,
            'bEvent_type' => $booking->bEvent_type,
            'bStatus' => $booking->bStatus,
            'bPrice' => $booking->bPrice,
            'bPayment_status' => $booking->bPayment_status,
            'bApproved_by' => $booking->bApproved_by,
            'bApproved_at' => $booking->bApproved_at,
            'bReject_by' => $booking->bReject_by,
            'bReject_at' => $booking->bReject_at,
            'bRejection_reason' => $booking->bRejection_reason,
        ]);
    }
}