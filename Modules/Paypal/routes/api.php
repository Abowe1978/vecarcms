<?php

use Illuminate\Support\Facades\Route;
use Modules\Paypal\Http\Controllers\PaypalController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('paypal', PaypalController::class)->names('paypal');
});
