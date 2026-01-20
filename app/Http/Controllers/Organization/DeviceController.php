<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of devices
     */
    public function index(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile first.');
        }

        $query = $organization->devices()
            ->withCount(['campaigns', 'donations' => function ($query) {
                $query->where('payment_status', 'success');
            }])
            ->withSum(['donations' => function ($query) {
                $query->where('payment_status', 'success');
            }], 'amount');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('device_id', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $devices = $query->latest()->paginate(15);

        return view('organization.devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new device
     */
    public function create()
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile first.');
        }

        if ($organization->status != 'active') {
            return redirect()->route('organization.dashboard')
                ->with('error', 'Your organization must be approved before adding devices.');
        }

        return view('organization.devices.create');
    }

    /**
     * Store a newly created device
     */
    public function store(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization || $organization->status != 'active') {
            return redirect()->route('organization.dashboard')
                ->with('error', 'Your organization must be approved before adding devices.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['status'] = 'offline'; // New devices start as offline

        $device = Device::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'created',
            'model_type' => Device::class,
            'model_id' => $device->id,
            'description' => "Device '{$device->name}' added",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('organization.devices.show', $device)
            ->with('success', 'Device added successfully! Use the Device ID to pair your device.');
    }

    /**
     * Display the specified device
     */
    public function show(Device $device)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($device->organization_id != $userOrgId) {
            abort(403);
        }

        $device->load(['campaigns', 'donations' => function ($query) {
            $query->where('payment_status', 'success')->latest()->limit(10);
        }]);

        // Get statistics
        $stats = [
            'total_donations' => $device->donations()->where('payment_status', 'success')->count(),
            'total_amount' => $device->donations()->where('payment_status', 'success')->sum('amount'),
            'average_donation' => $device->donations()->where('payment_status', 'success')->avg('amount') ?? 0,
            'today_donations' => $device->donations()
                ->where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->count(),
            'today_amount' => $device->donations()
                ->where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'this_month_donations' => $device->donations()
                ->where('payment_status', 'success')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'this_month_amount' => $device->donations()
                ->where('payment_status', 'success')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        return view('organization.devices.show', compact('device', 'stats'));
    }

    /**
     * Show the form for editing the specified device
     */
    public function edit(Device $device)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($device->organization_id != $userOrgId) {
            abort(403);
        }

        return view('organization.devices.edit', compact('device'));
    }

    /**
     * Update the specified device
     */
    public function update(Request $request, Device $device)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($device->organization_id != $userOrgId) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:online,offline,maintenance',
        ]);

        $device->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $device->organization_id,
            'action' => 'updated',
            'model_type' => Device::class,
            'model_id' => $device->id,
            'description' => "Device '{$device->name}' updated",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('organization.devices.show', $device)
            ->with('success', 'Device updated successfully!');
    }

    /**
     * Remove the specified device
     */
    public function destroy(Device $device)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($device->organization_id != $userOrgId) {
            abort(403);
        }

        $deviceName = $device->name;

        // Log activity before deletion
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $device->organization_id,
            'action' => 'deleted',
            'model_type' => Device::class,
            'model_id' => $device->id,
            'description' => "Device '{$deviceName}' deleted",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $device->delete();

        return redirect()->route('organization.devices.index')
            ->with('success', 'Device deleted successfully!');
    }
}
