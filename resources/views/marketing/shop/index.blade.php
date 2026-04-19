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
            <!-- Products -->
            <div class="col-12">
                <!-- Sort & Search -->
                <div class="row mb-4 g-2">
                    <div class="col-12 col-md-6">
                        <form action="{{ route('marketing.shop.index') }}" method="GET" class="d-flex search-form-wrapper">
                            <input type="text" name="search" class="shop-search-input form-control" placeholder="{{ __('marketing.shop.search_products') }}" value="{{ request('search') }}">
                            <button type="submit" class="shop-search-btn pp-theme-btn ms-2">
                                <i class="fa-solid fa-search"></i>
                                <span class="d-none d-md-inline">{{ __('marketing.shop.search') }}</span>
                            </button>
                        </form>
                    </div>
                    <div class="col-12 col-md-6">
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
                        <div class="pp-offer-box-item d-flex flex-column" style="padding-bottom: 20px;">
                            <div class="product-image mb-3" style="height: 300px; overflow: hidden;">
                                <a href="{{ route('marketing.shop.product', $product->slug) }}">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 24px;">
                                </a>
                            </div>
                            <div class="pp-offer-content flex-grow-1 d-flex flex-column">
                                <h4 style="min-height: 2.5em; line-height: 1.25em;">
                                    <a href="{{ route('marketing.shop.product', $product->slug) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h4>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="h5 mb-0 text-primary">{{ $product->formatted_price }}</span>
                                    @if($product->is_in_stock)
                                        <span class="badge bg-success">{{ __('marketing.shop.in_stock') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('marketing.shop.out_of_stock') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('marketing.shop.product', $product->slug) }}" class="pp-theme-btn w-100 text-center" style="margin-top: auto; padding: 14px 24px;">
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
    <div class="top-shape">
        <img src="{{ asset('marketing/assets/img/home-1/cta/bg.png') }}" alt="img">
    </div>
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
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn pp-style-2 wow fadeInUp ms-3" data-wow-delay=".3s">{{ __('marketing.shop.get_started_now') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
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
    padding: 12px 28px !important;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%) !important;
    box-shadow: 0 4px 12px rgba(15, 105, 243, 0.25);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
}

.shop-search-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.shop-search-btn:hover::before {
    left: 100%;
}

.shop-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(15, 105, 243, 0.35);
    background: linear-gradient(135deg, #0d5ad4 0%, #140998 100%) !important;
}

.shop-search-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(15, 105, 243, 0.3);
}

.shop-search-btn i {
    font-size: 16px;
    transition: transform 0.3s ease;
}

.shop-search-btn:hover i {
    transform: scale(1.1);
}

/* Desktop: Show icon and text */
@media (min-width: 768px) {
    .shop-search-btn i {
        margin-right: 0;
    }

    .shop-search-btn span {
        margin-left: 0;
    }
}

/* Mobile: Icon only, centered */
@media (max-width: 767px) {
    .shop-search-btn {
        padding: 11px 16px !important;
        min-width: 46px;
    }

    .shop-search-btn i {
        margin: 0;
    }
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

/* Ensure product images maintain square aspect ratio */
.product-image {
    height: 300px !important;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 24px;
}

/* Mobile Responsive Styles */
@media (max-width: 991px) {
    /* Compact Search Bar */
    .shop-search-input {
        padding: 10px 14px;
        font-size: 14px;
    }

    .shop-search-btn {
        padding: 11px 20px !important;
        font-size: 14px;
        min-width: 44px;
    }

    .shop-search-btn i {
        font-size: 15px;
    }

    .shop-sort-select {
        padding: 10px 14px;
        font-size: 14px;
    }

    .search-form-wrapper {
        align-items: center;
    }
}

@media (max-width: 576px) {
    .shop-search-input {
        padding: 8px 12px;
        font-size: 13px;
    }

    .shop-search-btn {
        padding: 9px 16px !important;
        font-size: 13px;
        min-width: 42px;
    }

    .shop-search-btn i {
        font-size: 14px;
    }

    .shop-sort-select {
        padding: 8px 12px;
        font-size: 13px;
    }

    /* Maintain product image square ratio on small mobile */
    .product-image {
        height: 250px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// No additional scripts needed
</script>
@endpush
