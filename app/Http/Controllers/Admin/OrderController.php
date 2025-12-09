<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::select(
            'id',
            'order_code',
            'customer_name',
            'customer_phone',
            'grand_total',
            'fulfillment_status',
            'payment_status'
        )
        ->orderByDesc('ordered_at')
        ->get()
        ->groupBy('fulfillment_status');

        $statuses = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
        ];

        return view('pages.admin.orders', compact('orders', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $status = $request->input('fulfillment_status');
        $mappedStatus = match ($status) {
            'confirmed', 'preparing' => 'processing',
            'shipping' => 'shipped',
            'completed' => 'delivered',
            'returned' => 'cancelled',
            default => $status,
        };

        $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($mappedStatus, $allowed, true)) {
            return back()->withErrors(['fulfillment_status' => 'Trạng thái không hợp lệ.']);
        }

        $order->update(['fulfillment_status' => $mappedStatus]);

        if ($order->user_id) {
            $statusMessages = [
                'pending' => 'Đơn hàng đang chờ xác nhận.',
                'confirmed' => 'Đơn hàng đã được xác nhận.',
                'preparing' => 'Đơn hàng đang được chuẩn bị.',
                'shipping' => 'Đơn hàng đang được giao.',
                'delivered' => 'Đơn hàng đã giao thành công.',
                'completed' => 'Đơn hàng đã hoàn tất.',
                'cancelled' => 'Đơn hàng đã bị hủy.',
                'returned' => 'Đơn hàng đã được trả lại.',
            ];

            Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Đơn hàng #' . $order->order_code . ' cập nhật',
                'message' => $statusMessages[$order->fulfillment_status] ?? 'Đơn hàng của bạn đã được cập nhật.',
                'link' => route('profile.orders.show', $order->order_code),
            ]);
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }


    public function show(Order $order)
    {
        $order->load(['items.product']);
        return view('pages.admin.detail.orders', compact('order'));
    }

    public function quickProcess(Order $order)
    {
        if ($order->fulfillment_status === 'pending') {
            $order->update(['fulfillment_status' => 'processing']);
        }

        return redirect()->back()->with('success', 'Đã chuyển đơn sang "Đang xử lý".');
    }

}
