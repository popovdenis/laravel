<?php

use Illuminate\Support\Facades\Route;
use Modules\BookingCreditHistory\Http\Controllers\BookingCreditHistoryController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bookingcredithistory', BookingCreditHistoryController::class)->names('bookingcredithistory');
});
