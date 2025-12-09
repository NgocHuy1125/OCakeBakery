<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_value',
        'minimum_order_value',
        'issued_quantity',
        'used_quantity',
        'max_usage_per_user',
        'members_only',
        'applies_to_product_id',
        'applies_to_category_id',
        'starts_at',
        'ends_at',
        'status',
        'created_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_value' => 'decimal:2',
        'minimum_order_value' => 'decimal:2',
        'members_only' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_users')
            ->withPivot('usage_count');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
