<?php

namespace App\Services\Payment;

use App\Models\Order;
use Carbon\Carbon;

class SepayService
{
    public function isConfigured(): bool
    {
        return (bool) config('services.sepay.account_number');
    }

    public function qrTtlMinutes(): int
    {
        $ttl = (int) config('services.sepay.qr_ttl_minutes', 10);

        return $ttl > 0 ? $ttl : 10;
    }

    public function generateOrderPayment(Order $order): array
    {
        $amount = (int) round($order->grand_total ?? 0);
        $content = strtoupper(trim(config('services.sepay.content_prefix') . $order->order_code));
        $expiresAt = Carbon::now()->addMinutes($this->qrTtlMinutes());

        $qrUrl = $this->buildSepayQrUrl($content, $amount);
        $provider = $qrUrl ? 'sepay' : null;

        if (!$qrUrl) {
            $qrUrl = $this->buildVietQrUrl($content, $amount);
            $provider = $qrUrl ? 'vietqr' : 'none';
        }

        return [
            'provider' => $provider,
            'qr_url' => $qrUrl,
            'content' => $content,
            'amount' => $amount,
            'expires_at' => $expiresAt,
            'bank_code' => config('services.sepay.bank_code'),
            'account_number' => config('services.sepay.account_number'),
            'account_name' => config('services.sepay.account_name'),
        ];
    }

    public function assignPaymentIntent(Order $order): array
    {
        $payment = $this->generateOrderPayment($order);

        $order->forceFill([
            'payment_provider' => $payment['provider'],
        ])->save();

        return $payment;
    }

    protected function buildSepayQrUrl(string $content, int $amount): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $account = config('services.sepay.account_number');
        $bank = config('services.sepay.bank_code', 'MBBank');

        if (!$account) {
            return null;
        }

        $params = array_filter([
            'acc' => $account,
            'bank' => $bank,
            'amount' => $amount > 0 ? $amount : null,
            'des' => $content,
        ], static fn ($value) => $value !== null && $value !== '');

        $baseUrlConfig = config('services.sepay.base_url', 'https://qr.sepay.vn');

        if (is_string($baseUrlConfig) && str_contains($baseUrlConfig, 'api.sepay.vn')) {
            $baseUrlConfig = 'https://qr.sepay.vn';
        }

        $baseUrl = rtrim($baseUrlConfig, '/');

        return $baseUrl . '/img?' . http_build_query($params);
    }

    protected function buildVietQrUrl(string $content, int $amount): ?string
    {
        $bank = env('PAY_BANK_ID');
        $account = env('PAY_ACCOUNT_NO');

        if (!$bank || !$account) {
            return null;
        }

        $params = array_filter([
            'amount' => $amount > 0 ? $amount : null,
            'addInfo' => $content,
            'accountName' => env('PAY_ACCOUNT_NAME'),
        ], static fn ($value) => $value !== null && $value !== '');

        $query = http_build_query($params);

        return sprintf('https://img.vietqr.io/image/%s-%s-compact2.jpg?%s', $bank, $account, $query);
    }
}
