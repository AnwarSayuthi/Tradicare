<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

//Admin controller
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductListController;
use App\Http\Controllers\Admin\AppointmentSchedulesController;
use App\Http\Controllers\Admin\UserDetailsController;

//Customer controller
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\AppointmentController;


//Middleware
use App\Http\Middleware\RoleRedirect;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;



Route::get('/', function () {
    return view('welcome');
});

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




Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/products-list', [ProductListController::class, 'index'])->name('admin.productList');
    Route::get('/admin/appointment-schedules', [AppointmentSchedulesController::class, 'index'])->name('admin.appointmentSchedules');
    Route::get('/admin/user-details', [UserDetailsController::class, 'index'])->name('admin.userDetails');
    // Add other admin routes here
});

Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/customer/homepage', [HomeController::class, 'index'])->name('customer.homepage');
    Route::get('/customer/products', [ProductController::class, 'index'])->name('customer.products');
    Route::get('/customer/appointments', [AppointmentController::class, 'create'])->name('customer.appointment.create');
    Route::post('/customer/appointments', [AppointmentController::class, 'store'])->name('customer.appointment.store');
    // Add other user routes here
});
