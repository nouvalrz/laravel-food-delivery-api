<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Buyer Register
Route::post('/buyer/register', [App\Http\Controllers\Api\AuthController::class, 'buyerRegister']);

// Merchant Register
Route::post('/merchant/register', [App\Http\Controllers\Api\AuthController::class, 'merchantRegister']);

// Merchant Driver
Route::post('/driver/register', [App\Http\Controllers\Api\AuthController::class, 'driverRegister']);

// Login
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// logout
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');


// Role MERCHANT
Route::middleware(['auth:sanctum', 'role:' . User::ROLE_MERCHANT])->prefix('merchant')->group(function () {
    // Products
    Route::post('/products', [App\Http\Controllers\Api\Merchant\MerchantProductController::class, 'store']);
    Route::get('/products', [App\Http\Controllers\Api\Merchant\MerchantProductController::class, 'index']);
    Route::put('/products/{id}', [App\Http\Controllers\Api\Merchant\MerchantProductController::class, 'update']);
    Route::delete('/products/{id}', [App\Http\Controllers\Api\Merchant\MerchantProductController::class, 'destroy']);

    // Order
    Route::get('/orders', [App\Http\Controllers\Api\Merchant\MerchantOrderController::class, 'index']);
    Route::post('/orders/status', [App\Http\Controllers\Api\Merchant\MerchantOrderController::class, 'getOrderByStatus']);
});

// Role BUYER
Route::middleware(['auth:sanctum', 'role:' . User::ROLE_BUYER])->prefix('buyer')->group(function () {
    // create order
    Route::post('/orders', [App\Http\Controllers\Api\Buyer\BuyerOrderController::class, 'create']);
    Route::get('/orders', [App\Http\Controllers\Api\Buyer\BuyerOrderController::class, 'index']);
    Route::patch('/orders/{id}/cancel', [App\Http\Controllers\Api\Buyer\BuyerOrderController::class, 'cancelOrder']);
});

// Role DRIVER
Route::middleware(['auth:sanctum', 'role:' . User::ROLE_DRIVER])->group(function () {
    //
});

Route::prefix('public')->group(function () {
    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/merchant/{id}/products', [App\Http\Controllers\Api\ProductController::class, 'getProductsByMerchant']);
});

