<x-kiosk-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-500 to-blue-600 p-8">
        <div class="w-full max-w-2xl">
            <!-- Logo/Branding -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl shadow-2xl mb-6">
                    <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold text-white mb-3">Device Pairing</h1>
                <p class="text-2xl text-blue-100">Enter your Device ID to get started</p>
            </div>

            <!-- Pairing Form Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-12">
                @if(session('error'))
                <div class="mb-8 bg-red-50 border-2 border-red-200 rounded-2xl p-6">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-red-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xl text-red-800 font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="mb-8 bg-green-50 border-2 border-green-200 rounded-2xl p-6">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xl text-green-800 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('kiosk.process-pair') }}" class="space-y-8">
                    @csrf

                    <div>
                        <label for="device_id" class="block text-2xl font-semibold text-gray-900 mb-4">
                            Device ID
                        </label>
                        <input
                            type="text"
                            name="device_id"
                            id="device_id"
                            required
                            autofocus
                            class="w-full px-8 py-6 text-3xl font-mono text-center border-3 border-gray-300 rounded-2xl focus:ring-4 focus:ring-primary-500 focus:border-primary-500 uppercase tracking-wider"
                            placeholder="DEV-XXXXXXXXXXXX"
                            maxlength="16"
                        >
                        @error('device_id')
                            <p class="mt-3 text-lg text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-primary-500 to-blue-600 hover:from-primary-600 hover:to-blue-700 text-white text-2xl font-bold py-6 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200 touch-btn">
                        <svg class="w-8 h-8 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Pair Device
                    </button>
                </form>
            </div>

            <!-- Help Text -->
            <div class="mt-8 text-center">
                <p class="text-xl text-blue-100">
                    Don't have a Device ID? Contact your organization administrator.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-uppercase device ID input
        document.getElementById('device_id').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        // Auto-focus on page load
        window.addEventListener('load', function() {
            document.getElementById('device_id').focus();
        });
    </script>
    @endpush
</x-kiosk-layout>
