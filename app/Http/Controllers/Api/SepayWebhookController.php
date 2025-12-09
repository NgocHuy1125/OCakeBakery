<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\SepayTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SepayWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('SePay webhook received', ['payload' => $payload]);

        $transferType = Str::lower((string) data_get($payload, 'transferType'));
        if ($transferType && $transferType !== 'in') {
            return response()->json(['message' => 'Ignored (transferType)']);
        }

        SepayTransaction::create([
            'gateway' => (string) data_get($payload, 'gateway', ''),
            'transactionDate' => (string) data_get($payload, 'transactionDate', now()->toDateTimeString()),
            'accountNumber' => (string) data_get($payload, 'accountNumber', ''),
            'subAccount' => data_get($payload, 'subAccount'),
            'code' => (string) data_get($payload, 'code'),
            'content' => (string) data_get($payload, 'content', ''),
            'transferType' => (string) data_get($payload, 'transferType', ''),
            'description' => (string) data_get($payload, 'description', ''),
            'transferAmount' => (int) data_get($payload, 'transferAmount', 0),
            'referenceCode' => (string) data_get($payload, 'referenceCode'),
        ]);

        $orderCode = $this->extractOrderCode($payload);
        if (!$orderCode) {
            Log::warning('SePay webhook: không tìm thấy mã đơn trong payload', ['payload' => $payload]);
            return response()->json(['message' => 'Order code not found'], 200);
        }

        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            Log::warning('SePay webhook: mã đơn không tồn tại', ['order_code' => $orderCode]);
            return response()->json(['message' => 'Order not matched'], 200);
        }

        if ($order->payment_method !== 'sepay') {
            Log::info('SePay webhook: bỏ qua do đơn không sử dụng SePay', ['order_id' => $order->id]);
            return response()->json(['message' => 'Order not using SePay'], 200);
        }

        $amount = (int) data_get($payload, 'transferAmount', 0);
        $expected = (int) round($order->grand_total ?? 0);
        if ($expected > 0 && $amount < $expected) {
            Log::warning('SePay webhook: số tiền không khớp', [
                'order_id' => $order->id,
                'expected' => $expected,
                'actual' => $amount,
            ]);
            return response()->json(['message' => 'Amount mismatch'], 200);
        }

        $reference = (string) data_get($payload, 'referenceCode')
            ?: (string) data_get($payload, 'transactionCode')
            ?: (string) data_get($payload, 'id');

        $content = (string) data_get($payload, 'content') ?: (string) data_get($payload, 'description');

        $shouldAutoDeliver = $order->source_channel === 'store';
        $nextFulfillmentStatus = $shouldAutoDeliver
            ? 'delivered'
            : ($order->fulfillment_status === 'pending' ? 'processing' : $order->fulfillment_status);

        $order->forceFill([
            'payment_status' => 'paid',
            'payment_provider' => 'sepay',
            'paid_at' => now(),
            'fulfillment_status' => $nextFulfillmentStatus,
        ])->save();

        $transaction = PaymentTransaction::where('order_id', $order->id)
            ->where('method', 'sepay')
            ->orderByDesc('id')
            ->first();

        if (!$transaction) {
            $transaction = new PaymentTransaction([
                'order_id' => $order->id,
                'method' => 'sepay',
            ]);
        }

        $transaction->fill([
            'amount' => $amount > 0 ? $amount : $expected,
            'status' => 'successful',
            'reference_code' => $reference ?: $transaction->reference_code,
            'channel' => (string) data_get($payload, 'gateway') ?: 'sepay',
        ])->save();

        if ($order->customer_email && !$order->email_sent_at) {
            try {
                Mail::to($order->customer_email)->send(new OrderPaidMail($order));
                $order->forceFill(['email_sent_at' => now()])->save();
            } catch (\Throwable $e) {
                Log::error('SePay webhook: gửi mail thất bại', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['message' => 'OK'], 200);
    }

    protected function extractOrderCode(array $payload): ?string
    {
        $code = (string) data_get($payload, 'code');
        if ($code) {
            return Str::upper(trim($code));
        }

        $candidates = [
            (string) data_get($payload, 'content'),
            (string) data_get($payload, 'description'),
            (string) data_get($payload, 'transferContent'),
        ];

        foreach ($candidates as $text) {
            if (!$text) {
                continue;
            }

            if (preg_match('/((?:KL|POS|HDPOS)[0-9A-Z]+)/i', $text, $matches)) {
                $code = Str::upper($matches[1]);
                if (Str::startsWith($code, 'HD') && strlen($code) > 2) {
                    $code = substr($code, 2);
                }

                return $code;
            }
        }

        return null;
    }
}
