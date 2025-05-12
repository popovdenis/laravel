<?php

use Illuminate\Support\Facades\Route;
use Modules\UserSubscription\Http\Controllers\UserSubscriptionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('usersubscription', UserSubscriptionController::class)->names('usersubscription');
    Route::get('/my-subscription/change', [UserSubscriptionController::class, 'show'])->name('usersubscription::show');
    Route::post('/my-subscription/change', [UserSubscriptionController::class, 'store'])->name('usersubscription::store');
});
