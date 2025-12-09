<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_code',
        'supplier_name',
        'total_cost',
        'note',
        'created_by',
        'approved_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(InventoryReceiptItem::class, 'receipt_id');
    }
}
