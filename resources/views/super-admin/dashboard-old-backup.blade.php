<x-sidebar-layout>
    <x-slot name="logoRoute">{{ route('super-admin.dashboard') }}</x-slot>
    <x-slot name="sidebarTitle">Super Admin</x-slot>
    <x-slot name="title">Dashboard - Super Admin</x-slot>

    <x-slot name="navigation">
        <x-super-admin-navigation />
    </x-slot>

    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-600 mt-1">Platform Overview & Analytics</p>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Organizations -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Organizations</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_organizations'] }}</p>
                <div class="mt-3 flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['active_organizations'] }} active</span>
                    <span class="mx-2 text-gray-400">•</span>
                    <span class="text-yellow-600 font-medium">{{ $stats['pending_organizations'] }} pending</span>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">€{{ number_format($stats['total_donations_amount'], 2) }}</p>
                <p class="mt-3 text-sm text-gray-600">{{ number_format($stats['total_donations']) }} donations</p>
            </div>
        </div>

        <!-- Active Campaigns -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Active Campaigns</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_campaigns'] }}</p>
                <p class="mt-3 text-sm text-gray-600">{{ $stats['total_campaigns'] }} total</p>
            </div>
        </div>

        <!-- Online Devices -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Devices Online</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['online_devices'] }}</p>
                <p class="mt-3 text-sm text-gray-600">{{ $stats['total_devices'] }} total devices</p>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Donations Trend Chart -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Donations Trend</h3>
                <p class="text-sm text-gray-600 mt-1">Last 7 days</p>
            </div>
            <div style="height: 300px;">
                <canvas id="donationsTrendChart"></canvas>
            </div>
        </div>

        <!-- Revenue by Organization -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Distribution</h3>
                <p class="text-sm text-gray-600 mt-1">Top organizations</p>
            </div>
            <div style="height: 300px;">
                <canvas id="revenueDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity (2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Pending Organizations -->
            @if($pendingOrganizations->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $pendingOrganizations->count() }} organizations awaiting review</p>
                        </div>
                        <a href="{{ route('super-admin.organizations.index', ['status' => 'pending']) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    @foreach($pendingOrganizations as $org)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-gray-50 transition-all">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-white font-bold">
                                {{ substr($org->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $org->name }}</h4>
                                <p class="text-sm text-gray-600 truncate">{{ $org->contact_person }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $org->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('super-admin.organizations.show', $org) }}" class="ml-4 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            Review
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Donations -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Donations</h3>
                    <p class="text-sm text-gray-600 mt-1">Latest 10 successful transactions</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Organization</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Campaign</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentDonations as $donation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $donation->organization->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $donation->campaign->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-semibold text-green-600">€{{ number_format($donation->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $donation->created_at->format('M d, H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar (1 column) -->
        <div class="space-y-6">
            <!-- Today's Activity -->
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-6 text-white">
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
                </div>
            </div>

            <!-- Subscription Tiers -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscriptions</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600">Active</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['active_subscriptions'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-gray-100">
                        <span class="text-sm text-gray-600">Premium</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                            {{ $stats['premium_subscriptions'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-gray-100">
                        <span class="text-sm text-gray-600">Basic</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $stats['basic_subscriptions'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('super-admin.organizations.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                        <span class="text-sm font-medium text-gray-700">All Organizations</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('super-admin.shop.products.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                        <span class="text-sm font-medium text-gray-700">Shop Products</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('super-admin.shop.orders.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                        <span class="text-sm font-medium text-gray-700">Shop Orders</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Donations Trend Chart
        const donationsTrendCtx = document.getElementById('donationsTrendChart').getContext('2d');
        new Chart(donationsTrendCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']),
                datasets: [{
                    label: 'Donations',
                    data: @json($chartData['donations'] ?? [12, 19, 15, 25, 22, 30, 28]),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Revenue Distribution Chart
        const revenueDistCtx = document.getElementById('revenueDistributionChart').getContext('2d');
        new Chart(revenueDistCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['org_labels'] ?? ['Org 1', 'Org 2', 'Org 3', 'Others']),
                datasets: [{
                    data: @json($chartData['org_revenue'] ?? [300, 250, 200, 150]),
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(139, 92, 246)',
                        'rgb(251, 191, 36)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-sidebar-layout>
