<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns
     */
    public function index(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile first.');
        }

        $query = $organization->campaigns()
            ->withCount(['donations' => function ($query) {
                $query->where('payment_status', 'success');
            }])
            ->withSum(['donations' => function ($query) {
                $query->where('payment_status', 'success');
            }], 'amount')
            ->withCount('devices');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_code', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('campaign_type', $request->type);
        }

        $campaigns = $query->latest()->paginate(12);

        return view('organization.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign
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
                ->with('error', 'Your organization must be approved before creating campaigns.');
        }

        // Get available devices for assignment
        $devices = $organization->devices()->get();

        // Define design templates
        $templates = $this->getDesignTemplates();

        return view('organization.campaigns.create', compact('devices', 'templates'));
    }

    /**
     * Store a newly created campaign
     */
    public function store(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization || $organization->status != 'active') {
            return redirect()->route('organization.dashboard')
                ->with('error', 'Your organization must be approved before creating campaigns.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'campaign_type' => 'required|in:one-time,recurring',
            'reference_code' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'preset_amounts' => 'nullable|array',
            'preset_amounts.*' => 'nullable|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'allow_custom_amount' => 'nullable|boolean',
            'devices' => 'nullable|array',
            'devices.*' => 'exists:devices,id',
            'design_template' => 'nullable|string|max:50',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Filter out empty preset amounts
        if (isset($validated['preset_amounts'])) {
            $validated['preset_amounts'] = array_values(array_filter($validated['preset_amounts'], function ($amount) {
                return $amount !== null && $amount !== '';
            }));
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['campaign_logo'] = $request->file('logo')->store('campaign-logos', 'public');
        }

        // Set defaults
        $validated['organization_id'] = $organization->id;
        $validated['language'] = 'en';
        $validated['currency'] = 'EUR';

        // Store amount settings as JSON
        $validated['amount_settings'] = json_encode([
            'preset_amounts' => $validated['preset_amounts'] ?? [],
            'min_amount' => $validated['min_amount'] ?? 1,
            'max_amount' => $validated['max_amount'] ?? 10000,
            'allow_custom_amount' => $request->has('allow_custom_amount'),
        ]);

        // Store design settings as JSON
        $validated['design_settings'] = json_encode([
            'template' => $validated['design_template'] ?? 'modern',
            'primary_color' => $validated['primary_color'] ?? '#1163F0',
            'secondary_color' => $validated['secondary_color'] ?? '#1707B2',
            'font_family' => $validated['font_family'] ?? 'Inter',
            'logo' => $validated['campaign_logo'] ?? null,
        ]);

        // Remove individual amount and design fields as they're now in settings
        $deviceIds = $validated['devices'] ?? [];
        unset($validated['preset_amounts'], $validated['min_amount'], $validated['max_amount'], $validated['allow_custom_amount']);
        unset($validated['devices'], $validated['design_template'], $validated['primary_color'], $validated['secondary_color'], $validated['font_family'], $validated['logo'], $validated['campaign_logo']);

        $campaign = Campaign::create($validated);

        // Assign devices to campaign
        if (!empty($deviceIds)) {
            $campaign->devices()->attach($deviceIds);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'created',
            'model_type' => Campaign::class,
            'model_id' => $campaign->id,
            'description' => "Campaign '{$campaign->name}' created",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('organization.campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully!');
    }

    /**
     * Display the specified campaign
     */
    public function show(Campaign $campaign)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($campaign->organization_id != $userOrgId) {
            abort(403);
        }

        $campaign->load(['devices', 'donations' => function ($query) {
            $query->where('payment_status', 'success')->latest()->limit(10);
        }]);

        // Get statistics
        $stats = [
            'total_donations' => $campaign->donations()->where('payment_status', 'success')->count(),
            'total_amount' => $campaign->donations()->where('payment_status', 'success')->sum('amount'),
            'average_donation' => $campaign->donations()->where('payment_status', 'success')->avg('amount') ?? 0,
            'today_donations' => $campaign->donations()
                ->where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->count(),
            'today_amount' => $campaign->donations()
                ->where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'this_month_donations' => $campaign->donations()
                ->where('payment_status', 'success')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'this_month_amount' => $campaign->donations()
                ->where('payment_status', 'success')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        return view('organization.campaigns.show', compact('campaign', 'stats'));
    }

    /**
     * Show the form for editing the specified campaign
     */
    public function edit(Campaign $campaign)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($campaign->organization_id != $userOrgId) {
            abort(403);
        }

        $organization = auth()->user()->organization;
        $devices = $organization->devices()->get();
        $templates = $this->getDesignTemplates();

        // Get currently assigned device IDs
        $assignedDeviceIds = $campaign->devices->pluck('id')->toArray();

        return view('organization.campaigns.edit', compact('campaign', 'devices', 'templates', 'assignedDeviceIds'));
    }

    /**
     * Update the specified campaign
     */
    public function update(Request $request, Campaign $campaign)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($campaign->organization_id != $userOrgId) {
            abort(403);
        }

        $validated = $request->validate([
            // Basic info
            'campaign_name' => 'required|string|max:255',
            'campaign_type' => 'required|in:one-time,recurring',
            'reference_code' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled,ended',

            // Layout & Design
            'layout_type' => 'nullable|string|in:solid_color,dual_color,banner_image,full_background',
            'heading' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            // Donation amounts
            'amounts' => 'nullable|array',
            'amounts.*' => 'nullable|numeric|min:0',
            'button_position' => 'nullable|in:top,middle,bottom',
            'show_custom_amount' => 'nullable|boolean',

            // Thank you screen
            'thankyou_message' => 'nullable|string|max:500',
            'thankyou_subtitle' => 'nullable|string|max:500',
            'thankyou_position' => 'nullable|in:top,middle,bottom',
            'offer_receipt' => 'nullable|boolean',
            'thankyou_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            // Devices
            'devices' => 'nullable|array',
            'devices.*' => 'exists:devices,id',
        ]);

        // Get current design settings
        $currentDesignSettings = is_string($campaign->design_settings)
            ? json_decode($campaign->design_settings, true)
            : ($campaign->design_settings ?? []);

        // Handle background image upload
        $backgroundImagePath = $currentDesignSettings['background_image'] ?? null;
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($backgroundImagePath) {
                Storage::disk('public')->delete($backgroundImagePath);
            }
            $backgroundImagePath = $request->file('background_image')->store('campaign-backgrounds', 'public');
        }

        // Handle thank you image upload
        $thankyouImagePath = $currentDesignSettings['thankyou_image'] ?? null;
        if ($request->hasFile('thankyou_image')) {
            // Delete old image if exists
            if ($thankyouImagePath) {
                Storage::disk('public')->delete($thankyouImagePath);
            }
            $thankyouImagePath = $request->file('thankyou_image')->store('campaign-backgrounds', 'public');
        }

        // Filter out empty amounts
        $amounts = [];
        if (isset($validated['amounts'])) {
            $amounts = array_values(array_filter($validated['amounts'], function ($amount) {
                return $amount !== null && $amount !== '' && $amount > 0;
            }));
        }

        // Update campaign basic data
        $campaign->update([
            'name' => $validated['campaign_name'],
            'description' => $validated['message'] ?? $campaign->description,
            'campaign_type' => $validated['campaign_type'],
            'reference_code' => $validated['reference_code'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);

        // Update amount settings
        $campaign->amount_settings = json_encode([
            'preset_amounts' => $amounts,
            'min_amount' => !empty($amounts) ? min($amounts) : 1,
            'max_amount' => 100000,
            'allow_custom_amount' => $request->has('show_custom_amount'),
            'button_position' => $validated['button_position'] ?? 'middle',
        ]);

        // Update design settings
        $campaign->design_settings = json_encode(array_merge($currentDesignSettings, [
            'layout_type' => $validated['layout_type'] ?? $currentDesignSettings['layout_type'] ?? 'solid_color',
            'heading' => $validated['heading'] ?? $currentDesignSettings['heading'] ?? '',
            'message' => $validated['message'] ?? $currentDesignSettings['message'] ?? '',
            'primary_color' => $validated['primary_color'] ?? $currentDesignSettings['primary_color'] ?? '#1163F0',
            'accent_color' => $validated['accent_color'] ?? $currentDesignSettings['accent_color'] ?? '#F3F4F6',
            'background_image' => $backgroundImagePath,
            'thankyou_message' => $validated['thankyou_message'] ?? $currentDesignSettings['thankyou_message'] ?? 'Thank you!',
            'thankyou_subtitle' => $validated['thankyou_subtitle'] ?? $currentDesignSettings['thankyou_subtitle'] ?? '',
            'thankyou_position' => $validated['thankyou_position'] ?? $currentDesignSettings['thankyou_position'] ?? 'middle',
            'offer_receipt' => $request->has('offer_receipt'),
            'thankyou_image' => $thankyouImagePath,
        ]));

        $campaign->save();

        // Sync devices
        if (isset($validated['devices'])) {
            $campaign->devices()->sync($validated['devices']);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $campaign->organization_id,
            'action' => 'updated',
            'model_type' => Campaign::class,
            'model_id' => $campaign->id,
            'description' => "Campaign '{$campaign->name}' updated",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('organization.campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully!');
    }

    /**
     * Duplicate the specified campaign
     */
    public function duplicate(Campaign $campaign)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($campaign->organization_id != $userOrgId) {
            abort(403);
        }

        // Create a duplicate
        $duplicate = $campaign->replicate();
        $duplicate->name = $campaign->name . ' (Copy)';
        $duplicate->status = 'inactive'; // Set to inactive by default
        $duplicate->reference_code = null; // Clear reference code
        $duplicate->save();

        // Copy device assignments
        $campaign->devices->each(function ($device) use ($duplicate) {
            $duplicate->devices()->attach($device->id);
        });

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $campaign->organization_id,
            'action' => 'duplicated',
            'model_type' => Campaign::class,
            'model_id' => $duplicate->id,
            'description' => "Campaign '{$campaign->name}' duplicated as '{$duplicate->name}'",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('organization.campaigns.edit', $duplicate)
            ->with('success', 'Campaign duplicated successfully! You can now customize it.');
    }

    /**
     * Remove the specified campaign
     */
    public function destroy(Campaign $campaign)
    {
        // Authorize
        $userOrgId = auth()->user()->organization ? auth()->user()->organization->id : null;
        if ($campaign->organization_id != $userOrgId) {
            abort(403);
        }

        $campaignName = $campaign->name;

        // Log activity before deletion
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $campaign->organization_id,
            'action' => 'deleted',
            'model_type' => Campaign::class,
            'model_id' => $campaign->id,
            'description' => "Campaign '{$campaignName}' deleted",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $campaign->delete();

        return redirect()->route('organization.campaigns.index')
            ->with('success', 'Campaign deleted successfully!');
    }

    /**
     * Get available design templates
     */
    private function getDesignTemplates()
    {
        return [
            [
                'id' => 'modern',
                'name' => 'Modern Gradient',
                'description' => 'Clean, modern design with gradient accents',
                'preview_colors' => ['#1163F0', '#1707B2'],
                'default_font' => 'Inter',
            ],
            [
                'id' => 'classic',
                'name' => 'Classic Blue',
                'description' => 'Professional and trustworthy blue theme',
                'preview_colors' => ['#2563EB', '#1E40AF'],
                'default_font' => 'Roboto',
            ],
            [
                'id' => 'warm',
                'name' => 'Warm Orange',
                'description' => 'Friendly and inviting warm tones',
                'preview_colors' => ['#F59E0B', '#D97706'],
                'default_font' => 'Poppins',
            ],
            [
                'id' => 'nature',
                'name' => 'Nature Green',
                'description' => 'Fresh and eco-friendly green palette',
                'preview_colors' => ['#10B981', '#059669'],
                'default_font' => 'Lato',
            ],
            [
                'id' => 'elegant',
                'name' => 'Elegant Purple',
                'description' => 'Sophisticated purple and violet theme',
                'preview_colors' => ['#8B5CF6', '#7C3AED'],
                'default_font' => 'Playfair Display',
            ],
            [
                'id' => 'vibrant',
                'name' => 'Vibrant Red',
                'description' => 'Bold and energetic red accents',
                'preview_colors' => ['#EF4444', '#DC2626'],
                'default_font' => 'Montserrat',
            ],
            [
                'id' => 'minimal',
                'name' => 'Minimal Black',
                'description' => 'Simple monochrome design',
                'preview_colors' => ['#111827', '#374151'],
                'default_font' => 'SF Pro Display',
            ],
            [
                'id' => 'ocean',
                'name' => 'Ocean Blue',
                'description' => 'Calm and serene ocean-inspired palette',
                'preview_colors' => ['#0EA5E9', '#0284C7'],
                'default_font' => 'Open Sans',
            ],
        ];
    }
}
