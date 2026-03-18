<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'title',
        'description',
        'c_id',
        'image_path',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
        'c_id' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ImageCategory::class, 'c_id', 'id');
    }
}
