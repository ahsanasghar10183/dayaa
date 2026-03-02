<x-organization-sidebar-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wizard Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Customize Thank You Screen</h1>
                    <p class="mt-2 text-gray-600">Design the screen donors see after completing their donation</p>
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
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Design</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Donations</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-primary text-white font-semibold shadow-primary">4</div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Thank You</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">5</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Finish</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('organization.campaigns.wizard.step4.post') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side: Form -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Thank You Screen Design</h3>

                        @if(in_array(session('campaign_wizard.layout_type'), ['banner_image', 'full_background']))
                        <!-- Thank You Screen Image (Optional different image) -->
                        <div class="mb-6">
                            <label class="flex items-center justify-between mb-2">
                                <span class="block text-sm font-medium text-gray-700">
                                    @if(session('campaign_wizard.layout_type') == 'banner_image')
                                        Thank You Banner Image
                                    @else
                                        Thank You Background Image
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500">(Optional - uses campaign image by default)</span>
                            </label>
                            <input type="file" name="thankyou_image" id="thankyou_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Leave empty to use the same image as campaign screen</p>
                        </div>
                        @endif

                        @if(session('campaign_wizard.layout_type') == 'solid_color')
                        <!-- Thank You Background Color -->
                        <div class="mb-6">
                            <label for="thankyou_color" class="block text-sm font-medium text-gray-700 mb-2">Thank You Screen Color</label>
                            <div class="flex gap-3">
                                <input type="color" name="thankyou_color" id="thankyou_color" value="{{ old('thankyou_color', session('campaign_wizard.primary_color', '#10B981')) }}" class="h-12 w-20 rounded-lg border-2 border-gray-200 cursor-pointer">
                                <input type="text" id="thankyou_color_text" value="{{ old('thankyou_color', session('campaign_wizard.primary_color', '#10B981')) }}" readonly class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Can be different from campaign color</p>
                        </div>
                        @endif

                        @if(session('campaign_wizard.layout_type') == 'dual_color')
                        <!-- Thank You Header & Body Colors -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="thankyou_header_color" class="block text-sm font-medium text-gray-700 mb-2">Header Color</label>
                                <div class="flex gap-2">
                                    <input type="color" name="thankyou_header_color" id="thankyou_header_color" value="{{ old('thankyou_header_color', session('campaign_wizard.primary_color', '#10B981')) }}" class="h-12 w-16 rounded-lg border-2 border-gray-200 cursor-pointer">
                                    <input type="text" id="thankyou_header_color_text" value="{{ old('thankyou_header_color', session('campaign_wizard.primary_color', '#10B981')) }}" readonly class="flex-1 px-2 py-2 border border-gray-300 rounded-lg bg-gray-50 text-xs text-gray-700 font-mono">
                                </div>
                            </div>
                            <div>
                                <label for="thankyou_body_color" class="block text-sm font-medium text-gray-700 mb-2">Body Color</label>
                                <div class="flex gap-2">
                                    <input type="color" name="thankyou_body_color" id="thankyou_body_color" value="{{ old('thankyou_body_color', session('campaign_wizard.accent_color', '#F0FDF4')) }}" class="h-12 w-16 rounded-lg border-2 border-gray-200 cursor-pointer">
                                    <input type="text" id="thankyou_body_color_text" value="{{ old('thankyou_body_color', session('campaign_wizard.accent_color', '#F0FDF4')) }}" readonly class="flex-1 px-2 py-2 border border-gray-300 rounded-lg bg-gray-50 text-xs text-gray-700 font-mono">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Thank You Message -->
                        <div class="mb-6">
                            <label for="thankyou_message" class="block text-sm font-medium text-gray-700 mb-2">Thank You Message *</label>
                            <textarea name="thankyou_message" id="thankyou_message" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Thank you for your generous donation!">{{ old('thankyou_message', 'Thank you for your generous donation!') }}</textarea>
                        </div>

                        <!-- Thank You Subtitle -->
                        <div class="mb-6">
                            <label for="thankyou_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <input type="text" name="thankyou_subtitle" id="thankyou_subtitle" value="{{ old('thankyou_subtitle', 'Your support makes a real difference in our community.') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Additional message...">
                        </div>

                        <!-- Message Position -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Message Position</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="thankyou_position" value="top" class="hidden position-radio">
                                    <div class="position-card border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Top</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="thankyou_position" value="middle" class="hidden position-radio" checked>
                                    <div class="position-card border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Middle</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="thankyou_position" value="bottom" class="hidden position-radio">
                                    <div class="position-card border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Bottom</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Offer Donation Receipt Toggle -->
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
                                <input type="checkbox" name="offer_receipt" id="offerReceiptToggle" value="1" checked class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('organization.campaigns.wizard.step3') }}" class="btn-secondary">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </a>
                        <button type="submit" class="btn-primary">
                            Continue to Final Details
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
                            <h3 class="text-white font-semibold">Thank You Screen Preview</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                        </div>

                        <div id="preview" class="bg-white rounded-xl overflow-hidden shadow-xl aspect-[9/16] max-h-[600px]">
                            <div id="preview-content" class="h-full flex flex-col"></div>
                        </div>

                        <p class="text-xs text-gray-400 mt-4 text-center">This is what donors will see</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const layoutType = '{{ session("campaign_wizard.layout_type") }}';
        const primaryColor = '{{ session("campaign_wizard.primary_color", "#10B981") }}';
        const accentColor = '{{ session("campaign_wizard.accent_color", "#F0FDF4") }}';
        const backgroundImage = '{{ session("campaign_wizard.background_image_url", "") }}';

        const preview = document.getElementById('preview-content');
        const thankyouImageInput = document.getElementById('thankyou_image');
        const thankyouMessageInput = document.getElementById('thankyou_message');
        const thankyouSubtitleInput = document.getElementById('thankyou_subtitle');
        const thankyouColorInput = document.getElementById('thankyou_color');
        const thankyouColorText = document.getElementById('thankyou_color_text');
        const thankyouHeaderColorInput = document.getElementById('thankyou_header_color');
        const thankyouHeaderColorText = document.getElementById('thankyou_header_color_text');
        const thankyouBodyColorInput = document.getElementById('thankyou_body_color');
        const thankyouBodyColorText = document.getElementById('thankyou_body_color_text');
        const offerReceiptToggle = document.getElementById('offerReceiptToggle');
        const positionRadios = document.querySelectorAll('.position-radio');
        const positionCards = document.querySelectorAll('.position-card');

        let thankyouImageUrl = '';
        let currentPosition = 'middle';
        let showReceipt = true;

        // Color pickers
        thankyouColorInput?.addEventListener('input', function() {
            thankyouColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        thankyouHeaderColorInput?.addEventListener('input', function() {
            thankyouHeaderColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        thankyouBodyColorInput?.addEventListener('input', function() {
            thankyouBodyColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        // Image upload
        thankyouImageInput?.addEventListener('change', function(e) {
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

        // Text inputs
        thankyouMessageInput?.addEventListener('input', updatePreview);
        thankyouSubtitleInput?.addEventListener('input', updatePreview);

        // Position selection
        positionRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                positionCards.forEach(card => {
                    card.classList.remove('border-primary-500', 'bg-primary-50');
                    card.classList.add('border-gray-200');
                });
                if (this.checked) {
                    positionCards[index].classList.remove('border-gray-200');
                    positionCards[index].classList.add('border-primary-500', 'bg-primary-50');
                    currentPosition = this.value;
                    updatePreview();
                }
            });
        });

        // Trigger checked position
        const checkedPosition = document.querySelector('.position-radio:checked');
        if (checkedPosition) {
            checkedPosition.dispatchEvent(new Event('change'));
        }

        // Receipt toggle
        offerReceiptToggle?.addEventListener('change', function() {
            showReceipt = this.checked;
            updatePreview();
        });

        function updatePreview() {
            const message = thankyouMessageInput?.value || 'Thank you for your generous donation!';
            const subtitle = thankyouSubtitleInput?.value || 'Your support makes a real difference.';
            const tyColor = thankyouColorInput?.value || primaryColor;
            const tyHeaderColor = thankyouHeaderColorInput?.value || primaryColor;
            const tyBodyColor = thankyouBodyColorInput?.value || accentColor;

            const imageUrl = thankyouImageUrl || (backgroundImage ? `/storage/${backgroundImage}` : '');
            const justifyClass = currentPosition === 'top' ? 'justify-start pt-12' : currentPosition === 'bottom' ? 'justify-end pb-12' : 'justify-center';

            const successIcon = `
                <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full shadow-lg animate-scaleIn">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            `;

            const receiptButton = showReceipt ? `
                <button class="mt-6 px-6 py-3 bg-white border-2 border-green-600 text-green-600 rounded-lg font-semibold shadow-md hover:bg-green-50 flex items-center mx-auto">
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
                        <div class="h-full flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: ${tyColor}">
                            ${successIcon}
                            <h1 class="text-2xl font-bold text-white mb-3">${message}</h1>
                            <p class="text-white opacity-90 text-sm max-w-sm">${subtitle}</p>
                            ${receiptButton}
                        </div>
                    `;
                    break;

                case 'dual_color':
                    html = `
                        <div class="h-1/3 flex flex-col items-center justify-center p-6 text-center" style="background-color: ${tyHeaderColor}">
                            ${successIcon}
                        </div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: ${tyBodyColor}">
                            <h1 class="text-2xl font-bold mb-3" style="color: ${tyHeaderColor}">${message}</h1>
                            <p class="text-gray-700 text-sm max-w-sm">${subtitle}</p>
                            ${receiptButton}
                        </div>
                    `;
                    break;

                case 'banner_image':
                    html = `
                        <div class="h-1/3 relative" style="background: ${imageUrl ? `url(${imageUrl})` : 'linear-gradient(135deg, #10B981 0%, #059669 100%)'} center/cover">
                            ${!imageUrl ? '<div class="absolute inset-0 flex items-center justify-center text-white opacity-30"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>' : ''}
                        </div>
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
                            ${!imageUrl ? '<div class="absolute inset-0 flex items-center justify-center opacity-10"><svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>' : ''}
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
</x-organization-sidebar-layout>
