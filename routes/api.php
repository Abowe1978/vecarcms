<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - VeCarCMS
|--------------------------------------------------------------------------
|
| REST API routes for VeCarCMS.
| Protected by Sanctum authentication.
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Future API routes will be added here
// Examples:
// Route::apiResource('posts', PostApiController::class)->middleware('auth:sanctum');
// Route::apiResource('pages', PageApiController::class)->middleware('auth:sanctum');
