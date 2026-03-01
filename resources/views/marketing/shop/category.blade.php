@extends('marketing.layouts.master')

@section('title', $category->name . ' - Dayaa Shop')
@section('meta_description', $category->description ?? 'Browse ' . $category->name . ' products at Dayaa shop.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ $category->name }}</h1>
                @if($category->description)
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ $category->description }}
                </p>
                @endif
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('marketing.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('marketing.shop.index') }}">Shop</a></li>
                        <li class="breadcrumb-item active">{{ $category->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Category Content -->
<section class="section-padding fix">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="shop-sidebar">
                    <h4 class="mb-3">Categories</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('marketing.shop.index') }}" class="text-decoration-none">
                                All Products
                            </a>
                        </li>
                        @foreach($categories as $cat)
                        <li class="mb-2">
                            <a href="{{ route('marketing.shop.category', $cat->slug) }}" class="text-decoration-none {{ $cat->id == $category->id ? 'fw-bold text-primary' : '' }}">
                                {{ $cat->name }} <span class="text-muted">({{ $cat->active_products_count }})</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <!-- Sort -->
                <div class="row mb-4">
                    <div class="col-md-6 ms-auto">
                        <form action="{{ route('marketing.shop.category', $category->slug) }}" method="GET">
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
                    No products found in this category.
                </div>
                @endif
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

.breadcrumb {
    background-color: transparent;
}
</style>
@endpush
