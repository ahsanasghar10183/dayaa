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
                    <div class="row g-2" id="image-thumbnails">
                        @if($product->images->count() > 1)
                            @foreach($product->images as $image)
                            <div class="col-3">
                                <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" class="img-fluid rounded thumbnail-image" style="cursor: pointer;" onclick="changeMainImage('{{ $image->url }}')">
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="mb-3">{{ $product->localized_name }}</h1>

                    <div class="mb-4">
                        @php
                            $taxRate = 0.19; // 19% VAT in Germany
                            $priceExclTax = $product->price;
                            $taxAmount = $priceExclTax * $taxRate;
                            $priceInclTax = $priceExclTax + $taxAmount;
                        @endphp

                        <h2 class="text-primary mb-1">€{{ number_format($priceExclTax, 2) }}</h2>
                        <p class="text-muted small mb-2">
                            zzgl. MwSt. €{{ number_format($taxAmount, 2) }} (19%)
                        </p>
                        <p class="small mb-2">
                            <strong>€{{ number_format($priceInclTax, 2) }} inkl. MwSt.</strong>
                        </p>
                        <p class="text-muted small">
                            zzgl. <a href="#" class="text-decoration-none">Versandkosten</a>
                        </p>

                        @if($product->compare_price)
                        @php
                            $comparePriceExclTax = $product->compare_price;
                            $comparePriceInclTax = $comparePriceExclTax + ($comparePriceExclTax * $taxRate);
                        @endphp
                        <p class="text-muted mt-2">
                            <del>€{{ number_format($comparePriceExclTax, 2) }}</del>
                            <span class="badge bg-danger ms-2">{{ __('marketing.shop.save_percentage', ['percentage' => $product->discount_percentage]) }}</span>
                        </p>
                        @endif
                    </div>

                    <!-- Variation Selector with Thumbnails (if product has variations) -->
                    @if($product->isVariable() && $product->inStockVariations->count() > 0)
                    <div class="variation-selector mb-4">
                        <label class="form-label fw-bold mb-3">{{ __('marketing.shop.select_variation') }}</label>
                        <div class="row g-3" id="variation-thumbnails">
                            @foreach($product->inStockVariations as $index => $variation)
                            <div class="col-auto">
                                <div class="variation-thumbnail {{ $index === 0 ? 'selected' : '' }}"
                                     data-variation-id="{{ $variation->id }}"
                                     data-price="{{ $variation->effective_price }}"
                                     data-compare-price="{{ $variation->effective_compare_price ?? '' }}"
                                     data-stock="{{ $variation->quantity }}"
                                     data-name="{{ $variation->name }}"
                                     data-image="{{ $variation->image_url }}"
                                     data-images="{{ json_encode($variation->image_gallery) }}"
                                     onclick="selectVariation(this)"
                                     style="cursor: pointer; position: relative;">
                                    <div style="width: 100px; height: 100px; border: 3px solid {{ $index === 0 ? '#0F69F3' : '#dee2e6' }}; border-radius: 12px; overflow: hidden; transition: all 0.3s ease;">
                                        <img src="{{ $variation->image_url }}" alt="{{ $variation->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="fw-semibold d-block text-truncate" style="max-width: 100px;">{{ $variation->name }}</small>
                                        @if($variation->price)
                                        <small class="text-muted">{{ $variation->formatted_price }}</small>
                                        @endif
                                    </div>
                                    <!-- Selected checkmark -->
                                    <div class="variation-checkmark" style="position: absolute; top: 5px; right: 5px; width: 24px; height: 24px; background: #0F69F3; border-radius: 50%; display: {{ $index === 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center;">
                                        <svg width="14" height="14" fill="white" viewBox="0 0 16 16">
                                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- Hidden input to store selected variation ID -->
                        <input type="hidden" id="selected-variation-id" value="{{ $product->inStockVariations->first()->id ?? '' }}">
                    </div>
                    @endif

                    <div class="availability mb-4">
                        @if($product->is_in_stock)
                        <span class="badge bg-success" id="stock-badge">
                            {{ __('marketing.shop.in_stock') }}
                            @if(!$product->isVariable())
                                ({{ $product->quantity }} {{ __('marketing.shop.available') }})
                            @endif
                        </span>
                        @else
                        <span class="badge bg-danger">{{ __('marketing.shop.out_of_stock') }}</span>
                        @endif
                    </div>

                    @if($product->is_in_stock)
                    <div class="row g-3 mb-4">
                        <div class="col-auto">
                            <label for="quantity" class="form-label">{{ __('marketing.shop.quantity') }}</label>
                            <input type="number" class="form-control" id="product-quantity" value="1" min="1" max="{{ $product->isVariable() ? 999 : $product->quantity }}" style="width: 100px;">
                        </div>
                        <div class="col-auto d-flex align-items-end gap-2">
                            <button type="button" onclick="addToCart({{ $product->id }})" class="pp-theme-btn" id="add-to-cart-btn">
                                <i class="fa-solid fa-shopping-cart"></i> {{ __('marketing.shop.add_to_cart') }}
                            </button>
                            <button type="button" onclick="buyNow({{ $product->id }})" class="pp-theme-btn-bordered" id="buy-now-btn" style="background: white; border: 2px solid #0F69F3; color: #0F69F3; border-radius: 100px; padding: 17px 24px; font-weight: 600; font-size: 16px; line-height: 1; transition: all 0.3s ease;">
                                <i class="fa-solid fa-bolt"></i> {{ __('marketing.shop.buy_now') }}
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        {{ __('marketing.shop.out_of_stock_message') }}
                    </div>
                    @endif

                    <div class="description mb-4">
                        <h4>{{ __('marketing.shop.description') }}</h4>
                        <p>{{ $product->localized_description }}</p>
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
                <div class="pp-offer-box-item d-flex flex-column">
                    <div class="product-image mb-3" style="height: 300px; overflow: hidden;">
                        <a href="{{ route('marketing.shop.product', $relatedProduct->slug) }}">
                            <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 24px;">
                        </a>
                    </div>
                    <div class="pp-offer-content flex-grow-1 d-flex flex-column">
                        <h4>
                            <a href="{{ route('marketing.shop.product', $relatedProduct->slug) }}" class="text-decoration-none">
                                {{ $relatedProduct->name }}
                            </a>
                        </h4>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 mb-0 text-primary">{{ $relatedProduct->formatted_price }}</span>
                            @if($relatedProduct->is_in_stock)
                                <span class="badge bg-success">{{ __('marketing.shop.in_stock') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('marketing.shop.out_of_stock') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('marketing.shop.product', $relatedProduct->slug) }}" class="pp-theme-btn mt-3 w-100 text-center">
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

