<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\OrganizationController as SuperAdminOrganization;
use App\Http\Controllers\Organization\DashboardController as OrgDashboard;
use App\Http\Controllers\Organization\ProfileController as OrgProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
});

// Organization Admin Routes
Route::prefix('organization')->name('organization.')->middleware(['auth', 'verified', 'org_admin'])->group(function () {
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

    // Donations
    Route::get('/donations', [\App\Http\Controllers\Organization\DonationController::class, 'index'])->name('donations.index');

    // Reports & Analytics
    Route::get('/reports', [\App\Http\Controllers\Organization\ReportingController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Organization\ReportingController::class, 'export'])->name('reports.export');

    // Billing & Subscription
    Route::get('/billing', [\App\Http\Controllers\Organization\SubscriptionController::class, 'index'])->name('billing.index');
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
