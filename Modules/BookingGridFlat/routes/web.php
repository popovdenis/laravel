<?php

use Illuminate\Support\Facades\Route;
use Modules\BookingGridFlat\Http\Controllers\BookingGridFlatController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bookinggridflat', BookingGridFlatController::class)->names('bookinggridflat');
});
