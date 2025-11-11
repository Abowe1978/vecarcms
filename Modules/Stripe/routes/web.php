<?php

use Illuminate\Support\Facades\Route;
use Modules\Stripe\app\Http\Controllers\StripeController;
use Modules\Stripe\app\Http\Controllers\StripeWebhookController;
use Modules\Stripe\app\Http\Controllers\Admin\StripeSettingsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('stripe', StripeController::class)->names('stripe');
});

// Webhook route - non richiede autenticazione
Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth', 'admin'])
    ->group(function () {
        Route::get('stripe/settings', [StripeSettingsController::class, 'show'])
            ->name('stripe.settings');
        Route::put('stripe/settings', [StripeSettingsController::class, 'update'])
            ->name('stripe.settings.update');
    });
