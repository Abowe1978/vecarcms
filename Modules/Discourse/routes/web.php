<?php

use Illuminate\Support\Facades\Route;
use Modules\Discourse\Http\Controllers\DiscourseController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('discourse', DiscourseController::class)->names('discourse');
});
