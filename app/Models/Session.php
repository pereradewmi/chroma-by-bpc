<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_name',
        'teacher_id'
    ];

    // Relationship with teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'T_ID');
    }
}