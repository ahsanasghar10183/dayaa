<x-kiosk-layout>
    <div id="kioskApp" class="w-full h-full">
        <!-- Loading Screen -->
        <div id="loadingScreen" class="hidden fixed inset-0 bg-white z-50 flex items-center justify-center">
            <div class="text-center">
                <svg class="animate-spin h-20 w-20 text-primary-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-2xl text-gray-700">Loading...</p>
            </div>
        </div>

        <!-- Main Campaign Display -->
        <div id="campaignScreen" class="w-full h-full flex flex-col">
            <!-- Dynamic Campaign Content -->
            <div id="campaignContent" class="flex-1 flex flex-col relative overflow-hidden">
                @php
                    // Ensure settings are arrays (handle both string and array cases)
                    $settings = is_array($campaign->design_settings)
                        ? $campaign->design_settings
                        : (is_string($campaign->design_settings)
                            ? json_decode($campaign->design_settings, true)
                            : []);

                    $amountSettings = is_array($campaign->amount_settings)
                        ? $campaign->amount_settings
                        : (is_string($campaign->amount_settings)
                            ? json_decode($campaign->amount_settings, true)
                            : []);

                    // Debug: Log the settings (will be visible in browser console via JavaScript)
                    $debugInfo = [
                        'campaign_id' => $campaign->id,
                        'campaign_name' => $campaign->name,
                        'settings_type' => gettype($settings),
                        'settings' => $settings,
                        'amounts_type' => gettype($amountSettings),
                        'amounts' => $amountSettings,
                    ];

                    // Extract layout and design settings with fallbacks
                    $layoutType = $settings['layout_type'] ?? 'solid_color';
                    $heading = $settings['heading'] ?? $campaign->name ?? 'Support Our Cause';
                    $message = $settings['message'] ?? $campaign->description ?? '';
                    $primaryColor = $settings['primary_color'] ?? '#1163F0';
                    $accentColor = $settings['accent_color'] ?? '#F3F4F6';
                    $backgroundImage = $settings['background_image'] ?? null;
                    $logo = $settings['logo'] ?? null;

                    // Extract amount settings with fallbacks
                    $amounts = $amountSettings['preset_amounts'] ?? $amountSettings['amounts'] ?? [5, 10, 20, 50, 100];
                    // Ensure amounts are numeric
                    $amounts = array_map('floatval', array_filter($amounts, function($a) { return $a > 0; }));
                    $showCustom = $amountSettings['allow_custom_amount'] ?? $amountSettings['allow_custom'] ?? true;
                    $buttonPosition = $amountSettings['button_position'] ?? 'middle';
                @endphp

                <!-- Debug Info (visible in console) -->
                <script>
                    console.log('=== KIOSK DEBUG INFO ===');
                    console.log(@json($debugInfo));
                    console.log('Layout Type:', '{{ $layoutType }}');
                    console.log('Heading:', '{{ $heading }}');
                    console.log('Primary Color:', '{{ $primaryColor }}');
                    console.log('Accent Color:', '{{ $accentColor }}');
                    console.log('Amounts:', @json($amounts));
                    console.log('Button Position:', '{{ $buttonPosition }}');
                </script>

                <!-- Background Layer -->
                @if($layoutType === 'solid_color')
                    <div class="absolute inset-0" style="background-color: {{ $primaryColor }};"></div>
                @elseif($layoutType === 'dual_color')
                    <div class="absolute top-0 left-0 right-0 h-1/3" style="background-color: {{ $primaryColor }};"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-2/3" style="background-color: {{ $accentColor }};"></div>
                @elseif($layoutType === 'banner_image')
                    @if($backgroundImage)
                        <div class="absolute top-0 left-0 right-0 h-1/3 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $backgroundImage) }}');"></div>
                    @else
                        <div class="absolute top-0 left-0 right-0 h-1/3" style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}dd 100%);"></div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 h-2/3" style="background-color: {{ $accentColor ?? '#ffffff' }};"></div>
                @elseif($layoutType === 'full_background')
                    @if($backgroundImage)
                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $backgroundImage) }}');"></div>
                        <div class="absolute inset-0 bg-black opacity-40"></div>
                    @else
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}aa 100%);"></div>
                    @endif
                @endif

                <!-- Content Container -->
                <div class="relative z-10 flex flex-col h-full p-12 {{ $buttonPosition === 'top' ? 'justify-start pt-16' : ($buttonPosition === 'bottom' ? 'justify-end pb-16' : 'justify-center') }}">
                    <!-- Header Section -->
                    @if($buttonPosition !== 'top')
                    <div class="text-center {{ $buttonPosition === 'middle' ? 'mb-12' : 'mb-8' }}">
                        @if(!empty($logo))
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-24 mx-auto mb-6">
                        @endif

                        <h1 class="text-6xl font-bold mb-6" style="color: {{ $layoutType === 'full_background' || ($layoutType === 'dual_color' && $buttonPosition !== 'bottom') ? '#FFFFFF' : $primaryColor }};">
                            {{ $heading }}
                        </h1>

                        @if($message)
                            <p class="text-4xl max-w-4xl mx-auto leading-relaxed" style="color: {{ $layoutType === 'full_background' || ($layoutType === 'dual_color' && $buttonPosition !== 'bottom') ? '#FFFFFF' : '#374151' }};">
                                {{ $message }}
                            </p>
                        @endif
                    </div>
                    @endif

                    <!-- Amount Selection -->
                    <div class="w-full max-w-5xl mx-auto">
                        <!-- Amount Buttons Grid -->
                        <div class="grid grid-cols-3 gap-8 mb-8">
                            @foreach($amounts as $amount)
                                @if($amount)
                                    <button
                                        onclick="selectAmount({{ $amount }})"
                                        class="touch-btn bg-white hover:bg-gray-50 border-4 rounded-[32px] p-10 min-w-[200px] transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-2xl group"
                                        style="border-color: {{ $primaryColor }};"
                                    >
                                        <div class="text-[64px] font-bold leading-tight" style="color: {{ $primaryColor }};">
                                            €{{ number_format($amount, 0) }}
                                        </div>
                                    </button>
                                @endif
                            @endforeach
                        </div>

                        <!-- Custom Amount -->
                        @if($showCustom)
                            <button
                                onclick="showCustomAmount()"
                                class="w-full touch-btn text-white text-3xl font-bold py-7 rounded-[32px] shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200"
                                style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}dd 100%);"
                            >
                                <svg class="w-10 h-10 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Enter Custom Amount
                            </button>
                        @endif
                    </div>

                    <!-- Header Section (if bottom position) -->
                    @if($buttonPosition === 'bottom')
                    <div class="text-center mt-8">
                        @if(!empty($logo))
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-24 mx-auto mb-6">
                        @endif

                        <h1 class="text-6xl font-bold mb-6" style="color: {{ $layoutType === 'full_background' ? '#FFFFFF' : ($layoutType === 'dual_color' ? '#FFFFFF' : $primaryColor) }};">
                            {{ $heading }}
                        </h1>

                        @if($message)
                            <p class="text-4xl max-w-4xl mx-auto leading-relaxed" style="color: {{ $layoutType === 'full_background' ? '#FFFFFF' : ($layoutType === 'dual_color' ? '#FFFFFF' : '#374151') }};">
                                {{ $message }}
                            </p>
                        @endif
                    </div>
                    @endif

                    <!-- Footer Hint -->
                    @if($layoutType !== 'full_background')
                        <div class="text-center text-xl mt-8" style="color: {{ $layoutType === 'dual_color' && $buttonPosition === 'top' ? '#FFFFFF' : '#6B7280' }};">
                            <p>Tap an amount to continue</p>
                        </div>
                    @else
                        <div class="text-center text-white opacity-90 text-xl mt-8">
                            <p>Tap an amount to continue</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Custom Amount Modal -->
        <div id="customAmountModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center">
            <div class="bg-white rounded-3xl shadow-2xl p-12 max-w-2xl w-full mx-4">
                <h2 class="text-4xl font-bold text-gray-900 mb-8 text-center">Enter Amount</h2>

                <div class="mb-8">
                    <input
                        type="text"
                        id="customAmountInput"
                        class="w-full text-6xl font-bold text-center border-4 border-primary-500 rounded-2xl p-6 focus:outline-none focus:ring-4 focus:ring-primary-300"
                        placeholder="0"
                        readonly
                    >
                </div>

                <!-- Number Pad -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    @for($i = 1; $i <= 9; $i++)
                        <button onclick="appendNumber('{{ $i }}')" class="touch-btn bg-gray-100 hover:bg-gray-200 text-4xl font-bold py-6 rounded-2xl transition-colors">
                            {{ $i }}
                        </button>
                    @endfor
                    <button onclick="clearAmount()" class="touch-btn bg-red-100 hover:bg-red-200 text-red-600 text-3xl font-bold py-6 rounded-2xl transition-colors">
                        Clear
                    </button>
                    <button onclick="appendNumber('0')" class="touch-btn bg-gray-100 hover:bg-gray-200 text-4xl font-bold py-6 rounded-2xl transition-colors">
                        0
                    </button>
                    <button onclick="appendNumber('00')" class="touch-btn bg-gray-100 hover:bg-gray-200 text-4xl font-bold py-6 rounded-2xl transition-colors">
                        00
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button onclick="closeCustomAmount()" class="touch-btn bg-gray-300 hover:bg-gray-400 text-gray-800 text-2xl font-bold py-6 rounded-2xl transition-colors">
                        Cancel
                    </button>
                    <button onclick="confirmCustomAmount()" class="touch-btn bg-gradient-to-r from-primary-500 to-blue-600 hover:from-primary-600 hover:to-blue-700 text-white text-2xl font-bold py-6 rounded-2xl transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedAmount = 0;
        let customAmountValue = '';
        let campaignData = @json($campaign);
        let deviceData = @json($device);
        let inactivityTimer = null;
        let heartbeatInterval = null;
        let pollInterval = null;

        // Amount selection
        function selectAmount(amount) {
            selectedAmount = amount;
            proceedToPayment();
        }

        // Custom amount functions
        function showCustomAmount() {
            document.getElementById('customAmountModal').classList.remove('hidden');
            customAmountValue = '';
            document.getElementById('customAmountInput').value = '';
        }

        function closeCustomAmount() {
            document.getElementById('customAmountModal').classList.add('hidden');
        }

        function appendNumber(num) {
            customAmountValue += num;
            document.getElementById('customAmountInput').value = customAmountValue;
        }

        function clearAmount() {
            customAmountValue = '';
            document.getElementById('customAmountInput').value = '';
        }

        function confirmCustomAmount() {
            const amount = parseInt(customAmountValue);
            if (amount && amount > 0) {
                selectedAmount = amount;
                closeCustomAmount();
                proceedToPayment();
            } else {
                alert('Please enter a valid amount');
            }
        }

        // Proceed to payment
        function proceedToPayment() {
            console.log('Proceeding with amount:', selectedAmount);
            // TODO: Implement payment flow in next step
            alert('Payment processing will be implemented in the next phase.\nSelected amount: €' + selectedAmount);

            // Reset after 3 seconds
            setTimeout(resetKiosk, 3000);
        }

        // Reset kiosk to initial state
        function resetKiosk() {
            selectedAmount = 0;
            customAmountValue = '';
            closeCustomAmount();
            resetInactivityTimer();
        }

        // Inactivity timer (reset after 60 seconds of inactivity)
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                console.log('Inactivity timeout - resetting kiosk');
                resetKiosk();
            }, 60000); // 60 seconds
        }

        // Track user activity
        document.addEventListener('click', resetInactivityTimer);
        document.addEventListener('touchstart', resetInactivityTimer);

        // Heartbeat to keep device online (every 30 seconds)
        function sendHeartbeat() {
            fetch('{{ route('kiosk.heartbeat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Heartbeat sent:', data);
            })
            .catch(error => {
                console.error('Heartbeat error:', error);
            });
        }

        // Poll for campaign updates (every 30 seconds)
        function pollCampaignUpdates() {
            fetch('{{ route('kiosk.get-campaign') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.campaign && data.campaign.id !== campaignData.id) {
                    console.log('Campaign changed, reloading...');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Poll error:', error);
            });
        }

        // Initialize on page load
        window.addEventListener('load', function() {
            resetInactivityTimer();

            // Start heartbeat
            heartbeatInterval = setInterval(sendHeartbeat, 30000); // Every 30 seconds

            // Start polling for updates
            pollInterval = setInterval(pollCampaignUpdates, 30000); // Every 30 seconds
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            clearInterval(heartbeatInterval);
            clearInterval(pollInterval);
            clearTimeout(inactivityTimer);
        });
    </script>
    @endpush
</x-kiosk-layout>