.pp-theme-btn-bordered:hover {
    background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%) !important;
    color: white !important;
    border-color: transparent !important;
}
</style>
@endpush

@push('scripts')
<script>
const productIsVariable = {{ $product->isVariable() ? 'true' : 'false' }};

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

function selectVariation(element) {
    // Remove selection from all thumbnails
    document.querySelectorAll('.variation-thumbnail').forEach(thumb => {
        const border = thumb.querySelector('div[style*="border"]');
        if (border) {
            border.style.borderColor = '#dee2e6';
        }
        const checkmark = thumb.querySelector('.variation-checkmark');
        if (checkmark) {
            checkmark.style.display = 'none';
        }
        thumb.classList.remove('selected');
    });

    // Add selection to clicked thumbnail
    const border = element.querySelector('div[style*="border"]');
    if (border) {
        border.style.borderColor = '#0F69F3';
    }
    const checkmark = element.querySelector('.variation-checkmark');
    if (checkmark) {
        checkmark.style.display = 'flex';
    }
    element.classList.add('selected');

    // Update hidden input
    const variationId = element.dataset.variationId;
    document.getElementById('selected-variation-id').value = variationId;

    // Update image gallery for variation
    updateImageGallery(element);

    // Update variation info
    updateVariationInfo(element);
}

