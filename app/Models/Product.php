<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'primary_category_id',
        'product_code',
        'name',
        'slug',
        'short_description',
        'description',
        'ingredients',
        'storage_instruction',
        'shelf_life',
        'listed_price',
        'sale_price',
        'total_stock',
        'unit_name',
        'weight_in_gram',
        'max_quantity_per_order',
        'status',
        'show_on_homepage',
        'is_featured',
        'sort_order',
        'view_count',
    ];

    protected $casts = [
        'listed_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'total_stock' => 'integer',
        'weight_in_gram' => 'decimal:2',
        'show_on_homepage' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function primaryCategory()
    {
        return $this->belongsTo(Category::class, 'primary_category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function defaultVariant()
    {
        $variants = $this->relationLoaded('variants')
            ? $this->variants
            : $this->variants()->orderByDesc('is_default')->orderBy('price')->get();

        return $variants->firstWhere('is_default', true) ?? $variants->first();
    }

    public function primaryImage()
    {
        $images = $this->relationLoaded('images')
            ? $this->images
            : $this->images()->orderByDesc('is_primary')->orderBy('display_order')->get();

        return $images->firstWhere('is_primary', true) ?? $images->first();
    }

    public function getDisplayPriceAttribute(): float
    {
        $variant = $this->defaultVariant();

        return (float) ($variant?->sale_price ?? $variant?->price ?? $this->sale_price ?? $this->listed_price ?? 0);
    }

    public function getOriginalPriceAttribute(): ?float
    {
        $variant = $this->defaultVariant();

        if ($variant && $variant->sale_price) {
            return (float) ($variant->price ?? $variant->sale_price);
        }

        if ($this->sale_price && $this->listed_price && $this->listed_price > $this->sale_price) {
            return (float) $this->listed_price;
        }

        return null;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $image = $this->primaryImage();

        if (!$image || !$image->image_url) {
            return null;
        }

        return static::resolveImageUrl($image->image_url);
    }

    public static function resolveImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        if (Str::startsWith($normalized, 'storage/')) {
            $relative = ltrim(substr($normalized, strlen('storage/')), '/');
            if (Storage::disk('public')->exists($relative)) {
                return asset('storage/' . $relative);
            }

            $normalized = $relative;
        }

        if (Storage::disk('public')->exists($normalized)) {
            return asset('storage/' . $normalized);
        }

        if (file_exists(public_path($normalized))) {
            return asset($normalized);
        }

        return null;
    }
}
