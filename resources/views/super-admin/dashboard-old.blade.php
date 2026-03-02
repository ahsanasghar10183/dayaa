<x-super-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="mt-2 text-gray-600">Welcome back, {{ auth()->user()->name }}. Here's what's happening with your platform today.</p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Organizations -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Organizations</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_organizations'] }}</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm">
                            <span class="flex items-center text-yellow-600">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span>
                                {{ $stats['pending_organizations'] }} pending
                            </span>
                            <span class="flex items-center text-green-600">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                {{ $stats['active_organizations'] }} active
                            </span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-gradient-primary rounded-xl flex items-center justify-center shadow-primary">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Donations -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold bg-gradient-primary bg-clip-text text-transparent mt-2">€{{ number_format($stats['total_donations_amount'], 2) }}</p>
                        <p class="mt-3 text-sm text-gray-600">{{ $stats['total_donations'] }} donations</p>
                    </div>
                    <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Campaigns -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Campaigns</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_campaigns'] }}</p>
                        <p class="mt-3 text-sm text-gray-600">{{ $stats['total_campaigns'] }} total campaigns</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Online Devices -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Online Devices</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['online_devices'] }}</p>
                        <p class="mt-3 text-sm text-gray-600">{{ $stats['total_devices'] }} total devices</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content - 2 columns -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Pending Organizations -->
                @if($pendingOrganizations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                                <p class="mt-1 text-sm text-gray-600">Organizations waiting for your review</p>
                            </div>
                            <a href="{{ route('super-admin.organizations.index', ['status' => 'pending']) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                View all →
                            </a>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($pendingOrganizations as $org)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-12 h-12 bg-gradient-primary rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($org->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $org->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $org->contact_person }} • {{ $org->user->email }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Submitted {{ $org->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('super-admin.organizations.show', $org) }}" class="btn-primary">
                                Review
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Donations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Donations</h3>
                        <p class="mt-1 text-sm text-gray-600">Latest successful transactions</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentDonations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $donation->organization->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $donation->campaign->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-green-600">€{{ number_format($donation->amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $donation->created_at->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar - 1 column -->
            <div class="space-y-8">
                <!-- Today's Stats -->
                <div class="bg-gradient-primary rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Today's Activity</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm opacity-90">Donations Today</p>
                            <p class="text-2xl font-bold">{{ $stats['donations_today'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Amount Today</p>
                            <p class="text-2xl font-bold">€{{ number_format($stats['donations_today_amount'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subscriptions Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscriptions</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Active</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $stats['active_subscriptions'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Premium</span>
                            <span class="text-sm font-semibold text-purple-600">{{ $stats['premium_subscriptions'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Basic</span>
                            <span class="text-sm font-semibold text-blue-600">{{ $stats['basic_subscriptions'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('super-admin.organizations.index') }}" class="block w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                            View All Organizations
                        </a>
                        <a href="{{ route('super-admin.organizations.index', ['status' => 'pending']) }}" class="block w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                            Review Pending Approvals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-super-admin-layout>
