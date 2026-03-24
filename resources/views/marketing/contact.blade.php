@extends('marketing.layouts.master')

@section('title', __('marketing.contact.title') . ' - Dayaa')
@section('meta_description', __('marketing.contact.page_subtitle'))

@section('content')

<!-- Page Banner -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.contact.title') }}</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    {{ __('marketing.contact.page_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding fix">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="pp-offer-box-item text-center h-100">
                    <div class="pp-offer-icon mx-auto" style="display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-envelope" style="font-size: 40px; color: #ffffff;"></i>
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.contact.email_us') }}</h3>
                        <p class="mb-2">{{ __('marketing.contact.email_general') }}</p>
                        <a href="mailto:info@dayaatech.de" class="d-block mb-2">info@dayaatech.de</a>
                        <p class="mb-2">{{ __('marketing.contact.email_support_label') }}</p>
                        <a href="mailto:support@dayaatech.de" class="d-block">support@dayaatech.de</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="pp-offer-box-item text-center h-100">
                    <div class="pp-offer-icon mx-auto" style="display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-phone" style="font-size: 40px; color: #ffffff;"></i>
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.contact.call_us') }}</h3>
                        <p class="mb-2">{!! __('marketing.contact.hours') !!}</p>
                        <a href="tel:+491234567890" class="d-block h4">+49 123 456 7890</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="pp-offer-box-item text-center h-100">
                    <div class="pp-offer-icon mx-auto" style="display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-map-marker-alt" style="font-size: 40px; color: #ffffff;"></i>
                    </div>
                    <div class="pp-offer-content">
                        <h3>{{ __('marketing.contact.visit_us') }}</h3>
                        <p class="mb-0">Dayaa Technologies GmbH<br>
                        Berliner Straße 123<br>
                        10115 Berlin<br>
                        Germany</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="section-padding fix section-bg" style="margin-bottom: 120px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="pp-section-title text-center">
                    <span class="pp-sub-title wow fadeInUp">{{ __('marketing.contact.get_in_touch') }}</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ __('marketing.contact.send_message_title') }}</h2>
                </div>

                @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('marketing.contact.submit') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('marketing.contact.name') }} {{ __('marketing.contact.required') }}</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('marketing.contact.email') }} {{ __('marketing.contact.required') }}</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">{{ __('marketing.contact.phone') }}</label>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject" class="form-label">{{ __('marketing.contact.subject') }} {{ __('marketing.contact.required') }}</label>
                                <input type="text"
                                       class="form-control @error('subject') is-invalid @enderror"
                                       id="subject"
                                       name="subject"
                                       value="{{ old('subject') }}"
                                       required>
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="message" class="form-label">{{ __('marketing.contact.message') }} {{ __('marketing.contact.required') }}</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          name="message"
                                          rows="6"
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="pp-theme-btn">
                                {{ __('marketing.contact.send') }} <i class="fa-solid fa-arrow-right-long"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.contact-form .form-control {
    padding: 12px 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
}

.contact-form .form-control:focus {
    border-color: #0F69F3;
    box-shadow: 0 0 0 0.2rem rgba(15, 105, 243, 0.15);
}

.contact-form .form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.contact-form textarea.form-control {
    resize: vertical;
}
</style>
@endpush
