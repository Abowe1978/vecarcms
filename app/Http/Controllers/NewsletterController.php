<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter (Mailchimp integration)
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Check if Mailchimp module is enabled
            if (class_exists('\Modules\Mailchimp\Services\MailchimpService')) {
                $mailchimpService = app('\Modules\Mailchimp\Services\MailchimpService');
                $mailchimpService->subscribe($validated['email']);
                
                return back()->with('success', 'Successfully subscribed to newsletter!');
            }
            
            // Otherwise, store in database for manual processing
            \DB::table('newsletter_subscribers')->insert([
                'email' => $validated['email'],
                'subscribed_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Thank you for subscribing!');
        } catch (\Exception $e) {
            \Log::error('Newsletter subscription error: ' . $e->getMessage());
            
            return back()->with('error', 'Error subscribing to newsletter. Please try again.');
        }
    }
}

