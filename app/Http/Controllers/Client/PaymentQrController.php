<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\SepayService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentQrController extends Controller
{
    public function __construct(private readonly SepayService $sepay)
    {
    }

    public function show(string $code)
    {
        $order = Order::where('order_code', strtoupper($code))->firstOrFail();

        // Kiểm tra quyền sở hữu đơn hàng
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Kiểm tra phương thức thanh toán
        if ($order->payment_method !== 'sepay') {
            return redirect()
                ->route('profile.orders.show', $order->order_code)
                ->with('warning', 'Đơn hàng không hỗ trợ thanh toán QR.');
        }

        // Kiểm tra trạng thái thanh toán
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('profile.orders.show', $order->order_code)
                ->with('info', 'Đơn hàng đã được thanh toán trước đó.');
        }

        $payment = $this->sepay->assignPaymentIntent($order);

        $expiresAt = $payment['expires_at'] ?? Carbon::now()->addMinutes($this->sepay->qrTtlMinutes());
        if (!$expiresAt instanceof Carbon) {
            $expiresAt = Carbon::parse($expiresAt);
        }

        $payment = [
            'provider' => $payment['provider'] ?? $order->payment_provider ?? 'sepay',
            'provider_label' => $order->payment_provider_label,
            'qr_url' => $payment['qr_url'] ?? null,
            'amount' => (int) round($order->grand_total ?? 0),
            'content' => $payment['content'] ?? ('TTDH' . $order->order_code),
            'expires_at' => $expiresAt,
        ];

        return view('pages.client.paymentQR', compact('order', 'payment'));
    }

    public function status(string $code): JsonResponse
    {
        $order = Order::where('order_code', strtoupper($code))->firstOrFail();

        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        if ($order->payment_method !== 'sepay') {
            return response()->json(['ok' => false, 'message' => 'Order not using SePay'], 400);
        }

        return response()->json([
            'ok' => true,
            'payment_status' => $order->payment_status,
            'redirect' => route('profile.orders.show', $order->order_code),
        ]);
    }
}
