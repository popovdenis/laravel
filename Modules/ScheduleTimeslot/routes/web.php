<?php

use Illuminate\Support\Facades\Route;
use Modules\ScheduleTimeslot\Http\Controllers\ScheduleTimeslotController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('scheduletimeslot', ScheduleTimeslotController::class)->names('scheduletimeslot');
});
