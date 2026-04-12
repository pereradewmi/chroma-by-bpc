<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classdetails';

    protected $primaryKey = 'cID';

    protected $fillable = [
        'cName',
        'cDescription',
        'cImage',
        'cVideo',
        'status',
        'classfee',
        'admission_amount',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Get the default image if none is set
     */
    public function getClassImage()
    {
        if ($this->cImage && file_exists(storage_path('app/public/classes/'.$this->cImage))) {
            return asset('storage/classes/'.$this->cImage);
        }

        return asset('front-assets/img/logo.png');
    }

    public function getClassVideo()
    {
        if ($this->cVideo && file_exists(storage_path('app/public/class-videos/'.$this->cVideo))) {
            return asset('storage/class-videos/'.$this->cVideo);
        }

        return asset('front-assets/img/home/pottery.mp4');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classes', 'class_id', 'student_id')
            ->withTimestamps();
    }
}
