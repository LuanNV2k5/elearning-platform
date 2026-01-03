<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Chưa đăng nhập
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();

        // User chưa có role (data lỗi)
        if (!$user->role) {
            abort(403, 'User chưa được gán role');
        }

        // Kiểm tra role theo quan hệ
        if (!in_array($user->role->name, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
