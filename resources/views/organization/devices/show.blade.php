<x-organization-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('organization.devices.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Devices
            </a>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <!-- Device Info -->
                <div class="flex-1">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="flex-shrink-0 h-16 w-16 rounded-xl {{ $device->status == 'online' ? 'bg-green-100' : ($device->status == 'maintenance' ? 'bg-yellow-100' : 'bg-gray-100') }} flex items-center justify-center shadow-lg">
                            <svg class="h-8 w-8 {{ $device->status == 'online' ? 'text-green-600' : ($device->status == 'maintenance' ? 'text-yellow-600' : 'text-gray-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $device->name }}</h1>
                            @if($device->description)
                            <p class="text-gray-600 text-lg">{{ $device->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 mt-6">
                        <!-- Status Badge -->
                        @if($device->status == 'online')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                Online
                            </span>
                        @elseif($device->status == 'maintenance')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Maintenance
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                <span class="w-2 h-2 bg-gray-600 rounded-full mr-2"></span>
                                Offline
                            </span>
                        @endif

                        @if($device->location)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $device->location }}
                        </span>
                        @endif

                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-mono bg-gray-100 text-gray-700 border border-gray-200">
                            {{ $device->device_id }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('organization.devices.edit', $device) }}" class="btn-primary whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Device
                    </a>
                    <form method="POST" action="{{ route('organization.devices.destroy', $device) }}" onsubmit="return confirm('Are you sure you want to delete this device? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-danger">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Donations -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['total_donations']) }}</div>
                <div class="text-blue-100 text-sm">Total Donations</div>
            </div>

            <!-- Total Amount -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">€{{ number_format($stats['total_amount'], 2) }}</div>
                <div class="text-green-100 text-sm">Total Raised</div>
                <div class="mt-2 text-xs text-green-100 opacity-80">Avg: €{{ number_format($stats['average_donation'], 2) }}</div>
            </div>

            <!-- Today's Stats -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['today_donations']) }}</div>
                <div class="text-purple-100 text-sm">Today's Donations</div>
                <div class="mt-2 text-xs text-purple-100 opacity-80">€{{ number_format($stats['today_amount'], 2) }}</div>
            </div>

            <!-- This Month -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['this_month_donations']) }}</div>
                <div class="text-orange-100 text-sm">This Month</div>
                <div class="mt-2 text-xs text-orange-100 opacity-80">€{{ number_format($stats['this_month_amount'], 2) }}</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - 2/3 Width -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Device ID Card -->
                <div class="bg-gradient-to-br from-primary-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Device Pairing ID</h3>
                            <p class="text-blue-100 text-sm">Use this ID to connect your physical device</p>
                        </div>
                        <button onclick="copyDeviceId()" class="p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-xl p-4 backdrop-blur-sm mb-4">
                        <p class="text-2xl font-mono font-bold text-center tracking-wider">{{ $device->device_id }}</p>
                    </div>
                    <button onclick="togglePairingGuide()" class="w-full text-center text-blue-100 hover:text-white text-sm font-medium transition-colors">
                        View Pairing Instructions
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Pairing Guide (Hidden by default) -->
                <div id="pairingGuide" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="display: none;">
                    <div class="px-6 py-4 bg-gradient-to-r from-primary-50 to-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Device Pairing Guide
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- API Endpoint Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">API Endpoint</h4>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <code class="text-sm text-gray-800 break-all">{{ url('/api/device/pair') }}</code>
                            </div>
                        </div>

                        <!-- Step-by-step Guide -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Pairing Steps</h4>
                            <ol class="space-y-4">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                    <div class="flex-1">
                                        <p class="text-gray-800 font-medium">Send POST request to pairing endpoint</p>
                                        <div class="mt-2 bg-gray-900 rounded-lg p-3 overflow-x-auto">
                                            <pre class="text-xs text-green-400">POST {{ url('/api/device/pair') }}
Content-Type: application/json

{
  "device_id": "{{ $device->device_id }}"
}</pre>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                    <div class="flex-1">
                                        <p class="text-gray-800 font-medium">Save the API token from response</p>
                                        <p class="text-sm text-gray-600 mt-1">The response will include an API token. Store this securely on your device.</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                    <div class="flex-1">
                                        <p class="text-gray-800 font-medium">Send heartbeat every 60 seconds</p>
                                        <div class="mt-2 bg-gray-900 rounded-lg p-3 overflow-x-auto">
                                            <pre class="text-xs text-green-400">POST {{ url('/api/device/heartbeat') }}
Content-Type: application/json

