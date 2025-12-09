<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
use App\Services\Payment\SepayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OfflineOrderController extends Controller
{
    public function __construct(private readonly SepayService $sepay)
    {
    }

    public function index(): \Illuminate\View\View
    {
        $this->expireStaleQrOrders();

        $products = Product::with('images')
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->product_code,
                'price' => (float) $product->display_price,
                'sale_price' => (float) ($product->sale_price ?? 0),
                'unit_name' => $product->unit_name ?? 'sản phẩm',
                'stock' => (int) $product->total_stock,
                'image' => $product->primary_image_url,
            ]);

        $recentOrders = Order::where('source_channel', 'store')
            ->latest('created_at')
            ->paginate(6, [
                'id',
                'order_code',
                'customer_name',
                'customer_phone',
                'grand_total',
                'payment_method',
                'payment_status',
                'fulfillment_status',
                'ordered_at',
                'created_at',
            ], 'orders_page');

        return view('pages.client.offline-orders', [
            'products' => $products,
            'storeAddress' => config('app.store_address', '90 Độc Lập, phường Tân Phú, TP.HCM'),
            'recentOrders' => $recentOrders,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->expireStaleQrOrders();

        $data = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:150'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'payment_method' => ['required', 'in:cash,sepay'],
            'note' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $itemsPayload = collect($data['items'])
            ->map(fn ($item) => [
                'product_id' => (int) Arr::get($item, 'product_id'),
                'quantity' => (int) Arr::get($item, 'quantity', 1),
            ])
            ->filter(fn ($item) => $item['product_id'] > 0 && $item['quantity'] > 0)
            ->values();

        if ($itemsPayload->isEmpty()) {
            return response()->json([
                'ok' => false,
                'message' => 'Vui lòng chọn ít nhất một sản phẩm.',
            ], 422);
        }

        $payment = null;

        $order = DB::transaction(function () use ($data, $itemsPayload) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($itemsPayload as $payloadItem) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->findOrFail($payloadItem['product_id']);

                if ($product->status !== 'active') {
                    abort(422, "Sản phẩm {$product->name} hiện không còn bán.");
                }

                if ($product->total_stock < $payloadItem['quantity']) {
                    abort(422, "Sản phẩm {$product->name} không đủ tồn kho.");
                }

                $product->decrement('total_stock', $payloadItem['quantity']);

                $unitPrice = $product->sale_price ?? $product->display_price;
                $lineTotal = $unitPrice * $payloadItem['quantity'];
                $subtotal += $lineTotal;

                $variantName = optional($product->defaultVariant())->variant_name ?? 'Mặc định';

                $orderItems[] = [
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'product_name_snapshot' => $product->name,
                    'variant_name_snapshot' => $variantName,
                    'quantity' => $payloadItem['quantity'],
                    'list_price' => $product->listed_price ?? $unitPrice,
                    'sale_price' => $product->sale_price ?? $product->listed_price ?? $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'order_code' => Str::upper('POS' . now()->format('ymdHis') . random_int(10, 99)),
                'customer_name' => $data['customer_name'] ?: 'Khách lẻ',
                'customer_phone' => $data['customer_phone'] ?: '---',
                'customer_email' => $data['customer_email'],
                'address_line' => 'Nhận tại cửa hàng Kim Loan',
                'district_name' => 'Tân Phú',
                'ward_name' => 'Phường 9',
                'subtotal_amount' => $subtotal,
                'discount_amount' => 0,
                'shipping_fee' => 0,
                'grand_total' => $subtotal,
                'payment_method' => $data['payment_method'],
                'payment_provider' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'cash' ? 'paid' : 'processing',
                'paid_at' => $data['payment_method'] === 'cash' ? now() : null,
                'source_channel' => 'store',
                'customer_note' => $data['note'] ?? null,
                'fulfillment_status' => $data['payment_method'] === 'cash' ? 'delivered' : 'pending',
                'ordered_at' => now(),
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            PaymentTransaction::create([
                'order_id' => $order->id,
                'amount' => $subtotal,
                'method' => $data['payment_method'],
                'status' => $data['payment_method'] === 'cash' ? 'successful' : 'pending',
                'channel' => $data['payment_method'],
            ]);

            return $order;
        });

        $order = $order->fresh();

        if ($data['payment_method'] === 'sepay') {
            $payment = $this->sepay->assignPaymentIntent($order);
            $order->refresh();
        } elseif (!empty($data['customer_email'])) {
            try {
                $order->loadMissing('items');
                Mail::to($data['customer_email'])->send(new OrderPaidMail($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $order->load('items');

        if ($payment && isset($payment['expires_at']) && $payment['expires_at'] instanceof \Carbon\CarbonInterface) {
            $payment['expires_at'] = $payment['expires_at']->toIso8601String();
        }

        if ($payment) {
            $payment['provider_label'] = $order->payment_provider_label;
        }

        $adminIds = User::where('role', 'admin')->pluck('id');
        $creatorId = Auth::id();
        foreach ($adminIds as $adminId) {
            if ($creatorId && (int) $creatorId === (int) $adminId) {
                continue;
            }
            Notification::create([
                'user_id' => $adminId,
                'title' => 'Đơn hàng POS mới #' . $order->order_code,
                'message' => 'Nhân viên vừa tạo đơn tại quầy trị giá ' . number_format($order->grand_total, 0, ',', '.') . ' ₫.',
                'link' => route('admin.orders.show', $order->id),
            ]);
        }

        return response()->json([
            'ok' => true,
            'order' => [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'order_url' => route('admin.orders.show', $order->id),
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'customer_email' => $order->customer_email,
                'payment_method' => $order->payment_method,
                'payment_method_label' => $order->payment_method_label,
                'payment_status' => $order->payment_status,
                'fulfillment_status' => $order->fulfillment_status,
                'grand_total' => $order->grand_total,
                'created_at' => $order->created_at?->format('d/m/Y H:i'),
                'items' => $order->items->map(fn (OrderItem $item) => [
                    'name' => $item->product_name_snapshot,
                    'unit' => 'sản phẩm',
                    'quantity' => $item->quantity,
                    'price' => $item->sale_price ?? $item->list_price,
                    'line_total' => $item->line_total,
                ]),
            ],
            'payment' => $payment,
            'message' => $data['payment_method'] === 'cash'
                ? 'Đã tạo đơn hàng và ghi nhận thanh toán tiền mặt.'
                : 'Đã tạo đơn hàng. Vui lòng quét mã QR để thanh toán.',
        ]);
    }

    protected function expireStaleQrOrders(): void
    {
        $expiredOrders = Order::with('items')
            ->where('source_channel', 'store')
            ->where('payment_method', 'sepay')
            ->where('payment_status', 'processing')
            ->where('fulfillment_status', 'pending')
            ->where('ordered_at', '<=', now()->subMinutes(10))
            ->get();

        foreach ($expiredOrders as $order) {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('total_stock', $item->quantity);
            }

            PaymentTransaction::where('order_id', $order->id)
                ->where('method', 'sepay')
                ->update(['status' => 'failed']);

            $order->update([
                'payment_status' => 'failed',
                'fulfillment_status' => 'cancelled',
            ]);
        }
    }
}
