<x-organization-sidebar-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.billing.tiers_page_title') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('admin.billing.tiers_page_subtitle') }}</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Current Status --}}
        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">{{ __('admin.billing.your_12m_total') }}</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">€{{ number_format($total12m, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-700">{{ __('admin.billing.current_tier') }}</p>
                    <p class="text-2xl font-bold text-blue-900">
                        {{ $subscription && $subscription->tier ? $subscription->tier->name : __('admin.billing.free') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- How It Works --}}
        <div class="mb-8 bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.billing.how_it_works_title') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-bold">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">{{ __('admin.billing.step1_title') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('admin.billing.step1_text') }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-bold">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">{{ __('admin.billing.step2_title') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('admin.billing.step2_text') }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 font-bold">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">{{ __('admin.billing.step3_title') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('admin.billing.step3_text') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tier Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tiers as $tier)
                @php
                    $isCurrentTier = $currentTierId === $tier->id;
                    $isInRange = $tier->isInRange($total12m);
                @endphp

                <div class="bg-white rounded-lg border-2 {{ $isCurrentTier ? 'border-blue-500 shadow-lg' : 'border-gray-200' }} overflow-hidden hover:shadow-md transition-shadow">
                    @if($isCurrentTier)
                        <div class="bg-blue-500 text-white text-center py-2 text-sm font-semibold">
                            {{ __('admin.billing.your_current_tier_badge') }}
                        </div>
                    @elseif($isInRange)
                        <div class="bg-green-500 text-white text-center py-2 text-sm font-semibold">
                            {{ __('admin.billing.eligible_pending') }}
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">{{ $tier->name }}</h3>

                        {{-- Fundraising Range --}}
                        <p class="text-sm text-gray-600 mt-2">
                            @if($tier->max_amount)
                                €{{ number_format($tier->min_amount, 0) }} - €{{ number_format($tier->max_amount, 0) }}
                            @else
                                €{{ number_format($tier->min_amount, 0) }}+
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">{{ __('admin.billing.fundraising_range_12m') }}</p>

                        {{-- Monthly Fee --}}
                        <div class="mt-4 mb-6">
                            <span class="text-3xl font-bold text-gray-900">
                                €{{ number_format($tier->monthly_fee, 0) }}
                            </span>
                            <span class="text-gray-600">/{{ __('admin.billing.month') }}</span>
                        </div>

                        {{-- Status --}}
                        @if($isCurrentTier)
                            <div class="text-center py-2 bg-blue-50 text-blue-700 rounded font-medium text-sm">
                                {{ __('admin.billing.active_plan') }}
                            </div>
                        @elseif($isInRange)
                            <div class="text-center py-2 bg-green-50 text-green-700 rounded font-medium text-sm">
                                {{ __('admin.billing.upgrading_soon') }}
                            </div>
                        @elseif($total12m < $tier->min_amount)
                            <div class="text-center py-2 bg-gray-50 text-gray-600 rounded text-sm">
                                {{ __('admin.billing.amount_to_unlock', ['amount' => '€' . number_format($tier->min_amount - $total12m, 0)]) }}
                            </div>
                        @else
                            <div class="text-center py-2 bg-gray-50 text-gray-600 rounded text-sm">
                                {{ __('admin.billing.tier_change_next_billing') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Info Notice --}}
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start gap-3">
            <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="font-semibold text-yellow-900">{{ __('admin.billing.auto_tier_management') }}</h4>
                <p class="text-sm text-yellow-700 mt-1">
                    {{ __('admin.billing.auto_tier_management_text') }}
                </p>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-8 text-center">
            <a href="{{ route('organization.billing.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                &larr; {{ __('admin.billing.back_to_billing') }}
            </a>
        </div>

    </div>
</x-organization-sidebar-layout>
