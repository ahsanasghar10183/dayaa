<x-organization-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Reports & Analytics</h2>
                <p class="text-sm text-gray-500 mt-1">Donation insights and performance metrics</p>
            </div>
            <a href="{{ route('organization.reports.export', request()->query()) }}"
               class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            {{-- Today --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Today</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($todayStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $todayStats['count'] }} donations</p>
                @php
                    $diff = $todayStats['total'] - $yesterdayStats['total'];
                    $pct = $yesterdayStats['total'] > 0 ? round(($diff / $yesterdayStats['total']) * 100, 1) : 0;
                @endphp
                <p class="text-xs mt-2 {{ $diff >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $diff >= 0 ? '+' : '' }}€{{ number_format(abs($diff), 2) }} vs yesterday
                    @if($pct != 0)({{ $diff >= 0 ? '+' : '' }}{{ $pct }}%)@endif
                </p>
            </div>

            {{-- This Week --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">This Week</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($weekStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $weekStats['count'] }} donations</p>
                <p class="text-xs mt-2 text-gray-400">Avg €{{ number_format($weekStats['avg'], 2) }}</p>
            </div>

            {{-- This Month --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">This Month</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($monthStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $monthStats['count'] }} donations</p>
                <p class="text-xs mt-2 text-gray-400">Avg €{{ number_format($monthStats['avg'], 2) }}</p>
            </div>

            {{-- All Time --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">All Time</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($allTimeStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $allTimeStats['count'] }} donations</p>
                <p class="text-xs mt-2 text-gray-400">Avg €{{ number_format($allTimeStats['avg'], 2) }}</p>
            </div>
        </div>

        {{-- Top Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            {{-- Top Campaign --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-blue-700">Top Campaign</p>
                </div>
                @if($topCampaign && $topCampaign->campaign)
                    <p class="font-bold text-gray-900 truncate">{{ $topCampaign->campaign->name }}</p>
                    <p class="text-2xl font-bold text-blue-700 mt-1">€{{ number_format($topCampaign->total, 2) }}</p>
                @else
                    <p class="text-gray-400 text-sm">No data yet</p>
                @endif
            </div>

            {{-- Top Device --}}
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-purple-700">Top Device</p>
                </div>
                @if($topDevice && $topDevice->device)
                    <p class="font-bold text-gray-900 truncate">{{ $topDevice->device->name }}</p>
                    <p class="text-2xl font-bold text-purple-700 mt-1">€{{ number_format($topDevice->total, 2) }}</p>
                @else
                    <p class="text-gray-400 text-sm">No data yet</p>
                @endif
            </div>

            {{-- Active Devices --}}
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-green-700">Active Devices</p>
                </div>
                <p class="text-3xl font-bold text-green-700">{{ $activeDevicesCount }}</p>
                <p class="text-sm text-gray-500 mt-1">Currently online</p>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- 30-Day Trend --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Donation Trend (Last 30 Days)</h3>
                <div class="relative h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            {{-- Campaign Comparison --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Campaign Performance</h3>
                <div class="relative h-64">
                    <canvas id="campaignChart"></canvas>
                </div>
            </div>

            {{-- Hourly Activity --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Hourly Activity (Last 30 Days)</h3>
                <div class="relative h-64">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>

            {{-- Day of Week --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Day of Week Analysis (Last 90 Days)</h3>
                <div class="relative h-64">
                    <canvas id="dowChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Device Performance --}}
        @if(count($deviceChartData['labels']) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Device Performance</h3>
            <div class="relative h-64">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Filter Donations</h3>
            <form method="GET" action="{{ route('organization.reports.index') }}" id="filterForm">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    {{-- Date Preset --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                        <select name="date_preset" id="datePreset" onchange="handleDatePresetChange()"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="today" {{ $datePreset === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $datePreset === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last_7_days" {{ $datePreset === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ $datePreset === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ $datePreset === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $datePreset === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="custom" {{ $datePreset === 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    {{-- Custom date range --}}
                    <div id="customDateRange" class="{{ $datePreset === 'custom' ? '' : 'hidden' }} col-span-2 md:col-span-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">From</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div id="customDateRangeTo" class="{{ $datePreset === 'custom' ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-1">To</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    {{-- Campaign --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Campaign</label>
                        <select name="campaign_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Device --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Device</label>
                        <select name="device_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Devices</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Statuses</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    {{-- Amount Min --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Min Amount (€)</label>
                        <input type="number" name="amount_min" value="{{ request('amount_min') }}" step="0.01" min="0" placeholder="0.00"
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    {{-- Amount Max --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Max Amount (€)</label>
                        <input type="number" name="amount_max" value="{{ request('amount_max') }}" step="0.01" min="0" placeholder="Any"
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Receipt #, Transaction ID, amount..."
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary text-sm">Apply Filters</button>
                    <a href="{{ route('organization.reports.index') }}" class="btn-secondary text-sm">Reset</a>
                </div>
            </form>
        </div>

        {{-- Summary Stats Bar --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4 grid grid-cols-2 md:grid-cols-6 gap-4 text-center">
            <div>
                <p class="text-xs text-blue-600 font-medium">Total Records</p>
                <p class="text-lg font-bold text-blue-800">{{ number_format($summary->total_count) }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 font-medium">Total Amount</p>
                <p class="text-lg font-bold text-blue-800">€{{ number_format($summary->total_amount ?? 0, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 font-medium">Avg Amount</p>
                <p class="text-lg font-bold text-blue-800">€{{ number_format($summary->avg_amount ?? 0, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-green-600 font-medium">Successful</p>
                <p class="text-lg font-bold text-green-700">{{ number_format($summary->success_count) }}</p>
            </div>
            <div>
                <p class="text-xs text-yellow-600 font-medium">Pending</p>
                <p class="text-lg font-bold text-yellow-700">{{ number_format($summary->pending_count) }}</p>
            </div>
            <div>
                <p class="text-xs text-red-600 font-medium">Failed</p>
                <p class="text-lg font-bold text-red-700">{{ number_format($summary->failed_count) }}</p>
            </div>
        </div>

        {{-- Donations Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Donation Records</h3>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Per page:</span>
                    <select onchange="changePerPage(this.value)" class="rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>

            @if($donations->isEmpty())
                <div class="py-16 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No donations found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php $isSortedByDate = request('sort_by', 'created_at') === 'created_at'; @endphp
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_dir' => $isSortedByDate && request('sort_dir', 'desc') === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-gray-900">
                                        Date/Time
                                        @if($isSortedByDate)
                                            <svg class="w-3 h-3 {{ request('sort_dir', 'desc') === 'desc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/></svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php $isSortedByAmount = request('sort_by') === 'amount'; @endphp
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'amount', 'sort_dir' => $isSortedByAmount && request('sort_dir', 'desc') === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-gray-900">
                                        Amount
                                        @if($isSortedByAmount)
                                            <svg class="w-3 h-3 {{ request('sort_dir', 'desc') === 'desc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/></svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($donations as $donation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $donation->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $donation->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-600">{{ $donation->receipt_number ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">€{{ number_format($donation->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $donation->campaign->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $donation->device->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($donation->payment_status === 'success')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Success
                                        </span>
                                    @elseif($donation->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($donation->payment_status === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($donation->payment_status ?? 'Unknown') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-mono text-gray-400">{{ Str::limit($donation->sumup_transaction_id ?? $donation->transaction_id ?? '-', 20) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Showing {{ $donations->firstItem() }}–{{ $donations->lastItem() }} of {{ number_format($donations->total()) }} results
                    </p>
                    {{ $donations->links() }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const PRIMARY = '#1163F0';
        const PRIMARY_LIGHT = 'rgba(17,99,240,0.15)';
        const COLORS = ['#1163F0','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4','#F97316','#EC4899'];

        // ---- Trend Chart ----
        const trendData = @json($trendData);
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [{
                    label: 'Total (€)',
                    data: trendData.totals,
                    borderColor: PRIMARY,
                    backgroundColor: PRIMARY_LIGHT,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + trendData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => '€' + v.toLocaleString()
                        },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            maxRotation: 45,
                            maxTicksLimit: 10
                        }
                    }
                }
            }
        });

        // ---- Campaign Chart ----
        const campaignData = @json($campaignChartData);
        if (campaignData.labels.length > 0) {
            new Chart(document.getElementById('campaignChart'), {
                type: 'bar',
                data: {
                    labels: campaignData.labels,
                    datasets: [{
                        label: 'Total (€)',
                        data: campaignData.totals,
                        backgroundColor: COLORS,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + campaignData.counts[ctx.dataIndex] + ' donations)'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: v => '€' + v.toLocaleString() },
                            grid: { color: '#f3f4f6' }
                        },
                        x: { grid: { display: false }, ticks: { maxRotation: 45 } }
                    }
                }
            });
        } else {
            document.getElementById('campaignChart').closest('.relative').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400">No campaign data yet</div>';
        }

        // ---- Hourly Chart ----
        const hourlyData = @json($hourlyData);
        new Chart(document.getElementById('hourlyChart'), {
            type: 'bar',
            data: {
                labels: hourlyData.labels,
                datasets: [{
                    label: 'Donations',
                    data: hourlyData.counts,
                    backgroundColor: 'rgba(16,185,129,0.7)',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' donations (€' + hourlyData.totals[ctx.dataIndex].toFixed(2) + ')'
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false }, ticks: { maxRotation: 45 } }
                }
            }
        });

        // ---- Day of Week Chart ----
        const dowData = @json($dayOfWeekData);
        new Chart(document.getElementById('dowChart'), {
            type: 'bar',
            data: {
                labels: dowData.labels,
                datasets: [{
                    label: 'Donations',
                    data: dowData.counts,
                    backgroundColor: COLORS.slice(0, 7),
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' donations (€' + dowData.totals[ctx.dataIndex].toFixed(2) + ')'
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // ---- Device Chart ----
        @if(count($deviceChartData['labels']) > 0)
        const deviceData = @json($deviceChartData);
        new Chart(document.getElementById('deviceChart'), {
            type: 'bar',
            data: {
                labels: deviceData.labels,
                datasets: [{
                    label: 'Total (€)',
                    data: deviceData.totals,
                    backgroundColor: COLORS,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + deviceData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '€' + v.toLocaleString() },
                        grid: { color: '#f3f4f6' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
        @endif

        // ---- Date preset toggle ----
        function handleDatePresetChange() {
            const val = document.getElementById('datePreset').value;
            const show = val === 'custom';
            document.getElementById('customDateRange').classList.toggle('hidden', !show);
            document.getElementById('customDateRangeTo').classList.toggle('hidden', !show);
        }

        // ---- Per page change ----
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    </script>
    @endpush
</x-organization-layout>
