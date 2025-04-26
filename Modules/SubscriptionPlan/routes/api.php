<?php

use Illuminate\Support\Facades\Route;
use Modules\SubscriptionPlan\Http\Controllers\SubscriptionPlanController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('subscriptionplan', SubscriptionPlanController::class)->names('subscriptionplan');
});
