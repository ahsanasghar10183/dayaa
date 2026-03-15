<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook.secret');

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            default:
                // Unhandled event type
                \Log::info('Received unhandled Stripe webhook event: ' . $event->type);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle checkout.session.completed event
     */
    protected function handleCheckoutSessionCompleted($session)
    {
        $orderNumber = $session->metadata->order_number ?? null;

        if (!$orderNumber) {
            \Log::warning('Stripe webhook: No order_number in session metadata');
            return;
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            \Log::warning("Stripe webhook: Order not found: {$orderNumber}");
            return;
        }

        // Update order payment status
        $order->update([
            'payment_status' => 'completed',
            'stripe_payment_intent' => $session->payment_intent,
        ]);

        \Log::info("Stripe webhook: Order {$orderNumber} marked as completed");
    }

    /**
     * Handle payment_intent.succeeded event
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Find order by payment intent ID
        $order = Order::where('stripe_payment_intent', $paymentIntent->id)->first();

        if ($order && $order->payment_status !== 'completed') {
            $order->update([
                'payment_status' => 'completed',
            ]);

            \Log::info("Stripe webhook: Payment succeeded for order {$order->order_number}");
        }
    }

    /**
     * Handle payment_intent.payment_failed event
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        // Find order by payment intent ID
        $order = Order::where('stripe_payment_intent', $paymentIntent->id)->first();

        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'order_status' => 'failed',
            ]);

            \Log::info("Stripe webhook: Payment failed for order {$order->order_number}");
        }
    }
}
