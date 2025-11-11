<?php

use Illuminate\Support\Facades\Route;
use Modules\Paypal\Http\Controllers\PaypalController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('paypal', PaypalController::class)->names('paypal');
});
