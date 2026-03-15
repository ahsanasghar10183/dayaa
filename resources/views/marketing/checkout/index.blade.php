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

        <form action="{{ route('marketing.checkout.process') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Billing Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('marketing.checkout.billing_information') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="customer_name" class="form-label">{{ __('marketing.checkout.full_name') }} *</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">{{ __('marketing.checkout.email_address') }} *</label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                                    @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">{{ __('marketing.checkout.phone_number') }}</label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                                    @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="billing_address" class="form-label">{{ __('marketing.checkout.billing_address') }} *</label>
                                    <textarea class="form-control @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" rows="3" required>{{ old('billing_address') }}</textarea>
                                    @error('billing_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">{{ __('marketing.checkout.address_note') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sameAsBilling" checked onchange="toggleShippingAddress()">
                                <label class="form-check-label" for="sameAsBilling">
                                    <h4 class="mb-0 d-inline">{{ __('marketing.checkout.shipping_address_same') }}</h4>
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="shippingAddressFields" style="display: none;">
                            <div class="col-12">
                                <label for="shipping_address" class="form-label">{{ __('marketing.checkout.shipping_address') }}</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('marketing.checkout.address_note') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('marketing.checkout.payment_method') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" {{ old('payment_method', 'stripe') == 'stripe' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stripe">
                                    <strong>{{ __('marketing.checkout.payment_credit_card') }}</strong>
                                    <p class="mb-0 small text-muted">{{ __('marketing.checkout.payment_credit_card_desc') }}</p>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                <label class="form-check-label" for="paypal">
                                    <strong>{{ __('marketing.checkout.payment_paypal') }}</strong>
                                    <p class="mb-0 small text-muted">{{ __('marketing.checkout.payment_paypal_desc') }}</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_transfer">
                                    <strong>{{ __('marketing.checkout.payment_bank_transfer') }}</strong>
                                    <p class="mb-0 small text-muted">{{ __('marketing.checkout.payment_bank_transfer_desc') }}</p>
                                </label>
                            </div>
                            @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('marketing.checkout.order_summary') }}</h4>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">{{ __('marketing.checkout.items') }} ({{ $cartItems->count() }})</h6>
                            @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <small>{{ $item->product->name }}</small>
                                    <small class="text-muted"> x{{ $item->quantity }}</small>
                                </div>
                                <small>{{ $item->formatted_subtotal }}</small>
                            </div>
                            @endforeach

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('marketing.checkout.subtotal') }}:</span>
                                <strong>€{{ number_format($subtotal, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('marketing.checkout.tax') }}:</span>
                                <strong>€{{ number_format($tax, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>{{ __('marketing.checkout.shipping') }}:</span>
                                <strong>
                                    @if($shipping == 0)
                                    <span class="text-success">{{ __('marketing.checkout.free') }}</span>
                                    @else
                                    €{{ number_format($shipping, 2) }}
                                    @endif
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <h5>{{ __('marketing.checkout.total') }}:</h5>
                                <h5 class="text-primary">€{{ number_format($total, 2) }}</h5>
                            </div>

                            <button type="submit" class="pp-theme-btn w-100 text-center">
                                {{ __('marketing.checkout.place_order') }} <i class="fa-solid fa-lock"></i>
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fa-solid fa-shield-alt"></i> {{ __('marketing.checkout.secure_payment') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">{{ __('marketing.checkout.whats_included') }}</h6>
                            <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.warranty') }}</p>
                            <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.free_setup') }}</p>
                            <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.returns') }}</p>
                            <p class="small mb-0"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.checkout.support') }}</p>
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

@push('scripts')
<script>
function toggleShippingAddress() {
    const checkbox = document.getElementById('sameAsBilling');
    const shippingFields = document.getElementById('shippingAddressFields');
    shippingFields.style.display = checkbox.checked ? 'none' : 'block';
}
</script>
@endpush
