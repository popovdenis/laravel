<?php

use Illuminate\Support\Facades\Route;
use Modules\SubscriptionPlan\Http\Controllers\SubscriptionPlanController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subscriptionplan', SubscriptionPlanController::class)->names('subscriptionplan');
    Route::get('/subscription-plan/list', [SubscriptionPlanController::class, 'list']);
});
