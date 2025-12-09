<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InventoryReceipt;
use App\Models\InventoryReceiptItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{

    public function index()
    {
        $receipts = InventoryReceipt::with('creator')
            ->withSum('items as line_total_sum', 'line_total')
            ->orderByDesc('created_at')
            ->get();

        $products = Product::select('id', 'name')->orderBy('name')->get();

        return view('pages.admin.restock', compact('receipts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
            'unit_prices' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $receipt = InventoryReceipt::create([
                'receipt_code' => 'PN' . now()->format('ymdHis') . rand(10, 99),
                'supplier_name' => $request->input('supplier_name', 'Nội bộ'),
                'created_by' => Auth::id(),
                'total_cost' => 0,
                'variant_id' => null,
            ]);

            $total = 0;

            foreach ($request->products as $i => $productId) {
                $qty = (int) ($request->quantities[$i] ?? 0);
                $price = (float) ($request->unit_prices[$i] ?? 0);
                $lineTotal = $qty * $price;

                if ($qty <= 0 || $price <= 0) continue;

                InventoryReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'unit_cost' => $price,
                    'line_total' => $lineTotal,
                ]);

                Product::where('id', $productId)->increment('total_stock', $qty);
                $total += $lineTotal;
            }

            $receipt->update(['total_cost' => $total]);
            DB::commit();

            return redirect()
                ->route('admin.restock.index')
                ->with('success', 'Đã tạo phiếu nhập hàng thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Lỗi khi tạo phiếu nhập: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $receipt = InventoryReceipt::with(['creator', 'items.product'])->findOrFail($id);
        $displayTotal = $receipt->total_cost > 0
            ? $receipt->total_cost
            : $receipt->items->sum('line_total');

        return view('pages.admin.detail.restock', [
            'receipt' => $receipt,
            'displayTotal' => $displayTotal,
        ]);
    }
}
