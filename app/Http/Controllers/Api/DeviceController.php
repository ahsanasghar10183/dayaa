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
            'pin' => 'required|string|size:4',
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

        // Verify PIN (last 4 characters of device_id)
        $expectedPin = substr($device->device_id, -4);
        if ($request->pin !== $expectedPin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please check your device ID and PIN.'
            ], 401);
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

        // Update device info
        $device->update([
            'status' => 'online',
            'last_active' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device paired successfully',
            'data' => [
                'token' => $token,
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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device unpaired successfully'
        ]);
    }
}
