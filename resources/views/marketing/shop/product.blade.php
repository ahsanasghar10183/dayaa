@extends('marketing.layouts.master')

@section('title', $product->name . ' - Dayaa Shop')
@section('meta_description', Str::limit($product->description, 150))

@section('content')

<!-- Breadcrumb -->
<section class="section-padding" style="padding: 40px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('marketing.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketing.shop.index') }}">Shop</a></li>
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
                        Category: <a href="{{ route('marketing.shop.category', $product->category->slug) }}">{{ $product->category->name }}</a>
                    </p>
                    @endif

                    <div class="mb-4">
                        <h2 class="text-primary">{{ $product->formatted_price }}</h2>
                        @if($product->compare_price)
                        <p class="text-muted">
                            <del>€{{ number_format($product->compare_price, 2) }}</del>
                            <span class="badge bg-danger ms-2">Save {{ $product->discount_percentage }}%</span>
                        </p>
                        @endif
                    </div>

                    @if($product->sku)
                    <p class="text-muted small mb-3">SKU: {{ $product->sku }}</p>
                    @endif

                    <div class="availability mb-4">
                        @if($product->is_in_stock)
                        <span class="badge bg-success">In Stock ({{ $product->quantity }} available)</span>
                        @else
                        <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>

                    <div class="description mb-4">
                        <h4>Description</h4>
                        <p>{{ $product->description }}</p>
                    </div>

                    @if($product->specifications)
                    <div class="specifications mb-4">
                        <h4>Specifications</h4>
                        <ul class="list-unstyled">
                            @foreach($product->specifications as $key => $value)
                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($product->is_in_stock)
                    <form action="{{ route('marketing.cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-3">
                            <div class="col-auto">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" style="width: 100px;">
                            </div>
                            <div class="col-auto d-flex align-items-end">
                                <button type="submit" class="pp-theme-btn">
                                    Add to Cart <i class="fa-solid fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        This product is currently out of stock. Please check back later or contact us for availability.
                    </div>
                    @endif

                    <div class="product-meta">
                        <p class="mb-2"><i class="fa-solid fa-truck"></i> Free shipping on orders over €100</p>
                        <p class="mb-2"><i class="fa-solid fa-shield-alt"></i> 2-year warranty included</p>
                        <p class="mb-0"><i class="fa-solid fa-headset"></i> 24/7 customer support</p>
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
            <h2>Related Products</h2>
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
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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
</script>
@endpush
