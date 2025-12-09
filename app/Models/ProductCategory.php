<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
        'image_url',
        'description',
        'parent_id',
        'is_visible',
        'display_order',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'primary_category_id');
    }
}
