<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $cart = $this->resolveCart(false);

        $items = $cart?->items()
            ->with(['product' => fn($q) => $q->select('id', 'name', 'slug', 'product_code', 'unit_name', 'listed_price', 'sale_price')])
            ->orderByDesc('created_at')
            ->get() ?? collect();

        $totals = $this->calculateCartTotals($items);

        return view('pages.client.cart', compact('items', 'totals'));
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
            'note'       => ['nullable', 'string', 'max:255'],
        ]);

        $quantity = $data['quantity'] ?? 1;

        try {
            [$cart, $message] = DB::transaction(function () use ($data, $quantity) {
                $product = Product::lockForUpdate()->findOrFail($data['product_id']);

                if ($product->status !== 'active' || $product->total_stock <= 0) {
                    abort(422, 'Sản phẩm đã hết hàng hoặc đang tạm ngưng bán.');
                }

                if ($product->total_stock < $quantity) {
                    abort(422, 'Số lượng trong kho không đủ để thêm vào giỏ hàng.');
                }

                $cart = $this->resolveCart(true);

                $item = $cart->items()
                    ->lockForUpdate()
                    ->where('product_id', $product->id)
                    ->first();

                if ($item) {
                    $item->update([
                        'quantity' => $item->quantity + $quantity,
                        'note'     => $data['note'] ?? $item->note,
                    ]);
                    $message = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng.';
                } else {
                    $cart->items()->create([
                        'product_id' => $product->id,
                        'variant_id' => null,
                        'quantity'   => $quantity,
                        'note'       => $data['note'] ?? null,
                    ]);
                    $message = 'Đã thêm sản phẩm vào giỏ hàng.';
                }

                return [$cart, $message];
            }, 3);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'ok'    => false,
                'toast' => [
                    'type' => 'error',
                    'title' => 'Thêm thất bại',
                    'message' => $e->getMessage() ?: 'Không thể thêm sản phẩm vào giỏ hàng.',
                ]
            ], 422);
        }

        return response()->json([
            'ok'         => true,
            'toast'      => [
                'type' => 'success',
                'title' => 'Thành công',
                'message' => $message,
            ],
            'cart_count' => $cart->items()->sum('quantity'),
        ]);
    }

    public function updateQuantity(Request $request, CartItem $item): JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->resolveCart(false);
        abort_unless($cart && $item->cart_id === $cart->id, 403);

        if ($item->product->total_stock < $data['quantity']) {
            return response()->json([
                'ok' => false,
                'toast' => [
                    'type' => 'warning',
                    'title' => 'Vượt tồn kho',
                    'message' => 'Số lượng vượt quá tồn kho hiện tại.',
                ]
            ], 422);
        }

        $item->update(['quantity' => $data['quantity']]);
        $item->loadMissing('product');
        $itemTotal = $data['quantity'] * $this->getItemUnitPrice($item);
        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();
        $totals = $this->calculateCartTotals($items);

        return response()->json([
            'ok' => true,
            'toast' => [
                'type' => 'success',
                'title' => 'Cập nhật giỏ hàng',
                'message' => 'Đã cập nhật số lượng sản phẩm.',
            ],
            'item_total' => $itemTotal,
            'totals' => $totals,
        ]);
    }

    public function destroy(CartItem $item): JsonResponse
    {
        $cart = $this->resolveCart(false);
        abort_unless($cart && $item->cart_id === $cart->id, 403);

        $item->delete();
        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();
        $totals = $this->calculateCartTotals($items);

        return response()->json([
            'ok' => true,
            'toast' => [
                'type' => 'info',
                'title' => 'Đã xóa sản phẩm',
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.',
            ],
            'totals' => $totals,
        ]);
    }

    public function clear(): JsonResponse
    {
        $cart = $this->resolveCart(false);

        if (!$cart) {
            return response()->json([
                'ok' => false,
                'toast' => [
                    'type' => 'error',
                    'title' => 'Lỗi giỏ hàng',
                    'message' => 'Không tìm thấy giỏ hàng.',
                ]
            ], 404);
        }

        $cart->items()->delete();

        return response()->json([
            'ok' => true,
            'toast' => [
                'type' => 'info',
                'title' => 'Đã làm trống giỏ hàng',
                'message' => 'Toàn bộ sản phẩm đã được xóa.',
            ],
            'totals' => $this->calculateCartTotals(collect()),
        ]);
    }

    protected function resolveCart(bool $createIfMissing = true): ?ShoppingCart
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $cart = ShoppingCart::firstOrCreate(
                ['user_id' => $userId, 'status' => 'active'],
                ['status' => 'active']
            );

            return $cart->load('items.product');
        }

        $token = session('guest_cart_token');
        if (!$token) {
            if (!$createIfMissing) return null;
            $token = Str::uuid()->toString();
            session(['guest_cart_token' => $token]);
        }

        $cart = ShoppingCart::firstOrCreate(['guest_token' => $token, 'status' => 'active']);
        return $cart->load('items.product');
    }

    protected function calculateCartTotals(Collection $items): array
    {
        $subtotal = $items->sum(fn($i) => $i->quantity * $this->getItemUnitPrice($i));
        $shipping = $subtotal >= 100000 ? 0 : ($subtotal > 0 ? 30000 : 0);

        return [
            'subtotal'    => $subtotal,
            'shipping'    => $shipping,
            'discount'    => 0,
            'grand_total' => max(0, $subtotal + $shipping),
        ];
    }

    protected function getItemUnitPrice(CartItem $item): float
    {
        $product = $item->product;
        if (!$product) {
            return 0;
        }

        $basePrice = $product->listed_price ?? 0;
        $salePrice = $product->sale_price ?? null;

        return (float) ($salePrice ?? $basePrice);
    }
}
