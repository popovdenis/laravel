<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\OrderController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('orders', OrderController::class)->names('orders');
});
