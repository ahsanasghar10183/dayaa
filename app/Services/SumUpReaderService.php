<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around SumUp's Cloud API reader endpoints used during the
 * pairing flow. Checkout-on-reader calls live in the Phase 3 payment service.
 *
 * Endpoints:
 *   GET    /v0.1/merchants/{merchant_code}/readers
 *   POST   /v0.1/merchants/{merchant_code}/readers
 *   DELETE /v0.1/merchants/{merchant_code}/readers/{reader_id}
 *
 * All methods return an array shaped:
 *   ['ok' => bool, 'reason' => 'rejected'|'unreachable'|'error'|null, 'error' => string|null, 'data' => mixed]
 */
class SumUpReaderService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.sumup.base_url', 'https://api.sumup.com');
    }

    public function listReaders(string $apiKey, string $merchantCode): array
    {
        return $this->call('get', "/v0.1/merchants/{$merchantCode}/readers", $apiKey);
    }

    public function createReader(string $apiKey, string $merchantCode, string $pairingCode, string $name): array
    {
        return $this->call('post', "/v0.1/merchants/{$merchantCode}/readers", $apiKey, [
            'pairing_code' => $pairingCode,
            'name' => $name,
        ]);
    }

    public function deleteReader(string $apiKey, string $merchantCode, string $readerId): array
    {
        return $this->call('delete', "/v0.1/merchants/{$merchantCode}/readers/{$readerId}", $apiKey);
    }

    private function call(string $method, string $path, string $apiKey, array $body = []): array
    {
        try {
            $request = Http::withHeaders([
                'Authorization' => 'Bearer ' . trim($apiKey),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(20);

            $response = match ($method) {
                'get' => $request->get($this->baseUrl . $path),
                'post' => $request->post($this->baseUrl . $path, $body),
                'delete' => $request->delete($this->baseUrl . $path),
            };

            if ($response->status() === 401) {
                return ['ok' => false, 'reason' => 'rejected', 'error' => 'SumUp rejected the API key. Please reconnect your SumUp account.'];
            }

            if (in_array($response->status(), [403, 503, 0], true)) {
                Log::warning('SumUp reader API unreachable (likely geo/CDN block)', [
                    'path' => $path,
                    'status' => $response->status(),
                ]);
                return ['ok' => false, 'reason' => 'unreachable', 'error' => 'Could not reach SumUp from this server (network or regional block).'];
            }

            if ($response->failed()) {
                Log::warning('SumUp reader API call failed', [
                    'path' => $path,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                $message = data_get($response->json(), 'message')
                    ?? data_get($response->json(), 'error_message')
                    ?? data_get($response->json(), 'error.message')
                    ?? 'SumUp returned an error (HTTP ' . $response->status() . ').';

                return ['ok' => false, 'reason' => 'error', 'error' => $message];
            }

            return ['ok' => true, 'reason' => null, 'error' => null, 'data' => $response->json()];
        } catch (\Throwable $e) {
            Log::error('SumUp reader API exception', ['path' => $path, 'message' => $e->getMessage()]);
            return ['ok' => false, 'reason' => 'unreachable', 'error' => 'Could not reach SumUp (' . $e->getMessage() . ').'];
        }
    }
}
