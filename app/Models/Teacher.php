<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'address',
        'mobile_number',
        'subject_name'
    ];

    // Relationship with classes
    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    // Relationship with sessions
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}