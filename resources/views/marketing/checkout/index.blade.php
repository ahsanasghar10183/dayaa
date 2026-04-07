@extends('marketing.layouts.master')

@section('title', __('marketing.checkout.title') . ' - Dayaa')
@section('meta_description', __('marketing.checkout.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.checkout.title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.checkout.page_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Checkout Content -->
<section class="section-padding fix">
    <div class="container">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('marketing.checkout.process') }}" method="POST" class="checkout-form">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Contact Information -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('marketing.checkout.contact_information') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="customer_email" class="form-label">{{ __('marketing.checkout.email_address') }} *</label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required placeholder="you@example.com">
                                    @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('marketing.checkout.billing_address') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">{{ __('marketing.checkout.first_name') }} *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">{{ __('marketing.checkout.last_name') }} *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="company" class="form-label">{{ __('marketing.checkout.company') }} <span class="text-muted">({{ __('marketing.checkout.optional') }})</span></label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company') }}">
                                    @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">{{ __('marketing.checkout.address') }} *</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required placeholder="{{ __('marketing.checkout.address_placeholder') }}">
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="apartment" class="form-label">{{ __('marketing.checkout.apartment') }} <span class="text-muted">({{ __('marketing.checkout.optional') }})</span></label>
                                    <input type="text" class="form-control @error('apartment') is-invalid @enderror" id="apartment" name="apartment" value="{{ old('apartment') }}" placeholder="{{ __('marketing.checkout.apartment_placeholder') }}">
                                    @error('apartment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="city" class="form-label">{{ __('marketing.checkout.city') }} *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">{{ __('marketing.checkout.country') }} *</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                                    @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="postal_code" class="form-label">{{ __('marketing.checkout.postal_code') }} *</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                    @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="customer_phone" class="form-label">{{ __('marketing.checkout.phone_number') }} <span class="text-muted">({{ __('marketing.checkout.optional') }})</span></label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="+49 123 456 7890">
                                    @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sameAsBilling" name="same_as_billing" value="1" checked onchange="toggleShippingAddress()">
                                <label class="form-check-label" for="sameAsBilling">
                                    <h4 class="mb-0 d-inline">{{ __('marketing.checkout.shipping_address_same') }}</h4>
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="shippingAddressFields" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="shipping_first_name" class="form-label">{{ __('marketing.checkout.first_name') }} *</label>
                                    <input type="text" class="form-control" id="shipping_first_name" name="shipping_first_name" value="{{ old('shipping_first_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="shipping_last_name" class="form-label">{{ __('marketing.checkout.last_name') }} *</label>
                                    <input type="text" class="form-control" id="shipping_last_name" name="shipping_last_name" value="{{ old('shipping_last_name') }}">
                                </div>
                                <div class="col-12">
                                    <label for="shipping_company" class="form-label">{{ __('marketing.checkout.company') }} <span class="text-muted">({{ __('marketing.checkout.optional') }})</span></label>
                                    <input type="text" class="form-control" id="shipping_company" name="shipping_company" value="{{ old('shipping_company') }}">
                                </div>
                                <div class="col-12">
                                    <label for="shipping_address" class="form-label">{{ __('marketing.checkout.address') }} *</label>
                                    <input type="text" class="form-control" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="{{ __('marketing.checkout.address_placeholder') }}">
                                </div>
                                <div class="col-12">
                                    <label for="shipping_apartment" class="form-label">{{ __('marketing.checkout.apartment') }} <span class="text-muted">({{ __('marketing.checkout.optional') }})</span></label>
                                    <input type="text" class="form-control" id="shipping_apartment" name="shipping_apartment" value="{{ old('shipping_apartment') }}" placeholder="{{ __('marketing.checkout.apartment_placeholder') }}">
                                </div>
                                <div class="col-12">
                                    <label for="shipping_city" class="form-label">{{ __('marketing.checkout.city') }} *</label>
                                    <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="shipping_country" class="form-label">{{ __('marketing.checkout.country') }} *</label>
                                    <input type="text" class="form-control" id="shipping_country" name="shipping_country" value="{{ old('shipping_country') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="shipping_postal_code" class="form-label">{{ __('marketing.checkout.postal_code') }} *</label>
                                    <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="order-summary-card mb-4">
                        <div class="card-header">
                            <h4>{{ __('marketing.checkout.order_summary') }}</h4>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">{{ __('marketing.checkout.items') }} ({{ $cartItems->count() }})</h6>
                            @foreach($cartItems as $item)
                            <div class="order-item">
                                <div>
                                    <small>{{ $item->product->name }}</small><br>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <small><strong>{{ $item->formatted_subtotal }}</strong></small>
                            </div>
                            @endforeach

                            <div class="order-totals">
                                <div class="order-totals-row">
                                    <span>{{ __('marketing.checkout.subtotal') }}</span>
                                    <strong>€{{ number_format($subtotal, 2) }}</strong>
                                </div>
                                <div class="order-totals-row">
                                    <span>{{ __('marketing.checkout.tax') }}</span>
                                    <strong>€{{ number_format($tax, 2) }}</strong>
                                </div>
                                <div class="order-totals-row">
                                    <span>{{ __('marketing.checkout.shipping') }}</span>
                                    <strong>
                                        @if($shipping == 0)
                                        <span class="text-success">{{ __('marketing.checkout.free') }}</span>
                                        @else
                                        €{{ number_format($shipping, 2) }}
                                        @endif
                                    </strong>
                                </div>
                            </div>

                            <div class="order-total-final">
                                <h5>{{ __('marketing.checkout.total') }}</h5>
                                <h5 class="text-primary">€{{ number_format($total, 2) }}</h5>
                            </div>

                            <button type="submit" class="place-order-btn">
                                {{ __('marketing.checkout.place_order') }} <i class="fa-solid fa-lock"></i>
                            </button>

                            <div class="security-badge">
                                <small>
                                    <i class="fa-solid fa-shield-alt"></i> {{ __('marketing.checkout.secure_payment') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="whats-included-card">
                        <div class="card-body">
                            <h6>{{ __('marketing.checkout.whats_included') }}</h6>
                            <p><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.warranty') }}</p>
                            <p><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.free_setup') }}</p>
                            <p><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.returns') }}</p>
                            <p><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.support') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Shop Trust Section -->
<x-shop-trust-section />

@endsection

@push('styles')
<style>
/* Modern Checkout Styling */
.checkout-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 24px;
}

.checkout-card .card-header {
    background: linear-gradient(135deg, rgba(15, 105, 243, 0.03) 0%, rgba(23, 10, 181, 0.03) 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    padding: 20px 24px;
}

.checkout-card .card-header h4 {
    color: #1a1a2e;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.checkout-card .card-body {
    padding: 24px;
}

/* Form Elements */
.checkout-form .form-label {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.checkout-form .form-control,
.checkout-form .form-select {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 15px;
    transition: all 0.2s ease;
}

.checkout-form .form-control:focus,
.checkout-form .form-select:focus {
    border-color: #0F69F3;
    box-shadow: 0 0 0 3px rgba(15, 105, 243, 0.1);
    outline: none;
}

.checkout-form .form-control::placeholder {
    color: #9ca3af;
}

/* Checkbox Styling */
.form-check-input {
    width: 20px;
    height: 20px;
    margin-top: 0;
    cursor: pointer;
    border: 2px solid #d1d5db;
}

.form-check-input:checked {
    background-color: #0F69F3;
    border-color: #0F69F3;
}

.form-check-label {
    margin-left: 8px;
    cursor: pointer;
    user-select: none;
}

/* Order Summary */
.order-summary-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.06);
}

.order-summary-card .card-header {
    background: linear-gradient(135deg, rgba(15, 105, 243, 0.03) 0%, rgba(23, 10, 181, 0.03) 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    padding: 20px 24px;
}

.order-summary-card .card-header h4 {
    color: #1a1a2e;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.order-summary-card .card-body {
    padding: 24px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.order-item:last-child {
    border-bottom: none;
}

.order-item small {
    font-size: 14px;
    color: #4b5563;
}

.order-totals {
    padding: 16px 0;
    border-top: 2px solid rgba(0, 0, 0, 0.1);
}

.order-totals-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 15px;
}

.order-totals-row span {
    color: #4b5563;
}

.order-totals-row strong {
    color: #1a1a2e;
}

.order-total-final {
    display: flex;
    justify-content: space-between;
    padding: 16px 0;
    margin-top: 8px;
    border-top: 2px solid rgba(0, 0, 0, 0.1);
}

.order-total-final h5 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

/* Place Order Button */
.place-order-btn {
    width: 100%;
    padding: 16px 24px;
    font-size: 16px;
    font-weight: 600;
    background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%);
    border: none;
    border-radius: 10px;
    color: white;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.place-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 105, 243, 0.3);
}

/* Security Badge */
.security-badge {
    text-align: center;
    padding: 12px;
    background: rgba(15, 105, 243, 0.05);
    border-radius: 8px;
    margin-top: 16px;
}

.security-badge small {
    color: #4b5563;
    font-size: 13px;
}

.security-badge i {
    color: #0F69F3;
    margin-right: 4px;
}

/* What's Included Card */
.whats-included-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.06);
}

