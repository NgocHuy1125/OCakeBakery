<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\ShoppingCart;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \SePay\SePay\Events\SePayWebhookEvent::class,
            \App\Listeners\SePayWebhookListener::class,
        );
        View::composer('*', function ($view) {
            $notifications = collect();
            $cartCount = 0;

            if (Auth::check()) {
                $userId = Auth::id();
                $notifications = Notification::where('user_id', $userId)
                    ->latest()
                    ->take(10)
                    ->get();

                $cart = ShoppingCart::with('items')
                    ->where('user_id', $userId)
                    ->where('status', 'active')
                    ->first();

                if ($cart) {
                    $cartCount = (int) $cart->items->sum('quantity');
                }
            } else {
                $token = session('guest_cart_token');
                if ($token) {
                    $cart = ShoppingCart::with('items')
                        ->where('guest_token', $token)
                        ->where('status', 'active')
                        ->first();
                    if ($cart) {
                        $cartCount = (int) $cart->items->sum('quantity');
                    }
                }
            }

            $view->with([
                'notifications' => $notifications,
                'cartCount' => $cartCount,
            ]);
        });
    }
}
