<?php

use Illuminate\Support\Facades\Route;
use Modules\StripeCard\Http\Controllers\StripeCardController;
use Modules\Subscription\Http\Controllers\StripeWebhookController;

Route::middleware([])->prefix('v1')->group(function () {
    Route::apiResource('stripecard', StripeCardController::class)->names('stripecard');
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
});
