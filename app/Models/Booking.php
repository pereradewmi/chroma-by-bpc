<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'customer_name',
        'phone_number',
        'email',
        'number_of_people',
        'description',
        'status',
        'color',
        'price'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'price' => 'decimal:2'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Type constants
    const TYPE_EVENT = 'event';
    const TYPE_SESSION = 'session';

    // Color constants
    const COLOR_PENDING = '#ffc107'; // Yellow
    const COLOR_APPROVED = '#28a745'; // Green
    const COLOR_REJECTED = '#dc3545'; // Red

    /**
     * Get the status color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => self::COLOR_PENDING,
            self::STATUS_APPROVED => self::COLOR_APPROVED,
            self::STATUS_REJECTED => self::COLOR_REJECTED,
            default => self::COLOR_PENDING
        };
    }

    /**
     * Update the color based on status
     */
    protected static function booted()
    {
        static::saving(function ($booking) {
            $booking->color = match($booking->status) {
                self::STATUS_PENDING => self::COLOR_PENDING,
                self::STATUS_APPROVED => self::COLOR_APPROVED,
                self::STATUS_REJECTED => self::COLOR_REJECTED,
                default => self::COLOR_PENDING
            };
        });
    }

    /**
     * Scope for approved bookings
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
