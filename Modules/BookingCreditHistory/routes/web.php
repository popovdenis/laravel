<?php

use Illuminate\Support\Facades\Route;
use Modules\BookingCreditHistory\Http\Controllers\BookingCreditHistoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bookingcredithistory', BookingCreditHistoryController::class)->names('bookingcredithistory');
});
