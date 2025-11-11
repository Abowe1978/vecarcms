<?php

use Illuminate\Support\Facades\Route;
use Modules\Mailchimp\Http\Controllers\MailchimpController;
use Modules\Mailchimp\Http\Controllers\MailchimpWebhookController;

// Webhook route - non richiede autenticazione
Route::post('mailchimp/webhook', [MailchimpWebhookController::class, 'handleWebhook'])->name('mailchimp.webhook');

Route::middleware(['auth', 'verified', 'admin'])->prefix('mailchimp')->name('mailchimp.')->group(function () {
    Route::post('subscribe', [MailchimpController::class, 'subscribe'])->name('subscribe');
    Route::post('unsubscribe', [MailchimpController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('sync', [MailchimpController::class, 'sync'])->name('sync');
    Route::post('bulk-sync', [MailchimpController::class, 'bulkSync'])->name('bulk-sync');
    Route::get('lists', [MailchimpController::class, 'getLists'])->name('lists');
    Route::get('test-connection', [MailchimpController::class, 'testConnection'])->name('test-connection');
    Route::post('add-tags', [MailchimpController::class, 'addTags'])->name('add-tags');
    Route::post('remove-tags', [MailchimpController::class, 'removeTags'])->name('remove-tags');
});
