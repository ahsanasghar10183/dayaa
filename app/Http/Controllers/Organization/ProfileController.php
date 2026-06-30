<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StoreOrganizationProfileRequest;
use App\Http\Requests\Organization\UpdateOrganizationProfileRequest;
use App\Models\Organization;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show organization profile
     */
    public function show()
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile to get started.');
        }

        // Get statistics
        $stats = [
            'total_campaigns' => $organization->campaigns()->count(),
            'total_devices' => $organization->devices()->count(),
            'total_donations' => $organization->donations()->where('payment_status', 'completed')->count(),
        ];

        return view('organization.profile.show', compact('organization', 'stats'));
    }

    /**
     * Show create organization form
     */
    public function create(): View
    {
        // Check if user already has an organization
        if (auth()->user()->organization) {
            return redirect()->route('organization.profile.show');
        }

        return view('organization.profile.create');
    }

    /**
     * Store new organization
     */
    public function store(StoreOrganizationProfileRequest $request): RedirectResponse
    {
        // Check if user already has an organization
        if (auth()->user()->organization) {
            return redirect()->route('organization.profile.show')
                ->with('error', 'You already have an organization profile.');
        }

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Create organization
        $organization = auth()->user()->organization()->create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'created',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization profile created',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('organization.pending')
            ->with('completed', 'Your organization profile has been submitted for approval.');
    }

    /**
     * Show edit organization form
     */
    public function edit(): View
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.create');
        }

        return view('organization.profile.edit', compact('organization'));
    }

    /**
     * Update organization profile
     */
    public function update(UpdateOrganizationProfileRequest $request): RedirectResponse
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('error', 'Please create your organization profile first.');
        }

        $validated = $request->validated();
        unset($validated['remove_logo']);

        // Handle logo removal
        if ($request->has('remove_logo') && $request->remove_logo) {
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
                $validated['logo'] = null;
            }
        }
        // Handle logo upload
        elseif ($request->hasFile('logo')) {
            // Delete old logo
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $organization->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'updated',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization profile updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('completed', 'Organization profile updated successfully.');
    }
}
