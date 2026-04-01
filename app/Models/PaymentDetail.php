<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $table = 'classpaymentdetails';
    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'studentID',
        'classID',
        'month',
        'payment_type'
    ];

    /**
     * Get the student that owns the payment
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'studentID', 'AutoID');
    }

    /**
     * Get the class that owns the payment
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classID', 'cID');
    }
}