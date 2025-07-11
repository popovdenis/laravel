<?php

use Illuminate\Support\Facades\Route;
use Modules\LanguageLevel\Http\Controllers\LanguageLevelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/levels/{level:slug}', [LanguageLevelController::class, 'show'])->name('languagelevel::show');
});
