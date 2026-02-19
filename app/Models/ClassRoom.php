<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'class_rooms';

    protected $fillable = [
        'class_name',
        'teacher_id'
    ];

    // Relationship with teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'T_ID');
    }
}