.whats-included-card .card-body {
    padding: 24px;
}

.whats-included-card h6 {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 16px;
}

.whats-included-card p {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 12px;
}

.whats-included-card p:last-child {
    margin-bottom: 0;
}

.whats-included-card i {
    margin-right: 8px;
    font-size: 16px;
}

/* Google Places Autocomplete Styling */
.pac-container {
    background-color: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    margin-top: 4px;
    font-family: inherit;
    z-index: 9999;
}

.pac-item {
    padding: 12px 16px;
    cursor: pointer;
    border-top: 1px solid #f3f4f6;
    font-size: 14px;
    line-height: 1.5;
}

.pac-item:first-child {
    border-top: none;
}

.pac-item:hover {
    background-color: rgba(15, 105, 243, 0.05);
}

.pac-item-selected,
.pac-item-selected:hover {
    background-color: rgba(15, 105, 243, 0.1);
}

.pac-icon {
    margin-right: 12px;
    margin-top: 2px;
}

.pac-item-query {
    color: #1a1a2e;
    font-weight: 500;
}

.pac-matched {
    font-weight: 600;
    color: #0F69F3;
}

/* Mobile Responsive */
@media (max-width: 767px) {
    .checkout-card .card-header,
    .checkout-card .card-body,
    .order-summary-card .card-header,
    .order-summary-card .card-body,
    .whats-included-card .card-body {
        padding: 16px;
    }

    .pac-container {
        width: 100% !important;
        left: 0 !important;
    }
}
</style>
@endpush

