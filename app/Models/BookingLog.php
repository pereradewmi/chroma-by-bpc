<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class BookingLog extends Model
{
    use HasFactory;

    protected $table = 'bookingdetailsdeleted';
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'booking_id',
        'action',
        'old_data',
        'new_data',
        'description',
        'user_id',
        'logged_at'
    ];

    protected $casts = [
        'logged_at' => 'datetime'
    ];

    /**
     * Get the booking relationship
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_ID');
    }

    /**
     * Get the user who made the change
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get formatted action label
     */
    public function getFormattedActionAttribute()
    {
        $action = $this->attributes['action'] ?? '';
        $labels = [
            'created' => 'CREATED',
            'updated' => 'UPDATED',
            'approved' => 'APPROVED',
            'rejected' => 'REJECTED',
            'deleted' => 'DELETED',
            'visibility_updated' => 'VISIBILITY_UPDATED'
        ];
        return $labels[strtolower($action)] ?? strtoupper($action);
    }

    /**
     * Get user name
     */
    public function getUserNameAttribute()
    {
        if ($this->user) {
            return $this->user->name ?? 'System';
        }
        return 'System';
    }

    /**
     * Get user role
     */
    public function getUserRoleAttribute()
    {
        if ($this->user && method_exists($this->user, 'getRoleAttribute')) {
            return $this->user->getRoleAttribute();
        }
        return 'Admin';
    }

    /**
     * Get changes description
     */
    public function getChangesDescriptionAttribute()
    {
        return $this->attributes['description'] ?? $this->attributes['action'] ?? '';
    }

    /**
     * Get changes summary
     */
    public function getChangesSummaryAttribute()
    {
        return 'Action: ' . ($this->formatted_action ?? 'UPDATED');
    }

    /**
     * Log a booking change
     */
    public static function logBookingChange($booking, $action, $oldData = null, $newData = null, $description = null)
    {
        try {
            // Store as JSON if it's an array
            $oldDataJson = is_array($oldData) ? json_encode($oldData) : $oldData;
            $newDataJson = is_array($newData) ? json_encode($newData) : $newData;

            return self::create([
                'booking_id' => $booking->booking_ID,
                'action' => $action,
                'old_data' => $oldDataJson,
                'new_data' => $newDataJson,
                'description' => $description,
                'user_id' => Auth::id(),
                'logged_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log booking change: ' . $e->getMessage());
            return null;
        }
    }
}