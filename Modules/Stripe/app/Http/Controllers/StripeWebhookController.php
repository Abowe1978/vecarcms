<?php

namespace Modules\Stripe\app\Http\Controllers;

use App\Models\Integration;
use App\Models\User;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle a Stripe webhook call.
     */
    public function handleWebhook(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        
        try {
            // Recupera l'integrazione Stripe dal database
            $integration = Integration::where('module_name', 'Stripe')->first();
            
            if (!$integration || !$integration->is_enabled || !$integration->is_configured) {
                Log::error('Stripe integration not enabled or configured');
                return response('Module not enabled', 403);
            }
            
            $config = $integration->config ?? [];
            $webhookSecret = $config['webhook_secret'] ?? null;
            
            if (!$webhookSecret) {
                Log::error('Stripe webhook secret not configured');
                return response('Webhook not configured', 403);
            }
            
            // Verify webhook signature
            try {
                $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
            } catch (SignatureVerificationException $e) {
                Log::error('Stripe webhook signature verification failed', [
                    'error' => $e->getMessage(),
                ]);
                return response('Invalid signature', 400);
            }
            
            Log::info('Stripe webhook received', [
                'type' => $event->type,
                'id' => $event->id,
            ]);
            
            // Handle different event types
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event->data->object);
                    break;
                    
                case 'invoice.paid':
                    $this->handleInvoicePaid($event->data->object);
                    break;
                    
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;
                    
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;
                    
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;
                    
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;
                    
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event->data->object);
                    break;
                    
                default:
                    Log::info('Unhandled webhook event type', ['type' => $event->type]);
            }
            
            return response('Webhook handled', 200);
            
        } catch (\Exception $e) {
            Log::error('Error handling Stripe webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response('Webhook error: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Handle checkout session completed
     */
    private function handleCheckoutCompleted($session): void
    {
        DB::beginTransaction();
        try {
            $userId = $session->metadata->user_id ?? null;
            $planId = $session->metadata->plan_id ?? null;
            
            if (!$userId || !$planId) {
                Log::warning('Checkout session missing metadata', [
                    'session_id' => $session->id,
                ]);
                return;
            }
            
            $user = User::find($userId);
            $plan = Plan::find($planId);
            
            if (!$user || !$plan) {
                Log::error('User or plan not found', [
                    'user_id' => $userId,
                    'plan_id' => $planId,
                ]);
                return;
            }
            
            // Cancel existing active plan
            UserPlan::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);
            
            // Determine end date based on plan interval
            $endDate = $this->calculateEndDate($plan);
            
            // Create new user plan
            $userPlan = UserPlan::create([
                'user_id' => $userId,
                'plan_id' => $planId,
                'start_date' => now(),
                'end_date' => $endDate,
                'status' => 'active',
                'stripe_subscription_id' => $session->subscription ?? null,
            ]);
            
            // Create payment record
            Payment::create([
                'user_id' => $userId,
                'user_plan_id' => $userPlan->id,
                'amount' => $session->amount_total / 100, // Convert from cents
                'currency' => strtoupper($session->currency),
                'method' => 'card',
                'status' => 'paid',
                'provider' => 'stripe',
                'provider_payment_id' => $session->payment_intent ?? $session->id,
                'provider_payload' => json_encode($session),
                'paid_at' => now(),
            ]);
            
            DB::commit();
            
            Log::info('Checkout session processed successfully', [
                'user_id' => $userId,
                'plan_id' => $planId,
                'user_plan_id' => $userPlan->id,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing checkout session', [
                'error' => $e->getMessage(),
                'session_id' => $session->id,
            ]);
            throw $e;
        }
    }
    
    /**
     * Handle invoice paid (for subscriptions)
     */
    private function handleInvoicePaid($invoice): void
    {
        try {
            $subscriptionId = $invoice->subscription ?? null;
            
            if (!$subscriptionId) {
                return;
            }
            
            // Find user plan by subscription ID
            $userPlan = UserPlan::where('stripe_subscription_id', $subscriptionId)->first();
            
            if (!$userPlan) {
                Log::warning('User plan not found for subscription', [
                    'subscription_id' => $subscriptionId,
                ]);
                return;
            }
            
            // Create payment record for renewal
            Payment::create([
                'user_id' => $userPlan->user_id,
                'user_plan_id' => $userPlan->id,
                'amount' => $invoice->amount_paid / 100,
                'currency' => strtoupper($invoice->currency),
                'method' => 'card',
                'status' => 'paid',
                'provider' => 'stripe',
                'provider_payment_id' => $invoice->payment_intent ?? $invoice->id,
                'provider_payload' => json_encode($invoice),
                'paid_at' => now(),
            ]);
            
            // Extend user plan if it's about to expire
            if ($userPlan->end_date && $userPlan->end_date->isPast()) {
                $newEndDate = $this->calculateEndDate($userPlan->plan);
                $userPlan->update([
                    'end_date' => $newEndDate,
                    'status' => 'active',
                ]);
            }
            
            Log::info('Invoice paid processed', [
                'user_plan_id' => $userPlan->id,
                'invoice_id' => $invoice->id,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error processing invoice paid', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);
        }
    }
    
    /**
     * Handle invoice payment failed
     */
    private function handleInvoicePaymentFailed($invoice): void
    {
        try {
            $subscriptionId = $invoice->subscription ?? null;
            
            if (!$subscriptionId) {
                return;
            }
            
            $userPlan = UserPlan::where('stripe_subscription_id', $subscriptionId)->first();
            
            if (!$userPlan) {
                return;
            }
            
            // Create failed payment record
            Payment::create([
                'user_id' => $userPlan->user_id,
                'user_plan_id' => $userPlan->id,
                'amount' => $invoice->amount_due / 100,
                'currency' => strtoupper($invoice->currency),
                'method' => 'card',
                'status' => 'failed',
                'provider' => 'stripe',
                'provider_payment_id' => $invoice->id,
                'provider_payload' => json_encode($invoice),
            ]);
            
            Log::warning('Invoice payment failed', [
                'user_plan_id' => $userPlan->id,
                'invoice_id' => $invoice->id,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error processing invoice payment failed', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);
        }
    }
    
    /**
     * Handle subscription created
     */
    private function handleSubscriptionCreated($subscription): void
    {
        Log::info('Subscription created', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
        ]);
    }
    
    /**
     * Handle subscription updated
     */
    private function handleSubscriptionUpdated($subscription): void
    {
        try {
            $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();
            
            if (!$userPlan) {
                return;
            }
            
            // Update status based on subscription status
            $status = match($subscription->status) {
                'active' => 'active',
                'canceled' => 'cancelled',
                'past_due' => 'active', // Keep active but flag payment issue
                default => $userPlan->status,
            };
            
            $userPlan->update(['status' => $status]);
            
            Log::info('Subscription updated', [
                'subscription_id' => $subscription->id,
                'new_status' => $status,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error processing subscription updated', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);
        }
    }
    
    /**
     * Handle subscription deleted (cancelled)
     */
    private function handleSubscriptionDeleted($subscription): void
    {
        try {
            $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();
            
            if (!$userPlan) {
                return;
            }
            
            $userPlan->update([
                'status' => 'cancelled',
                'end_date' => now(),
            ]);
            
            Log::info('Subscription cancelled', [
                'subscription_id' => $subscription->id,
                'user_plan_id' => $userPlan->id,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error processing subscription deleted', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);
        }
    }
    
    /**
     * Handle payment intent succeeded
     */
    private function handlePaymentIntentSucceeded($paymentIntent): void
    {
        Log::info('Payment intent succeeded', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount / 100,
        ]);
    }
    
    /**
     * Handle payment intent failed
     */
    private function handlePaymentIntentFailed($paymentIntent): void
    {
        Log::warning('Payment intent failed', [
            'payment_intent_id' => $paymentIntent->id,
            'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);
    }
    
    /**
     * Calculate end date based on plan interval
     */
    private function calculateEndDate(Plan $plan): ?\Carbon\Carbon
    {
        if (!$plan->interval || !$plan->interval_count) {
            return null;
        }
        
        $startDate = now();
        
        return match($plan->interval) {
            'day' => $startDate->addDays($plan->interval_count),
            'week' => $startDate->addWeeks($plan->interval_count),
            'month' => $startDate->addMonths($plan->interval_count),
            'year' => $startDate->addYears($plan->interval_count),
            default => null,
        };
    }
} 