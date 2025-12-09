<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'subject',
        'message',
        'status',
        'handled_by',
    ];

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
