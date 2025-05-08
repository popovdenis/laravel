<?php

use Illuminate\Support\Facades\Route;
use Modules\BookingGridFlat\Http\Controllers\BookingGridFlatController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bookinggridflat', BookingGridFlatController::class)->names('bookinggridflat');
});
