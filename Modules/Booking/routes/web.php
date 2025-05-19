<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\BookingController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('booking/store', [BookingController::class, 'store']);
    Route::post('booking/cancel', [BookingController::class, 'cancel']);
    Route::post('booking/preferred-time', [BookingController::class, 'preferredTime']);
});
