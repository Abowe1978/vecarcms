<?php

use Illuminate\Support\Facades\Route;
use Modules\Discourse\Http\Controllers\DiscourseController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('discourse', DiscourseController::class)->names('discourse');
});
