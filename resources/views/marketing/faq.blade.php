@extends('marketing.layouts.master')

@section('title', __('marketing.faq.title') . ' - Dayaa')
@section('meta_description', __('marketing.faq.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.faq.title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.faq.page_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section-padding fix">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="mb-4">{{ __('marketing.faq.general') }}</h3>
                <div class="accordion mb-5" id="generalFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                {{ __('marketing.faq.general_q1') }}
                            </button>
                        </h4>
                        <div id="general1" class="accordion-collapse collapse show" data-bs-parent="#generalFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.general_a1') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                {{ __('marketing.faq.general_q2') }}
                            </button>
                        </h4>
                        <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.general_a2') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                {{ __('marketing.faq.general_q3') }}
                            </button>
                        </h4>
                        <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.general_a3') }}
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">{{ __('marketing.faq.technical') }}</h3>
                <div class="accordion mb-5" id="technicalFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tech1">
                                {{ __('marketing.faq.tech_q1') }}
                            </button>
                        </h4>
                        <div id="tech1" class="accordion-collapse collapse show" data-bs-parent="#technicalFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.tech_a1') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech2">
                                {{ __('marketing.faq.tech_q2') }}
                            </button>
                        </h4>
                        <div id="tech2" class="accordion-collapse collapse" data-bs-parent="#technicalFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.tech_a2') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech3">
                                {{ __('marketing.faq.tech_q3') }}
                            </button>
                        </h4>
                        <div id="tech3" class="accordion-collapse collapse" data-bs-parent="#technicalFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.tech_a3') }}
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">{{ __('marketing.faq.payment_security') }}</h3>
                <div class="accordion mb-5" id="paymentFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#payment1">
                                {{ __('marketing.faq.payment_q1') }}
                            </button>
                        </h4>
                        <div id="payment1" class="accordion-collapse collapse show" data-bs-parent="#paymentFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.payment_a1') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment2">
                                {{ __('marketing.faq.payment_q2') }}
                            </button>
                        </h4>
                        <div id="payment2" class="accordion-collapse collapse" data-bs-parent="#paymentFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.payment_a2') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment3">
                                {{ __('marketing.faq.payment_q3') }}
                            </button>
                        </h4>
                        <div id="payment3" class="accordion-collapse collapse" data-bs-parent="#paymentFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.payment_a3') }}
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">{{ __('marketing.faq.pricing_plans') }}</h3>
                <div class="accordion mb-5" id="pricingFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#price1">
                                {{ __('marketing.faq.pricing_q1') }}
                            </button>
                        </h4>
                        <div id="price1" class="accordion-collapse collapse show" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.pricing_a1') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price2">
                                {{ __('marketing.faq.pricing_q2') }}
                            </button>
                        </h4>
                        <div id="price2" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.pricing_a2') }}
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">{{ __('marketing.faq.support') }}</h3>
                <div class="accordion mb-5" id="supportFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#support1">
                                {{ __('marketing.faq.support_q1') }}
                            </button>
                        </h4>
                        <div id="support1" class="accordion-collapse collapse show" data-bs-parent="#supportFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.support_a1') }}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#support2">
                                {{ __('marketing.faq.support_q2') }}
                            </button>
                        </h4>
                        <div id="support2" class="accordion-collapse collapse" data-bs-parent="#supportFAQ">
                            <div class="accordion-body">
                                {{ __('marketing.faq.support_a2') }}
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
                        {{ __('marketing.faq.cta_title') }}
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        {{ __('marketing.faq.cta_text') }}
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.faq.cta_button') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
