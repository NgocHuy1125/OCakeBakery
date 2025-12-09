<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'status',
        'reference_code',
        'channel',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
