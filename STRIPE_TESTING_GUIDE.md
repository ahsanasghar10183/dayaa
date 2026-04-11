# 🧪 STRIPE TEST MODE - Complete Testing Guide

## 📋 Overview

This guide will walk you through setting up and testing the DAYAA subscription system using **Stripe Test Mode** - no real money involved, completely safe for testing!

---

## ✅ Prerequisites

Before starting, make sure you have:
- [ ] DAYAA application running locally (`php artisan serve`)
- [ ] Database set up and running
- [ ] A Stripe account (we'll create one if you don't have it)

---

## 🎯 STEP 1: Create Stripe Account (Test Mode)

### 1.1 Sign Up for Stripe

1. Go to **https://stripe.com**
2. Click **"Start now"** or **"Sign in"**
3. Create account with your email
4. **Important:** You'll automatically start in **Test Mode** (good for us!)

### 1.2 Verify You're in Test Mode

1. Look at the top-left corner of Stripe Dashboard
2. You should see a toggle that says **"Test mode"** with an orange indicator
3. If it says "Live mode", click it to switch to "Test mode"

**✅ You're now in Test Mode - perfect for testing!**

---

## 🎯 STEP 2: Get Your Stripe Test API Keys

### 2.1 Find API Keys

1. In Stripe Dashboard, click **"Developers"** in the top menu
2. Click **"API keys"** in the left sidebar
3. You'll see two keys:
   - **Publishable key** (starts with `pk_test_...`)
   - **Secret key** (starts with `sk_test_...`)

### 2.2 Copy Your Keys

1. Click **"Reveal test key"** on the Secret key
2. Copy both keys to a notepad temporarily

**Example:**
```
Publishable key: pk_test_51Ab12CdEfGh34IjKlMnOpQrStUvWxYz...
Secret key: sk_test_51Ab12CdEfGh34IjKlMnOpQrStUvWxYz...
```

### 2.3 Add Keys to Your Laravel .env File

1. Open `/Users/apple/Herd/dayaa/.env`
2. Find or add these lines:

```env
STRIPE_KEY=pk_test_51Ab12CdEfGh34IjKlMnOpQrStUvWxYz...
STRIPE_SECRET=sk_test_51Ab12CdEfGh34IjKlMnOpQrStUvWxYz...
```

3. Replace with your actual keys
4. Save the file

**✅ API Keys configured!**

---

## 🎯 STEP 3: Create Subscription Products in Stripe

Now we'll create 8 products (one for each tier) in Stripe.

### 3.1 Create Tier 1 Product

1. In Stripe Dashboard, click **"Products"** in the left menu
2. Click **"+ Add product"** button (top right)
3. Fill in the form:

```
Product name: DAYAA Tier 1
Description: For organizations raising €1,000-€10,000 per year
```

4. Under **Pricing**, configure:
```
Pricing model: Standard pricing
Price: 10.00
Billing period: Monthly
Currency: EUR
```

5. Click **"Save product"**

6. **IMPORTANT:** After saving, you'll see a **Price ID** like `price_1Ab12CdEfGh...`
7. **Copy this Price ID** - we'll need it soon!

### 3.2 Repeat for All Other Tiers

Create 7 more products with these details:

**Tier 2:**
- Product name: `DAYAA Tier 2`
- Description: `For organizations raising €10,000-€30,000 per year`
- Price: `20.00 EUR/month`

**Tier 3:**
- Product name: `DAYAA Tier 3`
- Description: `For organizations raising €30,000-€60,000 per year`
- Price: `30.00 EUR/month`

**Tier 4:**
- Product name: `DAYAA Tier 4`
- Description: `For organizations raising €60,000-€100,000 per year`
- Price: `60.00 EUR/month`

**Tier 5:**
- Product name: `DAYAA Tier 5`
- Description: `For organizations raising €100,000-€160,000 per year`
- Price: `100.00 EUR/month`

**Tier 6:**
- Product name: `DAYAA Tier 6`
- Description: `For organizations raising €160,000-€240,000 per year`
- Price: `160.00 EUR/month`

**Tier 7:**
- Product name: `DAYAA Tier 7`
- Description: `For organizations raising €240,000-€320,000 per year`
- Price: `240.00 EUR/month`

**Tier 8:**
- Product name: `DAYAA Tier 8`
- Description: `For organizations raising €320,000+ per year`
- Price: `320.00 EUR/month`

### 3.3 Collect All Price IDs

After creating all 8 products, you should have 8 Price IDs. Create a list like this:

```
Tier 1: price_1Ab12CdEfGh...
Tier 2: price_1Bc34DeFgHi...
Tier 3: price_1Cd56EfGhIj...
Tier 4: price_1De78FgHiJk...
Tier 5: price_1Ef90GhIjKl...
Tier 6: price_1Fg12HiJkLm...
Tier 7: price_1Gh34IjKlMn...
Tier 8: price_1Hi56JkLmNo...
```

