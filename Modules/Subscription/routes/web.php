<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Modules\Subscription\Http\Controllers\StripeWebhookController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subscription', SubscriptionController::class)->names('subscription');
    Route::get('/subscription/change', [SubscriptionController::class, 'show'])->name('subscription::show');
    Route::post('/subscription/change', [SubscriptionController::class, 'store'])->name('subscription::store');
//    Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe::webhook');
});
