<?php

use Illuminate\Support\Facades\Route;
use Modules\Stream\Http\Controllers\StreamController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('stream', StreamController::class)->names('stream');
});
