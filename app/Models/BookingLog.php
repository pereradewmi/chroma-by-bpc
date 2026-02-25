<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'action',
        'changes_description',
        'old_data',
        'new_data',
        'user_id',
        'user_name',
        'user_role',
        'original_title',
        'original_event_type',
        'original_booking_date',
        'original_start_datetime',
        'original_end_datetime',
        'original_status',
        'original_payment_status',
        'original_customer_name',
        'original_phone_number',
        'original_email',
        'original_price',
        'original_description',
        'original_rejection_reason',
        'logged_at'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'logged_at' => 'datetime',
        'original_booking_date' => 'date',
        'original_start_datetime' => 'datetime',
        'original_end_datetime' => 'datetime',
        'original_price' => 'decimal:2'
    ];

    /**
     * Relationship to the booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship to the user who made the change
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a log entry for booking changes
     */
    public static function logBookingChange($booking, $action, $oldData = null, $newData = null, $description = null)
    {
        $user = auth()->user();
        
        return self::create([
            'booking_id' => $booking->booking_ID,
            'action' => $action,
            'changes_description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'System',
            'user_role' => $user ? ($user->role ?? 'admin') : 'system',
            
            // Store original booking data
            'original_title' => $booking->bTitle,
            'original_event_type' => $booking->bEvent_type,
            'original_booking_date' => $booking->booking_date,
            'original_start_datetime' => $booking->bStart_datetime,
            'original_end_datetime' => $booking->bEnd_datetime,
            'original_status' => $booking->bStatus,
            'original_payment_status' => $booking->bPayment_status,
            'original_customer_name' => $booking->bName,
            'original_phone_number' => $booking->bPhone,
            'original_email' => $booking->bEmail,
            'original_price' => $booking->bPrice,
            'original_description' => $booking->bDescription,
            'original_rejection_reason' => $booking->bRejection_reason,
            
            'logged_at' => now()
        ]);
    }

    /**
     * Get formatted action name
     */
    public function getFormattedActionAttribute()
    {
        return strtoupper(str_replace('_', ' ', $this->action));
    }

    /**
     * Get changes summary
     */
    public function getChangesSummaryAttribute()
    {
        if (!$this->old_data || !$this->new_data) {
            return $this->changes_description ?? 'No changes recorded';
        }

        $changes = [];
        $oldData = $this->old_data;
        $newData = $this->new_data;

        foreach ($newData as $key => $newValue) {
            $oldValue = $oldData[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[] = ucfirst($key) . ": '{$oldValue}' → '{$newValue}'";
            }
        }

        return empty($changes) ? 'No significant changes' : implode(', ', $changes);
    }
}