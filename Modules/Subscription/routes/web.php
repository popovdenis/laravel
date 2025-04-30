<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('usersubscription', SubscriptionController::class)->names('usersubscription');
    Route::get('/usersubscription/change', [SubscriptionController::class, 'show'])->name('usersubscription::show');
    Route::post('/usersubscription/change', [SubscriptionController::class, 'store'])->name('usersubscription::store');
});
