<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'method_code' => 'COD',
                'name' => 'Thanh toán khi nhận hàng (COD)',
                'description' => 'Khách thanh toán tiền mặt khi nhận bánh.',
                'method_type' => 'offline',
                'configuration' => ['note' => 'Chuẩn bị tiền mặt khi nhận hàng.'],
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'method_code' => 'QR',
                'name' => 'Thanh toán qua QR đa năng',
                'description' => 'Quét mã QR hỗ trợ nhiều ngân hàng/ví điện tử.',
                'method_type' => 'online',
                'configuration' => [
                    'provider' => 'VietQR',
                    'bank' => env('PAY_BANK_ID', 'vcb'),
                    'account_no' => env('PAY_ACCOUNT_NO'),
                    'account_name' => env('PAY_ACCOUNT_NAME'),
                ],
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        PaymentMethod::withoutTimestamps(function () use ($methods) {
            foreach ($methods as $method) {
                PaymentMethod::updateOrCreate(
                    ['method_code' => $method['method_code']],
                    $method
                );
            }
        });
    }
}
