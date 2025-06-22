<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('refresh', 'refresh')->name('refresh');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::get('me', 'me')->name('me');
    });
});


Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::get('me', 'me')->name('me');
    });

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
});
