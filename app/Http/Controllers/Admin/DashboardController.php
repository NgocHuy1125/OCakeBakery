<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('ordered_at', today())->count();

        $dateField = DB::raw('COALESCE(ordered_at, created_at)');

        $monthRevenue = Order::whereYear($dateField, now()->year)
            ->whereMonth($dateField, now()->month)
            ->sum('grand_total');

        $activeProducts = Product::where('status', 'active')->count();
        $newUsers = User::whereMonth('created_at', now()->month)->count();

        $monthlyRevenueData = Order::selectRaw('MONTH(COALESCE(ordered_at, created_at)) as month, SUM(grand_total) as total')
            ->whereYear($dateField, now()->year)
            ->groupBy(DB::raw('MONTH(COALESCE(ordered_at, created_at))'))
            ->pluck('total', 'month');

        $monthlyOrdersData = Order::selectRaw('MONTH(COALESCE(ordered_at, created_at)) as month, COUNT(*) as total')
            ->whereYear($dateField, now()->year)
            ->groupBy(DB::raw('MONTH(COALESCE(ordered_at, created_at))'))
            ->pluck('total', 'month');

        $monthlyRevenue = [];
        $monthlyOrders = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyRevenue[] = (float) ($monthlyRevenueData[$month] ?? 0);
            $monthlyOrders[] = (int) ($monthlyOrdersData[$month] ?? 0);
        }

        $recentOrders = Order::orderByDesc($dateField)->take(6)->get();

        return view('pages.admin.dashboard', compact(
            'todayOrders',
            'monthRevenue',
            'activeProducts',
            'newUsers',
            'monthlyRevenue',
            'monthlyOrders',
            'recentOrders'
        ));
    }
}
