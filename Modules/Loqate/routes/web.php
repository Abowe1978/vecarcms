<?php

use Illuminate\Support\Facades\Route;
use Modules\Loqate\Http\Controllers\LoqateController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('loqate', LoqateController::class)->names('loqate');
});
