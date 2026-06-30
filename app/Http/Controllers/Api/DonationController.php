<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\DonationReceipt;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\SumUpReader;
use App\Services\SumUpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
     * Initiate SumUp Solo reader checkout for a donation.
     *
     * Required body: reader_id (id of one of the org's paired SumUp readers).
     * Returns client_transaction_id which the device will poll on.
     */
    public function initiateSumUpPayment(Request $request, $id): JsonResponse
    {
        $device = $request->user();

        $donation = Donation::where('id', $id)
            ->where('device_id', $device->id)
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found or does not belong to this device',
            ], 404);
        }

        if ($donation->payment_status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Donation already completed',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'reader_id' => 'required|integer|exists:sumup_readers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // SumUp enforces a €1 minimum charge on card readers. Reject sub-€1
        // amounts here so we don't waste a round-trip and get a generic error.
        if ((float) $donation->amount < 1.00) {
            return response()->json([
                'success' => false,
                'message' => 'SumUp requires a minimum donation of €1.00. Please choose a higher amount.',
                'reason' => 'amount_below_minimum',
            ], 422);
        }

        $organization = $donation->organization;

        if (!$organization || !$organization->isSumUpConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Organization has not connected a SumUp account.',
            ], 422);
        }

        $reader = SumUpReader::where('id', $request->reader_id)
            ->where('organization_id', $organization->id)
            ->first();

        if (!$reader) {
            return response()->json([
                'success' => false,
                'message' => 'Reader not found for this organization.',
            ], 404);
        }

        $sumupService = app(SumUpService::class);
        $result = $sumupService->initiateReaderCheckout(
            $organization,
            $reader,
            (float) $donation->amount,
            "DAYAA Donation - {$donation->campaign->name}",
            $donation->currency ?? 'EUR',
        );

        if (!$result['ok']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to initiate payment.',
                'reason' => $result['reason'] ?? null,
            ], 502);
        }

        $donation->update([
            'sumup_reader_id' => $reader->id,
            'client_transaction_id' => $result['client_transaction_id'],
            'sumup_status' => 'PENDING',
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully',
            'data' => [
                'donation_id' => $donation->id,
                'client_transaction_id' => $result['client_transaction_id'],
                'status' => 'PENDING',
                'amount' => $donation->amount,
                'polling_required' => true,
                'instruction' => 'Please complete payment on terminal',
                'mock' => $result['mock'] ?? false,
            ],
        ]);
    }

    /**
     * Poll the Transactions API for the donation's current SumUp status.
     */
    public function checkPaymentStatus(Request $request, $id): JsonResponse
    {
        $device = $request->user();

        $donation = Donation::where('id', $id)
            ->where('device_id', $device->id)
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found or does not belong to this device',
            ], 404);
        }

        if (in_array($donation->payment_status, ['completed', 'failed'])) {
            return response()->json([
                'success' => true,
                'data' => [
                    'donation_id' => $donation->id,
                    'payment_status' => $donation->payment_status,
                    'sumup_status' => $donation->sumup_status,
                    'sumup_transaction_id' => $donation->sumup_transaction_id,
                    'sumup_transaction_code' => $donation->sumup_transaction_code,
                ],
            ]);
        }

        if (!$donation->client_transaction_id) {
            return response()->json([
                'success' => false,
                'message' => 'No active SumUp checkout for this donation.',
            ], 400);
        }

        $organization = $donation->organization;
        if (!$organization) {
            return response()->json(['success' => false, 'message' => 'Organization not found.'], 404);
        }

        $result = app(SumUpService::class)
            ->getTransactionByClientId($organization, $donation->client_transaction_id);

        if (!$result['ok']) {
            // Not-found is normal while the cardholder is still tapping; surface as still pending.
            if (($result['reason'] ?? null) === 'not_found') {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'donation_id' => $donation->id,
                        'payment_status' => $donation->payment_status,
                        'sumup_status' => 'PENDING',
                    ],
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to check payment status.',
            ], 502);
        }

        $sumupStatus = strtoupper((string) $result['status']);

        $update = ['sumup_status' => $sumupStatus];

        if ($sumupStatus === 'SUCCESSFUL') {
            $update['payment_status'] = 'completed';
            $update['sumup_transaction_id'] = $result['transaction_id'];
            $update['sumup_transaction_code'] = $result['transaction_code'];
        } elseif (in_array($sumupStatus, ['FAILED', 'CANCELLED'], true)) {
            $update['payment_status'] = 'failed';
        }

        $donation->update($update);

        return response()->json([
            'success' => true,
            'data' => [
                'donation_id' => $donation->id,
                'payment_status' => $donation->payment_status,
                'sumup_status' => $sumupStatus,
                'sumup_transaction_id' => $donation->sumup_transaction_id,
                'sumup_transaction_code' => $donation->sumup_transaction_code,
                'mock' => $result['mock'] ?? false,
            ],
        ]);
    }

    /**
     * Cancel an in-progress SumUp checkout on the paired reader.
     */
    public function cancelSumUpPayment(Request $request, $id): JsonResponse
    {
        $device = $request->user();

        $donation = Donation::with('reader')
            ->where('id', $id)
            ->where('device_id', $device->id)
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found or does not belong to this device',
            ], 404);
        }

        if (in_array($donation->payment_status, ['completed', 'failed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Donation is already ' . $donation->payment_status . '.',
            ], 400);
        }

        $organization = $donation->organization;
        $reader = $donation->reader;

        if ($organization && $reader) {
            $result = app(SumUpService::class)->terminateReaderCheckout($organization, $reader);
            if (!$result['ok'] && ($result['reason'] ?? null) === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Could not cancel checkout.',
                ], 502);
            }
        }

        $donation->update([
            'payment_status' => 'failed',
            'sumup_status' => 'CANCELLED',
            'notes' => trim(($donation->notes ? $donation->notes . ' | ' : '') . 'Cancelled by device.'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment cancelled.',
            'data' => [
                'donation_id' => $donation->id,
                'payment_status' => $donation->payment_status,
                'sumup_status' => $donation->sumup_status,
            ],
        ]);
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

    /**
     * Send donation receipt via email
     */
    public function sendReceipt(Request $request, $id): JsonResponse
    {
        $device = $request->user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $donation = Donation::with(['campaign', 'organization'])
            ->where('id', $id)
            ->where('device_id', $device->id)
            ->where('payment_status', 'completed')
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found, does not belong to this device, or is not completed'
            ], 404);
        }

        try {
            // Send the receipt email
            Mail::to($request->email)->send(new DonationReceipt($donation));

            // Update donation with email if not already set
            if (!$donation->donor_email) {
                $donation->update(['donor_email' => $request->email]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Receipt sent successfully',
                'data' => [
                    'email' => $request->email,
                    'receipt_number' => $donation->receipt_number,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send receipt',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
