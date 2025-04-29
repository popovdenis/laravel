<?php

use Illuminate\Support\Facades\Route;
use Modules\UserSubscriptionPlan\Http\Controllers\SubscriptionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('usersubscription', SubscriptionController::class)->names('usersubscription');
});
