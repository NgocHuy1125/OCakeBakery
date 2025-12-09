<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $userRole = $user->role ?? null;
        $allowed = collect($roles)
            ->flatMap(fn ($role) => explode(',', $role))
            ->map(fn ($role) => trim(strtolower($role)))
            ->filter()
            ->contains(fn ($role) => $role === strtolower((string) $userRole));

        if (!$allowed) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
