<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AccountCreateController;
use Modules\User\Http\Controllers\AccountLoginController;
use Modules\User\Http\Controllers\AccountNewPasswordController;
use Modules\User\Http\Controllers\AccountResetPasswordController;
use Modules\User\Http\Controllers\ClassesController;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\OrderController;

Route::get('/', function () {
    return redirect('/account/profile');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('user', UserController::class)->names('user');
});

Route::middleware('auth')->prefix('account')->group(function () {
    // Profile Dashboard
    Route::get('profile', [UserController::class, 'index'])->name('profile.dashboard');

    // Account Information
    Route::get('edit', [UserController::class, 'edit'])->name('profile.account-information.edit');
    Route::patch('edit', [UserController::class, 'update'])->name('profile.account-information.update');
    Route::delete('edit', [UserController::class, 'destroy'])->name('profile.account-information.destroy');

    Route::get('my-orders', [OrderController::class, 'index'])->name('profile.orders.index');
    Route::post('my-orders', [OrderController::class, 'store'])->name('profile.orders.store');
    Route::get('my-orders/{order}', [OrderController::class, 'show'])->name('profile.orders.show');

    // My classes
    Route::get('classes', [ClassesController::class, 'index'])->name('account::classes.index');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [AccountCreateController::class, 'create'])->name('user::account.create');
    Route::post('register', [AccountCreateController::class, 'store'])->name('user::account.store');

    Route::get('login', [AccountLoginController::class, 'index'])->name('login');
    Route::post('login', [AccountLoginController::class, 'create'])->name('user::account.login.create');

    Route::get('forgot-password', [AccountResetPasswordController::class, 'create'])->name('user::account.password.create');
    Route::post('forgot-password', [AccountResetPasswordController::class, 'store'])->name('user::account.password.store');

    Route::get('reset-password/{token}', [AccountNewPasswordController::class, 'index'])->name('password.reset');
    Route::post('reset-password', [AccountNewPasswordController::class, 'create'])->name('password.store');
});

Route::post('logout', [AccountLoginController::class, 'destroy'])->name('logout');
