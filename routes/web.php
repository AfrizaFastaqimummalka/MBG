<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use Illuminate\Support\Facades\Route;

// ─── Guest routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',          [AuthController::class, 'login']);

    Route::get('/register',        [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',       [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password',[AuthController::class, 'sendReset'])->name('password.email');
});

// ─── Auth routes ──────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Home / Garasi
    Route::get('/',                    [HomeController::class, 'index'])->name('home');
    Route::post('/vehicles',           [HomeController::class, 'storeVehicle'])->name('vehicles.store');
    Route::delete('/vehicles/{vehicle}',[HomeController::class, 'destroyVehicle'])->name('vehicles.destroy');

    // Service
    Route::get('/service',             [ServiceController::class, 'index'])->name('service');
    Route::post('/services',           [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/services/{service}',[ServiceController::class, 'destroy'])->name('services.destroy');

    // Expense
    Route::get('/expense',             [ExpenseController::class, 'index'])->name('expense');

    // Parts
    Route::get('/parts',               [SparepartController::class, 'index'])->name('parts');
    Route::post('/spareparts',         [SparepartController::class, 'store'])->name('spareparts.store');
    Route::delete('/spareparts/{sparepart}',[SparepartController::class, 'destroy'])->name('spareparts.destroy');

    // Account
    Route::get('/account',             [AccountController::class, 'index'])->name('account');
    
    // Notifikasi
    Route::get('/account/notification',        [NotificationController::class, 'index'])->name('notification');
    Route::put('/account/notification',        [NotificationController::class, 'update'])->name('notification.update');
    Route::post('/account/notification/test',  [NotificationController::class, 'testSend'])->name('notification.test');

    // Keamanan
    Route::get('/account/security',            [SecurityController::class, 'index'])->name('security');
    Route::put('/account/security/password',   [SecurityController::class, 'updatePassword'])->name('security.password');
    Route::delete('/account/security/sessions',[SecurityController::class, 'logoutOtherDevices'])->name('security.logout-others');
    // Logout
    Route::post('/logout',             [AuthController::class, 'logout'])->name('logout');
});
