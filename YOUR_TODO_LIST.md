# ✅ YOUR SIMPLE TODO LIST

## Everything is set up! Here's what YOU need to do:

---

## 🎯 STEP 1: Get Stripe Test API Keys (5 minutes)

### What to do:
1. Go to **https://stripe.com** and sign up (if you don't have an account)
2. Make sure you're in **Test Mode** (toggle in top-left corner)
3. Click **"Developers"** in the top menu
4. Click **"API keys"** in the left sidebar
5. You'll see two keys - copy them both:
   - **Publishable key** (starts with `pk_test_...`)
   - **Secret key** (starts with `sk_test_...`) - Click "Reveal test key" first

### Where to add them:
Open `/Users/apple/Herd/dayaa/.env` and add these lines:

```env
STRIPE_KEY=pk_test_YOUR_KEY_HERE
STRIPE_SECRET=sk_test_YOUR_KEY_HERE
```

**✅ Done!**

---

## 🎯 STEP 2: Create 8 Products in Stripe (10 minutes)

### What to do:
In Stripe Dashboard:
1. Click **"Products"** in the left menu
2. Click **"+ Add product"** button

Then create these 8 products (one by one):

### Product 1:
```
Name: DAYAA Tier 1
Description: For organizations raising €1,000-€10,000 per year
Price: 10.00 EUR
Billing: Monthly
```
After saving, copy the **Price ID** (looks like `price_1Ab12CdEfGh...`)

### Product 2:
```
Name: DAYAA Tier 2
Description: For organizations raising €10,000-€30,000 per year
Price: 20.00 EUR
Billing: Monthly
```
Copy the **Price ID**

### Product 3:
```
Name: DAYAA Tier 3
Description: For organizations raising €30,000-€60,000 per year
Price: 30.00 EUR
Billing: Monthly
```
Copy the **Price ID**

### Product 4:
```
Name: DAYAA Tier 4
Description: For organizations raising €60,000-€100,000 per year
Price: 60.00 EUR
Billing: Monthly
```
Copy the **Price ID**

### Product 5:
```
Name: DAYAA Tier 5
Description: For organizations raising €100,000-€160,000 per year
Price: 100.00 EUR
Billing: Monthly
```
Copy the **Price ID**

### Product 6:
```
Name: DAYAA Tier 6
Description: For organizations raising €160,000-€240,000 per year
Price: 160.00 EUR
Billing: Monthly
```
Copy the **Price ID**

### Product 7:
```
Name: DAYAA Tier 7
Description: For organizations raising €240,000-€320,000 per year
Price: 240.00 EUR
Billing: Monthly
```
Copy the **Price ID**

### Product 8:
```
Name: DAYAA Tier 8
Description: For organizations raising €320,000+ per year
Price: 320.00 EUR
Billing: Monthly
```
Copy the **Price ID**

**✅ Done! You should now have 8 Price IDs**

---

## 🎯 STEP 3: Add Price IDs to Database Seeder (3 minutes)

### What to do:
1. Open this file: `/Users/apple/Herd/dayaa/database/seeders/SubscriptionTierSeeder.php`
2. Find these lines and replace the placeholder with your actual Price IDs:

**Line ~45:**
```php
'stripe_price_id' => 'price_tier1_REPLACE_ME',
```
Replace with:
```php
'stripe_price_id' => 'price_1Ab12CdEfGh...', // Your Tier 1 Price ID
```

**Do this for ALL 8 tiers!** (Tier 1 through Tier 8)

**✅ Done!**

---

## 🎯 STEP 4: Seed the Database (1 minute)

### What to do:
Open Terminal and run:

```bash
cd /Users/apple/Herd/dayaa
php artisan db:seed --class=SubscriptionTierSeeder
```

You should see:
```
Seeding: SubscriptionTierSeeder
Seeded: SubscriptionTierSeeder
```

**✅ Done!**

---

## 🎯 STEP 5: Set Up Webhooks for Testing (3 minutes)

### Option A: Using Stripe CLI (Recommended for Testing)

