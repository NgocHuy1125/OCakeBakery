<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['primaryCategory', 'variants' => function ($q) {
                $q->orderBy('is_default', 'desc')->orderBy('price');
            }, 'images' => function ($q) {
                $q->orderByDesc('is_primary')->orderBy('display_order');
            }])
            ->where('status', 'active');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->trim();
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('product_code', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where(function ($sub) use ($request) {
                $sub->whereHas('primaryCategory', function ($child) use ($request) {
                    $child->where('slug', $request->category);
                });
            });
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $products = $query
            ->orderByDesc('show_on_homepage')
            ->orderByDesc('is_featured')
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('pages.client.products', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::query()
            ->with([
                'primaryCategory',
                'variants' => function ($q) {
                    $q->orderBy('is_default', 'desc')->orderBy('price');
                },
                'images' => function ($q) {
                    $q->orderByDesc('is_primary')->orderBy('display_order');
                },
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->when($product->primaryCategory, function ($query) use ($product) {
                $query->whereHas('primaryCategory', function ($child) use ($product) {
                    $child->where('id', $product->primaryCategory->id);
                });
            })
            ->with(['primaryCategory', 'images'])
            ->orderByDesc('show_on_homepage')
            ->orderByDesc('is_featured')
            ->latest('updated_at')
            ->paginate(8, ['*'], 'related_page')
            ->withQueryString();

        return view('pages.client.detail.products', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', $request->input('keyword'));

        $products = Product::query()
            ->with(['primaryCategory', 'variants' => function ($q) {
                $q->orderBy('is_default', 'desc')->orderBy('price');
            }, 'images' => function ($q) {
                $q->orderByDesc('is_primary')->orderBy('display_order');
            }])
            ->where('status', 'active')
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('product_code', 'like', '%' . $keyword . '%');
            })
            ->paginate(12)
            ->withQueryString();

        return view('pages.client.search', compact('products', 'keyword'));
    }
}
