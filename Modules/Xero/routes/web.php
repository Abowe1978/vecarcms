<?php

use Illuminate\Support\Facades\Route;
use Modules\Xero\Http\Controllers\XeroController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('xero', XeroController::class)->names('xero');
});
