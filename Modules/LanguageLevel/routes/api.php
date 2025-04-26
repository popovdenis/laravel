<?php

use Illuminate\Support\Facades\Route;
use Modules\LanguageLevel\Http\Controllers\LanguageLevelController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('languagelevel', LanguageLevelController::class)->names('languagelevel');
});
