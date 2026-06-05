<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Public Store Profile (QR Code Link)
Route::get('s/{id}', [\App\Http\Controllers\StoreProfileController::class, 'show'])->name('stores.public_profile');

// Shop Side Placeholder (Home)
Route::get('/shop', function() {
    return "<h1>Welcome to Espresso Shop</h1><p>Customer facing portal coming soon.</p>";
})->name('shop.index');

// User Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    
    Route::post('otp/send', [\App\Http\Controllers\Auth\OtpController::class, 'sendOtp'])->name('otp.send');
    Route::post('otp/verify', [\App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('otp.verify');
});
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(['admin'])->group(function () {
        // Super Admin Only
        Route::middleware(['super_admin'])->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('stores', \App\Http\Controllers\Admin\StoreController::class);
            Route::resource('admins', \App\Http\Controllers\Admin\AdminManagementController::class);
            
            // Settings
            Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });

        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('sub_categories', \App\Http\Controllers\Admin\SubCategoryController::class);
        Route::resource('items', \App\Http\Controllers\Admin\ItemController::class);
        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
        
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::put('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('orders/{order}/refund', [\App\Http\Controllers\Admin\OrderController::class, 'refund'])->name('orders.refund');
        
        Route::get('weekly-favorites', [\App\Http\Controllers\Admin\WeeklyFavoriteController::class, 'index'])->name('weekly-favorites.index');
        Route::post('weekly-favorites', [\App\Http\Controllers\Admin\WeeklyFavoriteController::class, 'store'])->name('weekly-favorites.store');
        Route::delete('weekly-favorites/{id}', [\App\Http\Controllers\Admin\WeeklyFavoriteController::class, 'destroy'])->name('weekly-favorites.destroy');

        Route::get('popular-items', [\App\Http\Controllers\Admin\PopularItemController::class, 'index'])->name('popular-items.index');
        Route::post('popular-items', [\App\Http\Controllers\Admin\PopularItemController::class, 'store'])->name('popular-items.store');
        Route::delete('popular-items/{id}', [\App\Http\Controllers\Admin\PopularItemController::class, 'destroy'])->name('popular-items.destroy');
        
        Route::resource('free-drinks', \App\Http\Controllers\Admin\FreeDrinkController::class);

        // Store Manager Only
        Route::middleware(['store_manager'])->group(function () {
            Route::get('store-dashboard', [\App\Http\Controllers\Admin\StoreDashboardController::class, 'index'])->name('store-dashboard');
        });
    });
});
