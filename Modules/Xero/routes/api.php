<?php

use Illuminate\Support\Facades\Route;
use Modules\Xero\Http\Controllers\XeroController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('xero', XeroController::class)->names('xero');
});
