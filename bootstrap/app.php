<?php

use App\Exceptions\InsufficientStockException;
use App\Exceptions\InsufficientWalletBalanceException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (InsufficientStockException $e) {
            return response()->json([
                'message' => 'Stock unavailable',
                'error' => $e->getMessage(),
            ], 422);
        });
        $exceptions->render(function (InsufficientWalletBalanceException $e) {
            return response()->json([
                'message' => 'Insufficient wallet balance',
                'error' => $e->getMessage(),
            ], 422);
        });
    })
    ->create();
