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
use App\Http\Controllers\Student\MyCoursesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('profile')->group(function () {
    // Lessons
    Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('profile.courses.index');

    // Schedule
    Route::get('/schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])
        ->name('profile.schedule.index');
//    Route::post('/schedule/{schedule}/create-meeting', [\App\Http\Controllers\ScheduleController::class, 'create'])
//        ->name('profile.schedule.create-meeting');
    Route::get('/schedule/{schedule}/join', [\App\Http\Controllers\ScheduleController::class, 'join'])
        ->name('schedule.join');

    Route::get('/zoom/signature', [\App\Http\Controllers\ZoomSignatureController::class, 'generate']);

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

