<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login')->name('login');
});

Route::prefix('product')->controller(ProductController::class)->group(function () {
    Route::get('', 'search');

    Route::middleware('auth:api')->group(function () {
        Route::post('', 'store');
        Route::delete('{product}', 'delete');
        Route::post('{product}/add-media', 'addMedia');
    });
});

Route::prefix('cart')
    ->controller(CartController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::post('{product}/add', 'addToCart');
        Route::post('{product}/remove', 'removeFromCart');
        Route::post('/submit', 'submitCart');
    });
