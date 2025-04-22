<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/redirect', function () { return response('Zoom redirect success'); });
Route::get('/policy', function () { return response('Zoom policy success'); });
Route::get('/terms', function () { return response('Zoom terms success'); });
Route::get('/support', function () { return response('Zoom support success'); });
Route::get('/documentation', function () { return response('Zoom documentation success'); });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/blog.php';
require __DIR__.'/levels.php';
require __DIR__.'/booking.php';
