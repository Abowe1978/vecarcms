<?php

use Illuminate\Support\Facades\Route;
use Modules\Eventbrite\Http\Controllers\EventbriteController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('eventbrite', EventbriteController::class)->names('eventbrite');
});
