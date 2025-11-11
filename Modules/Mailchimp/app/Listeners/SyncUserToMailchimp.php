<?php

namespace Modules\Mailchimp\app\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Modules\Mailchimp\app\Services\MailchimpService;

class SyncUserToMailchimp
{
    protected MailchimpService $mailchimpService;

    public function __construct(MailchimpService $mailchimpService)
    {
        $this->mailchimpService = $mailchimpService;
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        try {
            $user = $event->user;
            
            // Sync user to Mailchimp
            $this->mailchimpService->syncUser($user);
            
            Log::info('User synced to Mailchimp after registration', [
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error syncing user to Mailchimp after registration', [
                'error' => $e->getMessage(),
                'user_id' => $event->user->id ?? null,
            ]);
        }
    }
}

