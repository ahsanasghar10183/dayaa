<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Pair a device and generate Sanctum token
     */
    public function pair(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|exists:devices,device_id',
            'pairing_pin' => 'required|string|size:6',
            'device_type' => 'nullable|string|in:ipad,android_tablet',
            'os_version' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::where('device_id', $request->device_id)->first();

        // Check if pairing PIN exists
        if (!$device->pairing_pin) {
            return response()->json([
                'success' => false,
                'message' => 'No pairing PIN generated. Please generate a new PIN from the dashboard.'
            ], 400);
        }

        // Check if pairing PIN has expired
        if ($device->pairing_pin_expires_at && $device->pairing_pin_expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Pairing PIN has expired. Please generate a new PIN from the dashboard.'
            ], 400);
        }

        // Verify pairing PIN from database
        if ($request->pairing_pin !== $device->pairing_pin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid pairing PIN. Please check your PIN and try again.'
            ], 401);
        }

        // Check if device is already paired
        if ($device->is_paired) {
            return response()->json([
                'success' => false,
                'message' => 'Device is already paired. Please unpair first or generate a new PIN.'
            ], 400);
        }

        // Check if device is active
        if ($device->status === 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Device is inactive. Please contact your administrator.'
            ], 403);
        }

        // Revoke all existing tokens for this device
        $device->tokens()->delete();

        // Generate new Sanctum token
        $token = $device->createToken('kiosk-app', ['device:access'])->plainTextToken;

        // Update device info - mark as paired
        $device->update([
            'status' => 'online',
            'last_active' => now(),
            'is_paired' => true,
            'paired_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device paired successfully',
            'data' => [
                'api_token' => $token,
                'device_name' => $device->name,
                'device_id' => $device->device_id,
                'organization' => $device->organization->name,
                'status' => $device->status,
                'device' => [
                    'id' => $device->id,
                    'device_id' => $device->device_id,
                    'name' => $device->name,
                    'location' => $device->location,
                    'status' => $device->status,
                    'organization' => [
                        'id' => $device->organization->id,
                        'name' => $device->organization->name,
                    ],
                ],
            ]
        ]);
    }

    /**
     * Send heartbeat to update device status
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $device = $request->user();

        $validator = Validator::make($request->all(), [
            'ip_address' => 'nullable|ip',
            'battery_level' => 'nullable|integer|min:0|max:100',
            'storage_available' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update device status
        if ($device->status !== 'maintenance') {
            $device->status = 'online';
        }
        $device->last_active = now();
        $device->save();

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
            'data' => [
                'status' => $device->status,
                'last_active' => $device->last_active->toIso8601String(),
            ]
        ]);
    }

    /**
     * Unpair device and revoke token
     */
    public function unpair(Request $request): JsonResponse
    {
        $device = $request->user();

        // Revoke all tokens for this device
        $device->tokens()->delete();

        // Update device status
        $device->update([
            'status' => 'offline',
            'is_paired' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device unpaired successfully'
        ]);
    }

    /**
     * Get device statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $device = $request->user();

        // Get all completed donations for this device
        $donations = $device->donations()->where('payment_status', 'completed');

        $stats = [
            'today_donations' => (clone $donations)->whereDate('created_at', today())->count(),
            'today_amount' => (clone $donations)->whereDate('created_at', today())->sum('amount'),
            'total_donations' => (clone $donations)->count(),
            'total_amount' => (clone $donations)->sum('amount'),
            'this_month_donations' => (clone $donations)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_amount' => (clone $donations)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get recent donations for this device
     */
    public function donations(Request $request): JsonResponse
    {
        $device = $request->user();

        $limit = $request->input('limit', 50);
        $status = $request->input('status'); // completed, failed, pending

        $query = $device->donations()
            ->with('campaign:id,name')
            ->latest();

        if ($status) {
            $query->where('payment_status', $status);
        }

        $donations = $query->limit($limit)->get()->map(function ($donation) {
            return [
                'id' => $donation->id,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'payment_status' => $donation->payment_status,
                'payment_method' => $donation->payment_method,
                'receipt_number' => $donation->receipt_number,
                'campaign' => [
                    'id' => $donation->campaign->id,
                    'name' => $donation->campaign->name,
                ],
                'created_at' => $donation->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $donations
        ]);
    }
}