**✅ All products created!**

---

## 🎯 STEP 4: Update Your Database Seeder

### 4.1 Open the Seeder File

Open this file: `/Users/apple/Herd/dayaa/database/seeders/SubscriptionTierSeeder.php`

### 4.2 Replace Price IDs

Find the section with all tiers and replace the placeholder Price IDs with your real ones:

**Find this:**
```php
'stripe_price_id' => 'price_tier1_REPLACE_ME',
```

**Replace with:**
```php
'stripe_price_id' => 'price_1Ab12CdEfGh...',  // Your actual Tier 1 Price ID
```

**Do this for all 8 tiers!**

### 4.3 Save the File

After replacing all 8 Price IDs, save the file.

**✅ Seeder updated!**

---

## 🎯 STEP 5: Set Up Database

### 5.1 Run Migrations

Open Terminal and navigate to your project:

```bash
cd /Users/apple/Herd/dayaa
```

Run migrations:

```bash
php artisan migrate
```

**Expected output:**
```
Migrating: 2026_04_10_000001_update_subscriptions_for_stripe
Migrated:  2026_04_10_000001_update_subscriptions_for_stripe
Migrating: 2026_04_10_000002_update_tier_change_logs
Migrated:  2026_04_10_000002_update_tier_change_logs
```

### 5.2 Seed Subscription Tiers

```bash
php artisan db:seed --class=SubscriptionTierSeeder
```

**Expected output:**
```
Seeding: SubscriptionTierSeeder
Seeded: SubscriptionTierSeeder
```

### 5.3 Verify Tiers Were Created

```bash
php artisan tinker
```

Then run:
```php
\App\Models\SubscriptionTier::count();
```

**Expected:** Should return `9` (9 tiers)

Type `exit` to exit tinker.

**✅ Database ready!**

---

## 🎯 STEP 6: Set Up Stripe Webhooks (For Testing)

We'll use **Stripe CLI** for local webhook testing (easiest method).

### 6.1 Install Stripe CLI

**On macOS:**
```bash
brew install stripe/stripe-cli/stripe
```

**On Windows:**
Download from: https://github.com/stripe/stripe-cli/releases/latest

### 6.2 Login to Stripe CLI

```bash
stripe login
```

This will open your browser - click **"Allow access"**

### 6.3 Forward Webhooks to Your Local App

In a **new terminal window**, run:

```bash
stripe listen --forward-to http://localhost:8000/api/webhook/stripe
```

**Important:** Keep this terminal window open while testing!

You'll see output like:
```
> Ready! Your webhook signing secret is whsec_1234567890abcdef...
```

### 6.4 Copy the Webhook Secret

Copy the `whsec_...` secret from the output.

### 6.5 Add Webhook Secret to .env

Open `.env` and add:

```env
STRIPE_WEBHOOK_SECRET=whsec_1234567890abcdef...
```

Replace with your actual webhook secret.

**✅ Webhooks configured!**

---

## 🎯 STEP 7: Register Webhook Route

### 7.1 Open routes/api.php

Open: `/Users/apple/Herd/dayaa/routes/api.php`

### 7.2 Add Webhook Route

Add this at the bottom of the file:

```php
use App\Http\Controllers\StripeWebhookController;

// Stripe Webhooks (must be outside auth middleware)
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);
```

### 7.3 Save the File

**✅ Webhook route registered!**

---

## 🎯 STEP 8: Register Middleware

### 8.1 Open app/Http/Kernel.php

Open: `/Users/apple/Herd/dayaa/app/Http/Kernel.php`

### 8.2 Add Middleware Alias

Find the `$middlewareAliases` array (or `$routeMiddleware` in older Laravel) and add:

```php
protected $middlewareAliases = [
    // ... existing middleware
    'ensureSubscribed' => \App\Http\Middleware\EnsureSubscribed::class,
];
```

### 8.3 Save the File

**✅ Middleware registered!**

---

## 🎯 STEP 9: Update Routes (Apply Middleware)

### 9.1 Open routes/web.php

Open: `/Users/apple/Herd/dayaa/routes/web.php`

### 9.2 Find Organization Routes

Look for routes that start with `/organization/`

### 9.3 Apply Middleware

Make sure billing routes are accessible:

