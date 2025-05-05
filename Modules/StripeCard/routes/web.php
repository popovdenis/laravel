<?php

use Illuminate\Support\Facades\Route;
use Modules\StripeCard\Http\Controllers\StripeCardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('stripecard', StripeCardController::class)->names('stripecard');
    Route::get('/', [StripeCardController::class, 'show'])->name('stripecard::form');
    Route::post('/attach', [StripeCardController::class, 'attach'])->name('stripecard::attach');

    Route::get('/checkout', [StripeCardController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/session', [StripeCardController::class, 'createSession'])->name('stripecard::checkout.session');
    Route::get('/checkout/success', fn () => view('stripecard::success'))->name('stripecard::checkout.success');
    Route::get('/checkout/cancel', fn () => view('stripecard::cancel'))->name('stripecard::checkout.cancel');
    Route::post('/stripe/webhook', [StripeCardController::class, 'handle']);
});
