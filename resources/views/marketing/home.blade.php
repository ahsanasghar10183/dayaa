@extends('marketing.layouts.master')

@section('title', __('marketing.home.hero_title') . ' - Dayaa')
@section('meta_description', __('marketing.home.hero_subtitle'))

@section('content')

<!-- pp Hero Section Start -->
<section class="pp-hero-section pp-hero-1 fix">
    <div class="top-shape">
        <img src="{{ asset('marketing/assets/img/home-1/hero/hero-bg.png') }}" alt="img">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="pp-hero-content">
                    <h1 class="wow img-custom-anim-left" data-wow-duration="1.3s" data-wow-delay="0.3s">
                       {{ __('marketing.home.hero_title') }}
                    </h1>
                    <p class="wow fadeInUp" data-wow-delay=".5s">
                        {{ __('marketing.home.hero_subtitle') }}
                    </p>
                    <div class="pp-hero-button">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.home.cta_primary') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                        <a href="{{ route('marketing.about') }}" class="pp-theme-btn pp-style-2 wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.home.cta_secondary') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="pp-hero-image wow img-custom-anim-bottom" data-wow-duration="1.3s" data-wow-delay="0.3s">
                    <img src="{{ asset('marketing/assets/img/home-1/hero/hero-1.jpg') }}" alt="Dayaa Platform Dashboard">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pp-Brand Section Start -->
{{-- Brands section commented out - can be added later when needed
<div class="pp-brand-section section-padding pb-0 fix">
    <div class="container custom-container-3">
        <div class="brand-wrapper style-2">
            <div class="brand-title wow fadeInUp" data-wow-delay=".3s">
                <h3>{{ __('marketing.home.trusted_by') }}</h3>
            </div>
            <div class="swiper pp-brand-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="brand-image text-center">
                            <img src="{{ asset('marketing/assets/img/home-1/brand/01.png') }}" alt="Partner Logo">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-image text-center">
                            <img src="{{ asset('marketing/assets/img/home-1/brand/02.png') }}" alt="Partner Logo">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-image text-center">
                            <img src="{{ asset('marketing/assets/img/home-1/brand/03.png') }}" alt="Partner Logo">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-image text-center">
                            <img src="{{ asset('marketing/assets/img/home-1/brand/04.png') }}" alt="Partner Logo">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-image text-center">
                            <img src="{{ asset('marketing/assets/img/home-1/brand/05.png') }}" alt="Partner Logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
--}}

