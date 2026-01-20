<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignWizardController extends Controller
{
    /**
     * Step 1: Show layout selection
     */
    public function step1()
    {
        return view('organization.campaigns.wizard.step1-layout');
    }

    /**
     * Step 1: Process layout selection
     */
    public function step1Post(Request $request)
    {
        $validated = $request->validate([
            'layout_type' => 'required|in:solid_color,dual_color,banner_image,full_background',
        ]);

        // Store in session
        session(['campaign_wizard.layout_type' => $validated['layout_type']]);

        return redirect()->route('organization.campaigns.wizard.step2');
    }

    /**
     * Step 2: Show design editor
     */
    public function step2()
    {
        if (!session()->has('campaign_wizard.layout_type')) {
            return redirect()->route('organization.campaigns.wizard.step1')
                ->with('error', 'Please select a layout first.');
        }

        return view('organization.campaigns.wizard.step2-design');
    }

    /**
     * Step 2: Process design settings
     */
    public function step2Post(Request $request)
    {
        $layoutType = session('campaign_wizard.layout_type');

        $rules = [
            'campaign_name' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
            'primary_color' => 'required|string|max:7',
        ];

        // Add conditional validation based on layout type
        if (in_array($layoutType, ['banner_image', 'full_background'])) {
            $rules['background_image'] = 'required|image|mimes:jpeg,png,jpg|max:5120';
        }

        if (in_array($layoutType, ['dual_color', 'solid_color'])) {
            $rules['accent_color'] = 'nullable|string|max:7';
        }

        $validated = $request->validate($rules);

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $path = $request->file('background_image')->store('campaign-backgrounds', 'public');
            session(['campaign_wizard.background_image_url' => $path]);
        }

        // Store in session
        session([
            'campaign_wizard.campaign_name' => $validated['campaign_name'],
            'campaign_wizard.heading' => $validated['heading'],
            'campaign_wizard.message' => $validated['message'] ?? '',
            'campaign_wizard.primary_color' => $validated['primary_color'],
            'campaign_wizard.accent_color' => $validated['accent_color'] ?? '#F3F4F6',
        ]);

        return redirect()->route('organization.campaigns.wizard.step3');
    }

    /**
     * Step 3: Show donation amounts configuration
     */
    public function step3()
    {
        if (!session()->has('campaign_wizard.campaign_name')) {
            return redirect()->route('organization.campaigns.wizard.step1')
                ->with('error', 'Please complete previous steps first.');
        }

        return view('organization.campaigns.wizard.step3-donations');
    }

    /**
     * Step 3: Process donation amounts
     */
    public function step3Post(Request $request)
    {
        $validated = $request->validate([
            'amounts' => 'required|array|min:1',
            'amounts.*' => 'numeric|min:0.01',
            'button_position' => 'required|in:top,middle,bottom',
            'show_custom_amount' => 'nullable|boolean',
        ]);

        // Filter out empty amounts
        $amounts = array_filter($validated['amounts'], function ($amount) {
            return $amount > 0;
        });

        // Store in session
        session([
            'campaign_wizard.amounts' => array_values($amounts),
            'campaign_wizard.button_position' => $validated['button_position'],
            'campaign_wizard.show_custom_amount' => $request->has('show_custom_amount'),
        ]);

        return redirect()->route('organization.campaigns.wizard.step4');
    }

    /**
     * Step 4: Show thank you screen customization
     */
    public function step4()
    {
        if (!session()->has('campaign_wizard.amounts')) {
            return redirect()->route('organization.campaigns.wizard.step1')
                ->with('error', 'Please complete previous steps first.');
        }

        return view('organization.campaigns.wizard.step4-thankyou');
    }

    /**
     * Step 4: Process thank you screen settings
     */
    public function step4Post(Request $request)
    {
        $layoutType = session('campaign_wizard.layout_type');

        $rules = [
            'thankyou_message' => 'required|string|max:500',
            'thankyou_subtitle' => 'nullable|string|max:500',
            'thankyou_position' => 'required|in:top,middle,bottom',
            'offer_receipt' => 'nullable|boolean',
        ];

        // Conditional validation based on layout
        if (in_array($layoutType, ['banner_image', 'full_background'])) {
            $rules['thankyou_image'] = 'nullable|image|mimes:jpeg,png,jpg|max:5120';
        }

        if ($layoutType == 'solid_color') {
            $rules['thankyou_color'] = 'nullable|string|max:7';
        }

        if ($layoutType == 'dual_color') {
            $rules['thankyou_header_color'] = 'nullable|string|max:7';
            $rules['thankyou_body_color'] = 'nullable|string|max:7';
        }

        $validated = $request->validate($rules);

        // Handle thank you image upload
        if ($request->hasFile('thankyou_image')) {
            $path = $request->file('thankyou_image')->store('campaign-backgrounds', 'public');
            session(['campaign_wizard.thankyou_image_url' => $path]);
        }

        // Store in session
        session([
            'campaign_wizard.thankyou_message' => $validated['thankyou_message'],
            'campaign_wizard.thankyou_subtitle' => $validated['thankyou_subtitle'] ?? '',
            'campaign_wizard.thankyou_position' => $validated['thankyou_position'],
            'campaign_wizard.offer_receipt' => $request->has('offer_receipt'),
            'campaign_wizard.thankyou_color' => $validated['thankyou_color'] ?? session('campaign_wizard.primary_color'),
            'campaign_wizard.thankyou_header_color' => $validated['thankyou_header_color'] ?? session('campaign_wizard.primary_color'),
            'campaign_wizard.thankyou_body_color' => $validated['thankyou_body_color'] ?? session('campaign_wizard.accent_color'),
        ]);

        return redirect()->route('organization.campaigns.wizard.step5');
    }

    /**
     * Step 5: Show final details
     */
    public function step5()
    {
        if (!session()->has('campaign_wizard.thankyou_message')) {
            return redirect()->route('organization.campaigns.wizard.step1')
                ->with('error', 'Please complete previous steps first.');
        }

        $organization = auth()->user()->organization;
        $devices = $organization->devices()->get();

        return view('organization.campaigns.wizard.step5-final', compact('devices'));
    }

    /**
     * Finish: Create the campaign
     */
    public function finish(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization || $organization->status != 'active') {
            return redirect()->route('organization.dashboard')
                ->with('error', 'Your organization must be approved before creating campaigns.');
        }

        $validated = $request->validate([
            'campaign_type' => 'required|in:one-time,recurring',
            'reference_code' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'devices' => 'nullable|array',
            'devices.*' => 'exists:devices,id',
        ]);

        // Get wizard data from session
        $wizard = session('campaign_wizard', []);

        // Prepare campaign data
        $campaignData = [
            'organization_id' => $organization->id,
            'name' => $wizard['campaign_name'],
            'description' => $wizard['message'] ?? null,
            'campaign_type' => $validated['campaign_type'],
            'reference_code' => $validated['reference_code'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'language' => 'en',
            'currency' => 'EUR',
        ];

        // Prepare amount settings
        $campaignData['amount_settings'] = json_encode([
            'preset_amounts' => $wizard['amounts'] ?? [],
            'min_amount' => min($wizard['amounts'] ?? [1]),
            'max_amount' => 100000,
            'allow_custom_amount' => $wizard['show_custom_amount'] ?? false,
            'button_position' => $wizard['button_position'] ?? 'middle',
        ]);

        // Prepare design settings
        $designSettings = [
            'layout_type' => $wizard['layout_type'],
            'heading' => $wizard['heading'],
            'message' => $wizard['message'] ?? '',
            'primary_color' => $wizard['primary_color'],
            'accent_color' => $wizard['accent_color'] ?? null,
            'background_image' => $wizard['background_image_url'] ?? null,
            'thankyou_message' => $wizard['thankyou_message'],
            'thankyou_subtitle' => $wizard['thankyou_subtitle'] ?? '',
            'thankyou_position' => $wizard['thankyou_position'],
            'offer_receipt' => $wizard['offer_receipt'] ?? false,
            'thankyou_color' => $wizard['thankyou_color'] ?? null,
            'thankyou_header_color' => $wizard['thankyou_header_color'] ?? null,
            'thankyou_body_color' => $wizard['thankyou_body_color'] ?? null,
            'thankyou_image' => $wizard['thankyou_image_url'] ?? null,
        ];

        $campaignData['design_settings'] = json_encode($designSettings);

        // Create campaign
        $campaign = Campaign::create($campaignData);

        // Assign devices
        if (!empty($validated['devices'])) {
            $campaign->devices()->attach($validated['devices']);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'created',
            'model_type' => Campaign::class,
            'model_id' => $campaign->id,
            'description' => "Campaign '{$campaign->name}' created via wizard",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Clear wizard session data
        session()->forget('campaign_wizard');

        return redirect()->route('organization.campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully! 🎉');
    }

    /**
     * Clear wizard and start over
     */
    public function reset()
    {
        session()->forget('campaign_wizard');
        return redirect()->route('organization.campaigns.wizard.step1');
    }
}
