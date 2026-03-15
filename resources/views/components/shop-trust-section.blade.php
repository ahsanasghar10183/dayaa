<!-- Shop Trust CTA Section -->
<section class="pp-cta-section section-padding fix">
    <div class="top-shape">
        <img src="{{ asset('marketing/assets/img/home-1/cta/bg.png') }}" alt="img">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h2 class="text-white mb-3 wow fadeInUp" data-wow-delay=".2s">
                        {{ __('marketing.shop_trust.heading') }}
                    </h2>
                    <p class="text-white-50 wow fadeInUp" data-wow-delay=".3s" style="font-size: 17px;">
                        {{ __('marketing.shop_trust.subheading') }}
                    </p>
                </div>

                <!-- Trust Points Grid -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".3s">
                        <div class="text-center">
                            <i class="fa-solid fa-badge-check mb-3" style="font-size: 40px; color: white;"></i>
                            <h5 class="text-white mb-2">{{ __('marketing.shop_trust.official_hardware') }}</h5>
                            <p class="text-white-50 mb-0">{{ __('marketing.shop_trust.official_hardware_desc') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".4s">
                        <div class="text-center">
                            <i class="fa-solid fa-plug mb-3" style="font-size: 40px; color: white;"></i>
                            <h5 class="text-white mb-2">{{ __('marketing.shop_trust.integration') }}</h5>
                            <p class="text-white-50 mb-0">{{ __('marketing.shop_trust.integration_desc') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".5s">
                        <div class="text-center">
                            <i class="fa-solid fa-headset mb-3" style="font-size: 40px; color: white;"></i>
                            <h5 class="text-white mb-2">{{ __('marketing.shop_trust.support') }}</h5>
                            <p class="text-white-50 mb-0">{{ __('marketing.shop_trust.support_desc') }}</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="text-center wow fadeInUp" data-wow-delay=".6s">
                    <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn">
                        {{ __('marketing.nav.get_started') }} <i class="fa-solid fa-arrow-right-long"></i>
                    </a>
                    <a href="{{ route('marketing.contact') }}" class="pp-theme-btn pp-style-2 ms-3">
                        {{ __('marketing.contact.title') }} <i class="fa-solid fa-arrow-right-long"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
