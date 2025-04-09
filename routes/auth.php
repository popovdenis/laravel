<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;

Route::middleware('auth')->prefix('profile')->group(function () {
    // Account Dashboard
    Route::get('/', function () {
        return view('profile.dashboard');
    })->name('profile.dashboard');

    // Account Information
    Route::get('/account-information', [ProfileController::class, 'edit'])->name('profile.account-information.edit');
    Route::patch('/account-information', [ProfileController::class, 'update'])->name('profile.account-information.update');
    Route::delete('/account-information', [ProfileController::class, 'destroy'])->name('profile.account-information.destroy');

    // Schedule
    Route::get('/schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('profile.schedule.index');
    Route::post('/schedule/{schedule}/create-meeting', [\App\Http\Controllers\ScheduleController::class, 'create'])
        ->name('profile.schedule.create-meeting');
    Route::get('/schedule/{schedule}/join', [\App\Http\Controllers\ScheduleController::class, 'join'])->name('schedule.join');

//    Route::get('/redirect', [\App\Http\Controllers\ZoomOAuthController::class, 'handleCallback']);
    Route::get('/zoom/oauth/callback', [\App\Http\Controllers\ZoomOAuthController::class, 'handleCallback']);
    Route::get('/zoom/join/{meetingId}', [\App\Http\Controllers\ZoomController::class, 'join'])->name('zoom.join');
    Route::get('/zoom/signature', [\App\Http\Controllers\ZoomSignatureController::class, 'generate']);

    // My Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('profile.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('profile.orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('profile.orders.show');

    // Email Verification & Password routes
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Logout
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
