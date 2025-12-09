<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id', 'product_id', 'quantity', 'unit_cost', 'line_total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function receipt()
    {
        return $this->belongsTo(InventoryReceipt::class, 'receipt_id');
    }
}
