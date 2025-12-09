<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
        'group_key',
        'updated_by',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
