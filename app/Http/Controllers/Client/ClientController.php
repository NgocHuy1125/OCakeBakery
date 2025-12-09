<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function client_home()
    {
        $today = Carbon::now();

        $banhkem = Product::query()
            ->where('status', 'active')
            ->whereHas('primaryCategory', fn($q) => $q->where('slug', 'banh-kem'))
            ->paginate(12, ['*'], 'banhkem_page');

        $banhngot = Product::query()
            ->where('status', 'active')
            ->whereHas('primaryCategory', fn($q) => $q->where('slug', 'banh-ngot'))
            ->paginate(12, ['*'], 'banhngot_page');

        $mousse = Product::query()
            ->where('status', 'active')
            ->whereHas('primaryCategory', fn($q) => $q->where('slug', 'banh-mousse-trai-cay'))
            ->paginate(12, ['*'], 'mousse_page');

        $tiramisu = Product::query()
            ->where('status', 'active')
            ->whereHas('primaryCategory', fn($q) => $q->where('slug', 'banh-tiramisu'))
            ->paginate(12, ['*'], 'tiramisu_page');

        $teabreak = Product::query()
            ->where('status', 'active')
            ->whereHas('primaryCategory', fn($q) => $q->where('slug', 'tea-break'))
            ->paginate(12, ['*'], 'teabreak_page');

        $sinhnhat = Product::query()
            ->where('status', 'active')
            ->whereHas('primaryCategory', fn($q) => $q->where('slug', 'banh-sinh-nhat'))
            ->paginate(12, ['*'], 'sinhnhat_page');

        $categories = Category::query()
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->take(12)
            ->get();

        $homeProducts = Product::query()
            ->with(['images', 'primaryCategory'])
            ->where('status', 'active')
            ->where('show_on_homepage', true)
            ->orderByDesc('is_featured')
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->with(['images', 'primaryCategory'])
            ->where('status', 'active')
            ->latest('created_at')
            ->take(12)
            ->get();

        $activePromotions = Promotion::query()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('start_date')
            ->take(6)
            ->get();

        return view('pages.client.home', [
            'categories' => $categories,
            'homeProducts' => $homeProducts,
            'latestProducts' => $latestProducts,
            'activePromotions' => $activePromotions,
            'banhkem' => $banhkem,
            'banhngot' => $banhngot,
            'mousse' => $mousse,
            'tiramisu' => $tiramisu,
            'teabreak' => $teabreak,
            'sinhnhat' => $sinhnhat,
        ]);
    }

    public function client_contact()
    {
        return view('pages.client.contact');
    }

    public function client_about()
    {
        return view('pages.client.about');
    }

    public function client_promotions()
    {
        $promotions = Promotion::query()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderByDesc('created_at')
            ->get();

        $discountProducts = Product::query()
            ->whereColumn('sale_price', '<', 'listed_price')
            ->where('status', 'active')
            ->with(['primaryCategory', 'images'])
            ->take(12)
            ->get();

        return view('pages.client.promotions', compact('promotions', 'discountProducts'));
    }

    public function client_store()
    {
        return view('pages.client.store');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'email' => 'required|email|max:150',
            'message' => 'required|string|max:1000',
        ]);

        ContactMessage::create([
            'full_name'    => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone'] ?? null,
            'subject'      => null,
            'message'      => $data['message'],
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Cảm ơn bạn! Tin nhắn đã được gửi, chúng tôi sẽ phản hồi sớm nhất.');
    }
}
