@extends('marketing.layouts.master')

@section('title', 'Datenschutzerklärung - Dayaa Tech Solution UG')
@section('meta_description', 'Datenschutzerklärung der Dayaa Tech Solution UG (haftungsbeschränkt) - Informationen zur Datenverarbeitung.')

@section('content')

<!-- Page Banner / Hero Section -->
<section class="pp-hero-section pp-hero-1 fix" style="padding: 120px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Datenschutzerklärung</h1>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Informationen zur Datenverarbeitung gemäß DSGVO
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Policy Content Section -->
<section class="pp-legal-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="pp-legal-content" style="background: #fff; padding: 60px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">

                    <!-- Placeholder Content -->
                    <div class="alert alert-info" style="background: #EFF6FF; border-left: 4px solid #0F69F3; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                        <h3 style="font-size: 1.5rem; font-weight: 600; color: #0F69F3; margin-bottom: 15px;">
                            <i class="fas fa-info-circle"></i> In Vorbereitung
                        </h3>
                        <p style="font-size: 1.1rem; color: #374151; line-height: 1.8; margin: 0;">
                            Die Datenschutzerklärung wird in Kürze veröffentlicht. Diese Seite befindet sich derzeit in Bearbeitung und wird zeitnah mit allen relevanten Informationen zur Datenverarbeitung gemäß DSGVO aktualisiert.
                        </p>
                    </div>

                    <!-- Contact Information -->
                    <div class="legal-section mb-5">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: #fff; background: #0F69F3; padding: 15px 25px; border-radius: 8px; margin-bottom: 25px;">Kontakt zum Datenschutz</h3>
                        <div style="line-height: 1.9; color: #374151; font-size: 1.05rem;">
                            <p style="margin-bottom: 15px;">Bei Fragen zum Datenschutz können Sie sich jederzeit an uns wenden:</p>
                            <p style="margin-bottom: 10px;"><strong style="color: #0F69F3;">dayaa tech solution UG (haftungsbeschränkt)</strong></p>
                            <p style="margin-bottom: 10px;">Ludwigstraße 80</p>
                            <p style="margin-bottom: 20px;">63263 Neu-Isenburg</p>
                            <p style="margin-bottom: 0;"><strong>E-Mail:</strong> <a href="mailto:info@dayaatech.de" style="color: #0F69F3; text-decoration: none;">info@dayaatech.de</a></p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-5 pt-4" style="border-top: 2px solid #E5E7EB;">
                        <p style="font-size: 1rem; color: #6B7280; margin: 0;">Stand: März 2026</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section (covers footer overlap) -->
<section class="pp-cta-section section-padding fix theme-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="pp-cta-content">
                    <h2 class="wow fadeInUp mb-4" data-wow-delay=".3s" style="line-height: 1.4;">
                        Fragen zum Datenschutz?
                    </h2>
                    <p class="wow fadeInUp mb-4" data-wow-delay=".5s" style="line-height: 1.8; font-size: 17px;">
                        Ihre Privatsphäre ist uns wichtig. Bei Fragen zur Datenverarbeitung oder zum Datenschutz stehen wir Ihnen gerne zur Verfügung.
                    </p>
                    <div class="pp-cta-button mt-4">
                        <a href="{{ route('marketing.contact') }}" class="pp-theme-btn wow fadeInUp" data-wow-delay=".3s">Kontaktieren Sie uns <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
