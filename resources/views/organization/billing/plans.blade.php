<x-organization-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Subscription Tiers</h2>
            <p class="text-sm text-gray-500 mt-1">Your tier is automatically determined by your 12-month fundraising total</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Current Status --}}
        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">Your 12-Month Fundraising Total</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">€{{ number_format($total12m, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-700">Current Tier</p>
                    <p class="text-2xl font-bold text-blue-900">
                        {{ $subscription && $subscription->tier ? $subscription->tier->name : 'Free' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- How It Works --}}
        <div class="mb-8 bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">How Tier-Based Pricing Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-bold">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Fundraise & Grow</h4>
                        <p class="text-sm text-gray-600 mt-1">Your tier is based on total donations received in the last 12 months</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-bold">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Auto-Upgrade</h4>
                        <p class="text-sm text-gray-600 mt-1">When you reach a new tier threshold, you're automatically upgraded</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 font-bold">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Fair Pricing</h4>
                        <p class="text-sm text-gray-600 mt-1">Pay only for what you need - pricing scales with your success</p>
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
                            Your Current Tier
                        </div>
                    @elseif($isInRange)
                        <div class="bg-green-500 text-white text-center py-2 text-sm font-semibold">
                            Eligible (Pending Next Billing)
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
                        <p class="text-xs text-gray-500">12-month fundraising range</p>

                        {{-- Monthly Fee --}}
                        <div class="mt-4 mb-6">
                            <span class="text-3xl font-bold text-gray-900">
                                €{{ number_format($tier->monthly_fee, 0) }}
                            </span>
                            <span class="text-gray-600">/month</span>
                        </div>

                        {{-- Features --}}
                        <ul class="space-y-2 mb-6">
                            @foreach($tier->features as $feature)
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Status --}}
                        @if($isCurrentTier)
                            <div class="text-center py-2 bg-blue-50 text-blue-700 rounded font-medium text-sm">
                                Active Plan
                            </div>
                        @elseif($isInRange)
                            <div class="text-center py-2 bg-green-50 text-green-700 rounded font-medium text-sm">
                                Upgrading Soon
                            </div>
                        @elseif($total12m < $tier->min_amount)
                            <div class="text-center py-2 bg-gray-50 text-gray-600 rounded text-sm">
                                €{{ number_format($tier->min_amount - $total12m, 0) }} to unlock
                            </div>
                        @else
                            <div class="text-center py-2 bg-gray-50 text-gray-600 rounded text-sm">
                                Tier change on next billing
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
                <h4 class="font-semibold text-yellow-900">Automatic Tier Management</h4>
                <p class="text-sm text-yellow-700 mt-1">
                    Tier changes are applied automatically on your next billing date. You'll receive an email notification 7 days before any tier change.
                    Your 12-month fundraising total is recalculated daily.
                </p>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-8 text-center">
            <a href="{{ route('organization.billing.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Back to Billing Overview
            </a>
        </div>

    </div>
</x-organization-layout>
