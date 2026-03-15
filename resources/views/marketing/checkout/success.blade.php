@extends('marketing.layouts.master')

@section('title', __('marketing.checkout.success_title') . ' - Dayaa')
@section('meta_description', __('marketing.checkout.success_meta'))

@section('content')

<!-- Success Section -->
<section class="section-padding fix">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="success-icon mb-4">
                    <i class="fa-solid fa-check-circle" style="font-size: 100px; color: #28a745;"></i>
                </div>
                <h1 class="mb-3">{{ __('marketing.checkout.success_heading') }}</h1>
                <p class="lead mb-4">{{ __('marketing.checkout.success_message') }}</p>

                <div class="card mb-4 text-start">
                    <div class="card-header">
                        <h4 class="mb-0">{{ __('marketing.checkout.order_details') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>{{ __('marketing.checkout.order_number') }}:</strong><br>
                                {{ $order->order_number }}
                            </div>
                            <div class="col-sm-6">
                                <strong>{{ __('marketing.checkout.order_date') }}:</strong><br>
                                {{ $order->created_at->format('F d, Y H:i') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>{{ __('marketing.checkout.customer_name') }}:</strong><br>
                                {{ $order->customer_name }}
                            </div>
                            <div class="col-sm-6">
                                <strong>{{ __('marketing.checkout.email') }}:</strong><br>
                                {{ $order->customer_email }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>{{ __('marketing.checkout.billing_address') }}:</strong><br>
                                {{ $order->billing_address }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 text-start">
                    <div class="card-header">
                        <h4 class="mb-0">{{ __('marketing.checkout.order_items') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('marketing.checkout.product') }}</th>
                                        <th>{{ __('marketing.checkout.quantity') }}</th>
                                        <th>{{ __('marketing.checkout.price') }}</th>
                                        <th>{{ __('marketing.checkout.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->formatted_unit_price }}</td>
                                        <td>{{ $item->formatted_total_price }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>{{ __('marketing.checkout.subtotal') }}:</strong></td>
                                        <td>€{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>{{ __('marketing.checkout.tax') }}:</strong></td>
                                        <td>€{{ number_format($order->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>{{ __('marketing.checkout.shipping') }}:</strong></td>
                                        <td>
                                            @if($order->shipping_amount == 0)
                                            <span class="text-success">{{ __('marketing.checkout.free') }}</span>
                                            @else
                                            €{{ number_format($order->shipping_amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end"><h5 class="mb-0">{{ __('marketing.checkout.total') }}:</h5></td>
                                        <td><h5 class="mb-0">{{ $order->formatted_total }}</h5></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle"></i>
                    <strong>{{ __('marketing.checkout.whats_next') }}</strong><br>
                    {{ __('marketing.checkout.confirmation_email', ['email' => $order->customer_email]) }}
                    @if($order->payment_method == 'bank_transfer')
                    <br><br>
                    {{ __('marketing.checkout.bank_transfer_notice') }}
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn me-2">
                        {{ __('marketing.checkout.continue_shopping') }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('marketing.contact') }}" class="pp-theme-btn pp-style-2">
                        {{ __('marketing.checkout.contact_support') }}
                    </a>
                </div>

                <div class="mt-5 text-muted">
                    <p class="mb-2"><i class="fa-solid fa-headset"></i> {{ __('marketing.checkout.need_help', ['email' => 'support@dayaatech.de']) }}</p>
                    <p class="mb-0"><i class="fa-solid fa-phone"></i> {{ __('marketing.checkout.or_call', ['phone' => '+49 123 456 7890']) }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop Trust Section -->
<x-shop-trust-section />

@endsection
