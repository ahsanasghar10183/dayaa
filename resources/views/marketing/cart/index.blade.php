@extends('marketing.layouts.master')

@section('title', __('marketing.cart.title') . ' - Dayaa')
@section('meta_description', __('marketing.cart.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.cart.title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.cart.page_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="section-padding fix">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('marketing.cart.product') }}</th>
                                <th>{{ __('marketing.cart.price') }}</th>
                                <th>{{ __('marketing.cart.quantity') }}</th>
                                <th>{{ __('marketing.cart.total') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width: 80px; height: 80px; object-fit: cover;" class="me-3">
                                        <div>
                                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                                            @if($item->variation_name)
                                            <p class="mb-1 text-primary small"><strong>{{ $item->variation_name }}</strong></p>
                                            @endif
                                            <p class="mb-0 text-muted small">SKU: {{ $item->variation ? $item->variation->sku ?? $item->product->sku : $item->product->sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($item->variation)
                                        €{{ number_format($item->variation->effective_price, 2) }}
                                    @else
                                        {{ $item->product->formatted_price }}
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('marketing.cart.update', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group" style="width: 130px;">
                                            <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="1" max="{{ $item->variation ? $item->variation->quantity : $item->product->quantity }}">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <i class="fa-solid fa-sync"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ $item->formatted_subtotal }}</strong>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('marketing.cart.remove', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('marketing.cart.remove_confirm') }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn pp-style-2">
                        <i class="fa-solid fa-arrow-left"></i> {{ __('marketing.cart.continue_shopping') }}
                    </a>
                    <form action="{{ route('marketing.cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('{{ __('marketing.cart.clear_confirm') }}')">
                            <i class="fa-solid fa-trash"></i> {{ __('marketing.cart.clear_cart') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">{{ __('marketing.cart.order_summary') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('marketing.cart.subtotal') }}:</span>
                            <strong>€{{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('marketing.cart.tax_percentage') }}:</span>
                            <strong>€{{ number_format($tax, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('marketing.cart.shipping') }}:</span>
                            <strong>
                                @if($shipping == 0)
                                <span class="text-success">{{ __('marketing.cart.free') }}</span>
                                @else
                                €{{ number_format($shipping, 2) }}
                                @endif
                            </strong>
                        </div>
                        @if($subtotal < 100 && $subtotal > 0)
                        <div class="alert alert-info small">
                            {{ __('marketing.cart.free_shipping_threshold', ['amount' => number_format(100 - $subtotal, 2)]) }}
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>{{ __('marketing.cart.grand_total') }}:</h5>
                            <h5 class="text-primary">€{{ number_format($total, 2) }}</h5>
                        </div>
                        <a href="{{ route('marketing.checkout.index') }}" class="pp-theme-btn w-100 text-center">
                            {{ __('marketing.cart.proceed_checkout') }} <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fa-solid fa-shield-alt text-primary"></i> {{ __('marketing.cart.secure_checkout') }}</h6>
                        <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.cart.ssl_encrypted') }}</p>
                        <p class="small mb-2"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.cart.warranty_included') }}</p>
                        <p class="small mb-0"><i class="fa-solid fa-check text-success"></i> {{ __('marketing.cart.returns') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="empty-cart py-5">
                    <i class="fa-solid fa-shopping-cart" style="font-size: 80px; color: #ddd;"></i>
                    <h3 class="mt-4 mb-3">{{ __('marketing.cart.empty_cart') }}</h3>
                    <p class="text-muted mb-4">{{ __('marketing.cart.empty_cart_text') }}</p>
                    <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn">
                        {{ __('marketing.cart.browse_products') }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Shop Trust Section -->
<x-shop-trust-section />

@endsection
