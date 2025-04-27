<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\BookingController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('booking/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});
