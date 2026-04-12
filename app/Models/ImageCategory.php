<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImageCategory extends Model
{
    use HasFactory;

    protected $table = 'imagecategories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'status',
        'background_image',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'c_id', 'id');
    }
}
