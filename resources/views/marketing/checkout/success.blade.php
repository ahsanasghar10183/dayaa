@extends('marketing.layouts.master')

@section('title', __('marketing.checkout.success_title') . ' - Dayaa')
@section('meta_description', __('marketing.checkout.success_meta'))

@section('content')

<!-- Success Section -->
<section class="section-padding fix">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Header -->
                <div class="success-header text-center mb-5">
                    <div class="success-icon-wrapper mb-4">
                        <div class="success-icon-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                    <h1 class="success-title mb-3">{{ __('marketing.checkout.success_heading') }}</h1>
                    <p class="success-subtitle">{{ __('marketing.checkout.success_message') }}</p>
                    <div class="success-order-number">
                        <span class="label">{{ __('marketing.checkout.order_number') }}:</span>
                        <span class="number">{{ $order->order_number }}</span>
                    </div>
                </div>

                <!-- Order Details Card -->
                <div class="success-card mb-4">
                    <div class="success-card-header">
                        <h3>{{ __('marketing.checkout.order_details') }}</h3>
                    </div>
                    <div class="success-card-body">
                        <div class="order-info-grid">
                            <div class="order-info-item">
                                <span class="info-label">{{ __('marketing.checkout.order_date') }}</span>
                                <span class="info-value">{{ $order->created_at->format('F d, Y H:i') }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="info-label">{{ __('marketing.checkout.customer_name') }}</span>
                                <span class="info-value">{{ $order->customer_name }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="info-label">{{ __('marketing.checkout.email') }}</span>
                                <span class="info-value">{{ $order->customer_email }}</span>
                            </div>
                            <div class="order-info-item full-width">
                                <span class="info-label">{{ __('marketing.checkout.billing_address') }}</span>
                                <span class="info-value">{{ $order->billing_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Card -->
                <div class="success-card mb-4">
                    <div class="success-card-header">
                        <h3>{{ __('marketing.checkout.order_items') }}</h3>
                    </div>
                    <div class="success-card-body">
                        <!-- Order Items -->
                        @foreach($order->items as $item)
                        <div class="order-item-row">
                            <div class="item-details">
                                <div class="item-name">{{ $item->product_name }}</div>
                                <div class="item-meta">{{ __('marketing.checkout.quantity') }}: {{ $item->quantity }} × {{ $item->formatted_unit_price }}</div>
                            </div>
                            <div class="item-total">{{ $item->formatted_total_price }}</div>
                        </div>
                        @endforeach

                        <!-- Order Totals -->
                        <div class="order-totals-section">
                            <div class="order-total-row">
                                <span class="total-label">{{ __('marketing.checkout.subtotal') }}:</span>
                                <span class="total-value">€{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="order-total-row">
                                <span class="total-label">{{ __('marketing.checkout.tax') }}:</span>
                                <span class="total-value">€{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="order-total-row">
                                <span class="total-label">{{ __('marketing.checkout.shipping') }}:</span>
                                <span class="total-value">
                                    @if($order->shipping_amount == 0)
                                    <span class="text-success fw-semibold">{{ __('marketing.checkout.free') }}</span>
                                    @else
                                    €{{ number_format($order->shipping_amount, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="order-total-row grand-total">
                                <span class="total-label">{{ __('marketing.checkout.total') }}:</span>
                                <span class="total-value">{{ $order->formatted_total }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- What's Next Card -->
                <div class="whats-next-card mb-4">
                    <div class="whats-next-icon">
                        <i class="fa-solid fa-info-circle"></i>
                    </div>
                    <div class="whats-next-content">
                        <h4>{{ __('marketing.checkout.whats_next') }}</h4>
                        <p>{{ __('marketing.checkout.confirmation_email', ['email' => $order->customer_email]) }}</p>
                        @if($order->payment_method == 'bank_transfer')
                        <p class="mb-0 mt-3">
                            <i class="fa-solid fa-exclamation-triangle text-warning me-2"></i>
                            {{ __('marketing.checkout.bank_transfer_notice') }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="success-actions text-center">
                    <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn">
                        {{ __('marketing.checkout.continue_shopping') }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('marketing.contact') }}" class="pp-theme-btn pp-style-2 ms-2">
                        {{ __('marketing.checkout.contact_support') }}
                    </a>
                </div>

                <!-- Help Section -->
                <div class="help-section text-center mt-5">
                    <p class="help-text">
                        <i class="fa-solid fa-headset me-2"></i>
                        {{ __('marketing.checkout.need_help', ['email' => 'support@dayaatech.de']) }}
                    </p>
                    <p class="help-text mb-0">
                        <i class="fa-solid fa-phone me-2"></i>
                        {{ __('marketing.checkout.or_call', ['phone' => '+49 123 456 7890']) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop Trust Section -->
<x-shop-trust-section />

@endsection

@push('styles')
<style>
/* Success Header */
.success-header {
    padding: 40px 20px;
}

.success-icon-wrapper {
    display: flex;
    justify-content: center;
}

.success-icon-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
    animation: successPulse 2s ease-in-out infinite;
}

.success-icon-circle i {
    font-size: 60px;
    color: white;
}

@keyframes successPulse {
    0%, 100% {
        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 15px 50px rgba(16, 185, 129, 0.4);
        transform: scale(1.05);
    }
}

.success-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a2e;
}

.success-subtitle {
    font-size: 18px;
    color: #4b5563;
    margin-bottom: 24px;
}

.success-order-number {
    display: inline-block;
    background: linear-gradient(135deg, rgba(15, 105, 243, 0.1) 0%, rgba(23, 10, 181, 0.1) 100%);
    padding: 12px 24px;
    border-radius: 10px;
    border: 1px solid rgba(15, 105, 243, 0.2);
}

.success-order-number .label {
    font-size: 14px;
    color: #6b7280;
    margin-right: 8px;
}

.success-order-number .number {
    font-size: 16px;
    font-weight: 700;
    color: #0F69F3;
}

/* Success Cards */
.success-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.success-card-header {
    background: linear-gradient(135deg, rgba(15, 105, 243, 0.03) 0%, rgba(23, 10, 181, 0.03) 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    padding: 20px 24px;
}

.success-card-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}

.success-card-body {
    padding: 24px;
}

/* Order Info Grid */
.order-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.order-info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 15px;
    font-weight: 500;
    color: #1a1a2e;
    line-height: 1.5;
}

/* Order Items */
.order-item-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.order-item-row:last-of-type {
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    margin-bottom: 16px;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 4px;
}

.item-meta {
    font-size: 14px;
    color: #6b7280;
}

.item-total {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
}

/* Order Totals */
.order-totals-section {
    padding-top: 16px;
}

.order-total-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 15px;
}

.order-total-row .total-label {
    color: #6b7280;
    font-weight: 500;
}

.order-total-row .total-value {
    color: #1a1a2e;
    font-weight: 600;
}

.order-total-row.grand-total {
    border-top: 2px solid rgba(0, 0, 0, 0.1);
    padding-top: 16px;
    margin-top: 8px;
    font-size: 18px;
}

.order-total-row.grand-total .total-label,
.order-total-row.grand-total .total-value {
    font-weight: 700;
    color: #1a1a2e;
}

/* What's Next Card */
.whats-next-card {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.whats-next-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.whats-next-content {
    flex: 1;
}

.whats-next-content h4 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 12px;
}

.whats-next-content p {
    font-size: 15px;
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Success Actions */
.success-actions {
    margin-top: 40px;
}

.success-actions .pp-theme-btn {
    padding: 14px 32px;
    font-size: 16px;
    font-weight: 600;
}

/* Help Section */
.help-section {
    padding: 32px 0;
    border-top: 1px solid rgba(0, 0, 0, 0.06);
}

.help-text {
    font-size: 15px;
    color: #6b7280;
    margin-bottom: 8px;
}

.help-text i {
    color: #0F69F3;
}

/* Mobile Responsive */
@media (max-width: 767px) {
    .success-icon-circle {
        width: 100px;
        height: 100px;
    }

    .success-icon-circle i {
        font-size: 50px;
    }

    .success-title {
        font-size: 26px;
    }

    .success-subtitle {
        font-size: 16px;
    }

    .order-info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .success-card-header,
    .success-card-body {
        padding: 16px;
    }

    .success-order-number {
        padding: 10px 20px;
    }

    .whats-next-card {
        flex-direction: column;
        padding: 20px;
    }

    .whats-next-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }

    .success-actions .pp-theme-btn {
        display: block;
        width: 100%;
        margin-bottom: 12px;
    }

    .success-actions .pp-theme-btn.ms-2 {
        margin-left: 0 !important;
    }
}
</style>
@endpush
