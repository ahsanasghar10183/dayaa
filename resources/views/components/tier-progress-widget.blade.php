@props(['subscription', 'tierProgress'])

@php
    $currentTier = $tierProgress['current_tier'];
    $nextTier = $tierProgress['next_tier'];
    $total12m = $tierProgress['total_12m'];
    $progress = $tierProgress['progress'];
    $amountToNext = $tierProgress['amount_to_next_tier'];
@endphp

<div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">{{ __('admin.dashboard.subscription_tier') }}</h3>
        <a href="{{ route('organization.billing.index') }}" class="text-xs text-blue-100 hover:text-white transition-colors">
            {{ __('admin.common.view_details') }} →
        </a>
    </div>

    {{-- Current Tier --}}
    <div class="mb-6">
        <p class="text-sm text-blue-100 mb-1">{{ __('admin.dashboard.current_tier') }}</p>
        <div class="flex items-baseline justify-between">
            <h4 class="text-3xl font-bold">{{ $currentTier ? $currentTier->name : 'Free' }}</h4>
            <div class="text-right">
                <p class="text-2xl font-bold">
                    €{{ number_format($currentTier ? $currentTier->monthly_fee : 0, 0) }}
                </p>
                <p class="text-xs text-blue-100">{{ __('admin.billing.per_month') }}</p>
            </div>
        </div>
    </div>

    {{-- 12-Month Fundraising Total --}}
    <div class="bg-white bg-opacity-10 rounded-lg p-4 mb-4 backdrop-blur-sm">
        <p class="text-xs text-blue-100 mb-1">{{ __('admin.billing.12_month_fundraising') }}</p>
        <p class="text-2xl font-bold">€{{ number_format($total12m, 0) }}</p>
        <p class="text-xs text-blue-100 mt-1">{{ __('admin.billing.determines_tier') }}</p>
    </div>

    @if($nextTier)
        {{-- Progress to Next Tier --}}
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-blue-100">{{ __('admin.billing.progress_to') }} {{ $nextTier->name }}</p>
                <p class="text-xs font-semibold">{{ number_format($progress, 1) }}%</p>
            </div>
            <div class="w-full bg-blue-900 bg-opacity-30 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-500 shadow-lg"
                     style="width: {{ min($progress, 100) }}%"></div>
            </div>
        </div>

        {{-- Amount to Next Tier --}}
        <div class="border-t border-white border-opacity-20 pt-4">
            <p class="text-sm">
                <span class="font-bold">€{{ number_format($amountToNext, 0) }}</span>
                <span class="text-blue-100">{{ __('admin.billing.more_to_reach') }}</span>
            </p>
            <p class="text-xs text-blue-100 mt-1">
                {{ $nextTier->name }} (€{{ number_format($nextTier->monthly_fee, 0) }}/{{ __('admin.billing.month') }})
            </p>
        </div>
    @else
        {{-- At Highest Tier --}}
        <div class="border-t border-white border-opacity-20 pt-4 text-center">
            <svg class="w-12 h-12 text-yellow-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <p class="text-sm font-semibold">{{ __('admin.billing.highest_tier') }}</p>
            <p class="text-xs text-blue-100 mt-1">{{ __('admin.billing.excellent_work') }}</p>
        </div>
    @endif

    {{-- Subscription Status --}}
    @if($subscription)
        <div class="mt-4 pt-4 border-t border-white border-opacity-20">
            <div class="flex items-center justify-between text-xs">
                <span class="text-blue-100">{{ __('admin.dashboard.status') }}</span>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $subscription->status === 'active' ? 'bg-green-400 text-green-900' : 'bg-yellow-400 text-yellow-900' }}">
                    {{ ucfirst($subscription->status) }}
                </span>
            </div>
            @if($subscription->current_period_end)
                <div class="flex items-center justify-between text-xs mt-2">
                    <span class="text-blue-100">{{ __('admin.billing.next_billing') }}</span>
                    <span class="font-semibold">{{ $subscription->current_period_end->format('M d, Y') }}</span>
                </div>
            @endif
        </div>
    @endif
</div>
