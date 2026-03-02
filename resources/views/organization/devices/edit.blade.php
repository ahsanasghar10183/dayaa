<x-organization-sidebar-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('organization.devices.show', $device) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Device
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Device</h1>
            <p class="mt-2 text-gray-600">{{ $device->name }}</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('organization.devices.update', $device) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
                <!-- Device Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Device Name *
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $device->name) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="e.g., Main Entrance Device"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location
                    </label>
                    <input
                        type="text"
                        name="location"
                        id="location"
                        value="{{ old('location', $device->location) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('location') border-red-500 @enderror"
                        placeholder="e.g., Main Office, Building A"
                    >
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Additional information about this device..."
                    >{{ old('description', $device->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status *
                    </label>
                    <select
                        name="status"
                        id="status"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('status') border-red-500 @enderror"
                    >
                        <option value="online" {{ old('status', $device->status) == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ old('status', $device->status) == 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="maintenance" {{ old('status', $device->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Set to "Maintenance" to temporarily disable this device</p>
                </div>

                <!-- Device ID (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Device ID
                    </label>
                    <div class="flex items-center">
                        <input
                            type="text"
                            value="{{ $device->device_id }}"
                            readonly
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono"
                        >
                        <button type="button" onclick="copyDeviceId()" class="ml-3 btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">This ID cannot be changed</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('organization.devices.show', $device) }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary px-8">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function copyDeviceId() {
            const deviceId = '{{ $device->device_id }}';
            navigator.clipboard.writeText(deviceId).then(() => {
                alert('Device ID copied to clipboard!');
            });
        }
    </script>
</x-organization-sidebar-layout>
