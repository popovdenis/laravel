<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subscription', SubscriptionController::class)->names('subscription');
    Route::get('/subscription/change', [SubscriptionController::class, 'show'])->name('subscription::show');
    Route::post('/subscription/change', [SubscriptionController::class, 'store'])->name('subscription::store');
});
