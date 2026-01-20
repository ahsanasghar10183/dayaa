<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DevicePairingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('device')->group(function () {
    // Device pairing endpoints
    Route::post('/pair', [DevicePairingController::class, 'pair']);
    Route::post('/heartbeat', [DevicePairingController::class, 'heartbeat']);
    Route::post('/status', [DevicePairingController::class, 'status']);

    // Protected device endpoints (requires device authentication)
    Route::middleware('auth:device')->group(function () {
        Route::get('/campaigns', [DevicePairingController::class, 'getCampaigns']);
        Route::post('/donation/record', [DevicePairingController::class, 'recordDonation']);
    });
});
