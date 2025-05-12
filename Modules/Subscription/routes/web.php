<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Modules\Subscription\Http\Controllers\StripeWebhookController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subscription', SubscriptionController::class)->names('subscription');
//    Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe::webhook');
});
