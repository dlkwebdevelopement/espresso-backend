<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\NotificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Notifications
Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications/token', [NotificationController::class, 'registerToken']);
Route::post('/notifications/send', [NotificationController::class, 'sendPushNotification']);
Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);

// User account and favorites endpoints
Route::post('/user/change-password', [UserController::class, 'changePassword']);
Route::post('/user/delete-account', [UserController::class, 'deleteAccount']);
Route::post('/user/profile', [ProfileController::class, 'getProfile']);
Route::post('/user/profile/update', [ProfileController::class, 'updateProfile']);
Route::post('/user/favorites/sync', [UserController::class, 'syncFavorites']);
Route::post('/user/favorites', [UserController::class, 'getFavorites']);
Route::post('/user/favorites/add', [UserController::class, 'addFavorite']);
Route::post('/user/favorites/remove', [UserController::class, 'removeFavorite']);

// Public routes for products
Route::get('/items', [ItemController::class, 'getCategoriesAndItems']);

// Order placement and retrieval endpoints
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders/refund', [OrderController::class, 'refund']);

// Admin / Real-time order tracker endpoints
Route::get('/orders/{order_id}/status', [OrderController::class, 'getOrderStatus']);
Route::get('/admin/orders', [OrderController::class, 'getAllActiveOrders']);
Route::put('/admin/orders/{order_id}/status', [OrderController::class, 'updateStatus']);

// Wallet transaction endpoints
Route::post('/wallet/transactions', [WalletController::class, 'store']);
Route::get('/wallet/transactions', [WalletController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
