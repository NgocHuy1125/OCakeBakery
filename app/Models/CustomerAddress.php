<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'district_code',
        'district_name',
        'ward_code',
        'ward_name',
        'address_line',
        'note',
        'is_default',
        ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
