<?php

use Illuminate\Support\Facades\Route;
use Modules\StripeCard\Http\Controllers\StripeCardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('stripecard', StripeCardController::class)->names('stripecard');
    Route::get('/', [StripeCardController::class, 'show'])->name('stripecard::form');
    Route::post('/attach', [StripeCardController::class, 'attach'])->name('stripecard::attach');
});
