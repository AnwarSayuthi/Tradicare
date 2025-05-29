<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\AdminViewController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Controllers\Process\PaymentController;
use App\Http\Controllers\Process\ReportController;


// Public routes
// Route::get('/', [CustomerViewController::class, 'landing']);
Route::get('/', [CustomerViewController::class, 'landing'])->name('landing');

// Auth routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Admin routes
// Inside your admin middleware group
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin view routes will go here
    Route::get('/dashboard', [AdminViewController::class, 'dashboard'])->name('dashboard');
    
    // Admin Orders
    Route::get('/orders', [AdminViewController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminViewController::class, 'showOrder'])->name('orders.show');

    // Product routes
    Route::get('/products', [App\Http\Controllers\Process\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\Process\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Process\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [App\Http\Controllers\Process\ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Process\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Process\ProductController::class, 'update'])->name('products.update');
    Route::put('/products/{product}/status', [App\Http\Controllers\Process\ProductController::class, 'updateStatus'])->name('products.update-status');
    Route::delete('/products/{product}', [App\Http\Controllers\Process\ProductController::class, 'destroy'])->name('products.destroy');
    
    // Appointment routes
    Route::get('/appointments', [App\Http\Controllers\Process\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [App\Http\Controllers\Process\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [App\Http\Controllers\Process\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [App\Http\Controllers\Process\AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{id}/edit', [App\Http\Controllers\Process\AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [App\Http\Controllers\Process\AppointmentController::class, 'update'])->name('appointments.update');
    Route::put('/appointments/{id}/status', [App\Http\Controllers\Process\AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::delete('/appointments/{id}', [App\Http\Controllers\Process\AppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // Admin Order Process routes
    Route::put('/orders/{order}/status', [App\Http\Controllers\Process\DashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // Report generation route
    Route::get('/reports/generate/{type}', [ReportController::class, 'generateAdminReport'])->name('reports.generate');
    
    // Services management views
    Route::get('/services', [AdminViewController::class, 'services'])->name('services.index');
    Route::get('/services/create', [AdminViewController::class, 'createService'])->name('services.create');
    Route::get('/services/{id}', [AdminViewController::class, 'showService'])->name('services.show');
    Route::get('/services/{id}/edit', [AdminViewController::class, 'editService'])->name('services.edit');
    
    // Services management process
    Route::post('/services', [App\Http\Controllers\Process\ServicesController::class, 'store'])->name('services.store');
    Route::put('/services/{id}', [App\Http\Controllers\Process\ServicesController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [App\Http\Controllers\Process\ServicesController::class, 'destroy'])->name('services.destroy');
    Route::patch('/services/{id}/toggle-status', [App\Http\Controllers\Process\ServicesController::class, 'toggleStatus'])->name('services.toggle-status');
});

// Customer routes
// Inside your customer middleware group
Route::middleware([CustomerMiddleware::class])->prefix('customer')->name('customer.')->group(function() 
{
    Route::get('/profile/location/{id}/get', [App\Http\Controllers\Process\ProfileController::class, 'getLocation'])->name('location.get');
    
    // Appointments - consolidated and consistent naming
    Route::get('/appointments', [CustomerViewController::class, 'appointments'])->name('appointments.index');
    Route::get('/appointments/create', [CustomerViewController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [CustomerViewController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/available-slots', [CustomerViewController::class, 'getAvailableTimeSlots'])->name('appointments.available-slots');
    Route::get('/appointments/payment/{appointment}', [CustomerViewController::class, 'showPayment'])->name('appointments.payment');
    Route::post('/appointments/payment/{appointment}', [CustomerViewController::class, 'processPayment'])->name('appointments.process-payment');
    Route::put('/appointments/{id}/cancel', [CustomerViewController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/appointments/{id}/reschedule', [CustomerViewController::class, 'rescheduleAppointment'])->name('appointments.reschedule');
    
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
    Route::get('/customer/profile/location/{id}/get', [App\Http\Controllers\Process\ProfileController::class, 'getLocation'])->name('customer.location.get');
    
    // Password change route
    Route::post('/profile/change-password', [App\Http\Controllers\Process\ProfileController::class, 'changePassword'])->name('password.change');
    // Remove this line as we're using the original password.change route instead
    // Route::put('/profile/update-password', [App\Http\Controllers\Process\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    // Cart routes
    Route::get('/cart', [App\Http\Controllers\CustomerViewController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{productId}', [App\Http\Controllers\Process\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'updateCartItem'])->name('cart.update');
    Route::post('/cart/remove/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'removeCartItem'])->name('cart.remove');
    Route::post('/cart/clear', [App\Http\Controllers\Process\CartController::class, 'clearCart'])->name('cart.clear');
    Route::post('/cart/increment/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'incrementCartItem'])->name('cart.increment');
    Route::post('/cart/decrement/{cartItemId}', [App\Http\Controllers\Process\CartController::class, 'decrementCartItem'])->name('cart.decrement');
    
    
    // Add this route inside the customer middleware group
    // Services and Appointments
    Route::get('/services', [CustomerViewController::class, 'services'])->name('services');
    Route::get('/services/{service}', [CustomerViewController::class, 'showService'])->name('services.show');
    Route::get('/appointment/create', [CustomerViewController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointment/store', [CustomerViewController::class, 'storeAppointment'])->name('appointments.store');
    
    // Add this to your customer routes section
    Route::get('/about', [CustomerViewController::class, 'about'])->name('about');
    
    // Move orders under profile section
    Route::get('/profile/orders', [CustomerViewController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [CustomerViewController::class, 'orderDetails'])->name('profile.orders.show');
    // Add this route definition if it doesn't exist
    Route::put('/profile/update-password', [App\Http\Controllers\Process\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    
    // Add this outside any middleware group
    // Payment routes
    Route::post('/payment/{type}/{id}', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
});
