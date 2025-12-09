<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use App\Models\PaymentTransaction;
use App\Models\Notification;
use App\Models\User;
use App\Services\Location\HcmLocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private readonly HcmLocationService $locationService)
    {
    }

    public function show(Request $request)
    {
        $cart = $this->resolveCart();
        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng đang trống, hãy thêm sản phẩm trước khi thanh toán.');
        }

        $cartItems = $cart->items()
            ->with([
                'product' => fn($q) => $q->select('id', 'name', 'product_code', 'slug', 'unit_name', 'listed_price', 'sale_price'),
                'variant' => fn($q) => $q->select('id', 'product_id', 'variant_name', 'sku', 'stock_quantity', 'price', 'sale_price'),
            ])
            ->get();

        $addresses = Auth::user()->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        $totals = $this->calculateCartTotals($cartItems);

        $districts = $addresses->isEmpty()
            ? $this->locationService->getDistricts()
            : [];

        return view('pages.client.checkout', compact('cartItems', 'addresses', 'totals', 'districts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->resolveCart();
        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng đang trống.');
        }

        $selectedAddress = CustomerAddress::where('user_id', Auth::id())
            ->findOrFail($request->input('address_id'));

        $paymentType = $request->input('checkout_payment', 'cod');
        \Log::info('checkout_payment = ' . $paymentType); // debug

        $paymentType = in_array($paymentType, ['cod', 'qr'], true) ? $paymentType : 'cod';
        $paymentMethod = $paymentType === 'qr' ? 'sepay' : 'cod';

        $customerNote = $request->string('customer_note')->trim()->value();

        try {
            $order = DB::transaction(function () use ($cart, $selectedAddress, $paymentMethod, $customerNote) {
                $cartItems = $cart->items()
                    ->lockForUpdate()
                    ->with(['product', 'variant'])
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \RuntimeException('Giỏ hàng trống.');
                }

                $totals = $this->calculateCartTotals($cartItems);

                $order = Order::create([
                    'order_code' => Str::upper('KL' . now()->format('ymdHis') . random_int(10, 99)),
                    'user_id' => Auth::id(),
                    'payment_method' => $paymentMethod,
                    'payment_provider' => $paymentMethod,
                    'customer_name' => $selectedAddress->receiver_name,
                    'customer_phone' => $selectedAddress->receiver_phone,
                    'customer_email' => $selectedAddress->receiver_email,
                    'address_line' => $selectedAddress->address_line,
                    'district_name' => $selectedAddress->district_name,
                    'ward_name' => $selectedAddress->ward_name,
                    'customer_note' => $customerNote,
                    'subtotal_amount' => $totals['subtotal'],
                    'discount_amount' => $totals['discount'],
                    'shipping_fee' => $totals['shipping'],
                    'grand_total' => $totals['grand_total'],
                    'ordered_at' => now(),
                    'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'processing',
                    'fulfillment_status' => 'pending',
                    'source_channel' => 'website',
                ]);

                foreach ($cartItems as $item) {
                    $variant = $item->variant;
                    $productModel = Product::lockForUpdate()->find($item->product_id);

                    if (! $productModel) {
                        throw new \RuntimeException('Sản phẩm đã không còn tồn tại.');
                    }

                    if ($productModel->total_stock < $item->quantity) {
                        throw new \RuntimeException("Sản phẩm {$productModel->name} không đủ tồn kho.");
                    }

                    if ($variant && $variant->stock_quantity < $item->quantity) {
                        throw new \RuntimeException("Sản phẩm {$variant->variant_name} không đủ tồn kho.");
                    }

                    if ($variant) {
                        $variant->decrement('stock_quantity', $item->quantity);
                    }
                    $productModel->decrement('total_stock', $item->quantity);

                    [$listPrice, $salePrice] = $this->getItemPrices($item);
                    $lineTotal = $item->quantity * $salePrice;
                    $productName = $item->product->name ?? $productModel->name ?? '';

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'product_name_snapshot' => $productName,
                        'variant_name_snapshot' => $variant->variant_name ?? '',
                        'quantity' => $item->quantity,
                        'list_price' => $listPrice,
                        'sale_price' => $salePrice,
                        'line_total' => $lineTotal,
                    ]);
                }

                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'amount' => $totals['grand_total'],
                    'method' => $paymentMethod,
                    'status' => 'pending',
                    'channel' => $paymentMethod,
                ]);

                $cart->items()->delete();
                $cart->update(['status' => 'ordered']);
                return $order;
            });
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }

        if ($paymentMethod === 'cod') {
            return redirect()->route('profile.orders')
                ->with('success', 'Đặt hàng thành công! Mã đơn: ' . $order->order_code);
        }

        return redirect()->route('payment.qr', ['code' => $order->order_code])
            ->with('info', 'Đơn hàng đã được tạo, vui lòng quét mã QR để thanh toán.');
    }


    protected function resolveCart(): ?ShoppingCart
    {
        $userId = Auth::id();
        $cart = ShoppingCart::where('user_id', $userId)
            ->where('status', 'active')
            ->with('items')
            ->first();

        if (!$cart) {
            $guestToken = session('guest_cart_token');
            if ($guestToken) {
                $guestCart = ShoppingCart::where('guest_token', $guestToken)
                    ->where('status', 'active')
                    ->with('items')
                    ->first();
                if ($guestCart) {
                    $guestCart->update([
                        'user_id' => $userId,
                        'guest_token' => null,
                    ]);
                    $cart = $guestCart;
                }
            }
        }

        return $cart;
    }

    protected function calculateCartTotals($items): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            [$listPrice, $salePrice] = $this->getItemPrices($item);
            $subtotal += $item->quantity * ($salePrice ?? $listPrice);
        }

        $shipping = $subtotal >= 100000 ? 0 : ($subtotal > 0 ? 30000 : 0);
        $discount = 0;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'grand_total' => max(0, $subtotal + $shipping - $discount),
        ];
    }

    protected function getItemPrices($item): array
    {
        $variant = $item->variant;
        $product = $item->product;

        $listPrice = $variant?->price ?? $product->listed_price ?? 0;
        $salePrice = $variant?->sale_price ?? $product->sale_price ?? null;
        $effectiveSale = $salePrice !== null ? (float) $salePrice : (float) $listPrice;

        return [(float) $listPrice, $effectiveSale];
    }
}
