<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPayment extends Model
{
    use HasFactory;

    protected $table = 'teacherpaymentdetails';
    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'teacher_id',
        'amount',
        'month'
    ];

    /**
     * Get the teacher that owns the payment
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'T_ID');
    }
}
