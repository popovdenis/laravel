<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subscription', SubscriptionController::class)->names('subscription');
});
