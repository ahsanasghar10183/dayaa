@extends('marketing.layouts.master')

@section('title', __('marketing.features.page_title') . ' - Dayaa')
@section('meta_description', __('marketing.features.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.features.page_title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.features.page_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Overview -->
<section class="pp-offer-section section-padding fix">
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                {{ __('marketing.features.core_subtitle') }}
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               {{ __('marketing.features.core_title') }}
            </h2>
         </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="{{ __('marketing.features.feature_1_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.features.feature_1_title') }}</h3>
                        <p>
                            {{ __('marketing.features.feature_1_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="{{ __('marketing.features.feature_2_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.features.feature_2_title') }}</h3>
                        <p>
                            {{ __('marketing.features.feature_2_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="{{ __('marketing.features.feature_3_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.features.feature_3_title') }}</h3>
                        <p>
                            {{ __('marketing.features.feature_3_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="{{ __('marketing.features.feature_4_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.features.feature_4_title') }}</h3>
                        <p>
                            {{ __('marketing.features.feature_4_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="{{ __('marketing.features.feature_5_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.features.feature_5_title') }}</h3>
                        <p>
                            {{ __('marketing.features.feature_5_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="{{ __('marketing.features.feature_6_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.features.feature_6_title') }}</h3>
                        <p>
                            {{ __('marketing.features.feature_6_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Details -->
<section class="pp-key-feature-section section-padding pb-0 fix section-bg">
    <div class="container">
        <div class="pp-key-feature-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="pp-key-feature-image">
                        <img src="{{ asset('marketing/assets/img/home-1/feature/02.jpg') }}" alt="Dashboard">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-key-feature-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">{{ __('marketing.features.detail_1_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                 {{ __('marketing.features.detail_1_title') }}
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.features.detail_1_text') }}
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>{{ __('marketing.features.detail_1_list_1') }}</li>
                            <li>{{ __('marketing.features.detail_1_list_2') }}</li>
                            <li>{{ __('marketing.features.detail_1_list_3') }}</li>
                            <li>{{ __('marketing.features.detail_1_list_4') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pp-key-feature-section section-padding fix section-bg">
    <div class="container">
        <div class="pp-key-feature-wrapper pp-style-2">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="pp-key-feature-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">{{ __('marketing.features.detail_2_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                  {{ __('marketing.features.detail_2_title') }}
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.features.detail_2_text') }}
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>{{ __('marketing.features.detail_2_list_1') }}</li>
                            <li>{{ __('marketing.features.detail_2_list_2') }}</li>
                            <li>{{ __('marketing.features.detail_2_list_3') }}</li>
                            <li>{{ __('marketing.features.detail_2_list_4') }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-key-feature-image">
                        <img src="{{ asset('marketing/assets/img/home-1/feature/06.jpg') }}" alt="Reports">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="pp-cta-section section-padding fix theme-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="pp-cta-content">
                    <h2 class="wow fadeInUp mb-4" data-wow-delay=".3s" style="line-height: 1.4;">
                        {{ __('marketing.features.cta_title') }}
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        {{ __('marketing.features.cta_text') }}
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.features.cta_button') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
