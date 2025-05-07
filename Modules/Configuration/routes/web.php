<?php

use Illuminate\Support\Facades\Route;
use Modules\Configuration\Http\Controllers\ConfigurationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('configuration', ConfigurationController::class)->names('configuration');
});
