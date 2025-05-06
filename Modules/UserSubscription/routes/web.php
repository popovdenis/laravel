<?php

use Illuminate\Support\Facades\Route;
use Modules\UserSubscription\Http\Controllers\UserSubscriptionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('usersubscription', UserSubscriptionController::class)->names('usersubscription');
});
