<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookingdetails';

    protected $primaryKey = 'booking_ID';

    protected $fillable = [
        'bName',
        'bEmail',
        'bPhone',
        'booking_date',
        'bStart_datetime',
        'bEnd_datetime',
        'bTitle',
        'bDescription',
        'bEvent_type',
        'bEvent_Category',
        'bStatus',
        'bPrice',
        'bPayment_status',
        'bApproved_by',
        'bApproved_at',
        'bReject_by',
        'bReject_at',
        'bRejection_reason',
        'pubprievent',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'bStart_datetime' => 'datetime',
        'bEnd_datetime' => 'datetime',
        'bApproved_at' => 'datetime',
        'bReject_at' => 'datetime',
        'bPrice' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    // Type constants
    const TYPE_EVENT = 'event';

    const TYPE_SESSION = 'session';

    // Payment Status constants
    const PAYMENT_PENDING = 'pending';

    const PAYMENT_PAID = 'paid';

    const PAYMENT_REFUNDED = 'refunded';

    // Public/Private Event constants
    const EVENT_PUBLIC = 'PUB';

    const EVENT_PRIVATE = 'PRI';

    // Color constants
    const COLOR_PENDING = '#ffc107'; // Yellow

    const COLOR_APPROVED = '#28a745'; // Green

    const COLOR_REJECTED = '#dc3545'; // Red

    /**
     * Get the status color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->bStatus) {
            self::STATUS_PENDING => self::COLOR_PENDING,
            self::STATUS_APPROVED => self::COLOR_APPROVED,
            self::STATUS_REJECTED => self::COLOR_REJECTED,
            default => self::COLOR_PENDING
        };
    }

    /**
     * Scope for approved bookings
     */
    public function scopeApproved($query)
    {
        return $query->where('bStatus', self::STATUS_APPROVED);
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('bStatus', self::STATUS_PENDING);
    }

    /**
     * Scope for public events
     */
    public function scopePublic($query)
    {
        return $query->where('pubprievent', self::EVENT_PUBLIC);
    }

    /**
     * Scope for private events
     */
    public function scopePrivate($query)
    {
        return $query->where('pubprievent', self::EVENT_PRIVATE);
    }
}
