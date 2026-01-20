<x-organization-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-gray-600">{{ auth()->user()->organization->name ?? 'Your Organization' }}</p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Campaigns Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-primary rounded-xl flex items-center justify-center shadow-primary">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Campaigns</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_campaigns'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['active_campaigns'] }} active</p>
            </div>

            <!-- Total Devices Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Devices</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_devices'] }}</p>
                <p class="text-xs text-green-600 mt-1">{{ $stats['online_devices'] }} online now</p>
            </div>

            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                <p class="text-3xl font-bold text-green-600">€{{ number_format($stats['total_amount'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">From {{ $stats['total_donations'] }} donations</p>
            </div>

            <!-- This Month Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">This Month</p>
                <p class="text-3xl font-bold text-gray-900">€{{ number_format($stats['this_month_amount'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['this_month_donations'] }} donations</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Donations -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Donations</h3>
                        <a href="{{ route('organization.donations.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View all →</a>
                    </div>

                    @if($recentDonations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campaign</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentDonations as $donation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $donation->campaign->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $donation->campaign->campaign_type }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-green-600">€{{ number_format($donation->amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs text-gray-600">{{ ucfirst($donation->payment_method) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $donation->created_at->format('M d, H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-sm text-gray-600">No donations yet</p>
                        <p class="text-xs text-gray-500">Donations will appear here once your campaigns are active</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Active Campaigns -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700">Active Campaigns</h3>
                        <a href="{{ route('organization.campaigns.index') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">View all →</a>
                    </div>

                    @if($activeCampaigns->count() > 0)
                    <div class="space-y-3">
                        @foreach($activeCampaigns as $campaign)
                        <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-900">{{ Str::limit($campaign->name, 25) }}</h4>
                                <span class="badge-success text-xs">Active</span>
                            </div>
                            <p class="text-xs text-gray-600">{{ ucfirst($campaign->campaign_type) }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $campaign->donations_count }} donations</span>
                                <a href="{{ route('organization.campaigns.show', $campaign) }}" class="text-xs text-primary-600 hover:text-primary-700">View →</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">No active campaigns</p>
                        <a href="{{ route('organization.campaigns.create') }}" class="mt-2 inline-block btn-primary text-xs">Create Campaign</a>
                    </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-primary rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Today's Activity</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm opacity-90">Donations Today</p>
                            <p class="text-2xl font-bold">{{ $stats['donations_today'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Revenue Today</p>
                            <p class="text-2xl font-bold">€{{ number_format($stats['donations_today_amount'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Avg. Donation</p>
                            <p class="text-2xl font-bold">€{{ $stats['total_donations'] > 0 ? number_format($stats['total_amount'] / $stats['total_donations'], 2) : '0.00' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subscription Info -->
                @if($subscription)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Subscription</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Plan</span>
                            <span class="badge-{{ $subscription->plan == 'premium' ? 'info' : 'gray' }}">{{ ucfirst($subscription->plan) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Price</span>
                            <span class="text-sm font-semibold">€{{ number_format($subscription->price, 2) }}/mo</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="badge-{{ $subscription->status == 'active' ? 'success' : 'warning' }}">{{ ucfirst($subscription->status) }}</span>
                        </div>
                        @if($subscription->next_billing_date)
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500">Next billing: {{ $subscription->next_billing_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('organization.campaigns.create') }}" class="w-full btn-primary text-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Campaign
                        </a>
                        <a href="{{ route('organization.devices.create') }}" class="w-full btn-secondary text-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Register Device
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-organization-layout>
