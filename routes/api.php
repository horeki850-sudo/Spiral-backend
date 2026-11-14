<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
// PRODUCT ROUTES - AUTHENTICATED USERS
Route::middleware('auth:sanctum')->group(function () {

    // Both normal users and admins can view products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Only admins can manage products
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);

        Route::match(['put', 'patch'], '/products/{product}', [
            ProductController::class, 'update'
        ]);

        Route::delete('/products/{product}', [
            ProductController::class, 'destroy'
        ]);
    });

});
Route::apiResource('carts', CartController::class);
Route::apiResource('cart-items', CartItemController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('wishlists', WishlistController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
Route::apiResource('users', UserController::class)
    ->except(['update', 'destroy']);
Route::match(['put', 'patch'], '/users/{user}', [
    UserController::class, 'update'
])->middleware('auth:sanctum');
Route::delete('/users/{user}', [
    UserController::class, 'destroy'
])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);
});