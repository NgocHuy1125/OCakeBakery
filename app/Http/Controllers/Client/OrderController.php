<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Hi?n th? danh s?ch ??n h?ng c?a ng??i d?ng hi?n t?i k?m b? l?c.
     */
    public function myOrders(Request $request): View
    {
        $filters = [
            'status' => $request->string('status')->trim()->value(),
            'search' => $request->string('search')->trim()->value(),
        ];

        $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if ($filters['status'] && ! in_array($filters['status'], $allowedStatuses, true)) {
            $filters['status'] = null;
        }

        $ordersQuery = Order::query()
            ->with([
                'items.product',
                'items.variant',
                'statusHistory' => fn ($query) => $query->latest('created_at'),
                'transactions' => fn ($query) => $query->latest('created_at'),
            ])
            ->where('user_id', Auth::id());

        if ($filters['status']) {
            $ordersQuery->where('fulfillment_status', $filters['status']);
        }

        if ($filters['search']) {
            $keyword = '%' . $filters['search'] . '%';
            $ordersQuery->where(function ($query) use ($keyword) {
                $query->where('order_code', 'like', $keyword)
                    ->orWhere('customer_name', 'like', $keyword)
                    ->orWhere('customer_phone', 'like', $keyword)
                    ->orWhere('customer_note', 'like', $keyword)
                    ->orWhereHas('items', function ($itemQuery) use ($keyword) {
                        $itemQuery->where('product_name_snapshot', 'like', $keyword)
                            ->orWhere('variant_name_snapshot', 'like', $keyword);
                    });
            });
        }

        $orders = $ordersQuery
            ->latest('ordered_at')
            ->paginate(8)
            ->withQueryString();

        $statusCounters = Order::query()
            ->select('fulfillment_status', DB::raw('COUNT(*) as total'))
            ->where('user_id', Auth::id())
            ->groupBy('fulfillment_status')
            ->pluck('total', 'fulfillment_status')
            ->toArray();

        $statusCounters = array_merge([
            'pending' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'cancelled' => 0,
        ], $statusCounters);
        $statusCounters['all'] = array_sum($statusCounters);

        return view('pages.client.profile.orders', [
            'orders' => $orders,
            'filters' => $filters,
            'statusCounters' => $statusCounters,
        ]);
    }

    /**
     * Hiển thị chi tiết 1 đơn hàng cụ thể.
     */
    public function show(string $code): View
    {
        $order = Order::query()
            ->with([
                // Các sản phẩm & biến thể trong đơn
                'items.product',
                'items.variant',

                // Lịch sử thay đổi trạng thái
                'statusHistory' => fn($q) => $q->orderByDesc('created_at'),

                // Lịch sử thanh toán
                'transactions' => fn($q) => $q->latest('created_at'),
            ])
            ->where('order_code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pages.client.detail.orders', compact('order'));
    }
}
