<x-kiosk-layout>
    @php
        $campaign = $donation?->campaign;
        $settings = is_array($campaign?->design_settings)
            ? $campaign->design_settings
            : (is_string($campaign?->design_settings)
                ? json_decode($campaign->design_settings, true) ?: []
                : []);

        $primaryColor = $settings['primary_color'] ?? '#1163F0';
        $thankyouMessage = $campaign->thankyou_message ?? 'Thank you for your generosity!';
        $thankyouSubtitle = $campaign->thankyou_subtitle ?? 'Your donation makes a difference.';
    @endphp

    <div class="w-full h-full flex items-center justify-center p-12 relative overflow-hidden"
         style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}dd 100%);">

        <div class="text-center max-w-3xl text-white">
            <!-- Check icon -->
            <div class="inline-flex items-center justify-center w-40 h-40 bg-white rounded-full mb-10 shadow-2xl">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: {{ $primaryColor }};">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                          d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-7xl font-bold mb-6">{{ $thankyouMessage }}</h1>
            <p class="text-3xl mb-10 opacity-90">{{ $thankyouSubtitle }}</p>

            @if($donation)
                <div class="bg-white bg-opacity-15 backdrop-blur rounded-3xl p-8 mb-10 inline-block">
                    <div class="text-2xl opacity-80 mb-2">Donation Amount</div>
                    <div class="text-6xl font-bold mb-6">
                        €{{ number_format((float) $donation->amount, 2) }}
                    </div>
                    <div class="text-xl opacity-80">Receipt: {{ $donation->receipt_number }}</div>
                </div>
            @endif

            <div class="text-2xl opacity-90 mt-6">
                Returning to home in <span id="countdown">10</span>s...
            </div>

            <button onclick="goHome()"
                    class="touch-btn mt-8 bg-white text-2xl font-bold py-5 px-12 rounded-2xl shadow-xl hover:scale-105 transition-transform"
                    style="color: {{ $primaryColor }};">
                Done
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        let countdown = 10;
        const countdownEl = document.getElementById('countdown');

        function goHome() {
            window.location.href = '{{ route('kiosk.display') }}';
        }

        const countdownInterval = setInterval(() => {
            countdown -= 1;
            if (countdownEl) countdownEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                goHome();
            }
        }, 1000);

        window.addEventListener('beforeunload', () => clearInterval(countdownInterval));
    </script>
    @endpush
</x-kiosk-layout>
