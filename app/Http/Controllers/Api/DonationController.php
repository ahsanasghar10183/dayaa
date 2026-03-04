<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    /**
     * Create a new donation
     */
    public function store(Request $request): JsonResponse
    {
        $device = $request->user();

        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'payment_method' => 'required|string|in:sumup,cash,card',
            'payment_status' => 'nullable|string|in:pending,completed,failed',
            'donor_email' => 'nullable|email',
            'donor_name' => 'nullable|string|max:255',
            'donor_phone' => 'nullable|string|max:50',
            'anonymous' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify campaign is assigned to this device
        $campaign = Campaign::find($request->campaign_id);
        if (!$device->campaigns()->where('campaigns.id', $campaign->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not assigned to this device'
            ], 403);
        }

        // Generate receipt number
        $receiptNumber = 'DYA-' . now()->format('Ymd') . '-' . Str::random(6);

        // Create donation
        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $campaign->organization_id,
            'device_id' => $device->id,
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'EUR',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status ?? 'pending',
            'receipt_number' => $receiptNumber,
            'donor_email' => $request->donor_email,
            'donor_name' => $request->donor_name ?? 'Anonymous',
            'donor_phone' => $request->donor_phone,
            'anonymous' => $request->anonymous ?? ($request->donor_email ? false : true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donation created successfully',
            'data' => [
                'id' => $donation->id,
                'receipt_number' => $donation->receipt_number,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'payment_status' => $donation->payment_status,
                'campaign' => [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                ],
                'created_at' => $donation->created_at->toIso8601String(),
            ]
        ], 201);
    }

    /**
     * Mark donation as completed
     */
    public function complete(Request $request, $id): JsonResponse
    {
        $device = $request->user();

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'nullable|string',
            'sumup_transaction_id' => 'nullable|string',
            'sumup_transaction_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $donation = Donation::where('id', $id)
            ->where('device_id', $device->id)
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found or does not belong to this device'
            ], 404);
        }

        // Support both generic transaction_id and SumUp-specific fields
        $updateData = [
            'payment_status' => 'completed',
        ];

        // If SumUp fields provided, use those
        if ($request->has('sumup_transaction_id')) {
            $updateData['sumup_transaction_id'] = $request->sumup_transaction_id;
        }

        if ($request->has('sumup_transaction_code')) {
            $updateData['sumup_transaction_code'] = $request->sumup_transaction_code;
        }

        // Otherwise, use generic transaction_id
        if ($request->has('transaction_id')) {
            $updateData['transaction_id'] = $request->transaction_id;
        }

        $donation->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Donation marked as completed',
            'data' => [
                'id' => $donation->id,
                'payment_status' => $donation->payment_status,
                'sumup_transaction_id' => $donation->sumup_transaction_id,
                'updated_at' => $donation->updated_at->toIso8601String(),
            ]
        ]);
    }

    /**
     * Mark donation as failed
     */
    public function fail(Request $request, $id): JsonResponse
    {
        $device = $request->user();

        $validator = Validator::make($request->all(), [
            'failure_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $donation = Donation::where('id', $id)
            ->where('device_id', $device->id)
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found or does not belong to this device'
            ], 404);
        }

        $donation->update([
            'payment_status' => 'failed',
            'notes' => $request->failure_reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donation marked as failed',
            'data' => [
                'id' => $donation->id,
                'payment_status' => $donation->payment_status,
                'updated_at' => $donation->updated_at->toIso8601String(),
            ]
        ]);
    }
}
