<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Device endpoints
Route::prefix('devices')->group(function () {
    // Public: Pairing
    Route::post('/pair', [DeviceController::class, 'pair']);

    // Protected: Requires Sanctum token
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/heartbeat', [DeviceController::class, 'heartbeat']);
        Route::post('/unpair', [DeviceController::class, 'unpair']);
        Route::get('/stats', [DeviceController::class, 'stats']);
        Route::get('/donations', [DeviceController::class, 'donations']);
    });
});

// Campaign endpoints (protected)
Route::middleware('auth:sanctum')->prefix('campaigns')->group(function () {
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/active', [CampaignController::class, 'getActive']);
});

// Donation endpoints (protected)
Route::middleware('auth:sanctum')->prefix('donations')->group(function () {
    Route::post('/', [DonationController::class, 'store']);
    Route::post('/{id}/sumup/initiate', [DonationController::class, 'initiateSumUpPayment']);
    Route::get('/{id}/sumup/status', [DonationController::class, 'checkPaymentStatus']);
    Route::patch('/{id}/complete', [DonationController::class, 'complete']);
    Route::patch('/{id}/fail', [DonationController::class, 'fail']);
    Route::post('/{id}/send-receipt', [DonationController::class, 'sendReceipt']);
});

// Stripe Webhooks (must be outside auth middleware)
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);
