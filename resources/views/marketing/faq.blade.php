@extends('marketing.layouts.master')

@section('title', 'FAQ - Dayaa Digital Donations')
@section('meta_description', 'Find answers to frequently asked questions about Dayaa\'s digital donation platform, features, pricing, and support.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Frequently Asked Questions</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Find answers to common questions about Dayaa
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
                <h3 class="mb-4">General Questions</h3>
                <div class="accordion mb-5" id="generalFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                What is Dayaa?
                            </button>
                        </h4>
                        <div id="general1" class="accordion-collapse collapse show" data-bs-parent="#generalFAQ">
                            <div class="accordion-body">
                                Dayaa is a comprehensive digital donation management platform that helps nonprofits and charities accept, track, and manage donations through smart kiosk devices, mobile apps, and online campaigns. We provide the technology infrastructure needed to modernize your fundraising operations.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                How does Dayaa work?
                            </button>
                        </h4>
                        <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                            <div class="accordion-body">
                                Dayaa connects donation devices (kiosks, tablets, card readers) to your campaigns through our platform. You create campaigns with customized donation amounts and branding, deploy devices at your locations, and track all donations in real-time through our dashboard. Donors can give using contactless cards, mobile payments, or traditional card payments.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                Who can use Dayaa?
                            </button>
                        </h4>
                        <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalFAQ">
                            <div class="accordion-body">
                                Dayaa is designed for nonprofits, charities, religious organizations, educational institutions, and any organization that collects donations. Whether you're a small local charity or a large international NGO, our platform scales to meet your needs.
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">Technical Questions</h3>
                <div class="accordion mb-5" id="technicalFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tech1">
                                What devices are supported?
                            </button>
                        </h4>
                        <div id="tech1" class="accordion-collapse collapse show" data-bs-parent="#technicalFAQ">
                            <div class="accordion-body">
                                Dayaa supports a wide range of devices including Android tablets with NFC readers, dedicated donation kiosks, SumUp card readers, and standard payment terminals. We also provide our own branded kiosk hardware that can be purchased through our shop.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech2">
                                How do I set up a campaign?
                            </button>
                        </h4>
                        <div id="tech2" class="accordion-collapse collapse" data-bs-parent="#technicalFAQ">
                            <div class="accordion-body">
                                Campaign setup is simple with our five-step wizard. You'll provide basic information, configure donation amounts, set up payment methods, customize the visual appearance, and review your settings. The entire process takes just 5-10 minutes.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech3">
                                Is internet connectivity required?
                            </button>
                        </h4>
                        <div id="tech3" class="accordion-collapse collapse" data-bs-parent="#technicalFAQ">
                            <div class="accordion-body">
                                Yes, devices need internet connectivity to process payments and sync donation data. Both WiFi and mobile data connections are supported. Devices can briefly operate offline and will sync when connectivity is restored.
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">Payment & Security</h3>
                <div class="accordion mb-5" id="paymentFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#payment1">
                                What payment methods are supported?
                            </button>
                        </h4>
                        <div id="payment1" class="accordion-collapse collapse show" data-bs-parent="#paymentFAQ">
                            <div class="accordion-body">
                                We support contactless cards (NFC), chip & PIN cards, magnetic stripe cards, Apple Pay, Google Pay, and other mobile wallet payments through our integrated payment providers (SumUp, Stripe).
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment2">
                                Is my data secure?
                            </button>
                        </h4>
                        <div id="payment2" class="accordion-collapse collapse" data-bs-parent="#paymentFAQ">
                            <div class="accordion-body">
                                Yes, security is our top priority. All payment data is encrypted and processed through PCI-DSS compliant payment providers. We never store complete card numbers. Donor information is encrypted at rest and in transit. Our platform undergoes regular security audits.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment3">
                                When do I receive donations?
                            </button>
                        </h4>
                        <div id="payment3" class="accordion-collapse collapse" data-bs-parent="#paymentFAQ">
                            <div class="accordion-body">
                                Donation payout timing depends on your payment provider. With SumUp, funds are typically transferred within 2-3 business days. With Stripe, standard payouts occur within 7 days, but can be accelerated to next-day for established accounts.
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">Pricing & Plans</h3>
                <div class="accordion mb-5" id="pricingFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#price1">
                                Are there any transaction fees?
                            </button>
                        </h4>
                        <div id="price1" class="accordion-collapse collapse show" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                Dayaa does not charge transaction fees. However, payment processors (SumUp, Stripe) charge their standard rates, typically 1.39-2.9% per transaction depending on the payment method and your agreement with them.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price2">
                                Can I cancel my subscription?
                            </button>
                        </h4>
                        <div id="price2" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                Yes, you can cancel your subscription at any time. If you're on a monthly plan, cancellation takes effect at the end of your billing period. Annual plans can be canceled with a refund for unused months (minus a 15% administration fee).
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-4">Support</h3>
                <div class="accordion mb-5" id="supportFAQ">
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#support1">
                                What support options are available?
                            </button>
                        </h4>
                        <div id="support1" class="accordion-collapse collapse show" data-bs-parent="#supportFAQ">
                            <div class="accordion-body">
                                All plans include email support with response within 24 hours. Professional and Enterprise plans include priority support with faster response times. Enterprise customers get dedicated support and a direct phone line.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#support2">
                                Do you offer training?
                            </button>
                        </h4>
                        <div id="support2" class="accordion-collapse collapse" data-bs-parent="#supportFAQ">
                            <div class="accordion-body">
                                Yes, we provide comprehensive documentation, video tutorials, and webinars for all customers. Professional and Enterprise plans include personalized onboarding sessions and staff training.
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
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">
                        Still Have Questions?
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay=".5s">
                        Our team is here to help. Get in touch and we'll answer all your questions.
                    </p>
                    <div class="pp-cta-button">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Contact Us <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
