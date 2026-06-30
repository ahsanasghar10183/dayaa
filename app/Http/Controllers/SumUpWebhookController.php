<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\SumUpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * SumUp webhook endpoint.
 *
 * SumUp does not sign webhooks (confirmed by SumUp support — no HMAC). The
 * payload alone cannot be trusted, so for every callback we look up the
 * referenced transaction via the Transactions API and treat that authoritative
 * response as the source of truth.
 */
class SumUpWebhookController extends Controller
{
    public function __construct(private SumUpService $sumupService) {}

    public function handle(Request $request): JsonResponse
    {
        Log::info('SumUp webhook received', [
            'payload' => $request->all(),
        ]);

        $payload = $request->all();
        $clientTransactionId = $payload['client_transaction_id']
            ?? $payload['payload']['client_transaction_id']
            ?? null;
        $sumupTransactionId = $payload['id']
            ?? $payload['payload']['id']
            ?? null;

        // Locate the donation. Prefer client_transaction_id (we generated it),
        // fall back to sumup_transaction_id if SumUp only sent its own id.
        $donation = null;
        if ($clientTransactionId) {
            $donation = Donation::where('client_transaction_id', $clientTransactionId)->first();
        }
        if (!$donation && $sumupTransactionId) {
            $donation = Donation::where('sumup_transaction_id', $sumupTransactionId)->first();
        }

        if (!$donation) {
            Log::warning('SumUp webhook: donation not found', [
                'client_transaction_id' => $clientTransactionId,
                'sumup_transaction_id' => $sumupTransactionId,
            ]);
            // Return 200 to avoid SumUp retry storms for legitimately unknown events.
            return response()->json(['success' => true, 'message' => 'No matching donation, ignored.']);
        }

        $organization = $donation->organization;
        if (!$organization) {
            return response()->json(['success' => false, 'message' => 'Donation has no organization.'], 422);
        }

        // Always verify via the Transactions API — the webhook itself is unsigned.
        $lookup = $this->sumupService->getTransactionByClientId(
            $organization,
            $donation->client_transaction_id ?? $clientTransactionId ?? ''
        );

        if (!$lookup['ok']) {
            Log::warning('SumUp webhook: verification failed', [
                'donation_id' => $donation->id,
                'reason' => $lookup['reason'] ?? null,
                'error' => $lookup['error'] ?? null,
            ]);
            return response()->json(['success' => false, 'message' => 'Could not verify transaction.'], 502);
        }

        $status = strtoupper((string) $lookup['status']);

        $update = ['sumup_status' => $status];

        if ($status === 'SUCCESSFUL') {
            $update['payment_status'] = 'completed';
            $update['sumup_transaction_id'] = $lookup['transaction_id'];
            $update['sumup_transaction_code'] = $lookup['transaction_code'];
        } elseif (in_array($status, ['FAILED', 'CANCELLED'], true)) {
            $update['payment_status'] = 'failed';
        }

        $donation->update($update);

        Log::info('SumUp webhook: donation updated', [
            'donation_id' => $donation->id,
            'sumup_status' => $status,
            'payment_status' => $donation->payment_status,
        ]);

        return response()->json(['success' => true]);
    }
}