{
  "device_id": "{{ $device->device_id }}",
  "api_token": "YOUR_API_TOKEN"
}</pre>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                    <div class="flex-1">
                                        <p class="text-gray-800 font-medium">Fetch assigned campaigns</p>
                                        <div class="mt-2 bg-gray-900 rounded-lg p-3 overflow-x-auto">
                                            <pre class="text-xs text-green-400">POST {{ url('/api/device/status') }}
Content-Type: application/json

{
  "device_id": "{{ $device->device_id }}",
  "api_token": "YOUR_API_TOKEN"
}</pre>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <!-- Test Pairing Button -->
                        <div class="flex justify-center">
                            <button onclick="testPairing()" class="btn-primary">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Test Pairing Connection
                            </button>
                        </div>

                        <!-- Test Result Area -->
                        <div id="testResult" class="hidden"></div>

                        <!-- Additional Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Important Notes</p>
                                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                                        <li>Keep your API token secure and never share it publicly</li>
                                        <li>The device status will automatically change to "online" when heartbeat is received</li>
                                        <li>If heartbeat stops, the device will remain online until manually changed</li>
                                        <li>Assign campaigns to this device from the campaign management page</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Donations -->
                @if($device->donations->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Donations
                        </h3>
                        <span class="text-sm text-gray-500">Last 10</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Campaign</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($device->donations as $donation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                            €{{ number_format($donation->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-900">{{ $donation->campaign->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $donation->created_at->format('M d, Y') }} at {{ $donation->created_at->format('H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Donations Yet</h3>
                    <p class="text-gray-600">This device hasn't processed any donations yet.</p>
                </div>
                @endif
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Device Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Device Info
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500">Last Active</div>
                                <div class="text-sm font-medium text-gray-900">
                                    @if($device->last_active)
                                        {{ $device->last_active->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500">Added On</div>
                                <div class="text-sm font-medium text-gray-900">{{ $device->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Campaigns -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Campaigns
                        </h3>
                        <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">{{ $device->campaigns->count() }}</span>
                    </div>

                    @if($device->campaigns->count() > 0)
                    <div class="space-y-2">
                        @foreach($device->campaigns as $campaign)
                        <a href="{{ route('organization.campaigns.show', $campaign) }}" class="block p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $campaign->name }}</span>
                                @if($campaign->status == 'active')
                                    <span class="badge-success text-xs">Active</span>
                                @else
                                    <span class="badge-gray text-xs">{{ ucfirst($campaign->status) }}</span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No campaigns assigned</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyDeviceId() {
            const deviceId = '{{ $device->device_id }}';
            navigator.clipboard.writeText(deviceId).then(() => {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.textContent = 'Device ID copied to clipboard!';
                document.body.appendChild(toast);

                // Remove after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        }

        function togglePairingGuide() {
            const guide = document.getElementById('pairingGuide');
            if (guide.style.display === 'none') {
                guide.style.display = 'block';
                // Scroll to the guide
                setTimeout(() => {
                    guide.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            } else {
                guide.style.display = 'none';
            }
        }

        async function testPairing() {
            const deviceId = '{{ $device->device_id }}';
            const resultDiv = document.getElementById('testResult');

            // Show loading state
            resultDiv.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4';
            resultDiv.innerHTML = `
                <div class="flex items-center text-blue-800">
                    <svg class="animate-spin h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-medium">Testing pairing connection...</span>
                </div>
            `;
            resultDiv.classList.remove('hidden');

            try {
                const response = await fetch('{{ url("/api/device/pair") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ device_id: deviceId })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Success
                    resultDiv.className = 'bg-green-50 border border-green-200 rounded-lg p-4';
                    resultDiv.innerHTML = `
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-green-800 mb-2">Pairing Successful!</p>
                                <div class="text-sm text-green-700 space-y-1">
                                    <p><strong>Device:</strong> ${data.data.device_name}</p>
                                    <p><strong>Organization:</strong> ${data.data.organization}</p>
                                    <p><strong>Status:</strong> ${data.data.status}</p>
                                    <div class="mt-3 bg-white bg-opacity-50 rounded p-3">
                                        <p class="text-xs font-semibold mb-1">API Token (save this securely):</p>
                                        <code class="text-xs break-all">${data.data.api_token}</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // Error
                    resultDiv.className = 'bg-red-50 border border-red-200 rounded-lg p-4';
                    resultDiv.innerHTML = `
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-red-800 mb-1">Pairing Failed</p>
                                <p class="text-sm text-red-700">${data.message || 'Unknown error occurred'}</p>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.className = 'bg-red-50 border border-red-200 rounded-lg p-4';
                resultDiv.innerHTML = `
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-red-800 mb-1">Connection Error</p>
                            <p class="text-sm text-red-700">${error.message}</p>
                        </div>
                    </div>
                `;
            }
        }
    </script>
</x-organization-layout>
