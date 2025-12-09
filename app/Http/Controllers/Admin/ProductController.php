<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 50);
        $perPage = $perPage > 0 ? min($perPage, 100) : 50;

        $products = Product::query()
            ->with(['primaryCategory', 'variants', 'images'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('keyword'), function ($q) use ($request) {
                $keyword = $request->string('keyword');
                $q->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('product_code', 'like', "%{$keyword}%");
                });
            })
            ->orderByDesc('show_on_homepage')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('pages.admin.products.index', compact('products', 'perPage'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('pages.admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'product_code' => ['required', 'string', 'max:30', 'unique:products,product_code'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:products,slug'],
            'primary_category_id' => ['required', 'exists:product_categories,id'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'listed_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'total_stock' => ['nullable', 'integer', 'min:0'],
            'unit_name' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:draft,active,out_of_stock,archived'],
            'show_on_homepage' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'variant_name' => ['nullable', 'string', 'max:120'],
            'sku' => ['nullable', 'string', 'max:60', 'unique:product_variants,sku'],
            'variant_price' => ['nullable', 'numeric', 'min:0'],
            'variant_sale_price' => ['nullable', 'numeric', 'min:0'],
            'variant_stock_quantity' => ['nullable', 'integer', 'min:0'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'product_image_urls' => ['nullable', 'string'],
        ]);

        $imageUrls = collect(preg_split('/\r\n|\r|\n/', $request->input('product_image_urls', '')))
            ->map(fn($url) => trim($url))
            ->filter();

        if (!$request->hasFile('image_files') && $imageUrls->isEmpty()) {
            throw ValidationException::withMessages([
                'image_files' => 'Vui lòng thêm ít nhất một ảnh cho sản phẩm.',
            ]);
        }

        $invalidUrls = $imageUrls->filter(fn($url) => !filter_var($url, FILTER_VALIDATE_URL));

        if ($invalidUrls->isNotEmpty()) {
            throw ValidationException::withMessages([
                'product_image_urls' => 'Một hoặc nhiều đường dẫn ảnh không hợp lệ.',
            ]);
        }

        DB::transaction(function () use ($request, $data, $imageUrls) {
            $product = Product::create([
                'primary_category_id' => $data['primary_category_id'],
                'product_code' => $data['product_code'],
                'name' => $data['name'],
                'slug' => $data['slug'] ?? Str::slug($data['name']),
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'listed_price' => $data['listed_price'],
                'sale_price' => $data['sale_price'] ?? null,
                'total_stock' => $data['total_stock'] ?? 0,
                'unit_name' => $data['unit_name'] ?? 'sản phẩm',
                'status' => $data['status'],
                'show_on_homepage' => $request->boolean('show_on_homepage'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => $data['variant_name'] ?? 'Mặc định',
                'sku' => $data['sku'] ?? null,
                'price' => $data['variant_price'] ?? $data['listed_price'],
                'sale_price' => $data['variant_sale_price'] ?? $data['sale_price'] ?? null,
                'stock_quantity' => $data['variant_stock_quantity'] ?? $data['total_stock'] ?? 0,
                'status' => $product->status === 'active' ? 'active' : 'inactive',
                'is_default' => true,
            ]);

            $this->replaceProductImages($product, $request, $imageUrls);
        });

        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm mới.');
    }

    public function edit(Product $product): View
    {
        $product->load(['variants', 'images']);
        $categories = Category::orderBy('name')->get();
        $variant = $product->variants->first();

        return view('pages.admin.products.edit', compact('product', 'categories', 'variant'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $variant = $product->variants->first();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'product_code' => ['required', 'string', 'max:30', 'unique:products,product_code,' . $product->id],
            'slug' => ['nullable', 'string', 'max:200', 'unique:products,slug,' . $product->id],
            'primary_category_id' => ['required', 'exists:product_categories,id'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'listed_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'total_stock' => ['nullable', 'integer', 'min:0'],
            'unit_name' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:draft,active,out_of_stock,archived'],
            'show_on_homepage' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'variant_name' => ['nullable', 'string', 'max:120'],
            'sku' => ['nullable', 'string', 'max:60', 'unique:product_variants,sku,' . ($variant->id ?? 'NULL')],
            'variant_price' => ['nullable', 'numeric', 'min:0'],
            'variant_sale_price' => ['nullable', 'numeric', 'min:0'],
            'variant_stock_quantity' => ['nullable', 'integer', 'min:0'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'product_image_urls' => ['nullable', 'string'],
        ]);

        $imageUrls = collect(preg_split('/\r\n|\r|\n/', $request->input('product_image_urls', '')))
            ->map(fn($url) => trim($url))
            ->filter();

        $invalidUrls = $imageUrls->filter(fn($url) => !filter_var($url, FILTER_VALIDATE_URL));

        if ($invalidUrls->isNotEmpty()) {
            throw ValidationException::withMessages([
                'product_image_urls' => 'Một hoặc nhiều đường dẫn ảnh không hợp lệ.',
            ]);
        }

        DB::transaction(function () use ($request, $product, $data, $variant, $imageUrls) {
            $product->update([
                'primary_category_id' => $data['primary_category_id'],
                'product_code' => $data['product_code'],
                'name' => $data['name'],
                'slug' => $data['slug'] ?? Str::slug($data['name']),
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'listed_price' => $data['listed_price'],
                'sale_price' => $data['sale_price'] ?? null,
                'total_stock' => $data['total_stock'] ?? ($variant->stock_quantity ?? $product->total_stock),
                'unit_name' => $data['unit_name'] ?? $product->unit_name,
                'status' => $data['status'],
                'show_on_homepage' => $request->boolean('show_on_homepage'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            if ($variant) {
                $variant->update([
                    'variant_name' => $data['variant_name'] ?? $variant->variant_name,
                    'sku' => $data['sku'] ?? $variant->sku,
                    'price' => $data['variant_price'] ?? $variant->price,
                    'sale_price' => $data['variant_sale_price'] ?? $variant->sale_price,
                    'stock_quantity' => $data['variant_stock_quantity'] ?? $variant->stock_quantity,
                    'status' => $product->status === 'active' ? 'active' : 'inactive',
                ]);
            }

            if ($request->hasFile('image_files') || $imageUrls->isNotEmpty()) {
                $this->replaceProductImages($product, $request, $imageUrls);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    protected function replaceProductImages(Product $product, Request $request, Collection $imageUrls): void
    {
        $storage = Storage::disk('public');
        $newImages = collect();

        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $file) {
                $path = $file->store('products', 'public');

                if (!$path || !$storage->exists($path)) {
                    throw ValidationException::withMessages([
                        'image_files' => 'Không thể lưu ảnh, vui lòng thử lại.',
                    ]);
                }

                $newImages->push([
                    'image_url' => 'storage/' . ltrim($path, '/'),
                    'alt_text' => $product->name,
                    'is_primary' => false,
                ]);
            }
        }

        foreach ($imageUrls as $url) {
            $newImages->push([
                'image_url' => $url,
                'alt_text' => $product->name,
                'is_primary' => false,
            ]);
        }

        if ($newImages->isEmpty()) {
            throw ValidationException::withMessages([
                'image_files' => 'Vui lòng thêm ít nhất một ảnh cho sản phẩm.',
            ]);
        }

        $hasPrimaryImage = false;
        $imageDisplayOrder = 0;

        $product->images()->delete();

        foreach ($newImages as $imageData) {
            $product->images()->create([
                ...$imageData,
                'is_primary' => !$hasPrimaryImage,
                'display_order' => $imageDisplayOrder++,
            ]);
            $hasPrimaryImage = true;
        }
    }
}
