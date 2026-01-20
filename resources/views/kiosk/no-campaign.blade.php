<x-kiosk-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 p-8">
        <div class="text-center max-w-3xl">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-32 h-32 bg-yellow-100 rounded-full mb-8">
                <svg class="w-16 h-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <!-- Message -->
            <h1 class="text-5xl font-bold text-gray-900 mb-6">No Active Campaign</h1>
            <p class="text-3xl text-gray-700 mb-8">
                This device doesn't have an active campaign assigned yet.
            </p>

            <!-- Device Info -->
            <div class="bg-white rounded-3xl shadow-xl p-8 mb-8">
                <div class="text-2xl text-gray-600 mb-2">Device Name</div>
                <div class="text-3xl font-bold text-gray-900 mb-6">{{ $device->name }}</div>

                <div class="text-2xl text-gray-600 mb-2">Device ID</div>
                <div class="text-3xl font-mono font-bold text-primary-600">{{ $device->device_id }}</div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border-4 border-blue-200 rounded-3xl p-8">
                <p class="text-2xl text-blue-900">
                    <strong>What to do:</strong><br>
                    Please contact your organization administrator to assign a campaign to this device.
                </p>
            </div>

            <!-- Unpair Button -->
            <div class="mt-8">
                <form method="POST" action="{{ route('kiosk.unpair') }}">
                    @csrf
                    <button type="submit" class="touch-btn bg-gray-600 hover:bg-gray-700 text-white text-2xl font-bold py-6 px-12 rounded-2xl shadow-xl transition-all duration-200">
                        Unpair Device
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh every 30 seconds to check for campaign assignment
        setInterval(() => {
            window.location.reload();
        }, 30000);
    </script>
    @endpush
</x-kiosk-layout>
