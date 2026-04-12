<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorPayment extends Model
{
    use HasFactory;

    protected $table = 'instructorpaymentdetails';

    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'instructor_id',
        'session_id',
        'amount',
        'month',
        'sessions_count',
        'description',
    ];

    /**
     * Get the instructor that owns the payment
     */
    public function instructor()
    {
        return $this->belongsTo(Teacher::class, 'instructor_id', 'T_ID');
    }

    /**
     * Get the session associated with the payment
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id', 'sID');
    }
}
