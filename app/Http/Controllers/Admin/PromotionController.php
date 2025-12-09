<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PromotionController extends Controller
{
    public function index()
    {
        Promotion::where('end_date', '<', now())->update(['status' => 'expired']);

        $promotions = Promotion::orderByDesc('id')->get();

        return view('pages.admin.promotions', compact('promotions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'slug'            => 'required|string|max:255|unique:promotions,slug',
            'discount_type'   => 'required|in:percentage,amount',
            'discount_value'  => 'required|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'banner'          => 'nullable|image|max:2048',
            'start_date'      => ['required', 'date', function ($attr, $value, $fail) {
                if (Carbon::parse($value)->lt(Carbon::today())) {
                    $fail('Ngày bắt đầu phải từ hôm nay hoặc sau hôm nay.');
                }
            }],
            'end_date'        => ['required', 'date', 'after:start_date', function ($attr, $value, $fail) {
                if (Carbon::parse($value)->lte(Carbon::today())) {
                    $fail('Ngày kết thúc phải sau hôm nay.');
                }
            }],
            'description'     => 'nullable|string',
        ], [
            'slug.unique'          => 'Slug khuyến mãi đã tồn tại.',
            'end_date.after'       => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $bannerUrl = null;

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('promotions', 'public');
            $bannerUrl = 'storage/' . $path;
        }

        Promotion::create([
            'name'               => $request->name,
            'slug'               => $request->slug,
            'description'        => $request->description,
            'banner_url'         => $bannerUrl,
            'discount_type'      => $request->discount_type,
            'discount_value'     => $request->discount_value,
            'max_discount_value' => $request->max_discount_value,
            'start_date'         => $request->start_date,
            'end_date'           => $request->end_date,
            'status'             => 'active',
            'created_by'         => Auth::id(),
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Đã tạo khuyến mãi thành công!');
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:promotions,slug,' . $promotion->id,
            'discount_type' => 'required|in:percentage,amount',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'banner' => 'nullable|image|max:2048',
            'start_date' => ['required', 'date', function ($attr, $value, $fail) {
                if (Carbon::parse($value)->lt(Carbon::today())) {
                    $fail('Ngày bắt đầu phải từ hôm nay hoặc sau hôm nay.');
                }
            }],
            'end_date' => ['required', 'date', 'after:start_date', function ($attr, $value, $fail) {
                if (Carbon::parse($value)->lte(Carbon::today())) {
                    $fail('Ngày kết thúc phải sau hôm nay.');
                }
            }],
            'description' => 'nullable|string',
        ], [
            'slug.unique' => 'Slug khuyến mãi đã tồn tại.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $bannerUrl = $promotion->banner_url;
        if ($request->hasFile('banner')) {
            if ($bannerUrl && str_starts_with($bannerUrl, 'storage/')) {
                $relativePath = str_replace('storage/', '', $bannerUrl);
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            }
            $bannerUrl = 'storage/' . $request->file('banner')->store('promotions', 'public');
        }

        $status = Carbon::parse($data['end_date'])->lt(now()) ? 'expired' : 'active';

        $promotion->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'banner_url' => $bannerUrl,
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'max_discount_value' => $data['max_discount_value'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $status,
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Đã cập nhật khuyến mãi.');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Đã xóa khuyến mãi thành công!');
    }
}