<!-- Featured Donation Devices Section -->
@if($featuredProducts->isNotEmpty())
<section class="pp-product-showcase section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                {{ __('marketing.home.shop_subtitle') }}
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               {{ __('marketing.home.shop_title') }}
            </h2>
         </div>
        <div class="row g-4">
            @foreach($featuredProducts as $index => $product)
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.3 + ($index * 0.2) }}s">
                <div class="pp-offer-box-item d-flex flex-column" style="padding-bottom: 20px;">
                    <div class="product-image mb-3" style="aspect-ratio: 1/1; overflow: hidden; position: relative;">
                        <a href="{{ route('marketing.shop.product', $product->slug) }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 24px; position: absolute; top: 0; left: 0;">
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
                            {{ __('marketing.home.shop_view_details') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn pp-style-2 wow fadeInUp" data-wow-delay=".5s">
                {{ __('marketing.home.shop_view_all') }} <i class="fa-solid fa-arrow-right-long"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Pp-About Section Start -->
<section class="pp-about-section section-padding fix" >
    <div class="container">
        <div class="pp-about-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="about-image">
                        <img src="{{ asset('marketing/assets/img/home-1/about/about-1.png') }}" alt="Dayaa Platform" class="wow img-custom-anim-left" data-wow-duration="1.3s" data-wow-delay="0.3s">
                        <div class="about-image-2">
                            <img src="{{ asset('marketing/assets/img/home-1/about/about-2.png') }}" alt="Digital Donations">
                        </div>
                        <div class="about-shape">
                            <img src="{{ asset('marketing/assets/img/home-1/about/shape-1.png') }}" alt="img">
                        </div>
                        <div class="circle-shape">
                            <img src="{{ asset('marketing/assets/img/home-1/about/shape-2.png') }}" alt="img">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" >
                    <div class="about-content">
                        <div class="pp-section-title mb-0" >
                            <span class="pp-sub-title wow fadeInUp" style="overflow: visible; padding-top: 4px; padding-bottom: 4px;">{{ __('marketing.home.about_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s" >
                                {{ __('marketing.home.about_title') }}
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.home.about_text') }}
                        </p>
                        <div class="about-count-item wow fadeInUp" data-wow-delay=".3s">
                            <div class="count-text">
                                <h2><span class="pp-count">500</span>+</h2>
                                <p>
                                    {{ __('marketing.home.about_stat_1') }}
                                </p>
                            </div>
                            <div class="count-text">
                                <h2><span class="pp-count">150</span>%</h2>
                                <p>
                                    {{ __('marketing.home.about_stat_2') }}
                                </p>
                            </div>
                            <div class="count-text">
                                <h2><span class="pp-count">24</span>/7</h2>
                                <p>
                                   {{ __('marketing.home.about_stat_3') }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('marketing.about') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">{{ __('marketing.home.about_cta') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pp-Offer Section Start -->
<section class="pp-offer-section section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                {{ __('marketing.home.offer_subtitle') }}
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               {{ __('marketing.home.offer_title') }}
            </h2>
         </div>
        <div class="row g-4">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="icon">
                    </div>
                    <div class="pp-offer-content">
                        <h3>
                            {{ __('marketing.home.offer_1_title') }}
                        </h3>
                        <p>
                            {{ __('marketing.home.offer_1_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="icon">
                    </div>
                    <div class="pp-offer-content">
                        <h3>
                            {{ __('marketing.home.offer_2_title') }}
                        </h3>
                        <p>
                            {{ __('marketing.home.offer_2_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="icon">
                    </div>
                    <div class="pp-offer-content">
                        <h3>
                            {{ __('marketing.home.offer_3_title') }}
                        </h3>
                        <p>
                            {{ __('marketing.home.offer_3_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pp-How-Wowk Section Start -->
<section class="pp-how-work-section section-padding fix section-bg-2">
    <div class="top-shape">
        <img src="{{ asset('marketing/assets/img/home-1/feature/bg-shape.png') }}" alt="img">
    </div>
    <div class="line-shape">
        <img src="{{ asset('marketing/assets/img/home-1/feature/line.png') }}" alt="img">
    </div>
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                {{ __('marketing.home.how_works_subtitle') }}
                <span class="pp-style-2"></span>
            </span>
            <h2 class="text-white wow fadeInUp" data-wow-delay=".3s">
               {{ __('marketing.home.how_works_title') }}
            </h2>
         </div>
        <div class="row g-4">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-how-work-items">
                    <h6>{{ __('marketing.home.step_1_label') }}</h6>
                    <h3>{{ __('marketing.home.step_1_title') }}</h3>
                    <p>
                        {{ __('marketing.home.step_1_desc') }}
                    </p>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-how-work-items pp-style-2">
                    <h6>{{ __('marketing.home.step_2_label') }}</h6>
                    <h3>{{ __('marketing.home.step_2_title') }}</h3>
                    <p>
                        {{ __('marketing.home.step_2_desc') }}
                    </p>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-how-work-items">
                    <h6>{{ __('marketing.home.step_3_label') }}</h6>
                    <h3>{{ __('marketing.home.step_3_title') }}</h3>
                    <p>
                        {{ __('marketing.home.step_3_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pp-key-feature Section Start -->
<section class="pp-key-feature-section section-padding pb-0 fix">
    <div class="container">
        <div class="pp-key-feature-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="pp-key-feature-image">
                        <img src="{{ asset('marketing/assets/img/home-1/feature/campaign-builder.png') }}" alt="Campaign Dashboard">
                        <div class="pp-key-feature-image-2 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/campaign-builder-3.png') }}" alt="Analytics">
                        </div>
                        <div class="pp-key-feature-image-3 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/campaign-builder-2.png') }}" alt="Mobile Kiosk">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-key-feature-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">{{ __('marketing.home.key_features_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                 {{ __('marketing.home.key_feature_1_title') }}
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.home.key_feature_1_text') }}
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>
                                {{ __('marketing.home.key_feature_1_list_1') }}
                            </li>
                            <li>
                                {{ __('marketing.home.key_feature_1_list_2') }}
                            </li>
                            <li>
                                {{ __('marketing.home.key_feature_1_list_3') }}
                            </li>
                        </ul>
                        <a href="{{ route('marketing.features') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">{{ __('marketing.home.key_feature_1_cta') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pp-key-feature Section Start -->
<section class="pp-key-feature-section section-padding fix">
    <div class="container">
        <div class="pp-key-feature-wrapper pp-style-2">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="pp-key-feature-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">{{ __('marketing.home.key_features_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                  {{ __('marketing.home.key_feature_2_title') }}
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.home.key_feature_2_text') }}
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>
                                {{ __('marketing.home.key_feature_2_list_1') }}
                            </li>
                            <li>
                                {{ __('marketing.home.key_feature_2_list_2') }}
                            </li>
                            <li>
                                {{ __('marketing.home.key_feature_2_list_3') }}
                            </li>
                        </ul>
                        <a href="{{ route('marketing.features') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">{{ __('marketing.home.key_feature_1_cta') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-key-feature-image">
                        <img src="{{ asset('marketing/assets/img/home-1/feature/analytics.png') }}" alt="Reports Dashboard">
                        <div class="pp-key-feature-image-2 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/Donations.png') }}" alt="Analytics Charts">
                        </div>
                        <div class="pp-key-feature-image-3 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/Raised.png') }}" alt="Data Insights">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials section commented out - not needed
<!-- Pp-testimonial Section Start -->
<section class="pp-testimonial-section section-padding fix">
    <div class="container">
        <div class="pp-section-title-area">
            <div class="pp-section-title">
                <span class="pp-sub-title wow fadeInUp">{{ __('marketing.home.testimonials_subtitle') }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                    {{ __('marketing.home.testimonials_title') }}
                </h2>
            </div>
             <div class="pp-array-buttons wow fadeInUp" data-wow-delay=".5s">
                <button class="array-prev"><i class="fa-solid fa-arrow-left-long"></i></button>
                <button class="array-next"><i class="fa-solid fa-arrow-right-long"></i></button>
          </div>
        </div>
        <div class="swiper pp-testimonial-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="pp-testimonial-card">
                        <p>
                            "{{ __('marketing.home.testimonial_1_text') }}"
                        </p>
                        <div class="pp-client-info-item">
                            <div class="pp-client-image">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Williams">
                            </div>
                            <div class="pp-content">
                                <h5>{{ __('marketing.home.testimonial_1_name') }}</h5>
                                <span>{{ __('marketing.home.testimonial_1_role') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="pp-testimonial-card">
                        <p>
                            "{{ __('marketing.home.testimonial_2_text') }}"
                        </p>
                        <div class="pp-client-info-item">
                            <div class="pp-client-image">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Schmidt">
                            </div>
                            <div class="pp-testimonial-content">
                                <h5>{{ __('marketing.home.testimonial_2_name') }}</h5>
                                <span>{{ __('marketing.home.testimonial_2_role') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
--}}

<!-- Pp-Trust Section Start -->
<section class="pp-trust-section section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center mb-5">
            <span class="pp-sub-title wow fadeInUp">
                {{ __('marketing.home.trust_section_subtitle') }}
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                {{ __('marketing.home.trust_section_title') }}
            </h2>
        </div>

        <!-- Features Grid -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-trust-feature-card text-center">
                    <div class="pp-trust-icon mb-3">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/innovation.svg') }}" alt="Innovation Icon" style="width: 64px; height: 64px;">
                    </div>
                    <h5 class="mb-2">{{ __('marketing.home.trust_feature_1_title') }}</h5>
                    <p class="text-muted mb-0">{{ __('marketing.home.trust_feature_1_desc') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-trust-feature-card text-center">
                    <div class="pp-trust-icon mb-3">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/payment.svg') }}" alt="Payment Icon" style="width: 64px; height: 64px;">
                    </div>
                    <h5 class="mb-2">{{ __('marketing.home.trust_feature_2_title') }}</h5>
                    <p class="text-muted mb-0">{{ __('marketing.home.trust_feature_2_desc') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-trust-feature-card text-center">
                    <div class="pp-trust-icon mb-3">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/security.svg') }}" alt="Security Icon" style="width: 64px; height: 64px;">
                    </div>
                    <h5 class="mb-2">{{ __('marketing.home.trust_feature_3_title') }}</h5>
                    <p class="text-muted mb-0">{{ __('marketing.home.trust_feature_3_desc') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".9s">
                <div class="pp-trust-feature-card text-center">
                    <div class="pp-trust-icon mb-3">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/guarantee.svg') }}" alt="Guarantee Icon" style="width: 64px; height: 64px;">
                    </div>
                    <h5 class="mb-2">{{ __('marketing.home.trust_feature_4_title') }}</h5>
                    <p class="text-muted mb-0">{{ __('marketing.home.trust_feature_4_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Divider Line -->
        <hr class="my-5 wow fadeInUp" data-wow-delay=".3s" style="border-top: 2px solid #e5e7eb; opacity: 0.5;">

        <!-- Payment Methods -->
        <div class="pp-payment-methods text-center wow fadeInUp" data-wow-delay=".5s">
            <p class="text-muted mb-4 fw-medium">{{ __('marketing.home.trust_payment_methods') }}</p>
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                <img src="{{ asset('marketing/assets/img/payment-methods/visa.svg') }}" alt="Visa" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/vpay.svg') }}" alt="V Pay" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/mastercard.svg') }}" alt="Mastercard" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/maestro.svg') }}" alt="Maestro" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/amex.svg') }}" alt="American Express" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/discover.svg') }}" alt="Discover" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/applepay.svg') }}" alt="Apple Pay" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/googlepay.svg') }}" alt="Google Pay" style="height: 32px;">
                <img src="{{ asset('marketing/assets/img/payment-methods/unionpay.svg') }}" alt="UnionPay" style="height: 32px;">
            </div>
        </div>
    </div>
</section>

<!-- Pp-cta Section Start -->
<section class="pp-cta-section section-padding fix theme-bg">
    <div class="top-shape">
        <img src="{{ asset('marketing/assets/img/home-1/cta/bg.png') }}" alt="img">
    </div>
    <div class="container">
        <div class="pp-cta-wrapper">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="pp-cta-image">
                        <img src="{{ asset('marketing/assets/img/home-1/cta/cta-1.jpg') }}" alt="Get Started" class="wow img-custom-anim-top" data-wow-duration="1.3s" data-wow-delay="0.3s">
                        <div class="pp-cta-image-2">
                            <!-- <img src="{{ asset('marketing/assets/img/home-1/cta/mobile-app.jpeg') }}" alt="Dayaa Platform" class="wow img-custom-anim-right" data-wow-duration="1.3s" data-wow-delay="0.3s"> -->
                        </div>
                        <div class="pp-shape float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/cta/shape-1.jpg') }}" alt="img">
                        </div>
                        <div class="pp-shape-2 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/cta/shape-2.jpg') }}" alt="img">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-cta-content">
                        <h2 class="wow fadeInUp mb-4" data-wow-delay=".3s" style="line-height: 1.4;">
                            {{ __('marketing.home.cta_title') }}
                        </h2>
                        <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                            {{ __('marketing.home.cta_text') }}
                        </p>
                        <div class="pp-cta-button mt-4">
                            <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.home.cta_button') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Product Image Hover Effect */
.product-image img {
    transition: transform 0.3s ease;
}

.product-image:hover img {
    transform: scale(1.05);
}

/* Trust Section Styles */
.pp-trust-section {
    background: #f9fafb;
}

.pp-trust-feature-card {
    padding: 2rem 1.5rem;
    background: white;
    border-radius: 16px;
    height: 100%;
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.pp-trust-feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(15, 105, 243, 0.1);
    border-color: rgba(15, 105, 243, 0.2);
}

.pp-trust-feature-card h5 {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    letter-spacing: 0.5px;
}

.pp-trust-feature-card p {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #6b7280;
}

.pp-trust-icon {
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

.pp-trust-icon img {
    filter: drop-shadow(0 4px 12px rgba(15, 105, 243, 0.15));
    transition: transform 0.3s ease;
}

.pp-trust-feature-card:hover .pp-trust-icon img {
    transform: scale(1.1);
}

/* Payment Methods Section */
.pp-payment-methods img {
    transition: transform 0.3s ease;
}

.pp-payment-methods img:hover {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pp-trust-feature-card {
        padding: 1.5rem 1rem;
        margin-bottom: 1rem;
    }

    .pp-payment-methods .d-flex {
        gap: 1rem !important;
    }

    .pp-payment-methods img {
        height: 28px !important;
    }
}
</style>
@endpush
