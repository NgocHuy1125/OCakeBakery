<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'email', 'otp_code', 'purpose',
        'expires_at', 'is_used', 'delivered_via',
        'attempts', 'consumed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}