```php
// Billing routes (accessible without subscription)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/organization/billing', [\App\Http\Controllers\Organization\SubscriptionController::class, 'index'])
        ->name('organization.billing.index');

    Route::get('/organization/billing/create', [\App\Http\Controllers\Organization\SubscriptionController::class, 'create'])
        ->name('organization.billing.create');

    Route::post('/organization/billing', [\App\Http\Controllers\Organization\SubscriptionController::class, 'store'])
        ->name('organization.billing.store');

    Route::get('/organization/billing/plans', [\App\Http\Controllers\Organization\SubscriptionController::class, 'plans'])
        ->name('organization.billing.plans');
});

// Other organization routes (require active subscription)
Route::middleware(['auth', 'verified', 'ensureSubscribed'])->prefix('organization')->name('organization.')->group(function () {
    Route::get('/dashboard', [OrganizationController::class, 'dashboard'])->name('dashboard');
    // ... other organization routes
});
```

**✅ Routes configured!**

---

## 🎯 STEP 10: Clear Cache & Restart

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Restart your development server:

```bash
php artisan serve
```

**✅ Application ready for testing!**

---

## 🧪 STEP 11: Test the Subscription Flow

### 11.1 Create a Test Organization

1. Go to **http://localhost:8000**
2. Register a new account as an organization user
3. Complete organization profile

### 11.2 Admin Approval (Super Admin)

1. Login as super admin
2. Approve the organization

### 11.3 Test Subscription Blocking

1. Login as the organization user
2. You should be **redirected to `/organization/billing/create`**
3. You should see the message: "Please activate your subscription to access the platform."

**✅ Middleware is working!**

### 11.4 Test Payment Form

1. You should see the subscription setup page
2. Recommended tier is shown (Tier 1 by default)
3. Fill in billing details:
   - Use any name and address
   - Email: your test email

### 11.5 Test Stripe Payment

**Use Stripe Test Cards:**

**Successful Payment:**
- Card number: `4242 4242 4242 4242`
- Expiry: Any future date (e.g., `12/25`)
- CVC: Any 3 digits (e.g., `123`)
- ZIP: Any 5 digits (e.g., `12345`)

1. Enter the test card details
2. Check "I agree to terms"
3. Click **"Activate Subscription"**

### 11.6 Verify Success

After payment:
1. You should be redirected to dashboard
2. You should see success message: "Subscription activated successfully!"
3. Check Stripe Dashboard → Customers → You should see your test customer
4. Check Stripe Dashboard → Subscriptions → You should see active subscription

**✅ Subscription created successfully!**

---

## 🧪 STEP 12: Test Webhooks

### 12.1 Check Webhook Terminal

Go to the terminal window where `stripe listen` is running.

You should see webhook events:
```
2026-04-10 10:30:15  --> customer.created [evt_...]
2026-04-10 10:30:16  --> payment_method.attached [evt_...]
2026-04-10 10:30:17  --> customer.subscription.created [evt_...]
2026-04-10 10:30:18  --> invoice.created [evt_...]
2026-04-10 10:30:19  --> invoice.payment_succeeded [evt_...]
```

### 12.2 Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

You should see logs like:
```
[2026-04-10 10:30:17] local.INFO: Stripe webhook received {"event_type":"customer.subscription.created"}
[2026-04-10 10:30:19] local.INFO: Invoice payment succeeded {"subscription_id":1}
```

**✅ Webhooks working!**

---

## 🧪 STEP 13: Test Automatic Tier Changes

### 13.1 Create Test Donations

We'll manually create donations to test tier changes.

Open tinker:
```bash
php artisan tinker
```

Create donations:
```php
$org = \App\Models\Organization::first();

// Create €12,000 in donations (should trigger Tier 2)
for ($i = 0; $i < 12; $i++) {
    \App\Models\Donation::create([
        'organization_id' => $org->id,
        'campaign_id' => 1, // Assuming you have a campaign with ID 1
        'amount' => 1000,
        'currency' => 'EUR',
        'payment_status' => 'completed',
        'payment_method' => 'sumup',
        'receipt_number' => 'TEST-' . time() . '-' . $i,
        'created_at' => now()->subDays($i),
    ]);
}
```

### 13.2 Check Tier Change Logs

Still in tinker:
```php
\App\Models\TierChangeLog::latest()->first();
```

You should see:
- `from_tier_id`: 1 (Tier 1)
- `to_tier_id`: 2 (Tier 2)
- `status`: 'pending'
- `scheduled_for`: Next billing date
- `donation_total_12m`: 12000.00

### 13.3 Check Email Would Be Sent

Check logs:
```bash
grep "Tier change notification" storage/logs/laravel.log
```

**✅ Tier change scheduled!**

---

## 🧪 STEP 14: Test Applying Tier Changes

### 14.1 Manually Trigger the Job

```bash
php artisan tinker
```

Run:
```php
$tierChange = \App\Models\TierChangeLog::where('status', 'pending')->first();
$tierService = app(\App\Services\SubscriptionTierService::class);
$tierService->applyTierChange($tierChange);
```

### 14.2 Verify in Stripe

