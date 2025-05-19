<?php

use Illuminate\Support\Facades\Route;
use Modules\Theme\Http\Controllers\ThemeController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('theme', ThemeController::class)->names('theme');
});
