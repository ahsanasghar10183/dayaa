@extends('marketing.layouts.master')

@section('title', 'Dayaa - Transform Your Fundraising with Digital Donations')
@section('meta_description', 'Empower your organization with Dayaa\'s innovative digital donation platform. Streamline donations, engage donors, and maximize your impact with our comprehensive fundraising solutions.')

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
                       Transform Your Fundraising <br> with Digital Donations
                    </h1>
                    <p class="wow fadeInUp" data-wow-delay=".5s">
                        Empower your organization with Dayaa's innovative digital donation platform. Streamline donations, engage donors, and maximize your impact with our comprehensive fundraising solutions designed for modern nonprofits.
                    </p>
                    <div class="pp-hero-button">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Get Started Now <i class="fa-solid fa-arrow-right-long"></i></a>
                        <a href="{{ route('marketing.about') }}" class="pp-theme-btn pp-style-2 wow fadeInUp" data-wow-delay=".3s">Learn More <i class="fa-solid fa-arrow-right-long"></i></a>
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
                <h3>Trusted by leading organizations worldwide</h3>
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

<!-- Pp-About Section Start -->
<section class="pp-about-section section-padding fix">
    <div class="container">
        <div class="pp-about-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="about-image">
                        <img src="{{ asset('marketing/assets/img/home-1/about/about-1.jpg') }}" alt="Dayaa Platform" class="wow img-custom-anim-left" data-wow-duration="1.3s" data-wow-delay="0.3s">
                        <div class="about-image-2">
                            <img src="{{ asset('marketing/assets/img/home-1/about/about-2.jpg') }}" alt="Digital Donations">
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
                            <span class="pp-sub-title wow fadeInUp">ABOUT US</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                Making Fundraising Easier for Every Organization
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            From small charities to large nonprofits, Dayaa's intuitive platform helps organizations manage donations effortlessly. Accept payments seamlessly, track donor engagement in real time, and achieve better fundraising results with our purpose-built donation management system.
                        </p>
                        <div class="about-count-item wow fadeInUp" data-wow-delay=".3s">
                            <div class="count-text">
                                <h2><span class="pp-count">500</span>+</h2>
                                <p>
                                    Organizations worldwide
                                </p>
                            </div>
                            <div class="count-text">
                                <h2><span class="pp-count">150</span>%</h2>
                                <p>
                                    Increase in donations
                                </p>
                            </div>
                            <div class="count-text">
                                <h2><span class="pp-count">24</span>/7</h2>
                                <p>
                                   Support available
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('marketing.about') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">Discover More <i class="fa-solid fa-arrow-right-long"></i></a>
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
                WHAT WE OFFER
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               Our Digital Donation Solutions
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
                            Campaign Management
                        </h3>
                        <p>
                            Create, manage, and track fundraising campaigns with powerful analytics and donor insights.
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
                            Smart Kiosk Devices
                        </h3>
                        <p>
                            Accept contactless donations with our intelligent kiosk terminals and mobile devices.
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
                            Real-Time Reporting
                        </h3>
                        <p>
                            Monitor donations and generate detailed reports with our comprehensive analytics dashboard.
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
                HOW IT WORKS
                <span class="pp-style-2"></span>
            </span>
            <h2 class="text-white wow fadeInUp" data-wow-delay=".3s">
               Fundraising Made Simple
            </h2>
         </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-how-work-items">
                    <h6>STEP-01</h6>
                    <h3>Set Up Your Campaign</h3>
                    <p>
                        Create your fundraising campaign in minutes with our intuitive wizard. Configure donation goals, payment methods, and campaign branding.
                    </p>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-how-work-items pp-style-2">
                    <h6>STEP-02</h6>
                    <h3>Deploy Your Devices</h3>
                    <p>
                        Connect your kiosk terminals and mobile devices. Activate donation collection points at your events, offices, or public locations.
                    </p>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-how-work-items">
                    <h6>STEP-03</h6>
                    <h3>Track and Optimize</h3>
                    <p>
                        Monitor donation performance in real-time. Generate reports, engage donors, and continuously improve your fundraising results.
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
                        <img src="{{ asset('marketing/assets/img/home-1/feature/02.jpg') }}" alt="Campaign Dashboard">
                        <div class="pp-key-feature-image-2 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/01.jpg') }}" alt="Analytics">
                        </div>
                        <div class="pp-key-feature-image-3 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/03.jpg') }}" alt="Mobile Kiosk">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-key-feature-content">
                        <div class="pp-section-title mb-0">
                            <span class="pp-sub-title wow fadeInUp">KEY FEATURES</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                 Intuitive Campaign Builder
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            Build engaging fundraising campaigns with our step-by-step wizard. Customize donation amounts, configure payment options, and brand your campaign with your organization's identity.
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>
                                Multi-step campaign creation wizard
                            </li>
                            <li>
                                Flexible donation amount configurations
                            </li>
                            <li>
                                Custom branding and themes
                            </li>
                        </ul>
                        <a href="{{ route('marketing.features') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">Read More <i class="fa-solid fa-arrow-right-long"></i></a>
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
                            <span class="pp-sub-title wow fadeInUp">KEY FEATURES</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                  Advanced Analytics & Insights
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            Gain deep insights into your fundraising performance with comprehensive analytics. Track donations by campaign, device, time period, and donor demographics to optimize your strategy.
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>
                                Real-time donation tracking
                            </li>
                            <li>
                                Customizable reporting dashboard
                            </li>
                            <li>
                                Donor engagement metrics
                            </li>
                        </ul>
                        <a href="{{ route('marketing.features') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">Read More <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pp-key-feature-image">
                        <img src="{{ asset('marketing/assets/img/home-1/feature/06.jpg') }}" alt="Reports Dashboard">
                        <div class="pp-key-feature-image-2 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/04.jpg') }}" alt="Analytics Charts">
                        </div>
                        <div class="pp-key-feature-image-3 float-bob-y">
                            <img src="{{ asset('marketing/assets/img/home-1/feature/05.jpg') }}" alt="Data Insights">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->isNotEmpty())