function updateImageGallery(element) {
    const imagesData = element.dataset.images;

    if (!imagesData) return;

    try {
        const images = JSON.parse(imagesData);

        if (images && images.length > 0) {
            // Update main image
            document.getElementById('mainImage').src = images[0];

            // Update thumbnails
            const thumbnailsContainer = document.getElementById('image-thumbnails');
            thumbnailsContainer.innerHTML = '';

            if (images.length > 1) {
                images.forEach((imageUrl, index) => {
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-3';

                    const img = document.createElement('img');
                    img.src = imageUrl;
                    img.alt = `${element.dataset.name} - Image ${index + 1}`;
                    img.className = 'img-fluid rounded thumbnail-image';
                    img.style.cursor = 'pointer';
                    img.onclick = function() { changeMainImage(imageUrl); };

                    if (index === 0) {
                        img.classList.add('active');
                    }

                    colDiv.appendChild(img);
                    thumbnailsContainer.appendChild(colDiv);
                });
            }
        }
    } catch (error) {
        console.error('Error parsing variation images:', error);
    }
}

function updateVariationInfo(element) {
    if (!element) {
        // Find selected variation thumbnail
        element = document.querySelector('.variation-thumbnail.selected');
        if (!element) return;
    }

    const stock = parseInt(element.dataset.stock);
    const name = element.dataset.name;

    // Update quantity max
    document.getElementById('product-quantity').max = stock;

    // Update stock badge
    const stockBadge = document.getElementById('stock-badge');
    if (stockBadge) {
        stockBadge.textContent = `{{ __('marketing.shop.in_stock') }} (${stock} {{ __('marketing.shop.available') }})`;
    }

    // Enable buttons
    document.getElementById('add-to-cart-btn').disabled = false;
    document.getElementById('buy-now-btn').disabled = false;
}

function getSelectedVariationId() {
    if (!productIsVariable) return null;

    const hiddenInput = document.getElementById('selected-variation-id');
    if (!hiddenInput) return null;

    return hiddenInput.value || null;
}

function addToCart(productId) {
    // Check if variation is required but not selected
    if (productIsVariable) {
        const variationId = getSelectedVariationId();
        if (!variationId) {
            alert('{{ __("marketing.shop.please_select_variation", [], "Please select a variation first") }}');
            return;
        }
    }

    const quantity = document.getElementById('product-quantity').value;
    const variationId = getSelectedVariationId();

    const requestData = {
        quantity: parseInt(quantity)
    };

    if (variationId) {
        requestData.variation_id = parseInt(variationId);
    }

    // Use fetch to add to cart without page reload
    fetch('{{ route("marketing.cart.add", ":productId") }}'.replace(':productId', productId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
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

        if (variationId) {
            const variationInput = document.createElement('input');
            variationInput.type = 'hidden';
            variationInput.name = 'variation_id';
            variationInput.value = variationId;
            form.appendChild(variationInput);
        }

        document.body.appendChild(form);
        form.submit();
    });
}

function buyNow(productId) {
    // Check if variation is required but not selected
    if (productIsVariable) {
        const variationId = getSelectedVariationId();
        if (!variationId) {
            alert('{{ __("marketing.shop.please_select_variation", [], "Please select a variation first") }}');
            return;
        }
    }

    const quantity = document.getElementById('product-quantity').value;
    const variationId = getSelectedVariationId();

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

    if (variationId) {
        const variationInput = document.createElement('input');
        variationInput.type = 'hidden';
        variationInput.name = 'variation_id';
        variationInput.value = variationId;
        form.appendChild(variationInput);
    }

    document.body.appendChild(form);
    form.submit();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (productIsVariable) {
        // Initialize with first variation selected
        const firstVariation = document.querySelector('.variation-thumbnail.selected');
        if (firstVariation) {
            updateImageGallery(firstVariation);
            updateVariationInfo(firstVariation);
        } else {
            // Disable buttons initially if no variation selected
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const buyNowBtn = document.getElementById('buy-now-btn');

            if (addToCartBtn) addToCartBtn.disabled = true;
            if (buyNowBtn) buyNowBtn.disabled = true;
        }
    }
});
</script>
@endpush
