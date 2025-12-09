<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SepayTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'gateway',
        'transactionDate',
        'accountNumber',
        'subAccount',
        'code',
        'content',
        'transferType',
        'description',
        'transferAmount',
        'referenceCode',
    ];
}
