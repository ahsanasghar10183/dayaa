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
        <div class="row">
            @foreach($featuredProducts as $index => $product)
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.3 + ($index * 0.2) }}s">
                <div class="pp-device-card">
                    <div class="pp-device-image-wrapper">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    </div>
                    <div class="pp-device-content">
                        <h3>{{ $product->name }}</h3>
                        <a href="{{ route('marketing.shop.product', $product->slug) }}" class="pp-theme-btn">
                            {{ __('marketing.home.shop_view_details') }} <i class="fa-solid fa-arrow-right-long"></i>
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
<section class="pp-about-section section-padding fix">
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
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">{{ __('marketing.home.about_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
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
        <div class="row">
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
        <div class="row">
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
                            <img src="{{ asset('marketing/assets/img/home-1/cta/mobile-app.jpeg') }}" alt="Dayaa Platform" class="wow img-custom-anim-right" data-wow-duration="1.3s" data-wow-delay="0.3s">
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
