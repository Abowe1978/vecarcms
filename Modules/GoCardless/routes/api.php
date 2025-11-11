<?php

use Illuminate\Support\Facades\Route;
use Modules\GoCardless\Http\Controllers\GoCardlessController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('gocardless', GoCardlessController::class)->names('gocardless');
});
