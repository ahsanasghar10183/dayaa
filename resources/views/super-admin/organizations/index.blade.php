<x-super-admin-sidebar-layout>
    <x-slot name="header">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('admin.super_admin.organizations') }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ __('admin.super_admin.organizations_subtitle') }}</p>
    </x-slot>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" action="{{ route('super-admin.organizations.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.super_admin.search_organizations_placeholder') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <select name="status" class="select sm:w-auto">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('admin.super_admin.status_all') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.super_admin.status_pending') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.common.active') }}</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('admin.super_admin.suspended') }}</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('admin.super_admin.rejected') }}</option>
                </select>

                <!-- Filter Button -->
                <button type="submit" class="btn-primary whitespace-nowrap">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    {{ __('admin.common.filter') }}
                </button>

                <!-- Reset -->
                @if(request('search') || request('status') != 'all')
                <a href="{{ route('super-admin.organizations.index') }}" class="btn-secondary whitespace-nowrap">
                    {{ __('admin.common.reset') }}
                </a>
                @endif
            </form>
        </div>

        <!-- Organizations List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.dashboard.organization') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.super_admin.contact') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.super_admin.subscription') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.dashboard.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.super_admin.registered') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($organizations as $org)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($org->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $org->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $org->charity_number ?? __('admin.super_admin.no_charity_number') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $org->contact_person }}</div>
                                <div class="text-xs text-gray-500">{{ $org->user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $org->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($org->subscription)
                                    <span class="badge-{{ $org->subscription->plan == 'premium' ? 'info' : 'gray' }}">
                                        {{ ucfirst($org->subscription->plan) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">{{ __('admin.super_admin.no_subscription') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($org->status == 'active')
                                    <span class="badge-success">{{ __('admin.common.active') }}</span>
                                @elseif($org->status == 'pending')
                                    <span class="badge-warning">{{ __('admin.super_admin.status_pending') }}</span>
                                @elseif($org->status == 'suspended')
                                    <span class="badge-error">{{ __('admin.super_admin.suspended') }}</span>
                                @else
                                    <span class="badge-gray">{{ __('admin.super_admin.rejected') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $org->created_at->translatedFormat('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('super-admin.organizations.show', $org) }}" class="text-primary-600 hover:text-primary-900">
                                    {{ __('admin.super_admin.view_details') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <p class="mt-4 text-sm">{{ __('admin.super_admin.no_organizations_found') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($organizations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $organizations->links() }}
            </div>
            @endif
        </div>
</x-super-admin-sidebar-layout>
