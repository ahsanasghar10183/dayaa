<x-organization-sidebar-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-sm text-gray-600 mt-1">{{ auth()->user()->organization->name ?? 'Your Organization' }}</p>
    </x-slot>

    <!-- Date Filter -->
    <div class="mb-6" x-data="{ customRange: {{ request('period') == 'custom' ? 'true' : 'false' }} }">
        <form method="GET" action="{{ route('organization.dashboard') }}" class="flex flex-wrap items-end gap-3">
            <!-- Period Dropdown -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Period
                </label>
                <select name="period"
                        @change="if($el.value === 'custom') { customRange = true; } else { customRange = false; $el.form.submit(); }"
                        class="px-4 py-2.5 pr-10 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all bg-white text-sm font-medium text-gray-700 min-w-[200px]">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_month" {{ request('period', 'this_month') == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="last_6_months" {{ request('period') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                    <option value="last_year" {{ request('period') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <!-- Custom Date Range -->
            <div x-show="customRange" x-collapse class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all text-sm">
                </div>
                <button type="submit" class="btn-primary whitespace-nowrap">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Apply Filter
                </button>
            </div>

            <!-- Clear Filter Button -->
            @if(request('period'))
            <a href="{{ route('organization.dashboard') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Clear Filter
            </a>
            @endif
        </form>

        <!-- Active Filter Display (Compact) -->
        @if(request('period'))
        <div class="mt-3 inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            @if(request('period') == 'custom')
                {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
            @else
                {{ ucwords(str_replace('_', ' ', request('period'))) }}
            @endif
        </div>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Campaigns -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Campaigns</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_campaigns'] }}</p>
                <p class="mt-3 text-sm text-green-600 font-medium">{{ $stats['active_campaigns'] }} active</p>
            </div>
        </div>

        <!-- Total Devices -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Devices</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_devices'] }}</p>
                <p class="mt-3 text-sm text-green-600 font-medium">{{ $stats['online_devices'] }} online</p>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">€{{ number_format($stats['total_amount'], 2) }}</p>
                <p class="mt-3 text-sm text-gray-600">{{ number_format($stats['total_donations']) }} donations</p>
            </div>
        </div>

        <!-- Current Period Revenue -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">This Month</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">€{{ number_format($stats['this_month_amount'], 2) }}</p>
                <p class="mt-3 text-sm text-gray-600">{{ $stats['this_month_donations'] }} donations</p>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Donations Trend Chart -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Donations Trend</h3>
                <p class="text-sm text-gray-600 mt-1">
                    @if(request('period') == 'custom')
                        {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                    @else
                        {{ ucwords(str_replace('_', ' ', request('period', 'This month'))) }} activity
                    @endif
                </p>
            </div>
            <div style="height: 300px;">
                <canvas id="donationsTrendChart"></canvas>
            </div>
        </div>

        <!-- Campaign Performance -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Campaign Performance</h3>
                <p class="text-sm text-gray-600 mt-1">Top performing campaigns</p>
            </div>
            <div style="height: 300px;">
                <canvas id="campaignPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Donations -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
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
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Active Campaigns</h3>
                        <a href="{{ route('organization.campaigns.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View all →</a>
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

                <!-- Today's Activity -->
                <div class="bg-gradient-dayaa rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Today's Activity</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm opacity-90">Donations</p>
                            <p class="text-3xl font-bold">{{ $stats['donations_today'] }}</p>
                        </div>
                        <div class="border-t border-white border-opacity-20 pt-4">
                            <p class="text-sm opacity-90">Revenue</p>
                            <p class="text-3xl font-bold">€{{ number_format($stats['donations_today_amount'], 2) }}</p>
                        </div>
                        <div class="border-t border-white border-opacity-20 pt-4">
                            <p class="text-sm opacity-90">Avg. Donation</p>
                            <p class="text-3xl font-bold">€{{ $stats['total_donations'] > 0 ? number_format($stats['total_amount'] / $stats['total_donations'], 2) : '0.00' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subscription Info -->
                @if($subscription)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription</h3>
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
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
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

    <!-- Chart.js Scripts -->
    <script>
        // Donations Trend Chart
        const donationsTrendCtx = document.getElementById('donationsTrendChart').getContext('2d');

        const dailyData = @json($dailyDonations);
        const dateLabels = dailyData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const donationCounts = dailyData.map(item => parseInt(item.count));
        const donationAmounts = dailyData.map(item => parseFloat(item.total));

        new Chart(donationsTrendCtx, {
            type: 'line',
            data: {
                labels: dateLabels,
                datasets: [
                    {
                        label: 'Number of Donations',
                        data: donationCounts,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Revenue (€)',
                        data: donationAmounts,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 1) {
                                        label += '€' + context.parsed.y.toFixed(2);
                                    } else {
                                        label += context.parsed.y;
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Donations Count'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Revenue (€)'
                        }
                    },
                }
            }
        });

        // Campaign Performance Chart (Top 5 campaigns by donations)
        const campaignPerformanceCtx = document.getElementById('campaignPerformanceChart').getContext('2d');

        // Sample data - will be replaced with real data from controller
        const campaignLabels = @json($activeCampaigns->pluck('name')->take(5));
        const campaignDonationCounts = @json($activeCampaigns->pluck('donations_count')->take(5));

        new Chart(campaignPerformanceCtx, {
            type: 'doughnut',
            data: {
                labels: campaignLabels.length > 0 ? campaignLabels : ['No campaigns yet'],
                datasets: [{
                    data: campaignDonationCounts.length > 0 ? campaignDonationCounts : [1],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(139, 92, 246)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (campaignLabels.length === 0) {
                                    return 'No data';
                                }
                                return context.label + ': ' + context.parsed + ' donations';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-organization-sidebar-layout>