1. Go to Stripe Dashboard → Subscriptions
2. Click on your test subscription
3. You should see the price changed to Tier 2 (€20/month)
4. There should be a prorated invoice

### 14.3 Verify in Database

In tinker:
```php
$org = \App\Models\Organization::first();
$org->subscription->tier->name; // Should return "Tier 2"
```

**✅ Tier change applied successfully!**

---

## 🧪 STEP 15: Test Payment Failures

### 15.1 Create Another Test Customer

1. Create another organization account
2. Get to subscription page

### 15.2 Use Decline Test Card

Use this card number:
- Card: `4000 0000 0000 0002`
- This card will be **declined**

### 15.3 Verify Error Handling

1. You should see an error message
2. Payment should not go through
3. No subscription created

**✅ Error handling works!**

---

## 🧪 STEP 16: Test Other Test Cards

Stripe provides many test cards for different scenarios:

### Success Cards
```
4242 4242 4242 4242  - Visa (succeeds)
5555 5555 5555 4444  - Mastercard (succeeds)
```

### Decline Cards
```
4000 0000 0000 0002  - Generic decline
4000 0000 0000 9995  - Insufficient funds
4000 0000 0000 0069  - Expired card
```

### Authentication Required
```
4000 0025 0000 3155  - 3D Secure authentication required
```

Try different cards to test various scenarios!

---

## 📊 STEP 17: Monitor Your Tests

### 17.1 Stripe Dashboard

Monitor everything in Stripe Dashboard:
- **Customers** - See test customers created
- **Subscriptions** - See active subscriptions
- **Payments** - See test payments
- **Logs** - See all API requests
- **Webhooks** - See webhook deliveries

### 17.2 Laravel Logs

Monitor application logs:
```bash
tail -f storage/logs/laravel.log
```

### 17.3 Database

Check database records:
```bash
php artisan tinker
```

```php
// Check subscriptions
\App\Models\Subscription::with('tier')->get();

// Check tier changes
\App\Models\TierChangeLog::with(['fromTier', 'toTier'])->get();

// Check donations
\App\Models\Donation::where('payment_status', 'completed')->count();
```

---

## 🎯 Quick Testing Checklist

Use this checklist for each test session:

- [ ] Stripe CLI is running (`stripe listen`)
- [ ] Laravel server is running (`php artisan serve`)
- [ ] Test mode is enabled in Stripe Dashboard
- [ ] Test API keys are in `.env`
- [ ] Webhook secret is in `.env`
- [ ] Database is seeded with tiers
- [ ] Routes are registered
- [ ] Middleware is applied

---

## 🔧 Troubleshooting

### Issue: "Subscription not created"
**Check:**
1. Stripe API keys in `.env`
2. Laravel logs: `tail -f storage/logs/laravel.log`
3. Stripe Dashboard → Developers → Logs

### Issue: "Webhooks not receiving"
**Check:**
1. `stripe listen` is running
2. Webhook route is registered (`/api/webhook/stripe`)
3. Webhook secret in `.env` matches CLI output

### Issue: "Tier not changing after donations"
**Check:**
1. Donations have `payment_status = 'completed'`
2. DonationObserver is registered in `AppServiceProvider`
3. Check tier change logs: `\App\Models\TierChangeLog::all()`

### Issue: "Card declined"
**Solutions:**
1. Use test card `4242 4242 4242 4242`
2. Check Stripe Dashboard for error details
3. Try different test card

### Issue: "Redirected to billing even with subscription"
**Check:**
1. Subscription status is 'active'
2. Middleware is correctly configured
3. Clear cache: `php artisan cache:clear`

---

## 🎉 You're Done!

You now have a fully functional test environment for the DAYAA subscription system!

### What You Can Test:
- ✅ Subscription creation with Stripe
- ✅ Payment processing
- ✅ Webhook handling
- ✅ Automatic tier changes
- ✅ Email notifications
- ✅ Dashboard widgets
- ✅ Error handling

### Next Steps:
1. **Test all user flows** - Registration → Subscription → Usage
2. **Test tier changes** - Create donations to trigger upgrades
3. **Test webhooks** - Payment success, failure, cancellation
4. **Review emails** - Check notification templates
5. **When satisfied** - Move to production setup

---

## 📚 Useful Resources

- **Stripe Test Cards:** https://stripe.com/docs/testing
- **Stripe CLI Docs:** https://stripe.com/docs/stripe-cli
- **Webhook Testing:** https://stripe.com/docs/webhooks/test
- **Laravel Logs:** `/Users/apple/Herd/dayaa/storage/logs/laravel.log`

---

## 🆘 Need Help?

If you encounter issues:
1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check Stripe Dashboard → Developers → Logs
4. Verify all configuration steps were completed

---

**Happy Testing! 🚀**

Remember: You're in **TEST MODE** - no real money involved, test freely!
