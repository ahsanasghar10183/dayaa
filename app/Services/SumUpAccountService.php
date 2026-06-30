<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Lightweight service for validating per-organization SumUp API keys
 * by calling GET /v0.1/me. The full Cloud API integration (Reader
 * Checkout, Webhooks, Polling) is implemented in Phase 3 separately.
 */
class SumUpAccountService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.sumup.base_url', 'https://api.sumup.com');
    }

    /**
     * Validate an API key by fetching the merchant profile.
     *
     * Outcomes:
     *   - ok=true                  → key verified online with SumUp
     *   - ok=false, unreachable    → could not reach SumUp (geo-block / network); caller may fall back to manual merchant_code
     *   - ok=false, rejected       → SumUp says the key is invalid (401)
     *   - ok=false, error          → any other failure
     *
     * @return array{ok: bool, merchant_code?: string, merchant_name?: string, error?: string, reason?: 'unreachable'|'rejected'|'error'}
     */
    public function verifyApiKey(string $apiKey): array
    {
        $apiKey = trim($apiKey);

        if ($apiKey === '') {
            return ['ok' => false, 'reason' => 'error', 'error' => 'API key is empty.'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->timeout(15)->get($this->baseUrl . '/v0.1/me');

            if ($response->status() === 401) {
                return [
                    'ok' => false,
                    'reason' => 'rejected',
                    'error' => 'SumUp rejected the API key. Please make sure you copied the full secret key (starts with sup_sk_).',
                ];
            }

            // 403 from SumUp is most often a Cloudflare geo/IP block, not an invalid key.
            // Treat it as unreachable so the caller can offer a manual-fallback path.
            if ($response->status() === 403 || $response->status() === 503 || $response->status() === 0) {
                Log::warning('SumUp /me unreachable (likely geo/CDN block)', [
                    'status' => $response->status(),
                ]);
                return [
                    'ok' => false,
                    'reason' => 'unreachable',
                    'error' => 'We could not reach SumUp from this server (network or regional block). The API key was not verified online.',
                ];
            }

            if ($response->failed()) {
                Log::warning('SumUp /me call failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [
                    'ok' => false,
                    'reason' => 'error',
                    'error' => 'SumUp returned an error (HTTP ' . $response->status() . '). Please try again.',
                ];
            }

            $data = $response->json();
            $merchantCode = data_get($data, 'merchant_profile.merchant_code') ?? data_get($data, 'merchant_code');
            $merchantName = data_get($data, 'merchant_profile.company_name')
                ?? data_get($data, 'merchant_profile.merchant_name')
                ?? data_get($data, 'personal_profile.first_name');

            if (!$merchantCode) {
                return ['ok' => false, 'reason' => 'error', 'error' => 'Could not read merchant code from SumUp response.'];
            }

            return [
                'ok' => true,
                'merchant_code' => $merchantCode,
                'merchant_name' => $merchantName,
            ];
        } catch (\Throwable $e) {
            Log::error('SumUp /me exception', ['message' => $e->getMessage()]);
            return [
                'ok' => false,
                'reason' => 'unreachable',
                'error' => 'Could not reach SumUp (' . $e->getMessage() . '). The API key was not verified online.',
            ];
        }
    }
}
