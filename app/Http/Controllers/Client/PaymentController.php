<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\SepayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct(private readonly SepayService $sepay)
    {
    }

    public function qr(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Bạn cần đăng nhập để thực hiện thanh toán.');
        }

        $code = strtoupper($request->query('code', ''));
        if ($code === '') {
            throw ValidationException::withMessages(['code' => 'Thiếu mã đơn hàng cần thanh toán.']);
        }

        $order = Order::where('order_code', $code)->firstOrFail();
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        if ($order->payment_method !== 'sepay') {
            abort(400, 'Đơn hàng không hỗ trợ thanh toán QR.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('profile.orders.show', $order->order_code)
                ->with('info', 'Đơn hàng đã được thanh toán trước đó.');
        }

        $payment = $this->sepay->assignPaymentIntent($order);
        if ($payment && isset($payment['expires_at']) && $payment['expires_at'] instanceof \Carbon\CarbonInterface) {
            $payment['provider_label'] = $order->refresh()->payment_provider_label;
        }

        return view('pages.client.paymentQR', [
            'order' => $order,
            'payment' => $payment,
        ]);
    }
}
