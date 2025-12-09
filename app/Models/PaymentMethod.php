<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_code',
        'name',
        'description',
        'method_type',
        'configuration',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_method_id');
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'payment_method_id');
    }
}
