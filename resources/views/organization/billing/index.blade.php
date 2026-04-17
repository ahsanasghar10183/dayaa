<x-organization-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Billing & Subscription</h2>
                <p class="text-sm text-gray-500 mt-1">Tier-based pricing that grows with your success</p>
            </div>
            <a href="{{ route('organization.billing.plans') }}" class="btn-primary">
                View All Tiers
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Pending Tier Change Alert --}}
        @if($pendingTierChange)
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-900">Tier Change Scheduled</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        Your subscription will be upgraded to <strong>{{ $pendingTierChange->toTier->name }}</strong> 
                        on {{ $pendingTierChange->scheduled_date->format('F j, Y') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Current Tier Card --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Current Tier</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $currentTier ? $currentTier->name : 'No Active Subscription' }}</h3>
                        @if($currentTier)
                        <p class="text-2xl font-bold text-primary-600 mt-1">
                            €{{ number_format($currentTier->monthly_fee, 2) }}
                            <span class="text-sm font-normal text-gray-400">/month</span>
                        </p>
                        @endif
                    </div>
                    @if($subscription && in_array($subscription->status, ['active', 'trialing']))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            @if($subscription->status === 'trialing')
                                Active (Payment in {{ \Carbon\Carbon::parse($subscription->current_period_end)->diffInDays(now()) }} days)
                            @else
                                Active
                            @endif
                        </span>
                    @endif
                </div>

                {{-- Billing Dates --}}
                @if($subscription)
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Next Billing</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">
                            {{ $subscription->next_billing_date?->format('M d, Y') ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Period Ends</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">
                            {{ $subscription->current_period_end?->format('M d, Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                @endif
            </div>

            {{-- 12-Month Total Card --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
                <p class="text-sm font-medium text-blue-700 mb-2">12-Month Fundraising</p>
                <p class="text-4xl font-bold text-blue-900">
                    €{{ number_format($total12m, 0) }}
                </p>
                <p class="text-xs text-blue-600 mt-2">Determines your subscription tier</p>
            </div>
        </div>

        {{-- Tier Progress --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Progress to Next Tier</h3>
                @if($nextTier)
                    <span class="text-sm text-gray-600">
                        {{ number_format($progress, 1) }}% to {{ $nextTier->name }}
                    </span>
                @endif
            </div>

            @if($nextTier)
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500"
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Current: €{{ number_format($total12m, 2) }}</p>
                        <p class="text-xs text-gray-500">12-month total</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">Next: €{{ number_format($nextTier->min_amount, 0) }}</p>
                        <p class="text-xs text-gray-500">{{ $nextTier->name }} tier</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-700">
                        <strong>€{{ number_format($nextTier->min_amount - $total12m, 2) }}</strong> more in fundraising 
                        to reach {{ $nextTier->name }} (€{{ number_format($nextTier->monthly_fee, 2) }}/month)
                    </p>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">You're at the highest tier!</h4>
                    <p class="text-sm text-gray-600">Keep up the excellent fundraising work.</p>
                </div>
            @endif
        </div>

        {{-- Usage Limits --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Usage & Limits</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Campaigns --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Active Campaigns</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $usedCampaigns }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>

                {{-- Devices --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Paired Devices</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $usedDevices }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 40%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Current Tier Features --}}
        @if($currentTier)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $currentTier->name }} Includes</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($currentTier->features as $feature)
                <li class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>
</x-organization-sidebar-layout>
