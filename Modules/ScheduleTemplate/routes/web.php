<?php

use Illuminate\Support\Facades\Route;
use Modules\ScheduleTemplate\Http\Controllers\ScheduleTemplateController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('scheduletemplate', ScheduleTemplateController::class)->names('scheduletemplate');
});
