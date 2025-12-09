<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'position',
        'display_order',
        'is_visible',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_visible' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}
