<?php

use Illuminate\Support\Facades\Route;
use Modules\Stripe\Http\Controllers\SubscriptionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/subscribe', [SubscriptionController::class, 'create'])->name('subscribe::create');
});
