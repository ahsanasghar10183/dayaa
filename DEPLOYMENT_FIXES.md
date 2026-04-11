# Production Deployment Fixes

## Issues Found:
1. ✅ Variation migrations already successfully run (batch 7, 8, 9)
2. ❌ Subscription migration failing (columns already exist)
3. ❌ Login 419 error (CSRF/Session issue)
4. ❌ Language switcher not working

## Fixes Applied to Local Code:

### 1. Fixed Subscription Migration
- Added safety checks to prevent duplicate column errors
- File: `database/migrations/2026_04_10_000001_update_subscriptions_for_stripe.php`

### 2. Fixed Tier Change Logs Migration
- Fixed column reference (triggered_at doesn't exist, using applied_at instead)
- Added proper column existence checks
- File: `database/migrations/2026_04_10_000002_update_tier_change_logs.php`

### 2. Session Configuration Needed in .env

Add these to your **production** `.env` file:

```env
# Session Configuration
SESSION_DOMAIN=.dayaatech.de
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Sanctum Configuration (for CSRF)
SANCTUM_STATEFUL_DOMAINS=software.dayaatech.de,dayaatech.de
```

## Deployment Steps for Live Server:

### Step 1: Push Code to Production
```bash
# Commit the fixed migration
git add database/migrations/2026_04_10_000001_update_subscriptions_for_stripe.php
git commit -m "Fix: Add safety checks to subscription migration"
git push origin main
```

### Step 2: On Live Server
```bash
# Pull latest code
git pull origin main

# Update .env file (add the session settings above)
nano .env

# Run migrations (now safe to run)
php8.4-cli artisan migrate --force

# Clear all caches
php8.4-cli artisan config:clear
php8.4-cli artisan route:clear
php8.4-cli artisan view:clear
php8.4-cli artisan cache:clear

# Rebuild caches
php8.4-cli artisan config:cache
php8.4-cli artisan route:cache

# Set correct permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 3: Test
1. **Clear browser cookies** for dayaatech.de domain
2. Try logging in at: https://software.dayaatech.de/login
3. Test language switcher
4. Test product variations in admin panel

## Why These Fixes Work:

### SESSION_DOMAIN=.dayaatech.de
- The leading dot (.) allows cookies to work across all subdomains
- Both `software.dayaatech.de` and `dayaatech.de` will share sessions
- Fixes 419 CSRF errors caused by session mismatch

### SESSION_SECURE_COOKIE=true
- Required for HTTPS sites (your production uses HTTPS)
- Cookies only sent over secure connections

### SANCTUM_STATEFUL_DOMAINS
- Tells Laravel which domains should get CSRF protection
- Required for your multi-domain setup

### Migration Safety Checks
- Checks if columns exist before adding them
- Prevents "Column already exists" errors
- Safe to run multiple times

## Troubleshooting:

### If login still fails with 419:
```bash
# Check if sessions table exists
php8.4-cli artisan migrate:status

# Manually clear sessions
php8.4-cli artisan tinker
>>> DB::table('sessions')->truncate();
>>> exit

# Restart PHP-FPM (if you have access)
sudo systemctl restart php8.4-fpm
```

### If language switcher doesn't work:
- Clear browser cache completely
- Check that routes are cached correctly
- Verify SetLocale middleware is active

### Verify Variation Migrations:
```bash
php8.4-cli artisan migrate:status | grep -E "product_type|product_variations|variation_to_cart"
```

Should show:
```
✓ 2026_04_11_134854_add_product_type_to_products_table ............... [7] Ran
✓ 2026_04_11_134857_create_product_variations_table .................. [8] Ran
✓ 2026_04_11_134900_add_variation_to_cart_items_and_order_items ...... [9] Ran
```

## Product Variations Feature Status:
✅ Database migrations complete
✅ Models updated
✅ Controllers ready
✅ Admin UI ready
✅ Frontend shop ready
✅ Cart system ready
✅ Checkout system ready

**The variation system is production-ready once login is fixed!**
