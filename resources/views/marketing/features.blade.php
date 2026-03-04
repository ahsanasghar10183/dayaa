@extends('marketing.layouts.master')

@section('title', 'Features - Dayaa Digital Donations')
@section('meta_description', 'Discover Dayaa\'s powerful features: campaign management, smart kiosks, real-time analytics, and seamless payment processing for nonprofit fundraising.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Platform Features</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Everything you need to run successful fundraising campaigns
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
                CORE FEATURES
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               Comprehensive Fundraising Tools
            </h2>
         </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="Campaign Management">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Campaign Management</h3>
                        <p>
                            Create and manage multiple fundraising campaigns with customizable donation amounts, goals, and branding.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="Smart Kiosks">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Smart Kiosk Devices</h3>
                        <p>
                            Deploy donation terminals that accept contactless payments, cards, and mobile payments seamlessly.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="Analytics">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Real-Time Analytics</h3>
                        <p>
                            Track donation performance with comprehensive dashboards and generate detailed reports instantly.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="Payment Processing">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Secure Payment Processing</h3>
                        <p>
                            Accept donations through SumUp, Stripe, and other payment providers with bank-level security.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="Device Management">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Device Management</h3>
                        <p>
                            Monitor and manage all your donation devices from a single dashboard with remote configuration.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="Donor Engagement">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Donor Engagement</h3>
                        <p>
                            Send automated thank-you messages and tax receipts to engage donors and build lasting relationships.
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
                            <span class="pp-sub-title wow fadeInUp">CAMPAIGN WIZARD</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                 Create Campaigns in Minutes
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            Our intuitive five-step wizard guides you through creating professional fundraising campaigns. Configure donation amounts, payment methods, and visual branding without technical expertise.
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>Step-by-step campaign creation</li>
                            <li>Customizable donation presets</li>
                            <li>Multiple currency support</li>
                            <li>Custom branding and themes</li>
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
                            <span class="pp-sub-title wow fadeInUp">REPORTING</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                  Insights That Drive Results
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            Make data-driven decisions with comprehensive analytics. Track donations by campaign, device, time period, and generate custom reports for stakeholders and audits.
                        </p>
                        <ul class="pp-feature-list wow fadeInUp" data-wow-delay=".3s">
                            <li>Real-time donation tracking</li>
                            <li>Custom date range reporting</li>
                            <li>Export to PDF, Excel, CSV</li>
                            <li>Donor demographics analysis</li>
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
                        See Dayaa in Action
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        Request a demo and discover how Dayaa can transform your fundraising operations.
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Request Demo <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
