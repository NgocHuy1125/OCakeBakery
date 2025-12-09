<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
        'website_url',
        'description',
        'display_order',
        'is_visible',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_visible' => 'boolean',
    ];
}
