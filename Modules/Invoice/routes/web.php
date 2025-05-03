<?php

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\InvoiceController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('invoice', InvoiceController::class)->names('invoice');
    Route::post('/stripe/invoice-webhook', [\Modules\Invoice\Http\Controllers\StripeInvoiceWebhookController::class, 'handle']);
});
