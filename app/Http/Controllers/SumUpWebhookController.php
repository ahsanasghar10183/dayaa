<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\SumUpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * SumUp Webhook Controller
 *
 * Handles webhooks from SumUp for payment events
 */
class SumUpWebhookController extends Controller
{
    private SumUpService $sumupService;

    public function __construct(SumUpService $sumupService)
    {
        $this->sumupService = $sumupService;
    }

    /**
     * Handle SumUp webhook
     *
     * Events:
     * - payment.successful
     * - payment.failed
     * - payment.refunded
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('SumUp webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // Get signature from header
        $signature = $request->header('X-Sumup-Signature', '');
        $payload = $request->getContent();

        // Verify signature (skip in test mode)
        if (!$this->sumupService->isTestMode()) {
            if (!$this->sumupService->verifyWebhookSignature($payload, $signature)) {
                Log::warning('SumUp webhook signature verification failed');

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid signature'
                ], 401);
            }
        }

        // Parse webhook data
        $data = $request->all();
        $eventType = $data['event_type'] ?? '';
        $checkoutReference = $data['checkout_reference'] ?? '';
        $transactionId = $data['transaction_id'] ?? '';
        $transactionCode = $data['transaction_code'] ?? '';
        $amount = $data['amount'] ?? 0;
        $currency = $data['currency'] ?? 'EUR';

        // Find donation by checkout reference (which is our donation ID)
        $donation = Donation::find($checkoutReference);

        if (!$donation) {
            Log::warning('SumUp webhook: Donation not found', [
                'checkout_reference' => $checkoutReference,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Donation not found'
            ], 404);
        }

        // Handle different event types
        switch ($eventType) {
            case 'payment.successful':
                $this->handlePaymentSuccessful($donation, $transactionId, $transactionCode);
                break;

            case 'payment.failed':
                $this->handlePaymentFailed($donation, $data['failure_reason'] ?? 'Unknown error');
                break;

            case 'payment.refunded':
                $this->handlePaymentRefunded($donation, $data);
                break;

            default:
                Log::info('SumUp webhook: Unknown event type', [
                    'event_type' => $eventType,
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully'
        ]);
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSuccessful(
        Donation $donation,
        string $transactionId,
        string $transactionCode
    ): void {
        Log::info('SumUp payment successful', [
            'donation_id' => $donation->id,
            'transaction_id' => $transactionId,
        ]);

        $donation->update([
            'payment_status' => 'completed',
            'sumup_transaction_id' => $transactionId,
            'sumup_transaction_code' => $transactionCode,
        ]);

        // TODO: Send confirmation email if donor email provided
        // TODO: Trigger any post-payment workflows
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailed(Donation $donation, string $reason): void
    {
        Log::warning('SumUp payment failed', [
            'donation_id' => $donation->id,
            'reason' => $reason,
        ]);

        $donation->update([
            'payment_status' => 'failed',
            'notes' => 'Payment failed: ' . $reason,
        ]);
    }

    /**
     * Handle refunded payment
     */
    private function handlePaymentRefunded(Donation $donation, array $data): void
    {
        Log::info('SumUp payment refunded', [
            'donation_id' => $donation->id,
            'refund_data' => $data,
        ]);

        $donation->update([
            'payment_status' => 'refunded',
            'notes' => 'Payment refunded via SumUp',
        ]);

        // TODO: Notify organization about refund
    }
}
