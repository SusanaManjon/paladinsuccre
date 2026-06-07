<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Api\Client\ProfileController;
use App\Http\Controllers\Api\Public\HomeController;
use App\Http\Controllers\Api\Public\CatalogController;

// ─── Rutas públicas ────────────────────────────────────────────────────────────
Route::get('/home-data',        [HomeController::class, 'index']);
Route::get('/products',         [CatalogController::class, 'index']);
Route::get('/products/{slug}',  [CatalogController::class, 'show']);
Route::get('/categories',       [CatalogController::class, 'categories']);

// ─── Autenticación ─────────────────────────────────────────────────────────────
Route::post('/login',    [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

// ─── Rutas protegidas ──────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/user',    [LoginController::class, 'user']);

    // ── Admin ──────────────────────────────────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        Route::apiResource('products',   AdminProductController::class);
        Route::apiResource('categories', AdminCategoryController::class);

        Route::get('/users',       [AdminUserController::class, 'index']);
        Route::post('/users',      [AdminUserController::class, 'store']);
        Route::get('/users/{id}',  [AdminUserController::class, 'show']);

        Route::get('/orders',                [AdminOrderController::class, 'index']);
        Route::get('/orders/{id}',           [AdminOrderController::class, 'show']);
        Route::patch('/orders/{id}/status',  [AdminOrderController::class, 'updateStatus']);
        Route::patch('/orders/{id}/verify',  [AdminOrderController::class, 'verifyPayment']);
        Route::post('/orders/{id}/payment-proof', [AdminOrderController::class, 'uploadPaymentProof']);
    });

    // ── Cliente ────────────────────────────────────────────────────────────────
    Route::middleware('client')->prefix('client')->group(function () {
        Route::get('/orders',                        [ClientOrderController::class, 'index']);
        Route::post('/orders',                       [ClientOrderController::class, 'store']);
        Route::get('/orders/{id}',                   [ClientOrderController::class, 'show']);
        Route::post('/orders/{id}/payment-proof',    [ClientOrderController::class, 'uploadPaymentProof']);

        Route::get('/profile',  [ProfileController::class, 'show']);
        Route::put('/profile',  [ProfileController::class, 'update']);
    });
});
