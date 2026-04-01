@extends('marketing.layouts.master')

@section('title', __('marketing.about.title') . ' - Dayaa')
@section('meta_description', __('marketing.about.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.about.page_title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.about.page_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="pp-about-section section-padding fix">
    <div class="container">
        <div class="pp-about-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="about-image">
                        <img src="{{ asset('marketing/assets/img/home-1/about/about-1.jpg') }}" alt="About Dayaa" class="wow img-custom-anim-left" data-wow-duration="1.3s" data-wow-delay="0.3s">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">{{ __('marketing.about.mission_subtitle') }}</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                {{ __('marketing.about.mission_title') }}
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.about.mission_text_1') }}
                        </p>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            {{ __('marketing.about.mission_text_2') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="pp-offer-section section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                {{ __('marketing.about.values_subtitle') }}
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               {{ __('marketing.about.values_title') }}
            </h2>
         </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="{{ __('marketing.about.value_1_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.about.value_1_title') }}</h3>
                        <p>
                            {{ __('marketing.about.value_1_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="{{ __('marketing.about.value_2_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.about.value_2_title') }}</h3>
                        <p>
                            {{ __('marketing.about.value_2_desc') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="{{ __('marketing.about.value_3_title') }}">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.about.value_3_title') }}</h3>
                        <p>
                            {{ __('marketing.about.value_3_desc') }}
                        </p>
                    </div>
                </div>
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
                        {{ __('marketing.about.cta_title') }}
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        {{ __('marketing.about.cta_text') }}
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.about.cta_button') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
