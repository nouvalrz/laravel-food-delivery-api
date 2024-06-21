<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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