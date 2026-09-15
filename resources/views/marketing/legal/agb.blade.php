@extends('marketing.layouts.master')

@section('title', __('marketing.agb.meta_title'))
@section('meta_description', __('marketing.agb.meta_description'))

@section('content')

<!-- Page Banner / Hero Section -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.agb.hero_heading') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.agb.hero_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- AGB Content Section -->
<section class="pp-legal-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="pp-legal-content" style="background: #fff; padding: 60px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <div class="text-center mb-5" style="border-bottom: 3px solid #0F69F3; padding-bottom: 30px;">
                        <h2 style="font-size: 1.5rem; font-weight: 600; color: #0F69F3; margin-bottom: 10px;">{{ __('marketing.agb.company_name') }}</h2>
                        <p style="font-size: 1.1rem; color: #6B7280; margin: 0;">{{ __('marketing.agb.stand') }}</p>
                    </div>

                    <!-- § 1 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_1_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_1_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_1_p2') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_1_p3') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_1_p4') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_1_p5') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_1_p6') }}</p>
                        </div>
                    </div>

                    <!-- § 2 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_2_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_2_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_2_p2') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_2_p3') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_2_p4') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_2_p5') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_2_p6') }}</p>
                        </div>
                    </div>

                    <!-- § 3 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_3_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_3_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_3_p2') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_3_p3') }}</p>
                        </div>
                    </div>

                    <!-- § 4 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_4_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_4_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_4_p2') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_4_p3') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_4_p4') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_4_p5') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_4_p6') }}</p>
                        </div>
                    </div>

                    <!-- § 5 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_5_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_5_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_5_p2') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_5_p3') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_5_p4') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_5_p5') }}</p>
                        </div>
                    </div>

                    <!-- § 6 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_6_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_6_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_6_p2') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_6_p3') }}</p>
                        </div>
                    </div>

                    <!-- § 7 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_7_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_7_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_7_p2') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_7_p3') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_7_p4') }}</p>
                        </div>
                    </div>

                    <!-- § 8 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_8_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_8_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_8_p2') }}</p>
                            <p style="margin-bottom: 10px;">{{ __('marketing.agb.section_8_list_intro') }}</p>

                            <ul style="margin-bottom: 20px; padding-left: 22px;">
                                @foreach (__('marketing.agb.section_8_list') as $item)
                                    <li style="margin-bottom: 10px;">{{ $item }}</li>
                                @endforeach
                            </ul>

                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_8_p4') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_8_p5') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_8_p6') }}</p>
                        </div>
                    </div>

                    <!-- § 9 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_9_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">

                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #0F69F3; margin: 0 0 15px;">{{ __('marketing.agb.section_9_sub1_title') }}</h4>
                            <p style="margin-bottom: 15px;">{{ __('marketing.agb.section_9_sub1_p1') }}</p>
                            <p style="margin-bottom: 25px;">{{ __('marketing.agb.section_9_sub1_p2') }}</p>

                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #0F69F3; margin: 0 0 15px;">{{ __('marketing.agb.section_9_sub2_title') }}</h4>
                            <p style="margin-bottom: 15px;">{{ __('marketing.agb.section_9_sub2_p1') }}</p>
                            <p style="margin-bottom: 25px;">{{ __('marketing.agb.section_9_sub2_p2') }}</p>

                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #0F69F3; margin: 0 0 15px;">{{ __('marketing.agb.section_9_sub3_title') }}</h4>
                            <p style="margin-bottom: 15px;">{{ __('marketing.agb.section_9_sub3_p1') }}</p>
                            <p style="margin-bottom: 15px;">{{ __('marketing.agb.section_9_sub3_p2') }}</p>
                            <p style="margin-bottom: 25px;">{{ __('marketing.agb.section_9_sub3_p3') }}</p>

                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #0F69F3; margin: 0 0 15px;">{{ __('marketing.agb.section_9_sub4_title') }}</h4>
                            <p style="margin-bottom: 10px;">{{ __('marketing.agb.section_9_sub4_intro') }}</p>
                            <ul style="margin-bottom: 15px; padding-left: 22px;">
                                @foreach (__('marketing.agb.section_9_sub4_list') as $item)
                                    <li style="margin-bottom: 10px;">{{ $item }}</li>
                                @endforeach
                            </ul>
                            <p style="margin-bottom: 25px;">{{ __('marketing.agb.section_9_sub4_p_after') }}</p>

                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #0F69F3; margin: 0 0 15px;">{{ __('marketing.agb.section_9_sub5_title') }}</h4>
                            <p style="margin-bottom: 15px;">{{ __('marketing.agb.section_9_sub5_p1') }}</p>
                            <p style="margin-bottom: 25px;">{{ __('marketing.agb.section_9_sub5_p2') }}</p>

                            <h4 style="font-size: 1.2rem; font-weight: 700; color: #0F69F3; margin: 0 0 15px;">{{ __('marketing.agb.section_9_sub6_title') }}</h4>
                            <p style="margin-bottom: 15px;">{{ __('marketing.agb.section_9_sub6_p1') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_9_sub6_p2') }}</p>
                        </div>
                    </div>

                    <!-- § 10 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_10_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_10_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_10_p2') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_10_p3') }}</p>
                        </div>
                    </div>

                    <!-- § 11 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_11_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_11_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_11_p2') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_11_p3') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_11_p4') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_11_p5') }}</p>
                        </div>
                    </div>

                    <!-- § 12 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_12_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_12_p1') }}</p>
                            <p style="margin-bottom: 20px;">{{ __('marketing.agb.section_12_p2') }}</p>
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_12_p3') }}</p>
                        </div>
                    </div>

                    <!-- § 13 -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">{{ __('marketing.agb.section_13_title') }}</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 0;">{{ __('marketing.agb.section_13_p1') }}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-5 pt-4" style="border-top: 2px solid #E5E7EB;">
                        <p style="font-size: 1.1rem; font-weight: 600; color: #0F69F3; margin-bottom: 10px;">{{ __('marketing.agb.footer_company') }}</p>
                        <p style="font-size: 1rem; color: #6B7280; margin: 0;">{{ __('marketing.agb.footer_location') }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section (covers footer overlap) -->
<section class="pp-cta-section section-padding fix theme-bg">
    <div class="top-shape">
        <img src="{{ asset('marketing/assets/img/home-1/cta/bg.png') }}" alt="img">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="pp-cta-content">
                    <h2 class="wow fadeInUp mb-4" data-wow-delay=".3s" style="line-height: 1.4;">
                        {{ __('marketing.agb.cta_heading') }}
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        {{ __('marketing.agb.cta_text') }}
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.agb.cta_button') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
