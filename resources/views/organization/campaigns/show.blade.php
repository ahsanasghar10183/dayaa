<x-organization-layout>
    @php
        $designSettings = is_string($campaign->design_settings) ? json_decode($campaign->design_settings, true) : ($campaign->design_settings ?? []);
        $amountSettings = is_string($campaign->amount_settings) ? json_decode($campaign->amount_settings, true) : ($campaign->amount_settings ?? []);
        $layoutType = $designSettings['layout_type'] ?? 'solid_color';
        $backgroundImageUrl = isset($designSettings['background_image']) ? asset('storage/' . $designSettings['background_image']) : '';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('organization.campaigns.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Campaigns
            </a>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <!-- Campaign Info -->
                <div class="flex-1">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="flex-shrink-0 h-16 w-16 rounded-xl bg-gradient-primary flex items-center justify-center shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $campaign->name }}</h1>
                            @if($campaign->description)
                            <p class="text-gray-600 text-lg">{{ $campaign->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 mt-6">
                        <!-- Status Badge -->
                        @if($campaign->status == 'active')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                Active
                            </span>
                        @elseif($campaign->status == 'scheduled')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Scheduled
                            </span>
                        @elseif($campaign->status == 'inactive')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                <span class="w-2 h-2 bg-gray-600 rounded-full mr-2"></span>
                                Inactive
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ended
                            </span>
                        @endif

                        <!-- Type Badge -->
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $campaign->campaign_type == 'recurring' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                            @if($campaign->campaign_type == 'recurring')
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Recurring
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                One-Time
                            @endif
                        </span>

                        @if($campaign->reference_code)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-mono bg-gray-100 text-gray-700 border border-gray-200">
                            Ref: {{ $campaign->reference_code }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('organization.campaigns.edit', $campaign) }}" class="btn-primary whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Campaign
                    </a>
                    <form method="POST" action="{{ route('organization.campaigns.destroy', $campaign) }}" onsubmit="return confirm('Are you sure you want to delete this campaign? This action cannot be undone.')">
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
                <!-- Campaign Preview -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Campaign Preview
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-900 rounded-xl p-4 shadow-xl">
                            <div class="bg-white rounded-lg overflow-hidden aspect-[9/16] max-h-[500px]">
                                <div id="campaign-preview" class="h-full flex flex-col"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Donations -->
                @if($campaign->donations->count() > 0)
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
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Device</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($campaign->donations as $donation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                            €{{ number_format($donation->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $donation->device->name ?? 'N/A' }}</span>
                                        </div>
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
                    <p class="text-gray-600">This campaign hasn't received any donations yet.</p>
                </div>
                @endif
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Campaign Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Campaign Info
                    </h3>
                    <div class="space-y-4">
                        @if($campaign->start_date)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500">Start Date</div>
                                <div class="text-sm font-medium text-gray-900">{{ $campaign->start_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($campaign->end_date)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500">End Date</div>
                                <div class="text-sm font-medium text-gray-900">{{ $campaign->end_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500">Created On</div>
                                <div class="text-sm font-medium text-gray-900">{{ $campaign->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donation Amounts -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Preset Amounts
                    </h3>

                    @if(isset($amountSettings['preset_amounts']) && count($amountSettings['preset_amounts']) > 0)
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        @foreach($amountSettings['preset_amounts'] as $amount)
                        <div class="text-center p-3 bg-gradient-to-br from-primary-50 to-blue-50 rounded-lg border border-primary-200">
                            <span class="text-sm font-bold text-primary-700">€{{ number_format($amount, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="space-y-2 text-sm pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Custom Amount:</span>
                            @if($amountSettings['allow_custom_amount'] ?? false)
                                <span class="badge-success text-xs">Allowed</span>
                            @else
                                <span class="badge-gray text-xs">Not Allowed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Design Colors -->
                @if($designSettings)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        Design Theme
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <div class="text-xs text-gray-500 mb-2">Layout Type</div>
                            <div class="px-3 py-2 bg-gray-50 rounded-lg text-sm font-medium text-gray-900 capitalize">
                                {{ str_replace('_', ' ', $layoutType) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 mb-2">Color Palette</div>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <div class="h-12 rounded-lg shadow-sm border border-gray-200" style="background: {{ $designSettings['primary_color'] ?? '#1163F0' }}"></div>
                                    <p class="text-xs text-gray-600 mt-1 text-center font-mono">{{ $designSettings['primary_color'] ?? '#1163F0' }}</p>
                                </div>
                                @if(in_array($layoutType, ['dual_color', 'banner_image']))
                                <div class="flex-1">
                                    <div class="h-12 rounded-lg shadow-sm border border-gray-200" style="background: {{ $designSettings['accent_color'] ?? '#F3F4F6' }}"></div>
                                    <p class="text-xs text-gray-600 mt-1 text-center font-mono">{{ $designSettings['accent_color'] ?? '#F3F4F6' }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assigned Devices -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Devices
                        </h3>
                        <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">{{ $campaign->devices->count() }}</span>
                    </div>

                    @if($campaign->devices->count() > 0)
                    <div class="space-y-2">
                        @foreach($campaign->devices as $device)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $device->name }}</span>
                                @if($device->status == 'online')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="w-1.5 h-1.5 bg-gray-600 rounded-full mr-1"></span>
                                        Offline
                                    </span>
                                @endif
                            </div>
                            @if($device->location)
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $device->location }}
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No devices assigned</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Campaign preview rendering
        const layoutType = '{{ $layoutType }}';
        const primaryColor = '{{ $designSettings["primary_color"] ?? "#1163F0" }}';
        const accentColor = '{{ $designSettings["accent_color"] ?? "#F3F4F6" }}';
        const heading = `{{ addslashes($designSettings["heading"] ?? $campaign->name) }}`;
        const message = `{{ addslashes($designSettings["message"] ?? "") }}`;
        const backgroundImage = '{{ $backgroundImageUrl }}';
        const amounts = @json($amountSettings['preset_amounts'] ?? [10, 25, 50]);
        const buttonPosition = '{{ $amountSettings["button_position"] ?? "middle" }}';
        const showCustomAmount = {{ ($amountSettings['allow_custom_amount'] ?? false) ? 'true' : 'false' }};

        const preview = document.getElementById('campaign-preview');

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderPreview() {
            const safeHeading = escapeHtml(heading);
            const safeMessage = escapeHtml(message);

            let buttonsHTML = amounts.map(amount =>
                `<button class="px-6 py-3 rounded-lg font-semibold shadow-lg text-white transition-transform hover:scale-105" style="background-color: ${primaryColor}">€${amount}</button>`
            ).join('');

            if (showCustomAmount) {
                buttonsHTML += `<button class="px-6 py-3 bg-white border-2 rounded-lg font-semibold shadow-lg transition-transform hover:scale-105" style="border-color: ${primaryColor}; color: ${primaryColor}">Custom</button>`;
            }

            const buttonContainer = `<div class="grid grid-cols-2 gap-3 w-full max-w-sm mx-auto">${buttonsHTML}</div>`;
            const justifyClass = buttonPosition === 'top' ? 'justify-start pt-12' : buttonPosition === 'bottom' ? 'justify-end pb-12' : 'justify-center';

            let html = '';

            switch(layoutType) {
                case 'solid_color':
                    html = `
                        <div class="h-full flex flex-col items-center ${justifyClass} p-8 text-center relative" style="background-color: ${primaryColor}">
                            ${buttonPosition !== 'bottom' ? `<h1 class="text-2xl font-bold text-white mb-4">${safeHeading}</h1>${safeMessage ? `<p class="text-white opacity-90 mb-8 text-sm">${safeMessage}</p>` : ''}` : ''}
                            ${buttonContainer}
                            ${buttonPosition === 'bottom' ? `<h1 class="text-2xl font-bold text-white mt-8 mb-4">${safeHeading}</h1>${safeMessage ? `<p class="text-white opacity-90 text-sm">${safeMessage}</p>` : ''}` : ''}
                        </div>
                    `;
                    break;

                case 'dual_color':
                    html = `
                        <div class="h-full flex flex-col relative">
                            <div class="h-1/3 flex flex-col items-center justify-center p-6 text-center" style="background-color: ${primaryColor}">
                                <h1 class="text-xl font-bold text-white">${safeHeading}</h1>
                            </div>
                            <div class="flex-1 flex flex-col items-center ${justifyClass} p-8" style="background-color: ${accentColor}">
                                ${buttonPosition !== 'bottom' && safeMessage ? `<p class="text-gray-700 mb-8 text-sm text-center">${safeMessage}</p>` : ''}
                                ${buttonContainer}
                                ${buttonPosition === 'bottom' && safeMessage ? `<p class="text-gray-700 mt-8 text-sm text-center">${safeMessage}</p>` : ''}
                            </div>
                        </div>
                    `;
                    break;

                case 'banner_image':
                    const bannerBg = backgroundImage ? `url('${backgroundImage}')` : `linear-gradient(135deg, ${primaryColor} 0%, ${primaryColor}dd 100%)`;
                    html = `
                        <div class="h-full flex flex-col relative">
                            <div class="h-1/3 relative bg-cover bg-center" style="background: ${bannerBg}">
                                ${!backgroundImage ? `<div class="absolute inset-0 flex items-center justify-center text-white opacity-30"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>` : ''}
                            </div>
                            <div class="flex-1 flex flex-col items-center ${justifyClass} p-8" style="background-color: ${accentColor || '#ffffff'}">
                                ${buttonPosition !== 'bottom' ? `<h1 class="text-xl font-bold mb-4" style="color: ${primaryColor}">${safeHeading}</h1>${safeMessage ? `<p class="text-gray-700 mb-8 text-sm text-center">${safeMessage}</p>` : ''}` : ''}
                                ${buttonContainer}
                                ${buttonPosition === 'bottom' ? `<h1 class="text-xl font-bold mt-8 mb-4" style="color: ${primaryColor}">${safeHeading}</h1>${safeMessage ? `<p class="text-gray-700 text-sm text-center">${safeMessage}</p>` : ''}` : ''}
                            </div>
                        </div>
                    `;
                    break;

                case 'full_background':
                    const fullBg = backgroundImage ? `url('${backgroundImage}')` : `linear-gradient(135deg, ${primaryColor} 0%, ${primaryColor}aa 100%)`;
                    html = `
                        <div class="h-full relative flex flex-col items-center ${justifyClass} p-8 text-center bg-cover bg-center" style="background: ${fullBg}">
                            ${backgroundImage ? '<div class="absolute inset-0 bg-black opacity-40"></div>' : ''}
                            ${!backgroundImage ? `<div class="absolute inset-0 flex items-center justify-center opacity-10"><svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>` : ''}
                            <div class="relative z-10 w-full">
                                ${buttonPosition !== 'bottom' ? `<h1 class="text-2xl font-bold text-white mb-4">${safeHeading}</h1>${safeMessage ? `<p class="text-white opacity-90 mb-8 text-sm">${safeMessage}</p>` : ''}` : ''}
                                <div class="grid grid-cols-2 gap-3 w-full max-w-sm mx-auto">
                                    ${amounts.map(amount => `<button class="px-6 py-3 bg-white text-gray-900 rounded-lg font-semibold shadow-xl transition-transform hover:scale-105">€${amount}</button>`).join('')}
                                    ${showCustomAmount ? `<button class="px-6 py-3 bg-white bg-opacity-20 border-2 border-white text-white rounded-lg font-semibold shadow-xl transition-transform hover:scale-105">Custom</button>` : ''}
                                </div>
                                ${buttonPosition === 'bottom' ? `<h1 class="text-2xl font-bold text-white mt-8 mb-4">${safeHeading}</h1>${safeMessage ? `<p class="text-white opacity-90 text-sm">${safeMessage}</p>` : ''}` : ''}
                            </div>
                        </div>
                    `;
                    break;
            }

            preview.innerHTML = html;
        }

        // Render on load
        if (preview) {
            renderPreview();
        }
    </script>
</x-organization-layout>
