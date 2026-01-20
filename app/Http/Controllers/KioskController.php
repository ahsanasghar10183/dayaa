<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
}
