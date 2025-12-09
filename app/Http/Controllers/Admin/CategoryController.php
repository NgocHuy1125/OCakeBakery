<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);

        return view('pages.admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:product_categories,slug'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'is_visible' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return back()->with('success', 'Đã tạo danh mục mới.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:product_categories,slug,' . $category->id],
            'short_description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'is_visible' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete($category->image_url);
            }
            $data['image_url'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return back()->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->slug === 'chua-xac-dinh') {
            return back()->with('error', 'Không thể xóa danh mục mặc định.');
        }

        $uncategorized = Category::firstOrCreate(
            ['slug' => 'chua-xac-dinh'],
            [
                'name' => 'Chưa xác định',
                'short_description' => 'Danh mục mặc định khi sản phẩm chưa được phân loại.',
                'is_visible' => false,
                'display_order' => 999,
            ]
        );

        \App\Models\Product::where('primary_category_id', $category->id)
            ->update(['primary_category_id' => $uncategorized->id]);

        if (Schema::hasTable('product_category_links')) {
            DB::table('product_category_links')
                ->where('category_id', $category->id)
                ->delete();
        }

        if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
            Storage::disk('public')->delete($category->image_url);
        }

        $category->delete();

        return back()->with('success', 'Đã xóa danh mục và chuyển sản phẩm sang "Chưa xác định".');
    }

}

