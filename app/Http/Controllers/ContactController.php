<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function submit(Request $request): RedirectResponse
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Check reCAPTCHA if enabled
        if (settings('recaptcha_enabled') && config('services.recaptcha.site_key')) {
            $request->validate([
                'g-recaptcha-response' => 'required|recaptcha',
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
                'g-recaptcha-response.recaptcha' => 'reCAPTCHA verification failed. Please try again.',
            ]);
        }

        try {
            // Store contact submission in database (if you have a ContactSubmission model)
            // ContactSubmission::create($validated);

            // Send email notification to admin
            $adminEmail = settings('contact_email') ?? config('mail.from.address');
            
            if ($adminEmail) {
                Mail::send('emails.contact-form', $validated, function ($message) use ($validated, $adminEmail) {
                    $message->to($adminEmail)
                            ->subject('New Contact Form Submission: ' . $validated['subject'])
                            ->replyTo($validated['email'], $validated['name']);
                });
            }

            // Send confirmation email to user
            Mail::send('emails.contact-confirmation', $validated, function ($message) use ($validated) {
                $message->to($validated['email'], $validated['name'])
                        ->subject('Thank you for contacting us - ' . settings('site_name', 'VeCarCMS'));
            });

            // Log the submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

            return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly.')
                ->withInput();
        }
    }
}