1. Install Stripe CLI:
   ```bash
   brew install stripe/stripe-cli/stripe
   ```

2. Login to Stripe:
   ```bash
   stripe login
   ```
   (This will open your browser - click "Allow access")

3. In a **new terminal window**, run:
   ```bash
   stripe listen --forward-to http://localhost:8000/api/webhook/stripe
   ```

4. You'll see output like:
   ```
   > Ready! Your webhook signing secret is whsec_1234567890abcdef...
   ```

5. Copy the `whsec_...` secret

6. Add to `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_YOUR_SECRET_HERE
   ```

7. **Keep this terminal window open while testing!**

**✅ Done!**

### Option B: Skip for Now (Test Later)
If you just want to test payments first, you can skip webhooks for now. They're only needed for automatic subscription updates.

---

## 🎯 STEP 6: Test It! (5 minutes)

### What to do:

1. Make sure your server is running:
   ```bash
   php artisan serve
   ```

2. Go to **http://localhost:8000**

3. Create a new organization account

4. Login as super admin and approve the organization

5. Login as the organization - you'll be redirected to subscription page

6. Fill in payment details using this **test card**:
   ```
   Card: 4242 4242 4242 4242
   Expiry: 12/25 (any future date)
   CVC: 123 (any 3 digits)
   ZIP: 12345 (any 5 digits)
   ```

7. Click "Activate Subscription"

8. You should be redirected to dashboard with success message!

**✅ It works!**

---

## 📊 What I Already Did For You:

✅ **Backend:**
- Created SubscriptionTierService (handles all tier logic)
- Created StripeService (handles Stripe API)
- Created StripeWebhookController (handles Stripe webhooks)
- Created EnsureSubscribed middleware (blocks access without subscription)
- Created ApplyPendingTierChanges job (daily cron for tier changes)
- Updated DonationObserver (triggers tier checks)

✅ **Frontend:**
- Created subscription setup page with Stripe payment form
- Created billing dashboard with tier progress
- Created tier progress widget for dashboard
- Created tier comparison page

✅ **Database:**
- Created migrations (already ran)
- Created seeder for 9 tiers (you just need to add Price IDs)

✅ **Routes:**
- Added webhook route to `routes/api.php`
- Added billing routes to `routes/web.php`

✅ **Middleware:**
- Registered `ensureSubscribed` middleware in `bootstrap/app.php`
- Excluded webhooks from CSRF protection

✅ **Scheduled Jobs:**
- Configured daily tier change job in `routes/console.php`

✅ **Email Notifications:**
- Created tier change scheduled email
- Created tier change applied email

✅ **Configuration:**
- Stripe configuration in `config/services.php`
- All caches cleared

---

## 🧪 Test Cards

Use these cards for different scenarios:

**Success:**
```
4242 4242 4242 4242 - Visa (succeeds)
5555 5555 5555 4444 - Mastercard (succeeds)
```

**Decline:**
```
4000 0000 0000 0002 - Generic decline
4000 0000 0000 9995 - Insufficient funds
```

**Authentication Required:**
```
4000 0025 0000 3155 - 3D Secure authentication
```

---

## 🆘 If Something Doesn't Work:

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Clear cache again:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Make sure Stripe CLI is running** (if using webhooks)

4. **Check .env file** has all Stripe keys

---

## 🎉 That's It!

Just follow the 6 steps above and you're done!

**Total time:** ~30 minutes

All the complex coding is already done - you just need to:
1. Get Stripe API keys
2. Create 8 products in Stripe
3. Copy Price IDs to seeder
4. Run seeder
5. Set up webhooks (optional for testing)
6. Test!

---

**Questions? Check the detailed guides:**
- `STRIPE_TESTING_GUIDE.md` - Complete testing guide
- `STRIPE_SUBSCRIPTION_SETUP_GUIDE.md` - Production setup guide
- `SUBSCRIPTION_IMPLEMENTATION_COMPLETE.md` - What was implemented

**Happy testing! 🚀**
