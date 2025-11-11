<?php

namespace Modules\Mailchimp\app\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\Mailchimp\app\Services\MailchimpService;

class UserObserver
{
    protected MailchimpService $mailchimpService;

    public function __construct(MailchimpService $mailchimpService)
    {
        $this->mailchimpService = $mailchimpService;
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Only sync if email or name changed and user is subscribed
        if ($user->wasChanged(['email', 'name', 'surname']) && 
            $user->mailchimp_status === 'subscribed') {
            
            try {
                $this->mailchimpService->updateMember($user);
                
                Log::info('User synced to Mailchimp after update', [
                    'user_id' => $user->id,
                    'changed' => $user->getChanges(),
                ]);
            } catch (\Exception $e) {
                Log::error('Error syncing user to Mailchimp on update', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // Unsubscribe from Mailchimp when user is deleted
        if ($user->mailchimp_status === 'subscribed') {
            try {
                $this->mailchimpService->unsubscribeMember($user);
                
                Log::info('User unsubscribed from Mailchimp after deletion', [
                    'user_id' => $user->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Error unsubscribing user from Mailchimp on deletion', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}

