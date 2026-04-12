<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'eventdetails';

    protected $primaryKey = 'eID';

    protected $fillable = [
        'eName',
        'eDescription',
        'eImage',
        'status',
        'dateFrom',
        'dateTo',
    ];

    protected $casts = [
        'dateFrom' => 'datetime',
        'dateTo' => 'datetime',
        'status' => 'integer',
    ];

    /**
     * Get the default image if none is set
     */
    public function getEventImage()
    {
        if ($this->eImage && file_exists(storage_path('app/public/events/'.$this->eImage))) {
            return asset('storage/events/'.$this->eImage);
        }

        return asset('front-assets/img/logo.png');
    }
}
