<?php

namespace App\Models;

use App\Models\CustomerAddress;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'customer_code',
        'full_name',
        'email',
        'phone_number',
        'password',
        'role',
        'avatar_url',
        'gender',
        'date_of_birth',
        'status',
        'email_verified',
        'phone_verified',
        'last_login_at',
        'address',
        'internal_note',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'last_login_at' => 'datetime',
        'date_of_birth' => 'date',
        'password' => 'hashed',
    ];

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
