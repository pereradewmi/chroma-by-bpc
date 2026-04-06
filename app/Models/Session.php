<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessiondetails';
    protected $primaryKey = 'sID';

    protected $fillable = [
        'sName',
        'sDescription',
        'sImage',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Get the default image if none is set
     */
    public function getSessionImage()
    {
        if ($this->sImage && file_exists(storage_path('app/public/sessions/' . $this->sImage))) {
            return asset('storage/sessions/' . $this->sImage);
        }
        return asset('front-assets/img/logo.png');
    }
}