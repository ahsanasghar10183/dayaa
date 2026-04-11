<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Services\StripeService;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle incoming Stripe webhooks
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe webhook invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe webhook invalid signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        try {
            $this->handleEvent($event);
        } catch (\Exception $e) {
            Log::error('Error processing Stripe webhook', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle specific webhook event types
     */
    protected function handleEvent($event): void
    {
        Log::info('Stripe webhook received', [
            'event_type' => $event->type,
            'event_id' => $event->id,
        ]);

        switch ($event->type) {
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.trial_will_end':
                $this->handleTrialWillEnd($event->data->object);
                break;

            default:
                Log::info('Unhandled webhook event type', ['type' => $event->type]);
        }
    }

    /**
     * Handle subscription created event
     */
    protected function handleSubscriptionCreated($stripeSubscription): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->update([
                'status' => $stripeSubscription->status,
                'stripe_status' => $stripeSubscription->status,
                'current_period_start' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
            ]);

            Log::info('Subscription created webhook processed', [
                'subscription_id' => $subscription->id,
                'stripe_subscription_id' => $stripeSubscription->id,
            ]);
        }
    }

    /**
     * Handle subscription updated event
     */
    protected function handleSubscriptionUpdated($stripeSubscription): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $updateData = [
                'status' => $stripeSubscription->status,
                'stripe_status' => $stripeSubscription->status,
                'current_period_start' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
            ];

            // Check if price changed (tier change applied)
            $newPriceId = $stripeSubscription->items->data[0]->price->id ?? null;
            if ($newPriceId && $newPriceId !== $subscription->stripe_price_id) {
                $updateData['stripe_price_id'] = $newPriceId;

                // Find the new tier
                $newTier = \App\Models\SubscriptionTier::where('stripe_price_id', $newPriceId)->first();
                if ($newTier) {
                    $updateData['tier_id'] = $newTier->id;
                    $updateData['pending_tier_id'] = null; // Clear pending tier

                    Log::info('Tier change detected in webhook', [
                        'subscription_id' => $subscription->id,
                        'old_tier_id' => $subscription->tier_id,
                        'new_tier_id' => $newTier->id,
                    ]);
                }
            }

            $subscription->update($updateData);

            Log::info('Subscription updated webhook processed', [
                'subscription_id' => $subscription->id,
                'status' => $stripeSubscription->status,
            ]);
        }
    }

    /**
     * Handle subscription deleted/canceled event
     */
    protected function handleSubscriptionDeleted($stripeSubscription): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'canceled',
                'stripe_status' => 'canceled',
                'canceled_at' => now(),
            ]);

            Log::warning('Subscription canceled', [
                'subscription_id' => $subscription->id,
                'organization_id' => $subscription->organization_id,
            ]);

            // TODO: Send cancellation email to organization
        }
    }

    /**
     * Handle successful invoice payment
     */
    protected function handleInvoicePaymentSucceeded($invoice): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'stripe_status' => 'active',
            ]);

            Log::info('Invoice payment succeeded', [
                'subscription_id' => $subscription->id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount_paid / 100,
            ]);

            // TODO: Send payment success email
        }
    }

    /**
     * Handle failed invoice payment
     */
    protected function handleInvoicePaymentFailed($invoice): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'past_due',
                'stripe_status' => 'past_due',
            ]);

            Log::error('Invoice payment failed', [
                'subscription_id' => $subscription->id,
                'invoice_id' => $invoice->id,
                'organization_id' => $subscription->organization_id,
            ]);

            // TODO: Send payment failed email to organization
        }
    }

    /**
     * Handle trial ending soon event
     */
    protected function handleTrialWillEnd($stripeSubscription): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            Log::info('Trial ending soon', [
                'subscription_id' => $subscription->id,
                'trial_end' => $subscription->trial_ends_at,
            ]);

            // TODO: Send trial ending email
        }
    }
}
