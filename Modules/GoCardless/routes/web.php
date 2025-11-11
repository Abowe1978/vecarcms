<?php

use Illuminate\Support\Facades\Route;
use Modules\GoCardless\Http\Controllers\GoCardlessController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('gocardless', GoCardlessController::class)->names('gocardless');
});
