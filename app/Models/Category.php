<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'image_url',
        'display_order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'primary_category_id');
    }

    public function getImageFullUrlAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        $path = $this->image_url;

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, 'storage/')) {
            $relative = ltrim(substr($path, strlen('storage/')), '/');
            if (Storage::disk('public')->exists($relative)) {
                return asset($path);
            }
            return null;
        }

        $relative = ltrim($path, '/');
        if (Storage::disk('public')->exists($relative)) {
            return asset('storage/' . $relative);
        }

        return asset('storage/' . $relative);
    }
}
