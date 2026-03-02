<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DevicePairingController extends Controller
{
    /**
     * Pair a device using its device_id and pairing PIN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pair(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'pairing_pin' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::where('device_id', $request->device_id)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found. Please check your Device ID.'
            ], 404);
        }

        // Check if device is already paired
        if ($device->is_paired) {
            return response()->json([
                'success' => false,
                'message' => 'Device is already paired. Please unpair it first or contact support.'
            ], 400);
        }

        // Validate pairing PIN
        if ($device->pairing_pin !== $request->pairing_pin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid pairing PIN. Please check your PIN and try again.'
            ], 401);
        }

        // Check if PIN has expired
        if (!$device->isPairingPinValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Pairing PIN has expired. Please generate a new PIN from the dashboard.'
            ], 401);
        }

        // Generate API token for the device
        $token = Str::random(64);
        $device->update([
            'api_token' => hash('sha256', $token),
            'last_active' => now(),
        ]);

        // Mark device as paired
        $device->markAsPaired();

        return response()->json([
            'success' => true,
            'message' => 'Device paired successfully',
            'data' => [
                'device_id' => $device->device_id,
                'device_name' => $device->name,
                'organization' => $device->organization->name,
                'status' => $device->status,
                'api_token' => $token, // Return plain token (only time it's visible)
            ]
        ]);
    }

    /**
     * Send heartbeat to update device status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function heartbeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'api_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::where('device_id', $request->device_id)
            ->where('api_token', hash('sha256', $request->api_token))
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device credentials'
            ], 401);
        }

        // Update last active and set online if not in maintenance
        if ($device->status != 'maintenance') {
            $device->status = 'online';
        }
        $device->last_active = now();
        $device->save();

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
            'data' => [
                'status' => $device->status,
                'last_active' => $device->last_active,
            ]
        ]);
    }

    /**
     * Get device status and configuration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'api_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::where('device_id', $request->device_id)
            ->where('api_token', hash('sha256', $request->api_token))
            ->with(['organization', 'campaigns' => function ($query) {
                $query->where('status', 'active');
            }])
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device credentials'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'device' => [
                    'id' => $device->device_id,
                    'name' => $device->name,
                    'location' => $device->location,
                    'status' => $device->status,
                    'organization' => [
                        'name' => $device->organization->name,
                        'email' => $device->organization->email,
                    ],
                ],
                'campaigns' => $device->campaigns->map(function ($campaign) {
                    return [
                        'id' => $campaign->id,
                        'name' => $campaign->name,
                        'type' => $campaign->type,
                        'reference_code' => $campaign->reference_code,
                        'design_settings' => $campaign->design_settings,
                        'amount_settings' => $campaign->amount_settings,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Get active campaigns for the device
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCampaigns(Request $request)
    {
        $device = $request->user('device');

        $campaigns = $device->campaigns()
            ->where('status', 'active')
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'type' => $campaign->type,
                    'reference_code' => $campaign->reference_code,
                    'design_settings' => $campaign->design_settings,
                    'amount_settings' => $campaign->amount_settings,
                    'thankyou_settings' => [
                        'message' => $campaign->thankyou_message,
                        'subtitle' => $campaign->thankyou_subtitle,
                        'image' => $campaign->thankyou_image ? asset('storage/' . $campaign->thankyou_image) : null,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Record a donation from the device
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordDonation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:card,cash,nfc',
            'transaction_id' => 'nullable|string',
            'donor_email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $device = $request->user('device');

        // Verify campaign is assigned to this device
        if (!$device->campaigns()->where('campaigns.id', $request->campaign_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not assigned to this device'
            ], 403);
        }

        $campaign = Campaign::find($request->campaign_id);

        // Create donation record
        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $campaign->organization_id,
            'device_id' => $device->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'success', // For physical device donations
            'transaction_id' => $request->transaction_id ?? Str::uuid(),
            'donor_email' => $request->donor_email,
            'donor_name' => $request->donor_name ?? 'Anonymous',
            'anonymous' => $request->donor_email ? false : true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donation recorded successfully',
            'data' => [
                'donation_id' => $donation->id,
                'transaction_id' => $donation->transaction_id,
                'amount' => $donation->amount,
                'campaign' => $campaign->name,
            ]
        ]);
    }
}
