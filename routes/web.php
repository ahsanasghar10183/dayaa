<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\OrganizationController as SuperAdminOrganization;
use App\Http\Controllers\Organization\DashboardController as OrgDashboard;
use App\Http\Controllers\Organization\ProfileController as OrgProfile;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\Marketing\ShopController;
use App\Http\Controllers\Marketing\CartController;
use App\Http\Controllers\Marketing\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Marketing Website Routes (dayaatech.de)
|--------------------------------------------------------------------------
*/

Route::domain(config('app.marketing_domain'))->name('marketing.')->group(function () {
    // Language Switcher (for marketing site)
    Route::get('/language/{locale}', function (string $locale) {
        $supported = ['en', 'de'];
        if (in_array($locale, $supported)) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    })->name('language.switch');

    // Homepage
    Route::get('/', [MarketingController::class, 'home'])->name('home');

    // About & Info Pages
    Route::get('/about', [MarketingController::class, 'about'])->name('about');
    Route::get('/features', [MarketingController::class, 'features'])->name('features');
    Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('/faq', [MarketingController::class, 'faq'])->name('faq');

    // Contact
    Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');
    Route::post('/contact', [MarketingController::class, 'submitContact'])->name('contact.submit');

    // Legal Pages
    Route::get('/agb', [MarketingController::class, 'agb'])->name('agb');
    Route::get('/impressum', [MarketingController::class, 'impressum'])->name('impressum');
    Route::get('/datenschutz', [MarketingController::class, 'privacy'])->name('privacy');

    // Shop
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product');
    });

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');

        // Debug route to test CSRF
        Route::get('/test-csrf', function() {
            return response()->json([
                'session_id' => session()->getId(),
                'csrf_token' => csrf_token(),
                'session_domain' => config('session.domain'),
                'cookie_domain' => config('session.domain'),
                'has_session' => session()->isStarted(),
            ]);
        })->name('test-csrf');

        // Debug POST to verify CSRF
        Route::post('/test-csrf-post', function(\Illuminate\Http\Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'CSRF token is valid!',
                'received_token' => $request->header('X-CSRF-TOKEN'),
                'session_token' => csrf_token(),
                'tokens_match' => $request->header('X-CSRF-TOKEN') === csrf_token(),
                'session_id_from_request' => session()->getId(),
                'cookies_received' => array_keys($request->cookies->all()),
                'session_cookie_name' => config('session.cookie'),
                'has_session_cookie' => $request->hasCookie(config('session.cookie')),
            ]);
        })->name('test-csrf-post');

        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::post('/buy-now/{product}', [CartController::class, 'buyNow'])->name('buy-now');
        Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'count'])->name('count');
        Route::get('/data', [CartController::class, 'data'])->name('data');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');

        // Stripe payment callbacks
        Route::get('/stripe/success', [CheckoutController::class, 'stripeSuccess'])->name('stripe.success');
        Route::get('/stripe/cancel', [CheckoutController::class, 'stripeCancel'])->name('stripe.cancel');
    });

    // Stripe Webhook (must be excluded from CSRF protection)
    Route::post('/webhook/stripe', [\App\Http\Controllers\Marketing\StripeWebhookController::class, 'handleWebhook'])->name('webhook.stripe');

    // Demo/Trial Signup - Redirects to platform
    Route::get('/get-started', function () {
        return redirect('https://' . config('app.platform_domain') . '/register');
    })->name('get-started');
});

/*
|--------------------------------------------------------------------------
| Platform Routes (software.dayaatech.de)
|--------------------------------------------------------------------------
*/

