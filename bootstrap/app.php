<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan alias 'role' agar bisa dipakai di route: middleware('role:admin')
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // API tidak punya halaman login web. Matikan redirect ke route('login')
        // agar user yang belum login menerima 401 JSON, bukan error redirect.
        $middleware->redirectGuestsTo(fn () => null);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Semua request ke /api/* selalu dianggap butuh JSON, walau lupa header
        // Accept: application/json. Jadi error tampil sebagai JSON, bukan halaman HTML.
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        // Jika belum login, balas 401 JSON (bukan redirect ke halaman login web).
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Belum login. Sertakan token pada header Authorization.',
                ], 401);
            }
        });
    })->create();
