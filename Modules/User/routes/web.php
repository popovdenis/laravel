<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('user', UserController::class)->names('user');
});
Route::middleware('auth')->prefix('profile')->group(function () {
    // Account Dashboard
    Route::get('/', [UserController::class, 'index'])->name('profile.dashboard');

    // Account Information
    Route::get('/account-information', [UserController::class, 'edit'])->name('profile.account-information.edit');
    Route::patch('/account-information', [UserController::class, 'update'])->name('profile.account-information.update');
    Route::delete('/account-information', [UserController::class, 'destroy'])->name('profile.account-information.destroy');
});
