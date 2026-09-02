<x-organization-sidebar-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.organization.donations') }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ __('admin.organization.donations_page.subtitle') }}</p>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Donations -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">{{ __('admin.organization.device_show.total_donations') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_donations']) }}</p>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">{{ __('admin.organization.donations_page.total_amount') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">€{{ number_format($stats['total_amount'], 2) }}</p>
            </div>
        </div>

        <!-- Today's Donations -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">{{ __('admin.organization.donations_page.today') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['today_donations']) }}</p>
                <p class="mt-3 text-sm text-gray-600">€{{ number_format($stats['today_amount'], 2) }}</p>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">{{ __('admin.organization.device_show.this_month') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['this_month_donations']) }}</p>
                <p class="mt-3 text-sm text-gray-600">€{{ number_format($stats['this_month_amount'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('organization.donations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Campaign Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.table_campaign') }}</label>
                <select name="campaign" class="select">
                    <option value="">{{ __('admin.organization.donations_page.all_campaigns') }}</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ request('campaign') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Device Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.table_device') }}</label>
                <select name="device" class="select">
                    <option value="">{{ __('admin.organization.donations_page.all_devices') }}</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" {{ request('device') == $device->id ? 'selected' : '' }}>
                            {{ $device->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.donations_page.from_date') }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all text-sm">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.donations_page.to_date') }}</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all text-sm">
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    {{ __('admin.organization.donations_page.apply_filters') }}
                </button>
                @if(request()->hasAny(['campaign', 'device', 'date_from', 'date_to']))
                <a href="{{ route('organization.donations.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ __('admin.organization.donations_page.clear_filters') }}
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Donations Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.organization.donations_page.all_donations_title') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ __('admin.organization.donations_page.total_donations_count', ['count' => $donations->total()]) }}</p>
        </div>

        @if($donations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.dashboard.amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.organization.table_campaign') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.organization.table_device') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.organization.donations_page.table_reader') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.organization.donations_page.table_payment_method') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('admin.organization.device_show.table_date_time') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($donations as $donation)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                €{{ number_format($donation->amount, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 truncate max-w-[160px] block">{{ $donation->campaign->name ?? __('admin.billing.not_available') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 truncate max-w-[140px] block">{{ $donation->device->name ?? __('admin.billing.not_available') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($donation->reader)
                                <span class="text-sm text-gray-600 truncate max-w-[140px] block">{{ $donation->reader->name }}</span>
                            @else
                                <span class="text-sm text-gray-400">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ ucfirst($donation->payment_method) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $donation->created_at->format('M d, Y') }}
                            <span class="text-gray-400">{{ __('admin.organization.device_show.at_time') }}</span>
                            {{ $donation->created_at->format('H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $donations->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('admin.organization.donations_page.no_donations_found_title') }}</h3>
            <p class="text-gray-600">
                @if(request()->hasAny(['campaign', 'device', 'date_from', 'date_to']))
                    {{ __('admin.organization.donations_page.no_donations_filtered_text') }}
                @else
                    {{ __('admin.organization.donations_page.no_donations_empty_text') }}
                @endif
            </p>
        </div>
        @endif
    </div>
</x-organization-sidebar-layout>
