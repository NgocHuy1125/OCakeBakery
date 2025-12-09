<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SepayWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $expected = config('services.sepay.webhook_token');
        if ($expected) {
            $incoming = $request->header('X-Webhook-Token') ?? $request->query('token');

            // Hỗ trợ header Authorization: "Apikey <token>" (SePay gửi dạng này)
            if (!$incoming) {
                $authHeader = $request->header('Authorization');
                if ($authHeader && stripos($authHeader, 'apikey ') === 0) {
                    $incoming = trim(substr($authHeader, 7));
                } elseif ($authHeader && stripos($authHeader, 'bearer ') === 0) {
                    $incoming = trim(substr($authHeader, 7));
                }
            }

            if (!$incoming || !hash_equals($expected, (string) $incoming)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        return $next($request);
    }
}
