@extends('marketing.layouts.master')

@section('title', __('marketing.shop.title') . ' - Dayaa')
@section('meta_description', __('marketing.shop.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.shop.page_title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.shop.page_subtitle') }}
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
                    <h4 class="mb-3">{{ __('marketing.shop.categories') }}</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('marketing.shop.index') }}" class="text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : '' }}">
                                {{ __('marketing.shop.all_products') }}
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
                            <input type="text" name="search" class="shop-search-input form-control" placeholder="{{ __('marketing.shop.search_products') }}" value="{{ request('search') }}">
                            <button type="submit" class="shop-search-btn pp-theme-btn ms-2">{{ __('marketing.shop.search') }}</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('marketing.shop.index') }}" method="GET">
                            @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="sort" class="shop-sort-select form-select" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('marketing.shop.newest_first') }}</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('marketing.shop.price_low_high') }}</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('marketing.shop.price_high_low') }}</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('marketing.shop.name_az') }}</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4 col-sm-6">
                        <div class="pp-offer-box-item  d-flex flex-column">
                            <div class="product-image mb-3" style="height: 300px; overflow: hidden;">
                                <a href="{{ route('marketing.shop.product', $product->slug) }}">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 24px;">
                                </a>
                            </div>
                            <div class="pp-offer-content flex-grow-1 d-flex flex-column">
                                <h4>
                                    <a href="{{ route('marketing.shop.product', $product->slug) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h4>
                               
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="h5 mb-0 text-primary">{{ $product->formatted_price }}</span>
                                    @if($product->is_in_stock)
                                        <span class="badge bg-success">{{ __('marketing.shop.in_stock') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('marketing.shop.out_of_stock') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('marketing.shop.product', $product->slug) }}" class="pp-theme-btn mt-3 w-100 text-center">
                                    {{ __('marketing.shop.view_details') }}
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
                    {{ __('marketing.shop.no_products_filter') }}
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
                        {{ __('marketing.shop.need_help_title') }}
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        {{ __('marketing.shop.need_help_text') }}
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.shop.contact_team') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn pp-style-2 wow fadeInUp ms-3" data-wow-delay=".3s">{{ __('marketing.shop.start_trial') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Sidebar Styling */
.shop-sidebar {
    background: linear-gradient(135deg, rgba(15, 105, 243, 0.05) 0%, rgba(23, 10, 181, 0.05) 100%);
    border-radius: 10px;
    padding: 25px;
    border: 1px solid rgba(15, 105, 243, 0.1);
}

.shop-sidebar h4 {
    color: #170AB5;
    font-weight: 600;
    border-bottom: 2px solid #0F69F3;
    padding-bottom: 10px;
}

.shop-sidebar a {
    color: #333;
    transition: all 0.3s ease;
    padding: 8px 12px;
    display: block;
    border-radius: 6px;
}

.shop-sidebar a:hover {
    color: #0F69F3 !important;
    background: rgba(15, 105, 243, 0.08);
    padding-left: 20px;
}

.shop-sidebar a.fw-bold {
    background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%);
    color: white !important;
    font-weight: 600;
}

/* Search Input Styling */
.shop-search-input {
    border: 2px solid rgba(15, 105, 243, 0.2);
    border-radius: 8px;
    padding: 12px 18px;
    transition: all 0.3s ease;
    font-size: 15px;
}

.shop-search-input:focus {
    border-color: #0F69F3;
    box-shadow: 0 0 0 0.2rem rgba(15, 105, 243, 0.15);
    outline: none;
}

.shop-search-input::placeholder {
    color: #999;
}

/* Search Button Styling */
.shop-search-btn {
    white-space: nowrap;
    padding: 12px 24px !important;
    font-size: 15px;
}

/* Sort Dropdown Styling */
.shop-sort-select {
    border: 2px solid rgba(15, 105, 243, 0.2);
    border-radius: 8px;
    padding: 12px 18px;
    transition: all 0.3s ease;
    font-size: 15px;
    background: white;
    cursor: pointer;
}

.shop-sort-select:focus {
    border-color: #0F69F3;
    box-shadow: 0 0 0 0.2rem rgba(15, 105, 243, 0.15);
    outline: none;
}

.shop-sort-select option {
    padding: 10px;
}

/* Product Image Hover Effect */
.product-image img {
    transition: transform 0.3s ease;
}

.product-image:hover img {
    transform: scale(1.05);
}
</style>
@endpush
