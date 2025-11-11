<?php

use Illuminate\Support\Facades\Route;
use Modules\Loqate\Http\Controllers\LoqateController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('loqate', LoqateController::class)->names('loqate');
});
