<?php

namespace Modules\Mailchimp\app\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Mailchimp\app\Services\MailchimpService;

class UpdateMailchimpOnProfileChange
{
    protected MailchimpService $mailchimpService;

    public function __construct(MailchimpService $mailchimpService)
    {
        $this->mailchimpService = $mailchimpService;
    }

    /**
     * Handle the event when a user profile is updated
     */
    public function handle($event): void
    {
        try {
            // Get the user from the event
            $user = $event->user ?? null;
            
            if (!$user) {
                return;
            }
            
            // Only sync if user is already subscribed to Mailchimp
            if ($user->mailchimp_status === 'subscribed') {
                $this->mailchimpService->updateMember($user);
                
                Log::info('User profile synced to Mailchimp after update', [
                    'user_id' => $user->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error syncing user profile to Mailchimp', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
            ]);
        }
    }
}

