<?php

use Illuminate\Support\Facades\Route;
use Modules\Stripe\Http\Controllers\StripeWebhookController;
use Modules\Stripe\Http\Controllers\SubscriptionController;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/subscribe', [SubscriptionController::class, 'create'])->name('subscribe::create');
    Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe::webhook');
});
