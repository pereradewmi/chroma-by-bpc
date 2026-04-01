<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teacherdetails';
    protected $primaryKey = 'T_ID';

    protected $fillable = [
        'tFName',
        'tLName',
        'tMobileNo',
        'tAddress',
        'Active',
        'teacher_type'
    ];

    protected $casts = [
        'Active' => 'integer',
    ];

    // Relationship with classes
    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id', 'T_ID');
    }

    // Relationship with sessions
    public function sessions()
    {
        return $this->hasMany(Session::class, 'teacher_id', 'T_ID');
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return $this->tFName . ' ' . $this->tLName;
    }

    // Scope for active teachers
    public function scopeActive($query)
    {
        return $query->where('Active', true);
    }
}