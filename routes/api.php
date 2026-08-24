<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);
Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/become-vendor', [VendorController::class, 'create']);

    Route::apiResource('products', ProductController::class)
        ->only(['store', 'update', 'destroy']);

    Route::apiResource('orders',OrderController::class);
});


