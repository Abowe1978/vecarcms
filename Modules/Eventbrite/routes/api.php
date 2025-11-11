<?php

use Illuminate\Support\Facades\Route;
use Modules\Eventbrite\Http\Controllers\EventbriteController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('eventbrite', EventbriteController::class)->names('eventbrite');
});
