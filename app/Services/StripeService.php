<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Invoice;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        // Initialize Stripe with secret key
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe customer for an organization
     */
    public function createCustomer(string $email, string $name, array $metadata = []): Customer
    {
        try {
            return Customer::create([
                'email' => $email,
                'name' => $name,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe: Error creating customer', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a setup intent for card collection
     */
    public function createSetupIntent(string $customerId): SetupIntent
    {
        try {
            return SetupIntent::create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe: Error creating setup intent', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Attach payment method to customer
     */
    public function attachPaymentMethod(string $paymentMethodId, string $customerId): PaymentMethod
    {
        try {
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
            $paymentMethod->attach(['customer' => $customerId]);
            
            // Set as default payment method
            Customer::update($customerId, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);

            return $paymentMethod;
        } catch (\Exception $e) {
            Log::error('Stripe: Error attaching payment method', [
                'payment_method_id' => $paymentMethodId,
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a subscription
     */
    public function createSubscription(string $customerId, string $priceId, array $metadata = [], int $trialPeriodDays = null): Subscription
    {
        try {
            $subscriptionData = [
                'customer' => $customerId,
                'items' => [
                    ['price' => $priceId],
                ],
                'metadata' => $metadata,
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => [
                    'save_default_payment_method' => 'on_subscription',
                ],
                'expand' => ['latest_invoice.payment_intent'],
            ];

            // Add trial period if specified
            if ($trialPeriodDays !== null) {
                $subscriptionData['trial_period_days'] = $trialPeriodDays;
            }

            return Subscription::create($subscriptionData);
        } catch (\Exception $e) {
            Log::error('Stripe: Error creating subscription', [
                'customer_id' => $customerId,
                'price_id' => $priceId,
                'trial_period_days' => $trialPeriodDays,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update subscription price (for tier changes)
     */
    public function updateSubscriptionPrice(string $subscriptionId, string $newPriceId): Subscription
    {
        try {
            $subscription = Subscription::retrieve($subscriptionId);

            return Subscription::update($subscriptionId, [
                'items' => [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'price' => $newPriceId,
                    ],
                ],
                'proration_behavior' => 'always_invoice',
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe: Error updating subscription price', [
                'subscription_id' => $subscriptionId,
                'new_price_id' => $newPriceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get subscription details
     */
    public function getSubscription(string $subscriptionId): Subscription
    {
        try {
            return Subscription::retrieve($subscriptionId);
        } catch (\Exception $e) {
            Log::error('Stripe: Error retrieving subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(string $subscriptionId, bool $immediately = false): Subscription
    {
        try {
            if ($immediately) {
                return Subscription::update($subscriptionId, [
                    'cancel_at_period_end' => false,
                ]);
            }

            return Subscription::update($subscriptionId, [
                'cancel_at_period_end' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe: Error canceling subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Reactivate canceled subscription
     */
    public function reactivateSubscription(string $subscriptionId): Subscription
    {
        try {
            return Subscription::update($subscriptionId, [
                'cancel_at_period_end' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe: Error reactivating subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get customer invoices
     */
    public function getCustomerInvoices(string $customerId, int $limit = 12): array
    {
        try {
            $invoices = Invoice::all([
                'customer' => $customerId,
                'limit' => $limit,
            ]);

            return $invoices->data;
        } catch (\Exception $e) {
            Log::error('Stripe: Error retrieving invoices', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get invoice details
     */
    public function getInvoice(string $invoiceId): Invoice
    {
        try {
            return Invoice::retrieve($invoiceId);
        } catch (\Exception $e) {
            Log::error('Stripe: Error retrieving invoice', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): \Stripe\Event
    {
        try {
            return \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            Log::error('Stripe: Webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
