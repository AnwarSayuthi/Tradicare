<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

// Admin controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductListController;
use App\Http\Controllers\Admin\AppointmentSchedulesController;
use App\Http\Controllers\Admin\UserDetailsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;

// Customer controllers
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\AppointmentController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\UserController;

// Middleware
use App\Http\Middleware\RoleRedirect;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard']); 
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Admin routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products-list', [ProductListController::class, 'index'])->name('productList');
    Route::get('/products/create', [ProductListController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductListController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductListController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductListController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductListController::class, 'destroy'])->name('products.destroy');
    
    // Appointments
    Route::get('/appointment-schedules', [AppointmentSchedulesController::class, 'index'])->name('appointmentSchedules');
    Route::get('/appointments/create', [AppointmentSchedulesController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentSchedulesController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [AppointmentSchedulesController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentSchedulesController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [AppointmentSchedulesController::class, 'destroy'])->name('appointments.destroy');
    
    // Users
    Route::get('/user-details', [UserDetailsController::class, 'index'])->name('userDetails');
    Route::get('/users/{id}', [UserDetailsController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserDetailsController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserDetailsController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserDetailsController::class, 'destroy'])->name('users.destroy');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [AdminOrderController::class, 'update'])->name('orders.update');
    
    // Services
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{id}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
});

// Customer routes
Route::middleware([CustomerMiddleware::class])->prefix('customer')->name('customer.')->group(function () {
    // Home
    Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    
    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem'])->name('cart.update');
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // Payments
    Route::post('/payments/order/{orderId}', [PaymentController::class, 'processOrderPayment'])->name('payments.order');
    Route::post('/payments/appointment/{appointmentId}', [PaymentController::class, 'processAppointmentPayment'])->name('payments.appointment');
    
    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('password.change');
    Route::put('/change-password', [UserController::class, 'updatePassword'])->name('password.update');
});

