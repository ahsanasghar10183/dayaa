<x-organization-sidebar-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wizard Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Configure Donation Amounts</h1>
                    <p class="mt-2 text-gray-600">Set up donation options for your donors</p>
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
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-primary text-white font-semibold shadow-primary">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Donations</span>
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

        <form method="POST" action="{{ route('organization.campaigns.wizard.step3.post') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side: Form -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Donation Amounts</h3>
                            <button type="button" id="addAmountBtn" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Amount
                            </button>
                        </div>

                        <div id="amountsContainer" class="space-y-3 mb-6">
                            <!-- Default amounts -->
                            <div class="donation-amount flex items-center gap-3">
                                <span class="text-2xl">€</span>
                                <input type="number" name="amounts[]" value="10" min="1" step="0.01" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent amount-input" placeholder="Amount">
                                <button type="button" class="remove-amount text-red-500 hover:text-red-700 opacity-0 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="donation-amount flex items-center gap-3">
                                <span class="text-2xl">€</span>
                                <input type="number" name="amounts[]" value="25" min="1" step="0.01" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent amount-input" placeholder="Amount">
                                <button type="button" class="remove-amount text-red-500 hover:text-red-700 opacity-0 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Button Position -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Button Position</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="button_position" value="top" class="hidden position-radio" checked>
                                    <div class="position-card border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Top</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="button_position" value="middle" class="hidden position-radio">
                                    <div class="position-card border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Middle</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="button_position" value="bottom" class="hidden position-radio">
                                    <div class="position-card border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary-500 transition-all">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Bottom</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Show Custom Amount Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Show "Choose Your Own Amount" Button</h4>
                                <p class="text-xs text-gray-500 mt-1">Allow donors to enter custom amounts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_custom_amount" id="customAmountToggle" value="1" checked class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('organization.campaigns.wizard.step2') }}" class="btn-secondary">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </a>
                        <button type="submit" class="btn-primary">
                            Continue to Thank You Screen
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

                        <div id="preview" class="bg-white rounded-xl overflow-hidden shadow-xl aspect-[9/16] max-h-[600px]">
                            <div id="preview-content" class="h-full flex flex-col"></div>
                        </div>

                        <p class="text-xs text-gray-400 mt-4 text-center">Buttons update in real-time</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const layoutType = '{{ session("campaign_wizard.layout_type") }}';
        const primaryColor = '{{ session("campaign_wizard.primary_color", "#1163F0") }}';
        const accentColor = '{{ session("campaign_wizard.accent_color", "#F3F4F6") }}';
        const heading = '{{ session("campaign_wizard.heading", "Your Campaign Heading") }}';
        const message = '{{ session("campaign_wizard.message", "Your message here...") }}';
        const backgroundImage = '{{ session("campaign_wizard.background_image_url", "") }}';

        const preview = document.getElementById('preview-content');
        const amountsContainer = document.getElementById('amountsContainer');
        const addAmountBtn = document.getElementById('addAmountBtn');
        const customAmountToggle = document.getElementById('customAmountToggle');
        const positionRadios = document.querySelectorAll('.position-radio');
        const positionCards = document.querySelectorAll('.position-card');

        let currentPosition = 'top';
        let showCustomAmount = true;

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

        // Trigger first position
        positionRadios[0].dispatchEvent(new Event('change'));

        // Custom amount toggle
        customAmountToggle.addEventListener('change', function() {
            showCustomAmount = this.checked;
            updatePreview();
        });

        // Add new amount
        addAmountBtn.addEventListener('click', function() {
            const newAmount = document.createElement('div');
            newAmount.className = 'donation-amount flex items-center gap-3 animate-slideDown';
            newAmount.innerHTML = `
                <span class="text-2xl">€</span>
                <input type="number" name="amounts[]" value="" min="1" step="0.01" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent amount-input" placeholder="Amount">
                <button type="button" class="remove-amount text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            amountsContainer.appendChild(newAmount);

            // Add event listeners
            const input = newAmount.querySelector('.amount-input');
            const removeBtn = newAmount.querySelector('.remove-amount');
            input.addEventListener('input', updatePreview);
            removeBtn.addEventListener('click', () => removeAmount(newAmount));

            updateRemoveButtons();
            updatePreview();
        });

        // Remove amount
        function removeAmount(element) {
            element.remove();
            updateRemoveButtons();
            updatePreview();
        }

        // Update remove button visibility
        function updateRemoveButtons() {
            const amounts = document.querySelectorAll('.donation-amount');
            amounts.forEach((amount, index) => {
                const removeBtn = amount.querySelector('.remove-amount');
                if (amounts.length > 1) {
                    removeBtn.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    removeBtn.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        // Listen to amount input changes
        document.querySelectorAll('.amount-input').forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        function updatePreview() {
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

            let html = '';
            const justifyClass = currentPosition === 'top' ? 'justify-start pt-12' : currentPosition === 'bottom' ? 'justify-end pb-12' : 'justify-center';

            switch(layoutType) {
                case 'solid_color':
                    html = `
                        <div class="h-full flex flex-col items-center ${justifyClass} p-8 text-center" style="background-color: ${primaryColor}">
                            ${currentPosition !== 'bottom' ? `<h1 class="text-2xl font-bold text-white mb-4">${heading}</h1><p class="text-white opacity-90 mb-8 text-sm">${message}</p>` : ''}
                            ${buttonContainer}
                            ${currentPosition === 'bottom' ? `<h1 class="text-2xl font-bold text-white mt-8 mb-4">${heading}</h1><p class="text-white opacity-90 text-sm">${message}</p>` : ''}
                        </div>
                    `;
                    break;

                case 'dual_color':
                    html = `
                        <div class="h-1/3 flex flex-col items-center justify-center p-6 text-center" style="background-color: ${primaryColor}">
                            <h1 class="text-xl font-bold text-white">${heading}</h1>
                        </div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8" style="background-color: ${accentColor}">
                            ${currentPosition !== 'bottom' ? `<p class="text-gray-700 mb-8 text-sm text-center">${message}</p>` : ''}
                            ${buttonContainer}
                            ${currentPosition === 'bottom' ? `<p class="text-gray-700 mt-8 text-sm text-center">${message}</p>` : ''}
                        </div>
                    `;
                    break;

                case 'banner_image':
                    html = `
                        <div class="h-1/3 relative" style="background: ${backgroundImage ? `url(/storage/${backgroundImage})` : 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + primaryColor + 'dd 100%)'} center/cover"></div>
                        <div class="flex-1 flex flex-col items-center ${justifyClass} p-8" style="background-color: ${accentColor || '#ffffff'}">
                            ${currentPosition !== 'bottom' ? `<h1 class="text-xl font-bold mb-4" style="color: ${primaryColor}">${heading}</h1><p class="text-gray-700 mb-8 text-sm text-center">${message}</p>` : ''}
                            ${buttonContainer}
                            ${currentPosition === 'bottom' ? `<h1 class="text-xl font-bold mt-8 mb-4" style="color: ${primaryColor}">${heading}</h1><p class="text-gray-700 text-sm text-center">${message}</p>` : ''}
                        </div>
                    `;
                    break;

                case 'full_background':
                    html = `
                        <div class="h-full relative flex flex-col items-center ${justifyClass} p-8 text-center" style="background: ${backgroundImage ? `url(/storage/${backgroundImage})` : 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + primaryColor + 'aa 100%)'} center/cover">
                            <div class="absolute inset-0 bg-black opacity-40"></div>
                            <div class="relative z-10">
                                ${currentPosition !== 'bottom' ? `<h1 class="text-2xl font-bold text-white mb-4">${heading}</h1><p class="text-white opacity-90 mb-8 text-sm">${message}</p>` : ''}
                                <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                                    ${amounts.map(amount => `<button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-xl">€${amount.toFixed(2)}</button>`).join('')}
                                    ${showCustomAmount ? `<button class="px-6 py-3 bg-white bg-opacity-20 border-2 border-white text-white rounded-lg font-semibold shadow-xl">Custom Amount</button>` : ''}
                                </div>
                                ${currentPosition === 'bottom' ? `<h1 class="text-2xl font-bold text-white mt-8 mb-4">${heading}</h1><p class="text-white opacity-90 text-sm">${message}</p>` : ''}
                            </div>
                        </div>
                    `;
                    break;
            }

            preview.innerHTML = html;
        }

        // Initial render
        updateRemoveButtons();
        updatePreview();
    </script>
</x-organization-sidebar-layout>
