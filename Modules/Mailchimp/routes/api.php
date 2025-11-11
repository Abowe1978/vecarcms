<?php

use Illuminate\Support\Facades\Route;
use Modules\Mailchimp\Http\Controllers\MailchimpController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('mailchimp', MailchimpController::class)->names('mailchimp');
});
