@extends('marketing.layouts.master')

@section('title', 'Shop - Dayaa Donation Devices & Equipment')
@section('meta_description', 'Browse our range of professional donation devices including kiosks, tablets, card readers, and accessories for digital fundraising.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Shop Donation Devices</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Professional equipment for modern fundraising
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Shop Content -->
<section class="section-padding fix">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="shop-sidebar">
                    <h4 class="mb-3">Categories</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('marketing.shop.index') }}" class="text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : '' }}">
                                All Products
                            </a>
                        </li>
                        @foreach($categories as $category)
                        <li class="mb-2">
                            <a href="{{ route('marketing.shop.category', $category->slug) }}" class="text-decoration-none">
                                {{ $category->name }} <span class="text-muted">({{ $category->active_products_count }})</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <!-- Sort & Search -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form action="{{ route('marketing.shop.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('marketing.shop.index') }}" method="GET">
                            @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4 col-sm-6">
                        <div class="pp-offer-box-item h-100 d-flex flex-column">
                            <div class="product-image mb-3" style="height: 200px; overflow: hidden;">
                                <a href="{{ route('marketing.shop.product', $product->slug) }}">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                            </div>
                            <div class="pp-offer-content flex-grow-1 d-flex flex-column">
                                <h4>
                                    <a href="{{ route('marketing.shop.product', $product->slug) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h4>
                                <p class="flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="h5 mb-0 text-primary">{{ $product->formatted_price }}</span>
                                    @if($product->is_in_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </div>
                                <a href="{{ route('marketing.shop.product', $product->slug) }}" class="pp-theme-btn mt-3 w-100 text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $products->links() }}
                </div>
                @else
                <div class="alert alert-info">
                    No products found. Try adjusting your search or filters.
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="pp-cta-section section-padding fix theme-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="pp-cta-content">
                    <h2 class="wow fadeInUp mb-4" data-wow-delay=".3s" style="line-height: 1.4;">
                        Need Help Choosing the Right Device?
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        Our team can help you select the perfect donation devices for your organization's needs. Get expert advice and personalized recommendations.
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Contact Our Team <i class="fa-solid fa-arrow-right-long"></i></a>
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn pp-style-2 wow fadeInUp ms-3" data-wow-delay=".3s">Start Free Trial <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.shop-sidebar a:hover {
    color: #0F69F3 !important;
}

.product-image img {
    transition: transform 0.3s ease;
}

.product-image:hover img {
    transform: scale(1.05);
}
</style>
@endpush
