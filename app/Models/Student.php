<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory;

    protected $table = 'studentdetails';

    protected $primaryKey = 'AutoID';

    protected $fillable = [
        'fName',
        'lName',
        'Age',
        'mobileNo',
        'Address',
        'Active',
        'studentemail',
        'studentpic',
        'guardian_name',
        'guardian_phone',
        'class_ids',
    ];

    protected $casts = [
        'Age' => 'integer',
        'Active' => 'integer',
    ];

    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'student_classes', 'student_id', 'class_id')
            ->withTimestamps();
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->studentpic) {
            $path = 'students/'.ltrim($this->studentpic, '/');

            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        return asset('front-assets/img/logo.png');
    }
}
