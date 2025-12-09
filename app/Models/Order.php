<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'coupon_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'address_line',
        'district_code',
        'district_name',
        'ward_code',
        'ward_name',
        'customer_note',
        'subtotal_amount',
        'discount_amount',
        'shipping_fee',
        'grand_total',
        'payment_status',
        'fulfillment_status',
        'source_channel',
        'approved_by',
        'updated_by',
        'ordered_at',
        'payment_method',
        'payment_provider',
        'paid_at',
        'email_sent_at',
    ];

    protected $appends = [
        'payment_method_label',
        'payment_provider_label',
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'ordered_at' => 'datetime',
        'paid_at' => 'datetime',
        'email_sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id')->orderByDesc('created_at');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        $map = [
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'sepay' => 'Quét QR (SePay)',
            'cash' => 'Tiền mặt tại cửa hàng',
        ];

        $method = $this->payment_method;

        return $method
            ? ($map[$method] ?? ucfirst($method))
            : '---';
    }

    public function getPaymentProviderLabelAttribute(): string
    {
        $map = [
            'sepay' => 'SePay',
            'vietqr' => 'VietQR',
            'cod' => 'COD',
            'cash' => 'Tiền mặt',
        ];

        $provider = $this->payment_provider ?: $this->payment_method;

        return $provider
            ? ($map[$provider] ?? ucfirst($provider))
            : '---';
    }
}
