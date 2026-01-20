<x-organization-layout>
    @php
        $amountSettings = is_string($campaign->amount_settings) ? json_decode($campaign->amount_settings, true) : $campaign->amount_settings;
        $designSettings = is_string($campaign->design_settings) ? json_decode($campaign->design_settings, true) : ($campaign->design_settings ?? []);
        $presetAmounts = $amountSettings['preset_amounts'] ?? [10, 25, 50];
        $layoutType = $designSettings['layout_type'] ?? 'solid_color';
        $backgroundImageUrl = isset($designSettings['background_image']) ? asset('storage/' . $designSettings['background_image']) : '';
        $thankyouImageUrl = isset($designSettings['thankyou_image']) ? asset('storage/' . $designSettings['thankyou_image']) : '';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Campaign</h1>
                <p class="mt-2 text-gray-600">{{ $campaign->name }}</p>
            </div>
            <a href="{{ route('organization.campaigns.show', $campaign) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Campaign
            </a>
        </div>

        <form method="POST" action="{{ route('organization.campaigns.update', $campaign) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side: Tabbed Form -->
                <div class="space-y-6">
                    <!-- Tab Navigation -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="flex border-b border-gray-200">
                            <button type="button" class="tab-btn flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 border-primary-600 text-primary-600 bg-primary-50" data-tab="layout">
                                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                </svg>
                                Layout & Design
                            </button>
                            <button type="button" class="tab-btn flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="donations">
                                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Donation Settings
                            </button>
                            <button type="button" class="tab-btn flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="thankyou">
                                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                Thank You Screen
                            </button>
                            <button type="button" class="tab-btn flex-1 px-6 py-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="settings">
                                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div class="p-6">
                            <!-- Layout & Design Tab -->
                            <div class="tab-content" id="layout-tab">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Layout & Design</h3>

                                <!-- Layout Type Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Layout Type</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="layout_type" value="solid_color" class="hidden layout-radio" {{ $layoutType == 'solid_color' ? 'checked' : '' }}>
                                            <div class="layout-card border-2 rounded-lg p-3 hover:border-primary-500 transition-all text-center">
                                                <div class="h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded mb-2"></div>
                                                <p class="text-xs font-medium text-gray-700">Solid Color</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="layout_type" value="dual_color" class="hidden layout-radio" {{ $layoutType == 'dual_color' ? 'checked' : '' }}>
                                            <div class="layout-card border-2 rounded-lg p-3 hover:border-primary-500 transition-all text-center">
                                                <div class="h-20 rounded mb-2 overflow-hidden">
                                                    <div class="h-1/3 bg-blue-600"></div>
                                                    <div class="h-2/3 bg-gray-100"></div>
                                                </div>
                                                <p class="text-xs font-medium text-gray-700">Dual Color</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="layout_type" value="banner_image" class="hidden layout-radio" {{ $layoutType == 'banner_image' ? 'checked' : '' }}>
                                            <div class="layout-card border-2 rounded-lg p-3 hover:border-primary-500 transition-all text-center">
                                                <div class="h-20 rounded mb-2 overflow-hidden">
                                                    <div class="h-1/3 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                                                    <div class="h-2/3 bg-white"></div>
                                                </div>
                                                <p class="text-xs font-medium text-gray-700">Banner Image</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="layout_type" value="full_background" class="hidden layout-radio" {{ $layoutType == 'full_background' ? 'checked' : '' }}>
                                            <div class="layout-card border-2 rounded-lg p-3 hover:border-primary-500 transition-all text-center">
                                                <div class="h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded mb-2"></div>
                                                <p class="text-xs font-medium text-gray-700">Full Background</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Campaign Name -->
                                <div class="mb-6">
                                    <label for="campaign_name" class="block text-sm font-medium text-gray-700 mb-2">Campaign Name *</label>
                                    <input type="text" name="campaign_name" id="campaign_name" value="{{ old('campaign_name', $campaign->name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                </div>

                                <!-- Heading -->
                                <div class="mb-6">
                                    <label for="heading" class="block text-sm font-medium text-gray-700 mb-2">Heading *</label>
                                    <input type="text" name="heading" id="heading" value="{{ old('heading', $designSettings['heading'] ?? 'Support Our Cause') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                </div>

                                <!-- Message -->
                                <div class="mb-6">
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                    <textarea name="message" id="message" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('message', $designSettings['message'] ?? '') }}</textarea>
                                </div>

                                <!-- Primary Color -->
                                <div class="mb-6">
                                    <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                                    <div class="flex gap-3">
                                        <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $designSettings['primary_color'] ?? '#1163F0') }}" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                                        <input type="text" id="primary_color_text" value="{{ old('primary_color', $designSettings['primary_color'] ?? '#1163F0') }}" readonly class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono text-sm">
                                    </div>
                                </div>

                                <!-- Accent Color (for dual_color, banner_image) -->
                                <div class="mb-6 accent-color-field" style="display: {{ in_array($layoutType, ['dual_color', 'banner_image']) ? 'block' : 'none' }}">
                                    <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                                    <div class="flex gap-3">
                                        <input type="color" name="accent_color" id="accent_color" value="{{ old('accent_color', $designSettings['accent_color'] ?? '#F3F4F6') }}" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                                        <input type="text" id="accent_color_text" value="{{ old('accent_color', $designSettings['accent_color'] ?? '#F3F4F6') }}" readonly class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono text-sm">
                                    </div>
                                </div>

                                <!-- Background Image (for banner_image, full_background) -->
                                <div class="background-image-field" style="display: {{ in_array($layoutType, ['banner_image', 'full_background']) ? 'block' : 'none' }}">
                                    <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $layoutType == 'banner_image' ? 'Banner Image' : 'Background Image' }}
                                    </label>
                                    @if($backgroundImageUrl)
                                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <img src="{{ $backgroundImageUrl }}" alt="Current background" class="h-24 w-full object-cover rounded">
                                        <p class="text-xs text-gray-500 mt-2">Current image (upload new to replace)</p>
                                    </div>
                                    @endif
                                    <input type="file" name="background_image" id="background_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image</p>
                                </div>
                            </div>

                            <!-- Donation Settings Tab -->
                            <div class="tab-content hidden" id="donations-tab">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Donation Amounts</h3>

                                <div id="amountsContainer" class="space-y-3 mb-6">
                                    @foreach($presetAmounts as $index => $amount)
                                    <div class="donation-amount flex items-center gap-3">
                                        <span class="text-2xl">€</span>
                                        <input type="number" name="amounts[]" value="{{ $amount }}" min="1" step="0.01" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 amount-input" placeholder="Amount">
                                        <button type="button" class="remove-amount text-red-500 hover:text-red-700 {{ count($presetAmounts) <= 1 ? 'opacity-0 pointer-events-none' : '' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" id="addAmountBtn" class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-primary-500 hover:text-primary-600 transition-all flex items-center justify-center gap-2 mb-6">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Amount
                                </button>

                                <!-- Button Position -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Button Position</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['top', 'middle', 'bottom'] as $position)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="button_position" value="{{ $position }}" class="hidden position-radio" {{ ($amountSettings['button_position'] ?? 'middle') == $position ? 'checked' : '' }}>
                                            <div class="position-card border-2 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($position == 'top')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @elseif($position == 'middle')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                    @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $position }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Custom Amount Toggle -->
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Allow Custom Amount</h4>
                                        <p class="text-xs text-gray-500 mt-1">Let donors enter their own amount</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="show_custom_amount" id="customAmountToggle" value="1" {{ ($amountSettings['allow_custom_amount'] ?? false) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Thank You Screen Tab -->
                            <div class="tab-content hidden" id="thankyou-tab">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Thank You Screen</h3>

                                <!-- Thank You Image (conditional) -->
                                <div class="thankyou-image-field mb-6" style="display: {{ in_array($layoutType, ['banner_image', 'full_background']) ? 'block' : 'none' }}">
                                    <label for="thankyou_image" class="block text-sm font-medium text-gray-700 mb-2">
                                        Thank You {{ $layoutType == 'banner_image' ? 'Banner' : 'Background' }} (Optional)
                                    </label>
                                    @if($thankyouImageUrl)
                                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <img src="{{ $thankyouImageUrl }}" alt="Current thank you image" class="h-24 w-full object-cover rounded">
                                        <p class="text-xs text-gray-500 mt-2">Current image (upload new to replace)</p>
                                    </div>
                                    @endif
                                    <input type="file" name="thankyou_image" id="thankyou_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <p class="mt-1 text-xs text-gray-500">Leave empty to use campaign image</p>
                                </div>

                                <!-- Thank You Message -->
                                <div class="mb-6">
                                    <label for="thankyou_message" class="block text-sm font-medium text-gray-700 mb-2">Thank You Message *</label>
                                    <textarea name="thankyou_message" id="thankyou_message" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">{{ old('thankyou_message', $designSettings['thankyou_message'] ?? 'Thank you for your generous donation!') }}</textarea>
                                </div>

                                <!-- Thank You Subtitle -->
                                <div class="mb-6">
                                    <label for="thankyou_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                                    <input type="text" name="thankyou_subtitle" id="thankyou_subtitle" value="{{ old('thankyou_subtitle', $designSettings['thankyou_subtitle'] ?? 'Your support makes a real difference.') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                </div>

                                <!-- Thank You Position -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Message Position</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['top', 'middle', 'bottom'] as $position)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="thankyou_position" value="{{ $position }}" class="hidden thankyou-position-radio" {{ ($designSettings['thankyou_position'] ?? 'middle') == $position ? 'checked' : '' }}>
                                            <div class="thankyou-position-card border-2 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($position == 'top')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @elseif($position == 'middle')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                    @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $position }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Offer Receipt -->
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Offer Donation Receipt
                                        </h4>
                                        <p class="text-xs text-gray-600 mt-1">Email a receipt to the donor</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="offer_receipt" id="offerReceiptToggle" value="1" {{ ($designSettings['offer_receipt'] ?? false) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Campaign Settings Tab -->
                            <div class="tab-content hidden" id="settings-tab">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Campaign Settings</h3>

                                <!-- Campaign Type -->
                                <div class="mb-6">
                                    <label for="campaign_type" class="block text-sm font-medium text-gray-700 mb-2">Campaign Type *</label>
                                    <select name="campaign_type" id="campaign_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                        <option value="one-time" {{ $campaign->campaign_type == 'one-time' ? 'selected' : '' }}>One-Time Donations</option>
                                        <option value="recurring" {{ $campaign->campaign_type == 'recurring' ? 'selected' : '' }}>Recurring Donations</option>
                                    </select>
                                </div>

                                <!-- Reference Code -->
                                <div class="mb-6">
                                    <label for="reference_code" class="block text-sm font-medium text-gray-700 mb-2">Reference Code</label>
                                    <input type="text" name="reference_code" id="reference_code" value="{{ old('reference_code', $campaign->reference_code) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" placeholder="e.g., SPRING2026">
                                </div>

                                <!-- Dates -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $campaign->start_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                    <select name="status" id="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                        <option value="inactive" {{ $campaign->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="active" {{ $campaign->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="scheduled" {{ $campaign->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="ended" {{ $campaign->status == 'ended' ? 'selected' : '' }}>Ended</option>
                                    </select>
                                </div>

                                <!-- Device Assignment -->
                                @if($devices->count() > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Assign to Devices</label>
                                    <div class="space-y-2 max-h-64 overflow-y-auto">
                                        @foreach($devices as $device)
                                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-primary-500 cursor-pointer transition-all">
                                            <input type="checkbox" name="devices[]" value="{{ $device->id }}" {{ in_array($device->id, $assignedDeviceIds) ? 'checked' : '' }} class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-gray-900">{{ $device->name }}</span>
                                                    @if($device->status == 'online')
                                                    <span class="badge-success text-xs">Online</span>
                                                    @else
                                                    <span class="badge-gray text-xs">Offline</span>
                                                    @endif
                                                </div>
                                                @if($device->location)
                                                <p class="text-xs text-gray-500">{{ $device->location }}</p>
                                                @endif
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('organization.campaigns.show', $campaign) }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary px-8">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                <!-- Right Side: Live Preview -->
                <div class="lg:sticky lg:top-8 lg:self-start">
                    <div class="bg-gray-900 rounded-2xl p-6 shadow-2xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-white font-semibold">Live Preview</h3>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="previewMode" class="text-xs text-gray-400 hover:text-white">Campaign</button>
                                <span class="text-gray-600">|</span>
                                <button type="button" id="previewThankYou" class="text-xs text-gray-400 hover:text-white">Thank You</button>
                            </div>
                        </div>

                        <div id="preview" class="bg-white rounded-xl overflow-hidden shadow-xl aspect-[9/16] max-h-[600px]">
                            <div id="preview-content" class="h-full flex flex-col"></div>
                        </div>

                        <p class="text-xs text-gray-400 mt-4 text-center">Updates in real-time</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Initial data
        let layoutType = '{{ $layoutType }}';
        let primaryColor = '{{ $designSettings["primary_color"] ?? "#1163F0" }}';
        let accentColor = '{{ $designSettings["accent_color"] ?? "#F3F4F6" }}';
        let backgroundImageUrl = '{{ $backgroundImageUrl }}';
        let thankyouImageUrl = '{{ $thankyouImageUrl }}';
        let currentButtonPosition = '{{ $amountSettings["button_position"] ?? "middle" }}';
        let currentThankyouPosition = '{{ $designSettings["thankyou_position"] ?? "middle" }}';
        let showCustomAmount = {{ ($amountSettings['allow_custom_amount'] ?? false) ? 'true' : 'false' }};
        let showReceipt = {{ ($designSettings['offer_receipt'] ?? false) ? 'true' : 'false' }};
        let isThankYouPreview = false;

        const preview = document.getElementById('preview-content');

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.dataset.tab;

                // Update buttons
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('border-primary-600', 'text-primary-600', 'bg-primary-50');
                    b.classList.add('border-transparent', 'text-gray-500');
                });
                this.classList.add('border-primary-600', 'text-primary-600', 'bg-primary-50');
                this.classList.remove('border-transparent', 'text-gray-500');

                // Update content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(tabId + '-tab').classList.remove('hidden');
            });
        });

        // Preview mode switching
        document.getElementById('previewMode').addEventListener('click', function() {
            isThankYouPreview = false;
            this.classList.remove('text-gray-400');
            this.classList.add('text-white');
            document.getElementById('previewThankYou').classList.remove('text-white');
            document.getElementById('previewThankYou').classList.add('text-gray-400');
            updatePreview();
        });

        document.getElementById('previewThankYou').addEventListener('click', function() {
            isThankYouPreview = true;
            this.classList.remove('text-gray-400');
            this.classList.add('text-white');
            document.getElementById('previewMode').classList.remove('text-white');
            document.getElementById('previewMode').classList.add('text-gray-400');
            updatePreview();
        });

        // Layout type change
        document.querySelectorAll('.layout-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                layoutType = this.value;

                // Update visual selection
                document.querySelectorAll('.layout-card').forEach(card => {
                    card.classList.remove('border-primary-500', 'bg-primary-50');
                    card.classList.add('border-gray-200');
                });
                this.nextElementSibling.classList.remove('border-gray-200');
                this.nextElementSibling.classList.add('border-primary-500', 'bg-primary-50');

                // Show/hide conditional fields
                const accentColorField = document.querySelector('.accent-color-field');
                const backgroundImageField = document.querySelector('.background-image-field');
                const thankyouImageField = document.querySelector('.thankyou-image-field');

                if (layoutType === 'dual_color' || layoutType === 'banner_image') {
                    accentColorField.style.display = 'block';
                } else {
                    accentColorField.style.display = 'none';
                }

                if (layoutType === 'banner_image' || layoutType === 'full_background') {
                    backgroundImageField.style.display = 'block';
                    thankyouImageField.style.display = 'block';
                } else {
                    backgroundImageField.style.display = 'none';
                    thankyouImageField.style.display = 'none';
                }

                updatePreview();
            });
        });

        // Trigger checked layout
        const checkedLayout = document.querySelector('.layout-radio:checked');
        if (checkedLayout) {
            checkedLayout.dispatchEvent(new Event('change'));
        }

        // Color pickers
        document.getElementById('primary_color').addEventListener('input', function() {
            primaryColor = this.value;
            document.getElementById('primary_color_text').value = this.value.toUpperCase();
            updatePreview();
        });

        document.getElementById('accent_color').addEventListener('input', function() {
            accentColor = this.value;
            document.getElementById('accent_color_text').value = this.value.toUpperCase();
            updatePreview();
        });

        // Text inputs
        document.getElementById('heading')?.addEventListener('input', updatePreview);
        document.getElementById('message')?.addEventListener('input', updatePreview);
        document.getElementById('thankyou_message')?.addEventListener('input', updatePreview);
        document.getElementById('thankyou_subtitle')?.addEventListener('input', updatePreview);

        // Image uploads
        document.getElementById('background_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    backgroundImageUrl = e.target.result;
                    updatePreview();
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('thankyou_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thankyouImageUrl = e.target.result;
                    updatePreview();
                };
                reader.readAsDataURL(file);
            }
        });

        // Amount management
        document.getElementById('addAmountBtn').addEventListener('click', function() {
            const container = document.getElementById('amountsContainer');
            const newAmount = document.createElement('div');
            newAmount.className = 'donation-amount flex items-center gap-3';
            newAmount.innerHTML = `
                <span class="text-2xl">€</span>
                <input type="number" name="amounts[]" value="" min="1" step="0.01" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 amount-input" placeholder="Amount">
                <button type="button" class="remove-amount text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newAmount);

            newAmount.querySelector('.amount-input').addEventListener('input', updatePreview);
            newAmount.querySelector('.remove-amount').addEventListener('click', function() {
                newAmount.remove();
                updateRemoveButtons();
                updatePreview();
            });

            updateRemoveButtons();
            updatePreview();
        });

        // Remove amount
        document.querySelectorAll('.remove-amount').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.donation-amount').remove();
                updateRemoveButtons();
                updatePreview();
            });
        });

        function updateRemoveButtons() {
            const amounts = document.querySelectorAll('.donation-amount');
            amounts.forEach(amount => {
                const btn = amount.querySelector('.remove-amount');
                if (amounts.length > 1) {
                    btn.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    btn.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        // Amount inputs
        document.querySelectorAll('.amount-input').forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        // Position radios
        document.querySelectorAll('.position-radio').forEach((radio, index) => {
            radio.addEventListener('change', function() {
                currentButtonPosition = this.value;
                document.querySelectorAll('.position-card').forEach(card => {
                    card.classList.remove('border-primary-500', 'bg-primary-50');
                    card.classList.add('border-gray-200');
                });
                this.nextElementSibling.classList.remove('border-gray-200');
                this.nextElementSibling.classList.add('border-primary-500', 'bg-primary-50');
                updatePreview();
            });
        });

        // Thank you position radios
        document.querySelectorAll('.thankyou-position-radio').forEach((radio, index) => {
            radio.addEventListener('change', function() {
                currentThankyouPosition = this.value;
                document.querySelectorAll('.thankyou-position-card').forEach(card => {
                    card.classList.remove('border-primary-500', 'bg-primary-50');
                    card.classList.add('border-gray-200');
                });
                this.nextElementSibling.classList.remove('border-gray-200');
                this.nextElementSibling.classList.add('border-primary-500', 'bg-primary-50');
                updatePreview();
            });
        });

        // Trigger checked positions
        const checkedPosition = document.querySelector('.position-radio:checked');
        if (checkedPosition) checkedPosition.dispatchEvent(new Event('change'));

        const checkedThankyouPosition = document.querySelector('.thankyou-position-radio:checked');
        if (checkedThankyouPosition) checkedThankyouPosition.dispatchEvent(new Event('change'));

        // Toggles
        document.getElementById('customAmountToggle').addEventListener('change', function() {
            showCustomAmount = this.checked;
            updatePreview();
        });

        document.getElementById('offerReceiptToggle').addEventListener('change', function() {
            showReceipt = this.checked;
            updatePreview();
        });

        function updatePreview() {
            if (isThankYouPreview) {
                renderThankYouPreview();
            } else {
                renderCampaignPreview();
            }
        }

        function renderCampaignPreview() {
            const heading = document.getElementById('heading')?.value || 'Support Our Cause';
            const message = document.getElementById('message')?.value || '';
            const amounts = Array.from(document.querySelectorAll('.amount-input'))
                .map(input => parseFloat(input.value) || 0)
                .filter(amount => amount > 0);

            let buttonsHTML = amounts.map(amount =>
                `<button class="px-6 py-3 rounded-lg font-semibold shadow-lg text-white" style="background-color: ${primaryColor}">€${amount.toFixed(2)}</button>`
            ).join('');

            if (showCustomAmount) {
                buttonsHTML += `<button class="px-6 py-3 bg-white border-2 rounded-lg font-semibold shadow-lg" style="border-color: ${primaryColor}; color: ${primaryColor}">Custom Amount</button>`;
            }

            const buttonContainer = `<div class="grid grid-cols-2 gap-3 w-full max-w-sm">${buttonsHTML}</div>`;
            const justifyClass = currentButtonPosition === 'top' ? 'justify-start pt-12' : currentButtonPosition === 'bottom' ? 'justify-end pb-12' : 'justify-center';

            let html = '';

            switch(layoutType) {
                case 'solid_color':
                    html = `
                        <div class="h-full flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: ${primaryColor}">
                            ${currentButtonPosition !== 'bottom' ? `<h1 class="text-2xl font-bold text-white mb-4">${heading}</h1><p class="text-white opacity-90 mb-8 text-sm">${message}</p>` : ''}
                            ${buttonContainer}
                            ${currentButtonPosition === 'bottom' ? `<h1 class="text-2xl font-bold text-white mt-8 mb-4">${heading}</h1><p class="text-white opacity-90 text-sm">${message}</p>` : ''}
                        </div>
                    `;
                    break;

                case 'dual_color':
                    html = `
                        <div class="h-1/3 flex flex-col items-center justify-center p-6 text-center" style="background-color: ${primaryColor}">
                            <h1 class="text-xl font-bold text-white">${heading}</h1>
                        </div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8" style="background-color: ${accentColor}">
                            ${currentButtonPosition !== 'bottom' ? `<p class="text-gray-700 mb-8 text-sm text-center">${message}</p>` : ''}
                            ${buttonContainer}
                            ${currentButtonPosition === 'bottom' ? `<p class="text-gray-700 mt-8 text-sm text-center">${message}</p>` : ''}
                        </div>
                    `;
                    break;

                case 'banner_image':
                    html = `
                        <div class="h-1/3 relative" style="background: ${backgroundImageUrl ? `url(${backgroundImageUrl})` : 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + primaryColor + 'dd 100%)'} center/cover"></div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8" style="background-color: ${accentColor || '#ffffff'}">
                            ${currentButtonPosition !== 'bottom' ? `<h1 class="text-xl font-bold mb-4" style="color: ${primaryColor}">${heading}</h1><p class="text-gray-700 mb-8 text-sm text-center">${message}</p>` : ''}
                            ${buttonContainer}
                            ${currentButtonPosition === 'bottom' ? `<h1 class="text-xl font-bold mt-8 mb-4" style="color: ${primaryColor}">${heading}</h1><p class="text-gray-700 text-sm text-center">${message}</p>` : ''}
                        </div>
                    `;
                    break;

                case 'full_background':
                    html = `
                        <div class="h-full relative flex flex-col items-center ${justifyClass} p-8 text-center" style="background: ${backgroundImageUrl ? `url(${backgroundImageUrl})` : 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + primaryColor + 'aa 100%)'} center/cover">
                            <div class="absolute inset-0 bg-black opacity-40"></div>
                            <div class="relative z-10">
                                ${currentButtonPosition !== 'bottom' ? `<h1 class="text-2xl font-bold text-white mb-4">${heading}</h1><p class="text-white opacity-90 mb-8 text-sm">${message}</p>` : ''}
                                <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                                    ${amounts.map(amount => `<button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-xl">€${amount.toFixed(2)}</button>`).join('')}
                                    ${showCustomAmount ? `<button class="px-6 py-3 bg-white bg-opacity-20 border-2 border-white text-white rounded-lg font-semibold shadow-xl">Custom Amount</button>` : ''}
                                </div>
                                ${currentButtonPosition === 'bottom' ? `<h1 class="text-2xl font-bold text-white mt-8 mb-4">${heading}</h1><p class="text-white opacity-90 text-sm">${message}</p>` : ''}
                            </div>
                        </div>
                    `;
                    break;
            }

            preview.innerHTML = html;
        }

        function renderThankYouPreview() {
            const message = document.getElementById('thankyou_message')?.value || 'Thank you for your generous donation!';
            const subtitle = document.getElementById('thankyou_subtitle')?.value || 'Your support makes a real difference.';
            const imageUrl = thankyouImageUrl || backgroundImageUrl;
            const justifyClass = currentThankyouPosition === 'top' ? 'justify-start pt-12' : currentThankyouPosition === 'bottom' ? 'justify-end pb-12' : 'justify-center';

            const successIcon = `
                <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            `;

            const receiptButton = showReceipt ? `
                <button class="mt-6 px-6 py-3 bg-white border-2 border-green-600 text-green-600 rounded-lg font-semibold shadow-md flex items-center mx-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Email Receipt
                </button>
            ` : '';

            let html = '';

            switch(layoutType) {
                case 'solid_color':
                    html = `
                        <div class="h-full flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: ${primaryColor}">
                            ${successIcon}
                            <h1 class="text-2xl font-bold text-white mb-3">${message}</h1>
                            <p class="text-white opacity-90 text-sm max-w-sm">${subtitle}</p>
                            ${receiptButton}
                        </div>
                    `;
                    break;

                case 'dual_color':
                    html = `
                        <div class="h-1/3 flex flex-col items-center justify-center p-6 text-center" style="background-color: ${primaryColor}">
                            ${successIcon}
                        </div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: ${accentColor}">
                            <h1 class="text-2xl font-bold mb-3" style="color: ${primaryColor}">${message}</h1>
                            <p class="text-gray-700 text-sm max-w-sm">${subtitle}</p>
                            ${receiptButton}
                        </div>
                    `;
                    break;

                case 'banner_image':
                    html = `
                        <div class="h-1/3 relative" style="background: ${imageUrl ? `url(${imageUrl})` : 'linear-gradient(135deg, #10B981 0%, #059669 100%)'} center/cover"></div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: #ffffff">
                            ${successIcon}
                            <h1 class="text-2xl font-bold text-green-600 mb-3">${message}</h1>
                            <p class="text-gray-700 text-sm max-w-sm">${subtitle}</p>
                            ${receiptButton}
                        </div>
                    `;
                    break;

                case 'full_background':
                    html = `
                        <div class="h-full relative flex flex-col items-center ${justifyClass} p-8 text-center" style="background: ${imageUrl ? `url(${imageUrl})` : 'linear-gradient(135deg, #10B981 0%, #059669 100%)'} center/cover">
                            <div class="absolute inset-0 bg-black opacity-30"></div>
                            <div class="relative z-10">
                                ${successIcon.replace('bg-green-500', 'bg-white').replace('text-white', 'text-green-600')}
                                <h1 class="text-2xl font-bold text-white mb-3">${message}</h1>
                                <p class="text-white opacity-95 text-sm max-w-sm">${subtitle}</p>
                                ${receiptButton.replace('bg-white border-2 border-green-600 text-green-600', 'bg-white text-green-600')}
                            </div>
                        </div>
                    `;
                    break;
            }

            preview.innerHTML = html;
        }

        // Initial render
        updatePreview();
    </script>
</x-organization-layout>
