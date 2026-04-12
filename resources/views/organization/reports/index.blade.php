<x-organization-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Donation Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Detailed donation records with advanced filtering and export</p>
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
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
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
                <p class="text-xs text-green-600 font-medium">Completed</p>
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
                                    @if($donation->payment_status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @elseif($donation->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($donation->payment_status === 'processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Processing
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
    <script>
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
</x-organization-sidebar-layout>
