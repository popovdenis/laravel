<?php

use Illuminate\Support\Facades\Route;
use Modules\StripeCard\Http\Controllers\StripeCardController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('stripecard', StripeCardController::class)->names('stripecard');
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
});
