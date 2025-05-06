<?php

use Illuminate\Support\Facades\Route;
use Modules\UserSubscription\Http\Controllers\UserSubscriptionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('usersubscription', UserSubscriptionController::class)->names('usersubscription');
});
