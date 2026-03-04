@extends('marketing.layouts.master')

@section('title', 'Pricing - Dayaa Digital Donations')
@section('meta_description', 'Flexible pricing plans for organizations of all sizes. Start with our free tier or choose a plan that fits your fundraising needs.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Pricing Plans</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Choose the perfect plan for your organization
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="pp-pricing-section section-padding fix">
    <div class="container">
        <div class="row">
            @forelse($tiers as $tier)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="pp-pricing-main-item">
                    <div class="pp-pricing-card-item">
                        <div class="pp-pricing-header">
                            <h5>{{ $tier->name }}</h5>
                            <h2>€{{ number_format($tier->price_monthly, 2) }}<span>/ Per Month</span></h2>
                            @if($tier->price_annually)
                            <p class="text-muted small">Or €{{ number_format($tier->price_annually, 2) }}/year (save 17%)</p>
                            @endif
                        </div>
                        @if($tier->features)
                        <ul class="pp-pricing-list">
                            @foreach(json_decode($tier->features, true) as $feature)
                            <li>
                                <img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        @if($tier->limits)
                        <div class="mt-3 mb-3">
                            <h6>Plan Limits:</h6>
                            <ul class="pp-pricing-list">
                                @if(isset($tier->limits['max_campaigns']))
                                <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">
                                    Up to {{ $tier->limits['max_campaigns'] }} campaigns
                                </li>
                                @endif
                                @if(isset($tier->limits['max_devices']))
                                <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">
                                    Up to {{ $tier->limits['max_devices'] }} devices
                                </li>
                                @endif
                                @if(isset($tier->limits['max_users']))
                                <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">
                                    Up to {{ $tier->limits['max_users'] }} users
                                </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="pricing-button">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn">Get Started</a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Default Pricing if no tiers in database -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="pp-pricing-main-item">
                    <div class="pp-pricing-card-item">
                        <div class="pp-pricing-header">
                            <h5>Starter</h5>
                            <h2>€29<span>/ Per Month</span></h2>
                        </div>
                        <ul class="pp-pricing-list">
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Up to 3 campaigns</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">2 donation devices</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Basic analytics</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Email support</li>
                        </ul>
                    </div>
                    <div class="pricing-button">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="pp-pricing-main-item">
                    <div class="pp-pricing-card-item">
                        <div class="pp-pricing-header">
                            <h5>Professional</h5>
                            <h2>€79<span>/ Per Month</span></h2>
                        </div>
                        <ul class="pp-pricing-list">
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Up to 10 campaigns</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">5 donation devices</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Advanced analytics</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Priority support</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Custom branding</li>
                        </ul>
                    </div>
                    <div class="pricing-button">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="pp-pricing-main-item">
                    <div class="pp-pricing-card-item">
                        <div class="pp-pricing-header">
                            <h5>Enterprise</h5>
                            <h2>Custom<span>/ Pricing</span></h2>
                        </div>
                        <ul class="pp-pricing-list">
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Unlimited campaigns</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Unlimited devices</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Custom analytics</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">24/7 dedicated support</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">API access</li>
                            <li><img src="{{ asset('marketing/assets/img/home-1/icon/cheak.svg') }}" alt="check">Custom integrations</li>
                        </ul>
                    </div>
                    <div class="pricing-button">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn">Contact Sales</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="pp-offer-section section-padding fix section-bg">
    <div class="container">
        <div class="pp-section-title text-center">
            <span class="pp-sub-title wow fadeInUp">
                PRICING FAQ
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               Common Questions
            </h2>
         </div>
         <div class="row justify-content-center">
             <div class="col-lg-8">
                 <div class="accordion" id="pricingFAQ">
                     <div class="accordion-item mb-3">
                         <h3 class="accordion-header">
                             <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                 Can I change plans later?
                             </button>
                         </h3>
                         <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFAQ">
                             <div class="accordion-body">
                                 Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate the charges.
                             </div>
                         </div>
                     </div>
                     <div class="accordion-item mb-3">
                         <h3 class="accordion-header">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                 Are there any setup fees?
                             </button>
                         </h3>
                         <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                             <div class="accordion-body">
                                 No, there are no setup fees. You only pay the monthly or annual subscription fee for your chosen plan.
                             </div>
                         </div>
                     </div>
                     <div class="accordion-item mb-3">
                         <h3 class="accordion-header">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                 What payment methods do you accept?
                             </button>
                         </h3>
                         <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                             <div class="accordion-body">
                                 We accept all major credit cards, SEPA direct debit, and bank transfers for annual plans.
                             </div>
                         </div>
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
                        Ready to Get Started?
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        Start your free trial today. No credit card required.
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Start Free Trial <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
