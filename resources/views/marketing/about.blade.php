@extends('marketing.layouts.master')

@section('title', 'About Us - Dayaa Digital Donations')
@section('meta_description', 'Learn about Dayaa\'s mission to transform fundraising through innovative digital donation solutions for nonprofits and charities worldwide.')

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">About Dayaa</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Empowering organizations to make a bigger impact through digital donations
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
                            <span class="pp-sub-title wow fadeInUp">OUR MISSION</span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                Revolutionizing Digital Fundraising
                            </h2>
                        </div>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            Dayaa was founded with a simple yet powerful mission: to make fundraising easier, more efficient, and more impactful for organizations of all sizes. We believe that technology should empower nonprofits to focus on their mission rather than struggling with complicated donation systems.
                        </p>
                        <p class="pp-text wow fadeInUp" data-wow-delay=".5s">
                            Our platform combines cutting-edge payment technology with intuitive campaign management tools, enabling organizations to accept donations seamlessly across multiple channels and devices.
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
                OUR VALUES
                <span class="pp-style-2"></span>
            </span>
            <h2 class="wow fadeInUp" data-wow-delay=".3s">
               What Drives Us
            </h2>
         </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/01.svg') }}" alt="Innovation">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Innovation</h3>
                        <p>
                            We continuously develop new features and improve our platform to stay ahead of fundraising trends.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/02.svg') }}" alt="Simplicity">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Simplicity</h3>
                        <p>
                            Complex technology shouldn't mean complicated interfaces. We make powerful tools easy to use.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                <div class="pp-offer-box-item">
                    <div class="pp-offer-icon">
                        <img src="{{ asset('marketing/assets/img/home-1/icon/03.svg') }}" alt="Impact">
                    </div>
                    <div class="pp-offer-content">
                        <h3>Impact</h3>
                        <p>
                            Every feature we build is designed to help organizations maximize their fundraising results.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="pp-cta-section section-padding fix theme-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="pp-cta-content">
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">
                        Ready to Transform Your Fundraising?
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay=".5s">
                        Join hundreds of organizations already using Dayaa to maximize their donation impact.
                    </p>
                    <div class="pp-cta-button">
                        <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Get Started <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
