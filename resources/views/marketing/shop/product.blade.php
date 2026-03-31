@extends('marketing.layouts.master')

@section('title', $product->name . ' - Dayaa Shop')
@section('meta_description', Str::limit($product->description, 150))

@section('content')

<!-- Breadcrumb -->
<section class="section-padding" style="padding: 40px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('marketing.home') }}">{{ __('marketing.shop.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketing.shop.index') }}">{{ __('marketing.shop.title') }}</a></li>
                @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('marketing.shop.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Product Detail -->
<section class="section-padding fix" style="padding-top: 0;">
    <div class="container">
        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-images">
                    <div class="main-image mb-3">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded" id="mainImage">
                    </div>
                    @if($product->images->count() > 1)
                    <div class="row g-2">
                        @foreach($product->images as $image)
                        <div class="col-3">
                            <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" class="img-fluid rounded thumbnail-image" style="cursor: pointer;" onclick="changeMainImage('{{ $image->url }}')">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="mb-3">{{ $product->name }}</h1>

                    @if($product->category)
                    <p class="text-muted mb-3">
                        {{ __('marketing.shop.category') }}: <a href="{{ route('marketing.shop.category', $product->category->slug) }}">{{ $product->category->name }}</a>
                    </p>
                    @endif

                    <div class="mb-4">
                        @php
                            $taxRate = 0.19; // 19% VAT in Germany
                            $priceExclTax = $product->price;
                            $taxAmount = $priceExclTax * $taxRate;
                            $priceInclTax = $priceExclTax + $taxAmount;
                        @endphp

                        <h2 class="text-primary mb-2">€{{ number_format($priceInclTax, 2) }}</h2>
                        <p class="text-muted small mb-0">
                            inkl. MwSt. €{{ number_format($taxAmount, 2) }} (19%)
                        </p>
                        <p class="text-muted small">
                            zzgl. <a href="#" class="text-decoration-none">Versandkosten</a>
                        </p>

                        @if($product->compare_price)
                        @php
                            $comparePriceInclTax = $product->compare_price + ($product->compare_price * $taxRate);
                        @endphp
                        <p class="text-muted mt-2">
                            <del>€{{ number_format($comparePriceInclTax, 2) }}</del>
                            <span class="badge bg-danger ms-2">{{ __('marketing.shop.save_percentage', ['percentage' => $product->discount_percentage]) }}</span>
                        </p>
                        @endif
                    </div>

                    @if($product->sku)
                    <p class="text-muted small mb-3">{{ __('marketing.shop.sku') }}: {{ $product->sku }}</p>
                    @endif

                    <div class="availability mb-4">
                        @if($product->is_in_stock)
                        <span class="badge bg-success">{{ __('marketing.shop.in_stock') }} ({{ $product->quantity }} {{ __('marketing.shop.available') }})</span>
                        @else
                        <span class="badge bg-danger">{{ __('marketing.shop.out_of_stock') }}</span>
                        @endif
                    </div>

                    <div class="description mb-4">
                        <h4>{{ __('marketing.shop.description') }}</h4>
                        <p>{{ $product->description }}</p>
                    </div>

                    @if($product->specifications)
                    <div class="specifications mb-4">
                        <h4>{{ __('marketing.shop.specifications') }}</h4>
                        <ul class="list-unstyled">
                            @foreach($product->specifications as $key => $value)
                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($product->is_in_stock)
                    <div class="row g-3 mb-4">
                        <div class="col-auto">
                            <label for="quantity" class="form-label">{{ __('marketing.shop.quantity') }}</label>
                            <input type="number" class="form-control" id="product-quantity" value="1" min="1" max="{{ $product->quantity }}" style="width: 100px;">
                        </div>
                        <div class="col-auto d-flex align-items-end gap-2">
                            <button type="button" onclick="addToCart({{ $product->id }})" class="pp-theme-btn">
                                <i class="fa-solid fa-shopping-cart"></i> {{ __('marketing.shop.add_to_cart') }}
                            </button>
                            <button type="button" onclick="buyNow({{ $product->id }})" class="pp-theme-btn">
                                <i class="fa-solid fa-bolt"></i> {{ __('marketing.shop.buy_now') }}
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        {{ __('marketing.shop.out_of_stock_message') }}
                    </div>
                    @endif

                    <div class="product-meta">
                        <p class="mb-2"><i class="fa-solid fa-truck"></i> {{ __('marketing.shop.free_shipping') }}</p>
                        <p class="mb-2"><i class="fa-solid fa-shield-alt"></i> {{ __('marketing.shop.warranty') }}</p>
                        <p class="mb-0"><i class="fa-solid fa-headset"></i> {{ __('marketing.shop.customer_support') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center mb-5">
            <h2>{{ __('marketing.shop.related_products') }}</h2>
        </div>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="pp-offer-box-item h-100">
                    <div class="product-image mb-3" style="height: 150px; overflow: hidden;">
                        <a href="{{ route('marketing.shop.product', $relatedProduct->slug) }}">
                            <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                    </div>
                    <div class="pp-offer-content">
                        <h5>
                            <a href="{{ route('marketing.shop.product', $relatedProduct->slug) }}" class="text-decoration-none">
                                {{ $relatedProduct->name }}
                            </a>
                        </h5>
                        <p class="mb-3">{{ $relatedProduct->formatted_price }}</p>
                        <a href="{{ route('marketing.shop.product', $relatedProduct->slug) }}" class="pp-theme-btn w-100 text-center">
                            {{ __('marketing.shop.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Shop Trust Section -->
<x-shop-trust-section />

@endsection

@push('styles')
<style>
.thumbnail-image {
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.thumbnail-image:hover,
.thumbnail-image.active {
    border-color: #0F69F3;
}

.product-meta i {
    color: #0F69F3;
    margin-right: 8px;
}

.breadcrumb {
    background-color: transparent;
}
</style>
@endpush

@push('scripts')
<script>
function changeMainImage(url) {
    document.getElementById('mainImage').src = url;

    // Update active thumbnail
    document.querySelectorAll('.thumbnail-image').forEach(img => {
        img.classList.remove('active');
        if (img.src === url) {
            img.classList.add('active');
        }
    });
}

function addToCart(productId) {
    const quantity = document.getElementById('product-quantity').value;

    // Use fetch to add to cart without page reload
    fetch('{{ route("marketing.cart.add", ":productId") }}'.replace(':productId', productId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: parseInt(quantity) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count if function exists
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
            // Open cart sidebar
            if (typeof openCartSidebar === 'function') {
                openCartSidebar();
            }
        } else {
            alert(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback to form submission if AJAX fails
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("marketing.cart.add", ":productId") }}'.replace(':productId', productId);

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = quantity;

        form.appendChild(csrfToken);
        form.appendChild(quantityInput);
        document.body.appendChild(form);
        form.submit();
    });
}

function buyNow(productId) {
    const quantity = document.getElementById('product-quantity').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("marketing.cart.buy-now", ":productId") }}'.replace(':productId', productId);

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';

    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = quantity;

    form.appendChild(csrfToken);
    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
