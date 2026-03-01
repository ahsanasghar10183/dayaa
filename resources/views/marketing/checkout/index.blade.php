@extends('marketing.layouts.master')

@section('title', 'Checkout - Dayaa Shop')
@section('meta_description', 'Complete your purchase of Dayaa donation devices and equipment.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Checkout</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Complete your order
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
                            <h4 class="mb-0">Billing Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="customer_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                                    @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                                    @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="billing_address" class="form-label">Billing Address *</label>
                                    <textarea class="form-control @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" rows="3" required>{{ old('billing_address') }}</textarea>
                                    @error('billing_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Include street, city, postal code, and country</small>
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
                                    <h4 class="mb-0 d-inline">Shipping address same as billing</h4>
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="shippingAddressFields" style="display: none;">
                            <div class="col-12">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Include street, city, postal code, and country</small>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Payment Method</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" {{ old('payment_method', 'stripe') == 'stripe' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stripe">
                                    <strong>Credit Card (Stripe)</strong>
                                    <p class="mb-0 small text-muted">Pay securely with your credit or debit card</p>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                <label class="form-check-label" for="paypal">
                                    <strong>PayPal</strong>
                                    <p class="mb-0 small text-muted">Pay with your PayPal account</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_transfer">
                                    <strong>Bank Transfer</strong>
                                    <p class="mb-0 small text-muted">Transfer payment directly to our bank account</p>
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
                            <h4 class="mb-0">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">Items ({{ $cartItems->count() }})</h6>
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
                                <span>Subtotal:</span>
                                <strong>€{{ number_format($subtotal, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (19%):</span>
                                <strong>€{{ number_format($tax, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping:</span>
                                <strong>
                                    @if($shipping == 0)
                                    <span class="text-success">FREE</span>
                                    @else
                                    €{{ number_format($shipping, 2) }}
                                    @endif
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <h5>Total:</h5>
                                <h5 class="text-primary">€{{ number_format($total, 2) }}</h5>
                            </div>

                            <button type="submit" class="pp-theme-btn w-100 text-center">
                                Place Order <i class="fa-solid fa-lock"></i>
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fa-solid fa-shield-alt"></i> Secure SSL Encrypted Payment
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">What's Included</h6>
                            <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> 2-Year Warranty</p>
                            <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> Free Setup Support</p>
                            <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> 30-Day Returns</p>
                            <p class="small mb-0"><i class="fa-solid fa-check text-success"></i> 24/7 Customer Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

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
