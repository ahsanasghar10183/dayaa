<?php
/**
 * Batch Translation Script
 * Translates all remaining view files to use Laravel translation system
 */

$translations = [
    // Pricing page translations
    [
        'file' => 'resources/views/marketing/pricing.blade.php',
        'replacements' => [
            ["@section('title', 'Pricing - Dayaa Digital Donations')", "@section('title', __('marketing.pricing.title') . ' - Dayaa')"],
            ["<h1 class=\"wow fadeInUp\" data-wow-delay=\".3s\">Pricing Plans</h1>", "<h1 class=\"wow fadeInUp\" data-wow-delay=\".3s\">{{ __('marketing.pricing.title') }}</h1>"],
            ["Choose the perfect plan for your organization", "{{ __('marketing.pricing.page_subtitle') }}"],
            ["/ Per Month</span>", "/ {{ __('marketing.pricing.per_month') }}</span>"],
            ["Or €", "{{ __('marketing.pricing.or') }} €"],
            ["/year (save 17%)", "/{{ __('marketing.pricing.year') }} ({{ __('marketing.pricing.save_17') }})"],
            ["Plan Limits:", "{{ __('marketing.pricing.plan_limits') }}:"],
            ["Up to", "{{ __('marketing.pricing.up_to') }}"],
            ["campaigns", "{{ __('marketing.pricing.campaigns') }}"],
            ["devices", "{{ __('marketing.pricing.devices') }}"],
            ["users", "{{ __('marketing.pricing.users') }}"],
            [">Get Started<", ">{{ __('marketing.pricing.get_started') }}<"],
            [">Contact Sales<", ">{{ __('marketing.pricing.contact_sales') }}<"],
            ["PRICING FAQ", "{{ __('marketing.pricing.faq_subtitle') }}"],
            ["Common Questions", "{{ __('marketing.pricing.faq_title') }}"],
            ["Ready to Get Started?", "{{ __('marketing.pricing.cta_title') }}"],
            ["Start your free trial today. No credit card required.", "{{ __('marketing.pricing.cta_text') }}"],
            [">Start Free Trial <", ">{{ __('marketing.pricing.cta_button') }} <"],
        ]
    ],

    // Contact page translations
    [
        'file' => 'resources/views/marketing/contact.blade.php',
        'replacements' => [
            ["@section('title', 'Contact Us - Dayaa Digital Donations')", "@section('title', __('marketing.contact.title') . ' - Dayaa')"],
            ["<h1 class=\"wow fadeInUp\" data-wow-delay=\".3s\">Contact Us</h1>", "<h1 class=\"wow fadeInUp\" data-wow-delay=\".3s\">{{ __('marketing.contact.title') }}</h1>"],
            ["We're here to help. Get in touch with our team.", "{{ __('marketing.contact.subtitle') }}"],
            ["<h3>Email Us</h3>", "<h3>{{ __('marketing.contact.email_us') }}</h3>"],
            ["For general inquiries:", "{{ __('marketing.contact.general_inquiries') }}:"],
            ["For support:", "{{ __('marketing.contact.for_support') }}:"],
            ["<h3>Call Us</h3>", "<h3>{{ __('marketing.contact.call_us') }}</h3>"],
            ["Monday - Friday", "{{ __('marketing.contact.business_hours') }}"],
            ["<h3>Visit Us</h3>", "<h3>{{ __('marketing.contact.visit_us') }}</h3>"],
            ["<span class=\"pp-sub-title wow fadeInUp\">GET IN TOUCH</span>", "<span class=\"pp-sub-title wow fadeInUp\">{{ __('marketing.contact.get_in_touch') }}</span>"],
            ["<h2 class=\"wow fadeInUp\" data-wow-delay=\".3s\">Send Us a Message</h2>", "<h2 class=\"wow fadeInUp\" data-wow-delay=\".3s\">{{ __('marketing.contact.send_message') }}</h2>"],
            ["<label for=\"name\" class=\"form-label\">Your Name *</label>", "<label for=\"name\" class=\"form-label\">{{ __('marketing.contact.your_name') }} *</label>"],
            ["<label for=\"email\" class=\"form-label\">Your Email *</label>", "<label for=\"email\" class=\"form-label\">{{ __('marketing.contact.your_email') }} *</label>"],
            ["<label for=\"phone\" class=\"form-label\">Phone Number</label>", "<label for=\"phone\" class=\"form-label\">{{ __('marketing.contact.phone_number') }}</label>"],
            ["<label for=\"subject\" class=\"form-label\">Subject *</label>", "<label for=\"subject\" class=\"form-label\">{{ __('marketing.contact.subject') }} *</label>"],
            ["<label for=\"message\" class=\"form-label\">Your Message *</label>", "<label for=\"message\" class=\"form-label\">{{ __('marketing.contact.your_message') }} *</label>"],
            ["Send Message <", "{{ __('marketing.contact.send') }} <"],
        ]
    ],

    // FAQ page translations
    [
        'file' => 'resources/views/marketing/faq.blade.php',
        'replacements' => [
            ["@section('title', 'FAQ - Dayaa Digital Donations')", "@section('title', __('marketing.faq.title') . ' - Dayaa')"],
            ["<h1 class=\"wow fadeInUp\" data-wow-delay=\".3s\">Frequently Asked Questions</h1>", "<h1 class=\"wow fadeInUp\" data-wow-delay=\".3s\">{{ __('marketing.faq.title') }}</h1>"],
            ["Find answers to common questions about Dayaa", "{{ __('marketing.faq.subtitle') }}"],
            ["<h3 class=\"mb-4\">General Questions</h3>", "<h3 class=\"mb-4\">{{ __('marketing.faq.general') }}</h3>"],
            ["<h3 class=\"mb-4\">Technical Questions</h3>", "<h3 class=\"mb-4\">{{ __('marketing.faq.technical') }}</h3>"],
            ["<h3 class=\"mb-4\">Payment & Security</h3>", "<h3 class=\"mb-4\">{{ __('marketing.faq.payment_security') }}</h3>"],
            ["<h3 class=\"mb-4\">Pricing & Plans</h3>", "<h3 class=\"mb-4\">{{ __('marketing.faq.pricing_plans') }}</h3>"],
            ["<h3 class=\"mb-4\">Support</h3>", "<h3 class=\"mb-4\">{{ __('marketing.faq.support') }}</h3>"],
            ["Still Have Questions?", "{{ __('marketing.faq.still_have_questions') }}"],
            ["Our team is here to help. Get in touch and we'll answer all your questions.", "{{ __('marketing.faq.team_help_text') }}"],
            [">Contact Us <", ">{{ __('marketing.faq.contact_us') }} <"],
        ]
    ],
];

echo "Starting batch translation...\n\n";

foreach ($translations as $translation) {
    $file = $translation['file'];
    echo "Processing: $file\n";

    if (!file_exists($file)) {
        echo "  ❌ File not found\n\n";
        continue;
    }

    $content = file_get_contents($file);
    $originalContent = $content;

    foreach ($translation['replacements'] as $replacement) {
        [$search, $replace] = $replacement;
        $content = str_replace($search, $replace, $content);
    }

    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "  ✅ Translated\n\n";
    } else {
        echo "  ⚠️  No changes made\n\n";
    }
}

echo "Batch translation complete!\n";