@push('scripts')
<!-- Google Maps JavaScript API with Places Library -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.places_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>

<script>
// Country code mapping for form population
const countryMapping = {
    'Germany': 'DE',
    'Austria': 'AT',
    'Switzerland': 'CH',
    'France': 'FR',
    'Netherlands': 'NL',
    'Belgium': 'BE',
    'Italy': 'IT',
    'Spain': 'ES',
    'Poland': 'PL',
    'United Kingdom': 'GB',
    'United States': 'US'
};

// Global autocomplete variables
let billingAutocomplete;
let shippingAutocomplete;

// Initialize autocomplete when Google Maps API is loaded
function initAutocomplete() {
    // Options for autocomplete - restrict to European countries
    const options = {
        types: ['address'],
        componentRestrictions: { country: ['de', 'at', 'ch', 'fr', 'nl', 'be', 'it', 'es', 'pl', 'gb', 'us'] }
    };

    // Initialize billing address autocomplete
    const billingAddressInput = document.getElementById('address');
    if (billingAddressInput) {
        billingAutocomplete = new google.maps.places.Autocomplete(billingAddressInput, options);
        billingAutocomplete.addListener('place_changed', function() {
            fillInAddress('billing');
        });
    }

    // Initialize shipping address autocomplete
    const shippingAddressInput = document.getElementById('shipping_address');
    if (shippingAddressInput) {
        shippingAutocomplete = new google.maps.places.Autocomplete(shippingAddressInput, options);
        shippingAutocomplete.addListener('place_changed', function() {
            fillInAddress('shipping');
        });
    }
}

// Fill in address fields based on Google Places selection
function fillInAddress(type) {
    // Determine which autocomplete instance to use
    const autocomplete = type === 'billing' ? billingAutocomplete : shippingAutocomplete;
    const place = autocomplete.getPlace();

    // Check if place has address components
    if (!place.address_components) {
        console.log('No address components found');
        return;
    }

    // Initialize variables for address components
    let streetNumber = '';
    let route = '';
    let city = '';
    let postalCode = '';
    let country = '';
    let countryCode = '';

    // Extract address components
    for (const component of place.address_components) {
        const componentType = component.types[0];

        switch (componentType) {
            case 'street_number':
                streetNumber = component.long_name;
                break;
            case 'route':
                route = component.long_name;
                break;
            case 'locality':
                city = component.long_name;
                break;
            case 'postal_code':
                postalCode = component.long_name;
                break;
            case 'country':
                country = component.long_name;
                countryCode = component.short_name;
                break;
            // Fallback for some countries that use different locality types
            case 'administrative_area_level_1':
                if (!city) {
                    city = component.long_name;
                }
                break;
            case 'postal_town':
                if (!city) {
                    city = component.long_name;
                }
                break;
        }
    }

    // Construct full street address
    const fullAddress = `${streetNumber} ${route}`.trim();

    // Determine field prefix based on type
    const prefix = type === 'billing' ? '' : 'shipping_';

    // Fill in the address field with street address only
    const addressField = document.getElementById(prefix + 'address');
    if (addressField && fullAddress) {
        addressField.value = fullAddress;
    }

    // Fill in city
    const cityField = document.getElementById(prefix + 'city');
    if (cityField && city) {
        cityField.value = city;
    }

    // Fill in postal code
    const postalCodeField = document.getElementById(prefix + 'postal_code');
    if (postalCodeField && postalCode) {
        postalCodeField.value = postalCode;
    }

    // Fill in country
    const countryField = document.getElementById(prefix + 'country');
    if (countryField && countryCode) {
        // Try to set country using the short code (e.g., 'DE', 'AT', etc.)
        countryField.value = countryCode.toUpperCase();

        // If that doesn't work, try the mapping
        if (!countryField.value && country && countryMapping[country]) {
            countryField.value = countryMapping[country];
        }
    }

    // Trigger change events to update any dependent fields
    if (cityField) cityField.dispatchEvent(new Event('change'));
    if (postalCodeField) postalCodeField.dispatchEvent(new Event('change'));
    if (countryField) countryField.dispatchEvent(new Event('change'));
}

function toggleShippingAddress() {
    const checkbox = document.getElementById('sameAsBilling');
    const shippingFields = document.getElementById('shippingAddressFields');
    shippingFields.style.display = checkbox.checked ? 'none' : 'block';
}
</script>
@endpush
