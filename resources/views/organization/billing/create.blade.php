<x-organization-sidebar-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.billing.activate_subscription') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('admin.billing.complete_setup_text') }}</p>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Alert: Required to Continue --}}
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-blue-900">{{ __('admin.billing.subscription_required') }}</h3>
                    <p class="mt-2 text-sm text-blue-700">
                        {{ __('admin.billing.subscription_required_text') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Your Recommended Tier --}}
        <div class="mb-8 bg-white rounded-xl shadow-sm border-2 border-blue-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">{{ __('admin.billing.recommended_tier') }}</h3>
                <p class="text-sm text-blue-50 mt-1">{{ __('admin.billing.based_on_expected_fundraising') }}</p>
            </div>

            <div class="p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h4 class="text-3xl font-bold text-gray-900">{{ $recommendedTier->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($recommendedTier->max_amount)
                                €{{ number_format($recommendedTier->min_amount, 0) }} - €{{ number_format($recommendedTier->max_amount, 0) }}
                            @else
                                €{{ number_format($recommendedTier->min_amount, 0) }}+
                            @endif
                            <span class="text-gray-500">/ {{ __('admin.billing.12_month_range') }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-bold text-blue-600">
                            €{{ number_format($recommendedTier->monthly_fee, 0) }}
                        </p>
                        <p class="text-sm text-gray-600">{{ __('admin.billing.per_month') }}</p>
                    </div>
                </div>

                {{-- Features Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6 pb-6 border-b border-gray-200">
                    @foreach($recommendedTier->features as $feature)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Tier Benefits Notice --}}
                <div class="bg-blue-50 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-blue-900 mb-2">{{ __('admin.billing.auto_tier_adjustment') }}</h5>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        {{ __('admin.billing.tier_grows_with_success') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Payment Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.billing.payment_details') }}</h3>

            <form id="payment-form" action="{{ route('organization.billing.store') }}" method="POST">
                @csrf

                <input type="hidden" name="tier_id" value="{{ $recommendedTier->id }}">

                {{-- Organization Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.organization_name') }}
                        </label>
                        <input type="text"
                               name="billing_name"
                               value="{{ auth()->user()->organization->name }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.email_address') }}
                        </label>
                        <input type="email"
                               name="billing_email"
                               value="{{ auth()->user()->email }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>

                {{-- Stripe Card Element --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('admin.billing.card_details') }}
                    </label>
                    <div id="card-element" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white">
                        <!-- Stripe Card Element will be inserted here -->
                    </div>
                    <div id="card-errors" class="mt-2 text-sm text-red-600"></div>
                </div>

                {{-- Billing Address --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.address') }}
                        </label>
                        <input type="text"
                               name="billing_address"
                               value="{{ auth()->user()->organization->address ?? '' }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.city') }}
                        </label>
                        <input type="text"
                               name="billing_city"
                               value="{{ auth()->user()->organization->city ?? '' }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.postal_code') }}
                        </label>
                        <input type="text"
                               name="billing_postal_code"
                               value="{{ auth()->user()->organization->postal_code ?? '' }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.country') }}
                        </label>
                        <select name="billing_country"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="DE">{{ __('admin.countries.germany') }}</option>
                            <option value="AT">{{ __('admin.countries.austria') }}</option>
                            <option value="CH">{{ __('admin.countries.switzerland') }}</option>
                            <option value="FR">{{ __('admin.countries.france') }}</option>
                            <option value="NL">{{ __('admin.countries.netherlands') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.billing.vat_number') }}
                            <span class="text-gray-400 font-normal">({{ __('admin.common.optional') }})</span>
                        </label>
                        <input type="text"
                               name="vat_number"
                               value="{{ auth()->user()->organization->vat_number ?? '' }}"
                               placeholder="DE123456789"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- Terms & Conditions --}}
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="terms_accepted"
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               required>
                        <span class="text-sm text-gray-700">
                            {{ __('admin.billing.i_agree_to') }}
                            <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-800 underline">{{ __('admin.billing.terms_and_conditions') }}</a>
                            {{ __('admin.billing.and') }}
                            <a href="/privacy" target="_blank" class="text-blue-600 hover:text-blue-800 underline">{{ __('admin.billing.privacy_policy') }}</a>
                        </span>
                    </label>
                </div>

                {{-- Order Summary --}}
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-base font-semibold text-gray-900 mb-4">{{ __('admin.billing.order_summary') }}</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $recommendedTier->name }} {{ __('admin.billing.subscription') }}</span>
                            <span class="font-semibold text-gray-900">€{{ number_format($recommendedTier->monthly_fee, 2) }}/{{ __('admin.billing.month') }}</span>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900">{{ __('admin.billing.total_today') }}</span>
                                <span class="text-2xl font-bold text-blue-600">€{{ number_format($recommendedTier->monthly_fee, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ __('admin.billing.billed_monthly') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('organization.billing.plans') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        {{ __('admin.billing.view_all_tiers') }} →
                    </a>
                    <button type="submit"
                            id="submit-button"
                            class="btn-primary px-8 py-3 text-base font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span id="button-text">{{ __('admin.billing.activate_subscription') }}</span>
                        <svg id="spinner" class="hidden w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Security Notice --}}
        <div class="flex items-center justify-center gap-2 text-sm text-gray-500 mb-8">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ __('admin.billing.secure_payment_stripe') }}</span>
        </div>

    </div>

    {{-- Stripe.js Integration --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config("services.stripe.key") }}');
        const elements = stripe.elements();

        // Create card element
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#1f2937',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    '::placeholder': {
                        color: '#9ca3af',
                    },
                },
                invalid: {
                    color: '#dc2626',
                    iconColor: '#dc2626',
                },
            },
        });

        cardElement.mount('#card-element');

        // Handle real-time validation errors
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Disable button and show loading state
            submitButton.disabled = true;
            buttonText.textContent = '{{ __("admin.billing.processing") }}...';
            spinner.classList.remove('hidden');

            try {
                // Create payment method
                const {paymentMethod, error} = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        name: document.querySelector('[name="billing_name"]').value,
                        email: document.querySelector('[name="billing_email"]').value,
                        address: {
                            line1: document.querySelector('[name="billing_address"]').value,
                            city: document.querySelector('[name="billing_city"]').value,
                            postal_code: document.querySelector('[name="billing_postal_code"]').value,
                            country: document.querySelector('[name="billing_country"]').value,
                        },
                    },
                });

                if (error) {
                    // Show error
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;

                    // Re-enable button
                    submitButton.disabled = false;
                    buttonText.textContent = '{{ __("admin.billing.activate_subscription") }}';
                    spinner.classList.add('hidden');
                } else {
                    // Add payment method ID to form
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.setAttribute('value', paymentMethod.id);
                    form.appendChild(hiddenInput);

                    // Submit form
                    form.submit();
                }
            } catch (err) {
                console.error('Payment error:', err);
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = '{{ __("admin.billing.payment_error") }}';

                // Re-enable button
                submitButton.disabled = false;
                buttonText.textContent = '{{ __("admin.billing.activate_subscription") }}';
                spinner.classList.add('hidden');
            }
        });
    </script>
</x-organization-sidebar-layout>
