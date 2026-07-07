<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Parameter $role diambil dari pemanggilan route, contoh: middleware('role:admin').
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Tolak jika belum login atau role tidak sesuai
        if (! $user || $user->role !== $role) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Akses ditolak. Hanya role '.$role.' yang diizinkan.',
            ], 403);
        }

        return $next($request);
    }
}
