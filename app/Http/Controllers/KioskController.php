<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Device;
use App\Models\Donation;
use App\Models\SumUpReader;
use App\Services\SumUpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KioskController extends Controller
{
    /**
     * Show the kiosk pairing page
     */
    public function pair()
    {
        return view('kiosk.pair');
    }

    /**
     * Process device pairing
     */
    public function processPair(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
        ]);

        $device = Device::where('device_id', $request->device_id)->first();

        if (!$device) {
            return back()->with('error', 'Invalid Device ID. Please check and try again.');
        }

        // Store device in session
        Session::put('kiosk_device_id', $device->id);
        Session::put('kiosk_device', $device);

        // Update device status and last active
        if ($device->status !== 'maintenance') {
            $device->update([
                'status' => 'online',
                'last_active' => now(),
            ]);
        }

        return redirect()->route('kiosk.display');
    }

    /**
     * Display the main kiosk interface
     */
    public function display(Request $request)
    {
        $deviceId = Session::get('kiosk_device_id');

        if (!$deviceId) {
            return redirect()->route('kiosk.pair')->with('error', 'Please pair your device first.');
        }

        $device = Device::with(['campaigns' => function ($query) {
            $query->where('status', 'active');
        }])->find($deviceId);

        if (!$device) {
            Session::forget(['kiosk_device_id', 'kiosk_device']);
            return redirect()->route('kiosk.pair')->with('error', 'Device not found.');
        }

        // Update last active
        $device->update(['last_active' => now()]);

        // Get the active campaign (first one if multiple)
        $campaign = $device->campaigns->first();

        if (!$campaign) {
            return view('kiosk.no-campaign', compact('device'));
        }

        return view('kiosk.display', compact('device', 'campaign'));
    }

    /**
     * API endpoint to get current campaign data
     */
    public function getCampaign(Request $request)
    {
        $deviceId = Session::get('kiosk_device_id');

        if (!$deviceId) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $device = Device::with(['campaigns' => function ($query) {
            $query->where('status', 'active');
        }])->find($deviceId);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        // Update last active
        $device->update(['last_active' => now()]);

        $campaign = $device->campaigns->first();

        if (!$campaign) {
            return response()->json(['campaign' => null]);
        }

        return response()->json([
            'campaign' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'type' => $campaign->type,
                'design_settings' => $campaign->design_settings,
                'amount_settings' => $campaign->amount_settings,
                'thankyou_message' => $campaign->thankyou_message,
                'thankyou_subtitle' => $campaign->thankyou_subtitle,
                'thankyou_image' => $campaign->thankyou_image ? asset('storage/' . $campaign->thankyou_image) : null,
            ],
            'device_status' => $device->status,
        ]);
    }

    /**
     * Heartbeat endpoint
     */
    public function heartbeat(Request $request)
    {
        $deviceId = Session::get('kiosk_device_id');

        if (!$deviceId) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $device = Device::find($deviceId);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        // Update last active
        $device->update(['last_active' => now()]);

        return response()->json([
            'status' => 'ok',
            'device_status' => $device->status,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Unpair device
     */
    public function unpair(Request $request)
    {
        $deviceId = Session::get('kiosk_device_id');

        if ($deviceId) {
            $device = Device::find($deviceId);
            if ($device) {
                $device->update(['status' => 'offline']);
            }
        }

        Session::forget(['kiosk_device_id', 'kiosk_device']);

        return redirect()->route('kiosk.pair')->with('success', 'Device unpaired successfully.');
    }

    /**
     * Create a donation record and kick off a SumUp Solo reader checkout.
     *
     * Mirrors Api\DonationController::store + initiateSumUpPayment, but uses the
     * kiosk Session-based device auth instead of Sanctum.
     */
    public function initiatePayment(Request $request)
    {
        $deviceId = Session::get('kiosk_device_id');
        if (!$deviceId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $device = Device::find($deviceId);
        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Device not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $campaign = Campaign::find($request->campaign_id);
        if (!$device->campaigns()->where('campaigns.id', $campaign->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not assigned to this device',
            ], 403);
        }

        $organization = $campaign->organization;
        if (!$organization || !$organization->isSumUpConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Organization has not connected a SumUp account.',
                'reason' => 'no_sumup',
            ], 422);
        }

        // Pick the first paired reader for this organization. The kiosk model
        // assumes one terminal per device location; if multi-reader selection
        // is ever needed, the request can pass reader_id explicitly.
        $reader = SumUpReader::where('organization_id', $organization->id)
            ->orderBy('id')
            ->first();

        if (!$reader) {
            return response()->json([
                'success' => false,
                'message' => 'No SumUp Solo terminal paired to this organization.',
                'reason' => 'no_reader',
            ], 422);
        }

        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $organization->id,
            'device_id' => $device->id,
            'amount' => $request->amount,
            'currency' => 'EUR',
            'payment_method' => 'sumup',
            'payment_status' => 'pending',
            'receipt_number' => 'DYA-' . now()->format('Ymd') . '-' . Str::random(6),
            'donor_name' => 'Anonymous',
            'anonymous' => true,
        ]);

        $result = app(SumUpService::class)->initiateReaderCheckout(
            $organization,
            $reader,
            (float) $donation->amount,
            "DAYAA Donation - {$campaign->name}",
            $donation->currency ?? 'EUR',
        );

        if (!$result['ok']) {
            $donation->update([
                'payment_status' => 'failed',
                'notes' => 'Initiate failed: ' . ($result['error'] ?? 'unknown'),
            ]);

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
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'donation_id' => $donation->id,
                'client_transaction_id' => $result['client_transaction_id'],
                'amount' => $donation->amount,
                'status' => 'PENDING',
                'mock' => $result['mock'] ?? false,
            ],
        ]);
    }

    /**
     * Poll SumUp for the current status of a donation's checkout.
     */
    public function paymentStatus(Request $request, $donationId)
    {
        $deviceId = Session::get('kiosk_device_id');
        if (!$deviceId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $donation = Donation::where('id', $donationId)
            ->where('device_id', $deviceId)
            ->first();

        if (!$donation) {
            return response()->json(['success' => false, 'message' => 'Donation not found'], 404);
        }

        // Already terminal — return what we have.
        if (in_array($donation->payment_status, ['completed', 'failed'], true)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'donation_id' => $donation->id,
                    'payment_status' => $donation->payment_status,
                    'sumup_status' => $donation->sumup_status,
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
            // Cardholder is still tapping — keep the front-end polling.
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
                'mock' => $result['mock'] ?? false,
            ],
        ]);
    }

    /**
     * Render the kiosk thank-you screen after a successful donation.
     */
    public function thankYou(Request $request)
    {
        $deviceId = Session::get('kiosk_device_id');
        if (!$deviceId) {
            return redirect()->route('kiosk.pair');
        }

        $donation = null;
        if ($request->filled('donation')) {
            $donation = Donation::with('campaign')
                ->where('id', $request->query('donation'))
                ->where('device_id', $deviceId)
                ->first();
        }

        return view('kiosk.thankyou', compact('donation'));
    }
}
