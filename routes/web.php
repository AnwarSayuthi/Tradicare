<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\AdminViewController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;


// Public routes
Route::get('/', [CustomerViewController::class, 'landing']);

// Auth routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Admin routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin view routes will go here
});

// Customer routes
Route::middleware([CustomerMiddleware::class])->prefix('customer')->name('customer.')->group(function () {
    // Appointments
    Route::get('/appointments', [CustomerViewController::class, 'appointments'])->name('appointments.index');
    Route::post('/appointments', [CustomerViewController::class, 'appointmentStore'])->name('appointment.store');
    Route::get('/appointment/create', [CustomerViewController::class, 'appointmentCreate'])->name('appointment.create');

    // Products
    Route::get('/products', [CustomerViewController::class, 'products'])->name('products.index');
    Route::get('/products/{product}', [CustomerViewController::class, 'showProduct'])->name('products.show');

    // Cart
    Route::get('/cart', [CustomerViewController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{product}', [CustomerViewController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [CustomerViewController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [CustomerViewController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [CustomerViewController::class, 'placeOrder'])->name('place.order');
    
    // Orders
    Route::get('/orders', [CustomerViewController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [CustomerViewController::class, 'orderDetails'])->name('orders.show');
    
    // Update the profile routes in the customer middleware group
    // Profile routes
    Route::get('/profile', [CustomerViewController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [App\Http\Controllers\Process\ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Location routes
    Route::post('/profile/location/add', [App\Http\Controllers\Process\ProfileController::class, 'addLocation'])->name('location.add');
    Route::put('/profile/location/{id}/update', [App\Http\Controllers\Process\ProfileController::class, 'updateLocation'])->name('location.update');
    Route::delete('/profile/location/{id}', [App\Http\Controllers\Process\ProfileController::class, 'deleteLocation'])->name('location.delete');
    Route::post('/profile/change-password', [App\Http\Controllers\Process\ProfileController::class, 'changePassword'])->name('password.change');
    
    // Cart routes
    Route::get('/cart', [App\Http\Controllers\CustomerViewController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{productId}', [App\Http\Controllers\Process\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'updateCartItem'])->name('cart.update');
    Route::post('/cart/remove/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'removeCartItem'])->name('cart.remove');
    Route::post('/cart/clear', [App\Http\Controllers\Process\CartController::class, 'clearCart'])->name('cart.clear');
    Route::post('/cart/increment/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'incrementCartItem'])->name('cart.increment');
    Route::post('/cart/decrement/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'decrementCartItem'])->name('cart.decrement');
});
