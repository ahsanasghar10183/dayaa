<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;

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
    });
});

// Campaign endpoints (protected)
Route::middleware('auth:sanctum')->prefix('campaigns')->group(function () {
    Route::get('/active', [CampaignController::class, 'getActive']);
});

// Donation endpoints (protected)
Route::middleware('auth:sanctum')->prefix('donations')->group(function () {
    Route::post('/', [DonationController::class, 'store']);
    Route::patch('/{id}/complete', [DonationController::class, 'complete']);
    Route::patch('/{id}/fail', [DonationController::class, 'fail']);
});
