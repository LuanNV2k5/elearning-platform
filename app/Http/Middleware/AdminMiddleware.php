<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            abort(403, 'Bạn không có quyền truy cập Admin');
        }

        return $next($request);
    }
}
