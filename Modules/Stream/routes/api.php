<?php

use Illuminate\Support\Facades\Route;
use Modules\Stream\Http\Controllers\StreamController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('stream', StreamController::class)->names('stream');
});
