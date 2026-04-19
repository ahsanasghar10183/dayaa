<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * SumUp Payment Integration Service
 *
 * This service handles all communication with SumUp API
 * for processing payments via SumUp Solo terminals
 */
class SumUpService
{
    private string $apiKey;
    private string $baseUrl;
    private bool $testMode;

    public function __construct()
    {
        $this->apiKey = config('services.sumup.api_key', '');
        $this->baseUrl = config('services.sumup.base_url', 'https://api.sumup.com');
        $this->testMode = config('services.sumup.test_mode', true);
    }

    /**
     * Check if SumUp is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Create a checkout session for payment
     *
     * @param float $amount Amount in EUR
     * @param string $currency Currency code (default: EUR)
     * @param string $checkoutReference Unique reference for this checkout
     * @param string|null $description Payment description
     * @return array Checkout data including checkout_id
     * @throws Exception
     */
    public function createCheckout(
        float $amount,
        string $currency = 'EUR',
        string $checkoutReference = '',
        ?string $description = null
    ): array {
        // If test mode and no credentials, return mock response
        if ($this->testMode && !$this->isConfigured()) {
            return $this->mockCreateCheckout($amount, $checkoutReference);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v0.1/checkouts', [
                'checkout_reference' => $checkoutReference,
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description ?? 'DAYAA Donation',
                'merchant_code' => config('services.sumup.merchant_code'),
            ]);

            if ($response->failed()) {
                Log::error('SumUp checkout creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Failed to create SumUp checkout: ' . $response->body());
            }

            $data = $response->json();

            Log::info('SumUp checkout created', [
                'checkout_id' => $data['id'] ?? null,
                'checkout_reference' => $checkoutReference,
            ]);

            return [
                'checkout_id' => $data['id'],
                'status' => $data['status'] ?? 'PENDING',
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'date' => $data['date'] ?? now()->toIso8601String(),
            ];
        } catch (Exception $e) {
            Log::error('SumUp checkout exception', [
                'message' => $e->getMessage(),
                'checkout_reference' => $checkoutReference,
            ]);

            throw $e;
        }
    }

    /**
     * Get checkout status
     *
     * @param string $checkoutId SumUp checkout ID
     * @return array Checkout status data
     * @throws Exception
     */
    public function getCheckoutStatus(string $checkoutId): array
    {
        // If test mode and no credentials, return mock response
        if ($this->testMode && !$this->isConfigured()) {
            return $this->mockGetCheckoutStatus($checkoutId);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/v0.1/checkouts/' . $checkoutId);

            if ($response->failed()) {
                Log::error('SumUp checkout status failed', [
                    'checkout_id' => $checkoutId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Failed to get SumUp checkout status: ' . $response->body());
            }

            $data = $response->json();

            return [
                'checkout_id' => $data['id'],
                'status' => $data['status'], // PENDING, PAID, FAILED
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'transaction_code' => $data['transaction_code'] ?? null,
                'date' => $data['date'] ?? null,
            ];
        } catch (Exception $e) {
            Log::error('SumUp checkout status exception', [
                'message' => $e->getMessage(),
                'checkout_id' => $checkoutId,
            ]);

            throw $e;
        }
    }

    /**
     * Process a payment (combines create checkout + wait for completion)
     *
     * @param float $amount Amount in EUR
     * @param string $checkoutReference Unique reference
     * @param string|null $description Payment description
     * @return array Payment result
     * @throws Exception
     */
    public function processPayment(
        float $amount,
        string $checkoutReference,
        ?string $description = null
    ): array {
        // Create checkout
        $checkout = $this->createCheckout($amount, 'EUR', $checkoutReference, $description);

        // Return checkout data for status polling
        return [
            'checkout_id' => $checkout['checkout_id'],
            'status' => 'pending',
            'polling_required' => true,
            'message' => 'Payment initiated. Please complete on terminal.',
        ];
    }

    /**
     * Verify webhook signature
     *
     * @param string $payload Webhook payload
     * @param string $signature Signature from header
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('services.sumup.webhook_secret', '');

        if (empty($secret)) {
            Log::warning('SumUp webhook secret not configured');
            return $this->testMode; // Allow in test mode
        }

        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Mock checkout creation for testing without credentials
     */
    private function mockCreateCheckout(float $amount, string $checkoutReference): array
    {
        Log::info('SumUp MOCK: Creating checkout', [
            'amount' => $amount,
            'reference' => $checkoutReference,
        ]);

        return [
            'checkout_id' => 'mock_checkout_' . uniqid(),
            'status' => 'PENDING',
            'amount' => $amount,
            'currency' => 'EUR',
            'date' => now()->toIso8601String(),
        ];
    }

    /**
     * Mock checkout status for testing without credentials
     */
    private function mockGetCheckoutStatus(string $checkoutId): array
    {
        Log::info('SumUp MOCK: Getting checkout status', [
            'checkout_id' => $checkoutId,
        ]);

        // Simulate payment completion after a few seconds
        return [
            'checkout_id' => $checkoutId,
            'status' => 'PAID', // Could be PENDING, PAID, or FAILED
            'amount' => 10.00,
            'currency' => 'EUR',
            'transaction_id' => 'mock_txn_' . uniqid(),
            'transaction_code' => 'MOCK' . rand(1000, 9999),
            'date' => now()->toIso8601String(),
        ];
    }

    /**
     * Get test mode status
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }
}
