<?php

use Illuminate\Support\Facades\Route;
use Modules\ScheduleTemplate\Http\Controllers\ScheduleTemplateController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('scheduletemplate', ScheduleTemplateController::class)->names('scheduletemplate');
});
