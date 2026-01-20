<x-organization-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wizard Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Design Your Campaign</h1>
                    <p class="mt-2 text-gray-600">Customize the look and feel of your campaign</p>
                </div>
                <a href="{{ route('organization.campaigns.index') }}" class="btn-secondary">Cancel</a>
            </div>

            <!-- Progress Steps -->
            <div class="flex items-center justify-center space-x-4 mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Layout</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-primary text-white font-semibold shadow-primary">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Design</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Donations</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">4</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Thank You</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">5</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Finish</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('organization.campaigns.wizard.step2.post') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="layout_type" value="{{ session('campaign_wizard.layout_type') }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side: Form -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Campaign Design</h3>

                        @if(in_array(session('campaign_wizard.layout_type'), ['banner_image', 'full_background']))
                        <!-- Background/Banner Image -->
                        <div class="mb-6">
                            <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">
                                @if(session('campaign_wizard.layout_type') == 'banner_image')
                                    Banner Image *
                                @else
                                    Background Image *
                                @endif
                            </label>
                            <input type="file" name="background_image" id="background_image" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 1920x600px for best quality</p>
                        </div>
                        @endif

                        <!-- Campaign Name -->
                        <div class="mb-6">
                            <label for="campaign_name" class="block text-sm font-medium text-gray-700 mb-2">Campaign Name *</label>
                            <input type="text" name="campaign_name" id="campaign_name" value="{{ old('campaign_name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g., Support Our Community">
                        </div>

                        <!-- Heading -->
                        <div class="mb-6">
                            <label for="heading" class="block text-sm font-medium text-gray-700 mb-2">Heading *</label>
                            <input type="text" name="heading" id="heading" value="{{ old('heading') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g., Help Us Make a Difference">
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea name="message" id="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Your donation helps us continue our mission...">{{ old('message') }}</textarea>
                        </div>

                        <!-- Primary Color -->
                        <div class="mb-6">
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary Color *</label>
                            <div class="flex gap-3">
                                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', '#1163F0') }}" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                                <input type="text" id="primary_color_text" value="{{ old('primary_color', '#1163F0') }}" readonly class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono">
                            </div>
                        </div>

                        @if(in_array(session('campaign_wizard.layout_type'), ['dual_color', 'solid_color']))
                        <!-- Accent/Secondary Color -->
                        <div class="mb-6">
                            <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                                @if(session('campaign_wizard.layout_type') == 'dual_color')
                                    Body Color *
                                @else
                                    Accent Color
                                @endif
                            </label>
                            <div class="flex gap-3">
                                <input type="color" name="accent_color" id="accent_color" value="{{ old('accent_color', '#F3F4F6') }}" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                                <input type="text" id="accent_color_text" value="{{ old('accent_color', '#F3F4F6') }}" readonly class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono">
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('organization.campaigns.wizard.step1') }}" class="btn-secondary">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </a>
                        <button type="submit" class="btn-primary">
                            Continue to Donations
                            <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right Side: Live Preview -->
                <div class="lg:sticky lg:top-8 lg:self-start">
                    <div class="bg-gray-900 rounded-2xl p-6 shadow-2xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-white font-semibold">Live Preview</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                        </div>

                        <div id="preview" class="bg-white rounded-xl overflow-hidden shadow-xl aspect-[9/16] max-h-[600px] relative">
                            <!-- Preview will be rendered here dynamically -->
                            <div id="preview-content" class="h-full flex flex-col">
                                <!-- Dynamic content based on layout type -->
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mt-4 text-center">Changes appear instantly</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const layoutType = '{{ session("campaign_wizard.layout_type") }}';
        const preview = document.getElementById('preview-content');
        const backgroundImageInput = document.getElementById('background_image');
        const headingInput = document.getElementById('heading');
        const messageInput = document.getElementById('message');
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorText = document.getElementById('primary_color_text');
        const accentColorInput = document.getElementById('accent_color');
        const accentColorText = document.getElementById('accent_color_text');

        let backgroundImageUrl = '';

        // Color picker sync
        primaryColorInput?.addEventListener('input', function() {
            primaryColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        accentColorInput?.addEventListener('input', function() {
            accentColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        // Background image handler
        backgroundImageInput?.addEventListener('change', function(e) {
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

        // Text inputs
        headingInput?.addEventListener('input', updatePreview);
        messageInput?.addEventListener('input', updatePreview);

        function updatePreview() {
            const heading = headingInput?.value || 'Your Campaign Heading';
            const message = messageInput?.value || 'Your campaign message will appear here...';
            const primaryColor = primaryColorInput?.value || '#1163F0';
            const accentColor = accentColorInput?.value || '#F3F4F6';

            let html = '';

            switch(layoutType) {
                case 'solid_color':
                    html = `
                        <div class="h-full flex flex-col items-center justify-center p-8 text-center" style="background-color: ${primaryColor}">
                            <h1 class="text-2xl font-bold text-white mb-4">${heading}</h1>
                            <p class="text-white opacity-90 mb-8 text-sm">${message}</p>
                            <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                                <button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-lg">€10</button>
                                <button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-lg">€25</button>
                            </div>
                        </div>
                    `;
                    break;

                case 'dual_color':
                    html = `
                        <div class="h-1/3 flex flex-col items-center justify-center p-6 text-center" style="background-color: ${primaryColor}">
                            <h1 class="text-xl font-bold text-white">${heading}</h1>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center p-8" style="background-color: ${accentColor}">
                            <p class="text-gray-700 mb-8 text-sm text-center">${message}</p>
                            <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                                <button class="px-6 py-3 rounded-lg font-semibold shadow-lg text-white" style="background-color: ${primaryColor}">€10</button>
                                <button class="px-6 py-3 rounded-lg font-semibold shadow-lg text-white" style="background-color: ${primaryColor}">€25</button>
                            </div>
                        </div>
                    `;
                    break;

                case 'banner_image':
                    html = `
                        <div class="h-1/3 relative" style="background: ${backgroundImageUrl ? `url(${backgroundImageUrl})` : 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + primaryColor + 'dd 100%)'} center/cover">
                            ${!backgroundImageUrl ? '<div class="absolute inset-0 flex items-center justify-center text-white opacity-50"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>' : ''}
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center p-8" style="background-color: ${accentColor || '#ffffff'}">
                            <h1 class="text-xl font-bold mb-4" style="color: ${primaryColor}">${heading}</h1>
                            <p class="text-gray-700 mb-8 text-sm text-center">${message}</p>
                            <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                                <button class="px-6 py-3 rounded-lg font-semibold shadow-lg text-white" style="background-color: ${primaryColor}">€10</button>
                                <button class="px-6 py-3 rounded-lg font-semibold shadow-lg text-white" style="background-color: ${primaryColor}">€25</button>
                            </div>
                        </div>
                    `;
                    break;

                case 'full_background':
                    html = `
                        <div class="h-full relative flex flex-col items-center justify-center p-8 text-center" style="background: ${backgroundImageUrl ? `url(${backgroundImageUrl})` : 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + primaryColor + 'aa 100%)'} center/cover">
                            ${!backgroundImageUrl ? '<div class="absolute inset-0 flex items-center justify-center opacity-20"><svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>' : ''}
                            <div class="absolute inset-0 bg-black opacity-40"></div>
                            <div class="relative z-10">
                                <h1 class="text-2xl font-bold text-white mb-4">${heading}</h1>
                                <p class="text-white opacity-90 mb-8 text-sm">${message}</p>
                                <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                                    <button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-xl">€10</button>
                                    <button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-xl">€25</button>
                                </div>
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
