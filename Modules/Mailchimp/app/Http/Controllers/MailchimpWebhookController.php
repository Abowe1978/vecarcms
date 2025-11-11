<?php

namespace Modules\Mailchimp\Http\Controllers;

use App\Models\Integration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class MailchimpWebhookController extends Controller
{
    /**
     * Handle a Mailchimp webhook call.
     */
    public function handleWebhook(Request $request): Response
    {
        $payload = $request->getContent();
        
        try {
            // Recupera l'integrazione Mailchimp dal database
            $integration = Integration::where('module_name', 'Mailchimp')->first();
            
            if (!$integration || !$integration->is_enabled || !$integration->is_configured) {
                Log::error('Mailchimp integration not enabled or configured');
                return response('Module not enabled', 403);
            }
            
            $data = json_decode($payload, true);
            
            if (!$data || !isset($data['type'])) {
                Log::error('Invalid Mailchimp webhook payload');
                return response('Invalid payload', 400);
            }
            
            // Per ora solo registriamo l'evento
            Log::info('Mailchimp webhook received', [
                'event' => $data['type'],
                'data' => $data,
            ]);
            
            // Gestione degli eventi specifici di Mailchimp
            switch ($data['type']) {
                case 'subscribe':
                    $this->handleSubscribe($data['data']);
                    break;
                case 'unsubscribe':
                    $this->handleUnsubscribe($data['data']);
                    break;
                case 'profile':
                    $this->handleProfileUpdate($data['data']);
                    break;
                case 'upemail':
                    $this->handleEmailUpdate($data['data']);
                    break;
                case 'cleaned':
                    $this->handleCleaned($data['data']);
                    break;
                case 'campaign':
                    $this->handleCampaign($data['data']);
                    break;
                default:
                    Log::info('Unhandled Mailchimp webhook type', ['type' => $data['type']]);
                    break;
            }
            
            return response('Webhook handled', 200);
            
        } catch (\Exception $e) {
            Log::error('Error handling Mailchimp webhook: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return response('Webhook error: ' . $e->getMessage(), 400);
        }
    }
    
    /**
     * Handle subscribe event
     */
    private function handleSubscribe(array $data): void
    {
        try {
            $email = $data['email'] ?? null;
            
            if (!$email) {
                return;
            }
            
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->update([
                    'mailchimp_status' => 'subscribed',
                    'mailchimp_synced_at' => now(),
                ]);
                
                Log::info('User subscribed via Mailchimp webhook', [
                    'user_id' => $user->id,
                    'email' => $email,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling subscribe webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle unsubscribe event
     */
    private function handleUnsubscribe(array $data): void
    {
        try {
            $email = $data['email'] ?? null;
            
            if (!$email) {
                return;
            }
            
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->update([
                    'mailchimp_status' => 'unsubscribed',
                    'mailchimp_synced_at' => now(),
                ]);
                
                Log::info('User unsubscribed via Mailchimp webhook', [
                    'user_id' => $user->id,
                    'email' => $email,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling unsubscribe webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle profile update event
     */
    private function handleProfileUpdate(array $data): void
    {
        try {
            $email = $data['email'] ?? null;
            
            if (!$email) {
                return;
            }
            
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->update([
                    'mailchimp_synced_at' => now(),
                ]);
                
                Log::info('User profile updated via Mailchimp webhook', [
                    'user_id' => $user->id,
                    'email' => $email,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling profile update webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle email update event
     */
    private function handleEmailUpdate(array $data): void
    {
        try {
            $oldEmail = $data['old_email'] ?? null;
            $newEmail = $data['new_email'] ?? null;
            
            if (!$oldEmail || !$newEmail) {
                return;
            }
            
            $user = User::where('email', $oldEmail)->first();
            
            if ($user) {
                $user->update([
                    'email' => $newEmail,
                    'mailchimp_synced_at' => now(),
                ]);
                
                Log::info('User email updated via Mailchimp webhook', [
                    'user_id' => $user->id,
                    'old_email' => $oldEmail,
                    'new_email' => $newEmail,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling email update webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle cleaned (bounced/invalid) event
     */
    private function handleCleaned(array $data): void
    {
        try {
            $email = $data['email'] ?? null;
            
            if (!$email) {
                return;
            }
            
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->update([
                    'mailchimp_status' => 'cleaned',
                    'mailchimp_synced_at' => now(),
                ]);
                
                Log::warning('User email cleaned (bounced) via Mailchimp webhook', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'reason' => $data['reason'] ?? 'unknown',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling cleaned webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle campaign event
     */
    private function handleCampaign(array $data): void
    {
        try {
            Log::info('Mailchimp campaign event received', [
                'campaign_id' => $data['id'] ?? null,
                'subject' => $data['subject'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error handling campaign webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
} 