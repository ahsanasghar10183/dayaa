<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\SumUpReader;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * SumUp payment integration — Solo card reader (Cloud API).
 *
 * Endpoints used:
 *   POST   /v0.1/merchants/{merchant_code}/readers/{reader_id}/checkout    — start checkout on reader
 *   POST   /v0.1/merchants/{merchant_code}/readers/{reader_id}/terminate   — cancel checkout
 *   GET    /v0.1/me/transactions?client_transaction_id={uuid}              — fetch transaction by our reference
 *   GET    /v0.1/me/transactions/{id}                                      — fetch transaction by SumUp id
 *
 * Per-organization credentials (api_key + merchant_code) are pulled from the
 * Organization model on each call. Test mode (no real merchant) still falls
 * back to a mock so dev + CI flows aren't blocked.
 */
class SumUpService
{
    private string $baseUrl;
    private bool $testMode;

    public function __construct()
    {
        $this->baseUrl = config('services.sumup.base_url', 'https://api.sumup.com');
        $this->testMode = (bool) config('services.sumup.test_mode', true);
    }

    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * Start a Reader Checkout. Sumup pushes the request to the physical Solo
     * terminal; the cardholder taps/inserts; SumUp later POSTs a webhook AND/OR
     * we poll the Transactions API to learn the final state.
     *
     * @return array{ok: bool, reason?: string, error?: string, client_transaction_id?: string, mock?: bool}
     */
    public function initiateReaderCheckout(
        Organization $organization,
        SumUpReader $reader,
        float $amount,
        string $description,
        string $currency = 'EUR',
    ): array {
        if (!$this->organizationCredentialsReady($organization)) {
            if ($this->testMode) {
                return $this->mockInitiate($amount, $currency);
            }
            return ['ok' => false, 'reason' => 'not_configured', 'error' => 'Organization has not connected SumUp.'];
        }

        if ($reader->organization_id !== $organization->id) {
            return ['ok' => false, 'reason' => 'invalid_reader', 'error' => 'Reader does not belong to this organization.'];
        }

        $clientTransactionId = (string) Str::uuid();
        $minorAmount = (int) round($amount * 100);

        try {
            $response = Http::withHeaders($this->headers($organization))
                ->timeout(30)
                ->post(
                    $this->baseUrl . "/v0.1/merchants/{$organization->sumup_merchant_code}/readers/{$reader->sumup_reader_id}/checkout",
                    [
                        'total_amount' => [
                            'value' => $minorAmount,
                            'currency' => $currency,
                            'minor_unit' => 2,
                        ],
                        'description' => Str::limit($description, 100, ''),
                        'client_transaction_id' => $clientTransactionId,
                    ],
                );

            if ($response->status() === 401) {
                return ['ok' => false, 'reason' => 'rejected', 'error' => 'SumUp rejected the organization API key.'];
            }

            if (in_array($response->status(), [403, 503, 0], true)) {
                Log::warning('SumUp reader checkout unreachable', ['status' => $response->status()]);
                return ['ok' => false, 'reason' => 'unreachable', 'error' => 'Could not reach SumUp from this server.'];
            }

            if ($response->failed()) {
                Log::error('SumUp reader checkout failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'reader_id' => $reader->sumup_reader_id,
                ]);
                return ['ok' => false, 'reason' => 'error', 'error' => data_get($response->json(), 'message', 'SumUp returned HTTP ' . $response->status())];
            }

            return [
                'ok' => true,
                'client_transaction_id' => data_get($response->json(), 'data.client_transaction_id', $clientTransactionId),
            ];
        } catch (\Throwable $e) {
            Log::error('SumUp reader checkout exception', ['message' => $e->getMessage()]);
            return ['ok' => false, 'reason' => 'unreachable', 'error' => 'Could not reach SumUp (' . $e->getMessage() . ').'];
        }
    }

    /**
     * Cancel an in-progress checkout on the reader.
     */
    public function terminateReaderCheckout(Organization $organization, SumUpReader $reader): array
    {
        if (!$this->organizationCredentialsReady($organization)) {
            return ['ok' => false, 'reason' => 'not_configured', 'error' => 'Organization has not connected SumUp.'];
        }

        try {
            $response = Http::withHeaders($this->headers($organization))
                ->timeout(15)
                ->post($this->baseUrl . "/v0.1/merchants/{$organization->sumup_merchant_code}/readers/{$reader->sumup_reader_id}/terminate");

            if ($response->successful() || $response->status() === 204) {
                return ['ok' => true];
            }

            return ['ok' => false, 'reason' => 'error', 'error' => 'SumUp returned HTTP ' . $response->status()];
        } catch (\Throwable $e) {
            return ['ok' => false, 'reason' => 'unreachable', 'error' => $e->getMessage()];
        }
    }

    /**
     * Look up a transaction by *our* client_transaction_id. This is the call
     * that must always run before a webhook is trusted (no HMAC available).
     *
     * Returned status values mirror SumUp's: SUCCESSFUL, FAILED, CANCELLED,
     * PENDING. We also include amount + transaction_id + transaction_code.
     */
    public function getTransactionByClientId(Organization $organization, string $clientTransactionId): array
    {
        if (!$this->organizationCredentialsReady($organization)) {
            if ($this->testMode) {
                return $this->mockTransaction($clientTransactionId);
            }
            return ['ok' => false, 'reason' => 'not_configured', 'error' => 'Organization has not connected SumUp.'];
        }

        try {
            $response = Http::withHeaders($this->headers($organization))
                ->timeout(15)
                ->get($this->baseUrl . '/v0.1/me/transactions', [
                    'client_transaction_id' => $clientTransactionId,
                ]);

            if ($response->status() === 404) {
                return ['ok' => false, 'reason' => 'not_found', 'error' => 'Transaction not found.'];
            }

            if ($response->status() === 401) {
                return ['ok' => false, 'reason' => 'rejected', 'error' => 'SumUp rejected the API key.'];
            }

            if ($response->failed()) {
                return ['ok' => false, 'reason' => 'error', 'error' => 'SumUp returned HTTP ' . $response->status()];
            }

            $data = $response->json();
            // The endpoint may return a single object or an array depending on filters; normalise.
            $txn = isset($data['id']) ? $data : (is_array($data) ? ($data[0] ?? null) : null);

            if (!$txn) {
                return ['ok' => false, 'reason' => 'not_found', 'error' => 'Transaction not found.'];
            }

            return [
                'ok' => true,
                'transaction_id' => data_get($txn, 'id'),
                'transaction_code' => data_get($txn, 'transaction_code'),
                'status' => data_get($txn, 'status'),
                'amount' => data_get($txn, 'amount'),
                'currency' => data_get($txn, 'currency'),
                'client_transaction_id' => data_get($txn, 'client_transaction_id', $clientTransactionId),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'reason' => 'unreachable', 'error' => $e->getMessage()];
        }
    }

    private function organizationCredentialsReady(Organization $organization): bool
    {
        return !empty($organization->sumup_api_key) && !empty($organization->sumup_merchant_code);
    }

    private function headers(Organization $organization): array
    {
        return [
            'Authorization' => 'Bearer ' . $organization->sumup_api_key,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    private function mockInitiate(float $amount, string $currency): array
    {
        Log::info('SumUp MOCK: initiate reader checkout', ['amount' => $amount, 'currency' => $currency]);
        return [
            'ok' => true,
            'mock' => true,
            'client_transaction_id' => 'mock_' . Str::random(16),
        ];
    }

    private function mockTransaction(string $clientTransactionId): array
    {
        Log::info('SumUp MOCK: transaction lookup', ['client_transaction_id' => $clientTransactionId]);
        return [
            'ok' => true,
            'mock' => true,
            'transaction_id' => 'mock_txn_' . Str::random(12),
            'transaction_code' => 'MOCK' . random_int(1000, 9999),
            'status' => 'SUCCESSFUL',
            'amount' => 10.00,
            'currency' => 'EUR',
            'client_transaction_id' => $clientTransactionId,
        ];
    }
}