<section class="pp-offer-section section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                SHOP
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               Featured Donation Devices
            </h2>
         </div>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-height: 80px; object-fit: contain;">
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h4 mb-0">{{ $product->formatted_price }}</span>
                            <a href="{{ route('marketing.shop.product', $product->slug) }}" class="pp-theme-btn">
                                View Details <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".5s">
                View All Products <i class="fa-solid fa-arrow-right-long"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Pp-testimonial Section Start -->
<section class="pp-testimonial-section section-padding fix">
    <div class="container">
        <div class="pp-section-title-area">
            <div class="pp-section-title">
                <span class="pp-sub-title wow fadeInUp">TESTIMONIALS</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                    Success Stories from Our Partners
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
                            "Dayaa has transformed how we collect donations. The kiosk devices are intuitive, and the real-time reporting helps us make data-driven decisions. We've seen a 180% increase in donations since implementing their platform."
                        </p>
                        <div class="pp-client-info-item">
                            <div class="pp-client-image">
                                <img src="{{ asset('marketing/assets/img/home-1/testimonial/client-1.png') }}" alt="Client">
                            </div>
                            <div class="pp-content">
                                <h5>Sarah Williams</h5>
                                <span>Director at Hope Foundation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="pp-testimonial-card">
                        <p>
                            "The campaign management tools are outstanding. We can set up new fundraising initiatives in minutes and track performance across all our donation points. Dayaa's support team is incredibly responsive and helpful."
                        </p>
                        <div class="pp-client-info-item">
                            <div class="pp-client-image">
                                <img src="{{ asset('marketing/assets/img/home-1/testimonial/client-2.png') }}" alt="Client">
                            </div>
                            <div class="pp-testimonial-content">
                                <h5>Michael Schmidt</h5>
                                <span>Fundraising Manager at Global Aid</span>
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
                            <img src="{{ asset('marketing/assets/img/home-1/cta/cta-2.jpg') }}" alt="Dayaa Platform" class="wow img-custom-anim-right" data-wow-duration="1.3s" data-wow-delay="0.3s">
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
                        <h2 class="wow fadeInUp" data-wow-delay=".3s">
                            Ready to Transform Your Fundraising?
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay=".5s">
                            Join hundreds of organizations already using Dayaa to maximize their donation impact. Get started today with a free trial and discover how easy digital fundraising can be.
                        </p>
                        <div class="pp-cta-button">
                            <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Get Started <i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
