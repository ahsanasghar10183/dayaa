<x-organization-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Campaign</h1>
                <p class="mt-2 text-gray-600">Set up a new donation campaign</p>
            </div>
            <a href="{{ route('organization.campaigns.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Campaigns
            </a>
        </div>

        <form method="POST" action="{{ route('organization.campaigns.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>

                <div class="space-y-6">
                    <!-- Campaign Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Campaign Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror" placeholder="e.g., Winter Fundraiser 2026">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Describe your campaign...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Campaign Type -->
                        <div>
                            <label for="campaign_type" class="block text-sm font-medium text-gray-700 mb-2">Campaign Type *</label>
                            <select name="campaign_type" id="campaign_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('campaign_type') border-red-500 @enderror">
                                <option value="">Select type...</option>
                                <option value="one-time" {{ old('campaign_type') == 'one-time' ? 'selected' : '' }}>One-Time Donation</option>
                                <option value="recurring" {{ old('campaign_type') == 'recurring' ? 'selected' : '' }}>Recurring Donation</option>
                            </select>
                            @error('campaign_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference Code -->
                        <div>
                            <label for="reference_code" class="block text-sm font-medium text-gray-700 mb-2">Reference Code</label>
                            <input type="text" name="reference_code" id="reference_code" value="{{ old('reference_code') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('reference_code') border-red-500 @enderror" placeholder="e.g., WF2026">
                            <p class="mt-1 text-xs text-gray-500">Optional internal reference</p>
                            @error('reference_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule & Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Schedule & Status</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="inactive" {{ old('status', 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Donation Amounts -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Donation Amounts</h3>
                <p class="text-sm text-gray-600 mb-4">Configure up to 6 preset donation amounts (leave empty if not needed)</p>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    @for($i = 1; $i <= 6; $i++)
                    <div>
                        <label for="preset_amount_{{ $i }}" class="block text-sm font-medium text-gray-700 mb-2">Amount {{ $i }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">€</span>
                            <input type="number" name="preset_amounts[]" id="preset_amount_{{ $i }}" value="{{ old('preset_amounts.'.$i-1) }}" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0.00">
                        </div>
                    </div>
                    @endfor
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Minimum Amount -->
                    <div>
                        <label for="min_amount" class="block text-sm font-medium text-gray-700 mb-2">Minimum Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">€</span>
                            <input type="number" name="min_amount" id="min_amount" value="{{ old('min_amount', 1) }}" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('min_amount') border-red-500 @enderror">
                        </div>
                        @error('min_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maximum Amount -->
                    <div>
                        <label for="max_amount" class="block text-sm font-medium text-gray-700 mb-2">Maximum Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">€</span>
                            <input type="number" name="max_amount" id="max_amount" value="{{ old('max_amount', 10000) }}" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('max_amount') border-red-500 @enderror">
                        </div>
                        @error('max_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Custom Amount Option -->
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="allow_custom_amount" value="1" {{ old('allow_custom_amount', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Allow donors to enter custom amounts</span>
                    </label>
                </div>
            </div>

            <!-- Design & Appearance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Design & Appearance</h3>

                <!-- Design Templates -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Choose Template</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($templates as $template)
                        <label class="cursor-pointer">
                            <input type="radio" name="design_template" value="{{ $template['id'] }}" {{ $loop->first ? 'checked' : '' }} class="hidden template-radio">
                            <div class="template-card border-2 border-gray-200 rounded-xl p-4 hover:border-primary-500 transition-all hover:shadow-md">
                                <div class="flex gap-2 mb-3">
                                    @foreach($template['preview_colors'] as $color)
                                    <div class="w-8 h-8 rounded-lg shadow-sm" style="background: {{ $color }}"></div>
                                    @endforeach
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $template['name'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $template['description'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Color Customization -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                        <div class="flex gap-3">
                            <input type="color" name="primary_color" id="primary_color" value="#1163F0" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                            <input type="text" id="primary_color_text" value="#1163F0" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" readonly>
                        </div>
                    </div>

                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                        <div class="flex gap-3">
                            <input type="color" name="secondary_color" id="secondary_color" value="#1707B2" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                            <input type="text" id="secondary_color_text" value="#1707B2" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" readonly>
                        </div>
                    </div>
                </div>

                <!-- Font Family -->
                <div class="mb-6">
                    <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">Font Family</label>
                    <select name="font_family" id="font_family" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="Inter">Inter - Modern & Clean</option>
                        <option value="Roboto">Roboto - Professional</option>
                        <option value="Poppins">Poppins - Friendly</option>
                        <option value="Lato">Lato - Neutral</option>
                        <option value="Playfair Display">Playfair Display - Elegant</option>
                        <option value="Montserrat">Montserrat - Bold</option>
                        <option value="SF Pro Display">SF Pro Display - Minimal</option>
                        <option value="Open Sans">Open Sans - Classic</option>
                    </select>
                </div>

                <!-- Campaign Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Campaign Logo (Optional)</label>
                    <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/svg+xml" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Max 2MB. Formats: JPEG, PNG, JPG, SVG</p>
                </div>
            </div>

            <!-- Device Assignment -->
            @if($devices->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Devices</h3>
                <p class="text-sm text-gray-600 mb-4">Select which devices will display this campaign</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($devices as $device)
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-primary-500 cursor-pointer transition-all hover:shadow-md">
                        <input type="checkbox" name="devices[]" value="{{ $device->id }}" class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
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
                            <p class="text-xs text-gray-500 mt-1">{{ $device->location }}</p>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-900">No devices available</h4>
                        <p class="text-sm text-blue-700 mt-1">You need to register devices before assigning them to campaigns. <a href="{{ route('organization.devices.create') }}" class="underline font-medium">Register a device now</a></p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('organization.campaigns.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Campaign
                </button>
            </div>
        </form>
    </div>

    <script>
        // Template selection visual feedback
        document.querySelectorAll('.template-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.template-card').forEach(card => {
                    card.classList.remove('border-primary-500', 'shadow-primary', 'bg-primary-50');
                    card.classList.add('border-gray-200');
                });
                if (this.checked) {
                    const card = this.nextElementSibling;
                    card.classList.remove('border-gray-200');
                    card.classList.add('border-primary-500', 'shadow-primary', 'bg-primary-50');
                }
            });
        });

        // Trigger first radio to select default template
        document.querySelector('.template-radio').dispatchEvent(new Event('change'));

        // Color picker sync with text input
        const primaryColor = document.getElementById('primary_color');
        const primaryColorText = document.getElementById('primary_color_text');
        const secondaryColor = document.getElementById('secondary_color');
        const secondaryColorText = document.getElementById('secondary_color_text');

        primaryColor.addEventListener('input', function() {
            primaryColorText.value = this.value.toUpperCase();
        });

        secondaryColor.addEventListener('input', function() {
            secondaryColorText.value = this.value.toUpperCase();
        });
    </script>
</x-organization-layout>