Route::domain(config('app.platform_domain'))->group(function () {

Route::get('/', function () {
    // If user is authenticated, redirect to dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Otherwise, redirect to login page
    return redirect()->route('login');
});

// Main dashboard route - redirects based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isSuperAdmin()) {
        return redirect()->route('super-admin.dashboard');
    }
    return redirect()->route('organization.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Super Admin Routes
Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'verified', 'super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

    // Organization Management
    Route::get('/organizations', [SuperAdminOrganization::class, 'index'])->name('organizations.index');
    Route::get('/organizations/{organization}', [SuperAdminOrganization::class, 'show'])->name('organizations.show');
    Route::post('/organizations/{organization}/approve', [SuperAdminOrganization::class, 'approve'])->name('organizations.approve');
    Route::post('/organizations/{organization}/reject', [SuperAdminOrganization::class, 'reject'])->name('organizations.reject');
    Route::post('/organizations/{organization}/suspend', [SuperAdminOrganization::class, 'suspend'])->name('organizations.suspend');
    Route::post('/organizations/{organization}/reactivate', [SuperAdminOrganization::class, 'reactivate'])->name('organizations.reactivate');
    Route::delete('/organizations/{organization}', [SuperAdminOrganization::class, 'destroy'])->name('organizations.destroy');

    // Shop Management
    Route::prefix('shop')->name('shop.')->group(function () {
        // Products
        Route::resource('products', \App\Http\Controllers\SuperAdmin\ShopProductController::class);
        Route::post('products/{product}/toggle-status', [\App\Http\Controllers\SuperAdmin\ShopProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::patch('products/{product}/images/{image}/set-primary', [\App\Http\Controllers\SuperAdmin\ShopProductController::class, 'setPrimaryImage'])->name('products.set-primary-image');

        // Product Variations
        Route::prefix('products/{product}/variations')->name('products.variations.')->group(function () {
            Route::post('/', [\App\Http\Controllers\SuperAdmin\ProductVariationController::class, 'store'])->name('store');
            Route::put('/{variation}', [\App\Http\Controllers\SuperAdmin\ProductVariationController::class, 'update'])->name('update');
            Route::delete('/{variation}', [\App\Http\Controllers\SuperAdmin\ProductVariationController::class, 'destroy'])->name('destroy');
            Route::post('/{variation}/toggle-status', [\App\Http\Controllers\SuperAdmin\ProductVariationController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/{variation}/images', [\App\Http\Controllers\SuperAdmin\ProductVariationController::class, 'getImages'])->name('get-images');
            Route::delete('/{variation}/images/{image}', [\App\Http\Controllers\SuperAdmin\ProductVariationController::class, 'deleteImage'])->name('delete-image');
        });

        // Orders
        Route::resource('orders', \App\Http\Controllers\SuperAdmin\ShopOrderController::class)->except(['create', 'store', 'edit']);
        Route::post('orders/{order}/update-status', [\App\Http\Controllers\SuperAdmin\ShopOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/update-payment', [\App\Http\Controllers\SuperAdmin\ShopOrderController::class, 'updatePayment'])->name('orders.update-payment');
    });
});

// Organization Admin Routes
Route::prefix('organization')->name('organization.')->middleware(['auth', 'verified', 'org_admin', 'ensureSubscribed'])->group(function () {
    Route::get('/dashboard', [OrgDashboard::class, 'index'])->name('dashboard');

    // Organization Profile
    Route::get('/profile', [OrgProfile::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [OrgProfile::class, 'create'])->name('profile.create');
    Route::post('/profile', [OrgProfile::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [OrgProfile::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [OrgProfile::class, 'update'])->name('profile.update');

    // Campaign Wizard (New Multi-Step Campaign Creation)
    Route::prefix('campaigns/wizard')->name('campaigns.wizard.')->group(function () {
        Route::get('/step-1', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step1'])->name('step1');
        Route::post('/step-1', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step1Post'])->name('step1.post');
        Route::get('/step-2', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step2'])->name('step2');
        Route::post('/step-2', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step2Post'])->name('step2.post');
        Route::get('/step-3', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step3'])->name('step3');
        Route::post('/step-3', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step3Post'])->name('step3.post');
        Route::get('/step-4', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step4'])->name('step4');
        Route::post('/step-4', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step4Post'])->name('step4.post');
        Route::get('/step-5', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'step5'])->name('step5');
        Route::post('/finish', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'finish'])->name('finish');
        Route::get('/reset', [\App\Http\Controllers\Organization\CampaignWizardController::class, 'reset'])->name('reset');
    });

    // Campaigns
    Route::resource('campaigns', \App\Http\Controllers\Organization\CampaignController::class);
    Route::post('/campaigns/{campaign}/duplicate', [\App\Http\Controllers\Organization\CampaignController::class, 'duplicate'])->name('campaigns.duplicate');

    // Devices
    Route::resource('devices', \App\Http\Controllers\Organization\DeviceController::class);
    Route::post('devices/{device}/regenerate-pin', [\App\Http\Controllers\Organization\DeviceController::class, 'regeneratePin'])->name('devices.regenerate-pin');

    // Donations
    Route::get('/donations', [\App\Http\Controllers\Organization\DonationController::class, 'index'])->name('donations.index');

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Organization\AnalyticsController::class, 'index'])->name('analytics.index');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Organization\ReportingController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Organization\ReportingController::class, 'export'])->name('reports.export');

    // Billing & Subscription
    Route::get('/billing', [\App\Http\Controllers\Organization\SubscriptionController::class, 'index'])->name('billing.index');
    Route::get('/billing/create', [\App\Http\Controllers\Organization\SubscriptionController::class, 'create'])->name('billing.create');
    Route::post('/billing', [\App\Http\Controllers\Organization\SubscriptionController::class, 'store'])->name('billing.store');
    Route::get('/billing/plans', [\App\Http\Controllers\Organization\SubscriptionController::class, 'plans'])->name('billing.plans');
    Route::post('/billing/plan', [\App\Http\Controllers\Organization\SubscriptionController::class, 'changePlan'])->name('billing.change-plan');

    // Status pages
    Route::get('/pending', function () {
        return view('organization.status.pending');
    })->name('pending');

    Route::get('/rejected', function () {
        return view('organization.status.rejected');
    })->name('rejected');

    Route::get('/suspended', function () {
        return view('organization.status.suspended');
    })->name('suspended');
});

// Language Switcher (accessible to all)
Route::get('/language/{locale}', function (string $locale) {
    $supported = ['en', 'de'];
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
    }
    return redirect()->back()->withInput();
})->name('language.switch');

// User Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Kiosk Routes (No authentication required)
Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/pair', [\App\Http\Controllers\KioskController::class, 'pair'])->name('pair');
    Route::post('/pair', [\App\Http\Controllers\KioskController::class, 'processPair'])->name('process-pair');
    Route::get('/display', [\App\Http\Controllers\KioskController::class, 'display'])->name('display');
    Route::get('/get-campaign', [\App\Http\Controllers\KioskController::class, 'getCampaign'])->name('get-campaign');
    Route::post('/heartbeat', [\App\Http\Controllers\KioskController::class, 'heartbeat'])->name('heartbeat');
    Route::post('/unpair', [\App\Http\Controllers\KioskController::class, 'unpair'])->name('unpair');

    // Donation Flow
    Route::post('/initiate-payment', [\App\Http\Controllers\KioskController::class, 'initiatePayment'])->name('initiate-payment');
    Route::get('/payment-status/{donationId}', [\App\Http\Controllers\KioskController::class, 'paymentStatus'])->name('payment-status');
    Route::get('/thankyou', [\App\Http\Controllers\KioskController::class, 'thankYou'])->name('thankyou');

    // SumUp Webhook (excluded from CSRF)
    Route::post('/sumup-webhook', [\App\Http\Controllers\KioskController::class, 'sumupWebhook'])->name('sumup-webhook');
});

require __DIR__.'/auth.php';

}); // End Platform Domain Group
