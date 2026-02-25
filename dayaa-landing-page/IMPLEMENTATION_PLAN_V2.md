# DAYAA - Donation Platform Implementation Plan v2.0
## Complete Revised Implementation Plan | February 25, 2026

---

## 🎯 PROJECT OVERVIEW

**Project Name:** Dayaa Donation Management Platform
**Version:** 2.0 (Major Revision)
**Technology Stack:**
- **Backend:** Laravel 12 + API
- **Admin Dashboard:** Blade Templates + TailwindCSS
- **Mobile App:** React Native (iOS + Android)
- **Languages:** English & German (Multilingual)
- **Payment Gateway:** SumUp Integration (Native SDK)
- **Subscription Management:** Stripe (Dynamic Tier-based Pricing)
- **Inspiration:** Payaz.com (UX/Functionality Reference)

**Color Scheme:**
- Primary Gradient: `#1163F0` → `#1707B2`
- Light theme with centralized color variables
- Modern, clean, and purpose-driven UI/UX

---

## 🏗️ ARCHITECTURAL CHANGES FROM V1.0

### **V1.0 Architecture (OLD):**
```
┌─────────────────────────────────┐
│   Web Browser (Kiosk Mode)     │
│   - Campaign Display            │
│   - Donation Flow               │
│   - WebBluetooth (SumUp)        │
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│      Laravel Backend            │
│   - Blade Views                 │
│   - Controllers                 │
│   - Database                    │
└─────────────────────────────────┘
```

**Problems:**
- ❌ No true kiosk lock (browser can be exited)
- ❌ Unreliable WebBluetooth for SumUp
- ❌ Browser controls visible
- ❌ Session management issues
- ❌ Unprofessional appearance

---

### **V2.0 Architecture (NEW):**
```
┌──────────────────────────────────────────────────────────────┐
│              MOBILE APP (React Native)                       │
│  ┌────────────────────────────────────────────────────────┐  │
│  │         KIOSK MODE (iPad/Android Tablet)               │  │
│  │  • True Kiosk Lock (Guided Access/Lock Task Mode)     │  │
│  │  • Campaign Display (API-driven)                      │  │
│  │  • Donation Flow (5 Steps)                            │  │
│  │  • SumUp SDK Integration (Native Bluetooth)           │  │
│  │  • Offline Queue & Sync                               │  │
│  │  • PIN-Protected Admin Panel                          │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
                            ↕
                    REST API (JSON)
                   (auth:sanctum)
                            ↕
┌──────────────────────────────────────────────────────────────┐
│           LARAVEL BACKEND (API + Web Dashboard)              │
│  ┌────────────────────────────────────────────────────────┐  │
│  │                  API LAYER (/api/v1)                   │  │
│  │  • Device Pairing & Management                        │  │
│  │  • Campaign Data Delivery                             │  │
│  │  • Donation Creation & Confirmation                   │  │
│  │  • Real-time Tier Checking                            │  │
│  │  • Heartbeat & Status Updates                         │  │
│  └────────────────────────────────────────────────────────┘  │
│  ┌────────────────────────────────────────────────────────┐  │
│  │            WEB DASHBOARD (Blade + Tailwind)            │  │
│  │  • Super Admin Panel                                  │  │
│  │  • Organization Admin Panel                           │  │
│  │  • Campaign Builder (5-Step Wizard)                   │  │
│  │  • Device Management                                  │  │
│  │  • Reports & Analytics                                │  │
│  │  • Subscription & Billing Management                  │  │
│  └────────────────────────────────────────────────────────┘  │
│  ┌────────────────────────────────────────────────────────┐  │
│  │               SERVICES & BACKGROUND JOBS               │  │
│  │  • SubscriptionTierService (Real-time Tier Checks)    │  │
│  │  • StripeService (Subscription Management)            │  │
│  │  • DonationObserver (Tier Check Trigger)              │  │
│  │  • Scheduled Jobs (Daily Tier Sync, Billing)          │  │
│  │  • Notification System (Email, Database)              │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
                            ↕
┌──────────────────────────────────────────────────────────────┐
│                    STRIPE API                                │
│  • Dynamic Tier-based Subscriptions                         │
│  • 9 Pricing Tiers (€0 - €320/month)                        │
│  • Automatic Billing & Invoice Generation                   │
│  • Payment Method Management                                │
│  • Webhook Events (payment success/failure)                 │
└──────────────────────────────────────────────────────────────┘
```

---

## 📊 NEW SUBSCRIPTION MODEL

### **Dynamic Tier-Based Pricing**

Monthly subscription fee is determined by **total donations raised in the last 12 months**.

| Tier | Fundraising Range (12 Months) | Monthly Fee | Stripe Price ID |
|------|-------------------------------|-------------|-----------------|
| **Basic** | €0 - €1,000 | €0.00 | - |
| **Tier 1** | €1,000 - €10,000 | €10.00 | price_tier1_xxx |
| **Tier 2** | €10,000 - €20,000 | €20.00 | price_tier2_xxx |
| **Tier 3** | €20,000 - €30,000 | €30.00 | price_tier3_xxx |
| **Tier 4** | €30,000 - €60,000 | €60.00 | price_tier4_xxx |
| **Tier 5** | €60,000 - €125,000 | €100.00 | price_tier5_xxx |
| **Tier 6** | €125,000 - €320,000 | €160.00 | price_tier6_xxx |
| **Tier 7** | €320,000 - €650,000 | €240.00 | price_tier7_xxx |
| **Tier 8** | €650,000 - €1,250,000 | €320.00 | price_tier8_xxx |
| **Enterprise** | €1,250,000+ | Custom | Contact Sales |

### **Tier Calculation Logic:**

**Real-time Tier Checking (After Every Donation):**
1. Donation is confirmed as successful
2. System recalculates total donations in last 12 months
3. System determines appropriate tier
4. If tier changed:
   - Schedule tier change for next billing date
   - Update subscription record with pending tier
   - Send notification email to organization
   - Display tier progress on dashboard
5. On billing date, Stripe subscription price is updated

**Example Flow:**
```
Day 1: Organization raises €8,500 (Tier 1: €10/month)
Day 15: New donation brings total to €10,200 (crosses threshold)
        → System schedules upgrade to Tier 2 (€20/month)
        → Email sent: "Congrats! Tier upgrading on March 1st"
        → Dashboard shows: "Scheduled: Tier 2 (€20/month) on March 1"
Day 30 (Billing Date):
        → Stripe subscription updated to €20/month
        → Tier officially changed to Tier 2
        → Invoice generated and sent
```

---

## 📋 IMPLEMENTATION PHASES

### **PHASE 1: Database Architecture Updates** (3-4 days)

#### 1.1 New Tables

**subscription_tiers:**
```sql
CREATE TABLE subscription_tiers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,                    -- 'Basic', 'Tier 1', etc.
    description TEXT NULL,                          -- Description for display
    min_amount DECIMAL(12,2) NOT NULL DEFAULT 0,   -- Minimum fundraising (€0, €1000, etc.)
    max_amount DECIMAL(12,2) NULL,                 -- Maximum (NULL = unlimited)
    monthly_fee DECIMAL(8,2) NOT NULL,             -- Monthly cost (€0, €10, €20, etc.)
    currency VARCHAR(3) DEFAULT 'EUR',              -- Currency code
    features JSON NULL,                             -- Optional: ['Feature 1', 'Feature 2']
    stripe_price_id VARCHAR(255) NULL,              -- Stripe Price ID (price_xxx)
    is_active BOOLEAN DEFAULT 1,                    -- Active/inactive
    sort_order INT DEFAULT 0,                       -- Display order
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_amount_range (min_amount, max_amount)
);
```

**tier_change_logs:**
```sql
CREATE TABLE tier_change_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT UNSIGNED NOT NULL,
    from_tier_id BIGINT UNSIGNED NULL,              -- Previous tier
    to_tier_id BIGINT UNSIGNED NOT NULL,            -- New tier
    triggered_by VARCHAR(50) NOT NULL,               -- 'donation', 'manual', 'scheduled_job'
    triggered_at TIMESTAMP NOT NULL,                 -- When change was detected
    applied_at TIMESTAMP NULL,                       -- When change was applied
    status VARCHAR(20) DEFAULT 'pending',            -- 'pending', 'applied', 'cancelled'
    donation_total_12m DECIMAL(12,2) NOT NULL,      -- 12-month total at time of change
    notification_sent BOOLEAN DEFAULT 0,             -- Email sent?
    notes TEXT NULL,                                 -- Optional admin notes
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    FOREIGN KEY (from_tier_id) REFERENCES subscription_tiers(id) ON DELETE SET NULL,
    FOREIGN KEY (to_tier_id) REFERENCES subscription_tiers(id) ON DELETE CASCADE,
    INDEX idx_org_status (organization_id, status),
    INDEX idx_triggered_at (triggered_at)
);
```

#### 1.2 Update Existing Tables

**subscriptions table:**
```sql
ALTER TABLE subscriptions
ADD COLUMN tier_id BIGINT UNSIGNED NULL AFTER organization_id,
ADD COLUMN current_monthly_fee DECIMAL(8,2) DEFAULT 0 AFTER tier_id,
ADD COLUMN total_12m_donations DECIMAL(12,2) DEFAULT 0 AFTER current_monthly_fee,
ADD COLUMN pending_tier_id BIGINT UNSIGNED NULL AFTER total_12m_donations,
ADD COLUMN tier_change_scheduled_at TIMESTAMP NULL AFTER pending_tier_id,
ADD COLUMN last_tier_check TIMESTAMP NULL AFTER tier_change_scheduled_at,
ADD COLUMN stripe_subscription_id VARCHAR(255) NULL AFTER next_billing_date,
ADD COLUMN stripe_customer_id VARCHAR(255) NULL AFTER stripe_subscription_id,
ADD COLUMN stripe_payment_method_id VARCHAR(255) NULL AFTER stripe_customer_id,
ADD COLUMN stripe_subscription_status VARCHAR(50) NULL AFTER stripe_payment_method_id,
ADD COLUMN trial_ends_at TIMESTAMP NULL AFTER stripe_subscription_status,
ADD COLUMN grace_period_ends_at TIMESTAMP NULL AFTER trial_ends_at,
ADD FOREIGN KEY (tier_id) REFERENCES subscription_tiers(id) ON DELETE SET NULL,
ADD FOREIGN KEY (pending_tier_id) REFERENCES subscription_tiers(id) ON DELETE SET NULL,
ADD INDEX idx_stripe_subscription (stripe_subscription_id),
ADD INDEX idx_stripe_customer (stripe_customer_id);

-- Remove old plan column
ALTER TABLE subscriptions DROP COLUMN plan;
```

**donations table:**
```sql
ALTER TABLE donations
ADD COLUMN triggered_tier_check BOOLEAN DEFAULT 0 AFTER payment_status,
ADD COLUMN tier_at_donation BIGINT UNSIGNED NULL AFTER triggered_tier_check,
ADD FOREIGN KEY (tier_at_donation) REFERENCES subscription_tiers(id) ON DELETE SET NULL,
ADD INDEX idx_tier_check (triggered_tier_check),
ADD INDEX idx_org_date (organization_id, created_at);
```

**devices table:**
```sql
ALTER TABLE devices
ADD COLUMN paired_at TIMESTAMP NULL AFTER last_active,
ADD COLUMN unpaired_at TIMESTAMP NULL AFTER paired_at,
ADD COLUMN device_type VARCHAR(50) NULL AFTER device_id,     -- 'ipad', 'android_tablet'
ADD COLUMN os_version VARCHAR(50) NULL AFTER device_type,
ADD COLUMN app_version VARCHAR(50) NULL AFTER os_version,
ADD INDEX idx_status (status),
ADD INDEX idx_last_active (last_active);
```

#### 1.3 Seed Initial Data

**Subscription Tiers Seeder:**
```php
// database/seeders/SubscriptionTiersSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionTiersSeeder extends Seeder
{
    public function run()
    {
        DB::table('subscription_tiers')->insert([
            [
                'name' => 'Basic',
                'description' => 'Perfect for getting started with fundraising',
                'min_amount' => 0,
                'max_amount' => 1000,
                'monthly_fee' => 0.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    'Up to €1,000 in donations',
                    'Unlimited campaigns',
                    'Unlimited devices',
                    'Basic analytics',
                    'Email support',
                ]),
                'stripe_price_id' => null, // No Stripe price for free tier
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 1',
                'description' => 'Growing organizations raising up to €10,000',
                'min_amount' => 1000,
                'max_amount' => 10000,
                'monthly_fee' => 10.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€1,000 - €10,000 in donations',
                    'Unlimited campaigns',
                    'Unlimited devices',
                    'Advanced analytics',
                    'Priority email support',
                ]),
                'stripe_price_id' => null, // Will be set during Stripe setup
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 2',
                'description' => 'Established organizations raising €10K - €20K',
                'min_amount' => 10000,
                'max_amount' => 20000,
                'monthly_fee' => 20.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€10,000 - €20,000 in donations',
                    'All Tier 1 features',
                    'Custom branding',
                    'Dedicated support',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 3',
                'description' => 'Successful organizations raising €20K - €30K',
                'min_amount' => 20000,
                'max_amount' => 30000,
                'monthly_fee' => 30.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€20,000 - €30,000 in donations',
                    'All Tier 2 features',
                    'API access',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 4',
                'description' => 'High-impact organizations raising €30K - €60K',
                'min_amount' => 30000,
                'max_amount' => 60000,
                'monthly_fee' => 60.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€30,000 - €60,000 in donations',
                    'All Tier 3 features',
                    'Custom integrations',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 5',
                'description' => 'Major organizations raising €60K - €125K',
                'min_amount' => 60000,
                'max_amount' => 125000,
                'monthly_fee' => 100.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€60,000 - €125,000 in donations',
                    'All Tier 4 features',
                    'Advanced reporting',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 6',
                'description' => 'Enterprise organizations raising €125K - €320K',
                'min_amount' => 125000,
                'max_amount' => 320000,
                'monthly_fee' => 160.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€125,000 - €320,000 in donations',
                    'All Tier 5 features',
                    'White-label options',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 7',
                'description' => 'Large-scale organizations raising €320K - €650K',
                'min_amount' => 320000,
                'max_amount' => 650000,
                'monthly_fee' => 240.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€320,000 - €650,000 in donations',
                    'All Tier 6 features',
                    'Dedicated account manager',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tier 8',
                'description' => 'Top-tier organizations raising €650K - €1.25M',
                'min_amount' => 650000,
                'max_amount' => 1250000,
                'monthly_fee' => 320.00,
                'currency' => 'EUR',
                'features' => json_encode([
                    '€650,000 - €1,250,000 in donations',
                    'All Tier 7 features',
                    'Priority feature requests',
                ]),
                'stripe_price_id' => null,
                'is_active' => true,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Ultra-high volume organizations (€1.25M+)',
                'min_amount' => 1250000,
                'max_amount' => null, // Unlimited
                'monthly_fee' => 0.00, // Custom pricing
                'currency' => 'EUR',
                'features' => json_encode([
                    '€1,250,000+ in donations',
                    'Custom pricing',
                    'All features included',
                    'Dedicated infrastructure',
                    'SLA guarantees',
                ]),
                'stripe_price_id' => null, // Manual subscription setup
                'is_active' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
```

---

### **PHASE 2: Models & Relationships** (2-3 days)

#### 2.1 New Models

**SubscriptionTier Model:**
```php
// app/Models/SubscriptionTier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionTier extends Model
{
    protected $fillable = [
        'name',
        'description',
        'min_amount',
        'max_amount',
        'monthly_fee',
        'currency',
        'features',
        'stripe_price_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Check if this tier is the free tier
     */
    public function isFree(): bool
    {
        return $this->monthly_fee == 0;
    }

    /**
     * Check if this tier is the enterprise tier
     */
    public function isEnterprise(): bool
    {
        return $this->name === 'Enterprise' || $this->max_amount === null;
    }

    /**
     * Check if given amount falls within this tier
     */
    public function includesAmount(float $amount): bool
    {
        if ($amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount === null) {
            return true; // Unlimited (Enterprise)
        }

        return $amount <= $this->max_amount;
    }

    /**
     * Get all subscriptions in this tier
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tier_id');
    }

    /**
     * Get all tier change logs TO this tier
     */
    public function tierChangesTo(): HasMany
    {
        return $this->hasMany(TierChangeLog::class, 'to_tier_id');
    }

    /**
     * Get all tier change logs FROM this tier
     */
    public function tierChangesFrom(): HasMany
    {
        return $this->hasMany(TierChangeLog::class, 'from_tier_id');
    }

    /**
     * Scope: Active tiers only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
```

**TierChangeLog Model:**
```php
// app/Models/TierChangeLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierChangeLog extends Model
{
    protected $fillable = [
        'organization_id',
        'from_tier_id',
        'to_tier_id',
        'triggered_by',
        'triggered_at',
        'applied_at',
        'status',
        'donation_total_12m',
        'notification_sent',
        'notes',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'applied_at' => 'datetime',
        'donation_total_12m' => 'decimal:2',
        'notification_sent' => 'boolean',
    ];

    /**
     * Get the organization
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the previous tier
     */
    public function fromTier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'from_tier_id');
    }

    /**
     * Get the new tier
     */
    public function toTier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'to_tier_id');
    }

    /**
     * Check if this is an upgrade
     */
    public function isUpgrade(): bool
    {
        if (!$this->fromTier) {
            return true; // First tier assignment
        }

        return $this->toTier->monthly_fee > $this->fromTier->monthly_fee;
    }

    /**
     * Check if this is a downgrade
     */
    public function isDowngrade(): bool
    {
        if (!$this->fromTier) {
            return false;
        }

        return $this->toTier->monthly_fee < $this->fromTier->monthly_fee;
    }

    /**
     * Check if pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if applied
     */
    public function isApplied(): bool
    {
        return $this->status === 'applied';
    }

    /**
     * Mark as applied
     */
    public function markApplied(): void
    {
        $this->update([
            'status' => 'applied',
            'applied_at' => now(),
        ]);
    }

    /**
     * Cancel pending change
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Scope: Pending changes only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Applied changes only
     */
    public function scopeApplied($query)
    {
        return $query->where('status', 'applied');
    }
}
```

#### 2.2 Update Existing Models

**Update Subscription Model:**
```php
// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'organization_id',
        'tier_id',
        'current_monthly_fee',
        'total_12m_donations',
        'pending_tier_id',
        'tier_change_scheduled_at',
        'last_tier_check',
        'status',
        'current_period_start',
        'current_period_end',
        'next_billing_date',
        'stripe_subscription_id',
        'stripe_customer_id',
        'stripe_payment_method_id',
        'stripe_subscription_status',
        'trial_ends_at',
        'grace_period_ends_at',
        'payment_method',
    ];

    protected $casts = [
        'current_monthly_fee' => 'decimal:2',
        'total_12m_donations' => 'decimal:2',
        'tier_change_scheduled_at' => 'datetime',
        'last_tier_check' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'next_billing_date' => 'datetime',
        'trial_ends_at' => 'datetime',
        'grace_period_ends_at' => 'datetime',
    ];

    /**
     * Get the organization
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the current tier
     */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'tier_id');
    }

    /**
     * Get the pending tier (if tier change scheduled)
     */
    public function pendingTier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'pending_tier_id');
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if subscription is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if subscription is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if tier change is scheduled
     */
    public function hasPendingTierChange(): bool
    {
        return $this->pending_tier_id !== null;
    }

    /**
     * Check if on trial
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if in grace period
     */
    public function inGracePeriod(): bool
    {
        return $this->grace_period_ends_at && $this->grace_period_ends_at->isFuture();
    }

    /**
     * Get days until next billing
     */
    public function daysUntilNextBilling(): int
    {
        if (!$this->next_billing_date) {
            return 0;
        }

        return now()->diffInDays($this->next_billing_date, false);
    }

    /**
     * Get percentage to next tier
     */
    public function percentageToNextTier(): ?float
    {
        if (!$this->tier) {
            return null;
        }

        $currentTier = $this->tier;

        // If already at max tier
        if (!$currentTier->max_amount) {
            return 100;
        }

        $nextTier = SubscriptionTier::active()
            ->where('min_amount', '>', $currentTier->min_amount)
            ->orderBy('min_amount', 'asc')
            ->first();

        if (!$nextTier) {
            return 100; // Already at top tier
        }

        $currentAmount = $this->total_12m_donations;
        $tierStart = $currentTier->min_amount;
        $tierEnd = $nextTier->min_amount;

        $percentage = (($currentAmount - $tierStart) / ($tierEnd - $tierStart)) * 100;

        return min(100, max(0, $percentage));
    }

    /**
     * Get amount needed to reach next tier
     */
    public function amountToNextTier(): ?float
    {
        if (!$this->tier) {
            return null;
        }

        $currentTier = $this->tier;

        if (!$currentTier->max_amount) {
            return null; // Already at enterprise tier
        }

        $nextTier = SubscriptionTier::active()
            ->where('min_amount', '>', $currentTier->min_amount)
            ->orderBy('min_amount', 'asc')
            ->first();

        if (!$nextTier) {
            return null;
        }

        $needed = $nextTier->min_amount - $this->total_12m_donations;

        return max(0, $needed);
    }
}
```

**Update Organization Model:**
```php
// Add to app/Models/Organization.php

/**
 * Get tier change logs
 */
public function tierChangeLogs(): HasMany
{
    return $this->hasMany(TierChangeLog::class);
}

/**
 * Get pending tier changes
 */
public function pendingTierChanges(): HasMany
{
    return $this->hasMany(TierChangeLog::class)
        ->where('status', 'pending');
}

/**
 * Get current subscription tier
 */
public function getCurrentTierAttribute(): ?SubscriptionTier
{
    return $this->subscription?->tier;
}

/**
 * Get 12-month donation total
 */
public function get12MonthTotalAttribute(): float
{
    return $this->donations()
        ->where('payment_status', 'success')
        ->where('created_at', '>=', now()->subMonths(12))
        ->sum('amount');
}
```

**Update Donation Model:**
```php
// Add to app/Models/Donation.php

/**
 * Get the tier at time of donation
 */
public function tierAtDonation(): BelongsTo
{
    return $this->belongsTo(SubscriptionTier::class, 'tier_at_donation');
}
```

---

### **PHASE 3: Stripe Integration** (3-4 days)

#### 3.1 Install Stripe SDK
```bash
composer require stripe/stripe-php
```

#### 3.2 Configuration

**Add to config/services.php:**
```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'product_id' => env('STRIPE_PRODUCT_ID'),
],
```

**Add to .env:**
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
STRIPE_PRODUCT_ID=prod_...
```

#### 3.3 Stripe Service

**app/Services/StripeService.php:**
```php
<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionTier;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create Stripe product and prices for all tiers
     * Run this once during setup
     */
    public function setupProductAndPrices(): array
    {
        // Create product
        $product = $this->stripe->products->create([
            'name' => 'Dayaa Platform Subscription',
            'description' => 'Monthly subscription based on fundraising performance',
        ]);

        $results = ['product_id' => $product->id, 'prices' => []];

        // Create prices for each paid tier
        $tiers = SubscriptionTier::active()
            ->where('monthly_fee', '>', 0)
            ->orderBy('sort_order')
            ->get();

        foreach ($tiers as $tier) {
            $price = $this->stripe->prices->create([
                'product' => $product->id,
                'unit_amount' => $tier->monthly_fee * 100, // Convert to cents
                'currency' => strtolower($tier->currency),
                'recurring' => ['interval' => 'month'],
                'nickname' => $tier->name,
            ]);

            // Update tier with Stripe price ID
            $tier->update(['stripe_price_id' => $price->id]);

            $results['prices'][$tier->name] = $price->id;
        }

        return $results;
    }

    /**
     * Create Stripe customer for organization
     */
    public function createCustomer(Organization $org): \Stripe\Customer
    {
        return $this->stripe->customers->create([
            'email' => $org->email,
            'name' => $org->name,
            'metadata' => [
                'organization_id' => $org->id,
                'charity_number' => $org->charity_number,
            ],
        ]);
    }

    /**
     * Create subscription for organization
     */
    public function createSubscription(
        Organization $org,
        SubscriptionTier $tier,
        string $paymentMethodId
    ): \Stripe\Subscription {
        $subscription = $org->subscription;

        // Create customer if doesn't exist
        if (!$subscription->stripe_customer_id) {
            $customer = $this->createCustomer($org);
            $subscription->update(['stripe_customer_id' => $customer->id]);
        }

        // Attach payment method
        $this->stripe->paymentMethods->attach($paymentMethodId, [
            'customer' => $subscription->stripe_customer_id,
        ]);

        // Set as default payment method
        $this->stripe->customers->update($subscription->stripe_customer_id, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId,
            ],
        ]);

        // Create subscription (free tier gets no Stripe subscription)
        if ($tier->isFree()) {
            $subscription->update([
                'tier_id' => $tier->id,
                'current_monthly_fee' => 0,
                'status' => 'active',
                'stripe_payment_method_id' => $paymentMethodId,
            ]);

            return null;
        }

        // Create Stripe subscription
        $stripeSubscription = $this->stripe->subscriptions->create([
            'customer' => $subscription->stripe_customer_id,
            'items' => [
                ['price' => $tier->stripe_price_id],
            ],
            'metadata' => [
                'organization_id' => $org->id,
                'tier_id' => $tier->id,
            ],
            'billing_cycle_anchor_config' => [
                'day_of_month' => 1, // Bill on 1st of each month
            ],
        ]);

        // Update local subscription
        $subscription->update([
            'stripe_subscription_id' => $stripeSubscription->id,
            'stripe_payment_method_id' => $paymentMethodId,
            'stripe_subscription_status' => $stripeSubscription->status,
            'tier_id' => $tier->id,
            'current_monthly_fee' => $tier->monthly_fee,
            'status' => 'active',
            'current_period_start' => now()->timestamp($stripeSubscription->current_period_start),
            'current_period_end' => now()->timestamp($stripeSubscription->current_period_end),
            'next_billing_date' => now()->timestamp($stripeSubscription->current_period_end),
        ]);

        return $stripeSubscription;
    }

    /**
     * Update subscription to new tier
     */
    public function updateSubscriptionTier(
        Subscription $subscription,
        SubscriptionTier $newTier
    ): ?\Stripe\Subscription {
        // If free tier, cancel Stripe subscription
        if ($newTier->isFree()) {
            if ($subscription->stripe_subscription_id) {
                $this->stripe->subscriptions->cancel($subscription->stripe_subscription_id);
            }

            $subscription->update([
                'tier_id' => $newTier->id,
                'current_monthly_fee' => 0,
                'stripe_subscription_id' => null,
                'stripe_subscription_status' => null,
            ]);

            return null;
        }

        // If no Stripe subscription exists, create one
        if (!$subscription->stripe_subscription_id) {
            throw new \Exception('Cannot update tier: No Stripe subscription exists');
        }

        // Get Stripe subscription
        $stripeSubscription = $this->stripe->subscriptions->retrieve(
            $subscription->stripe_subscription_id
        );

        // Update subscription price
        $updatedSubscription = $this->stripe->subscriptions->update(
            $subscription->stripe_subscription_id,
            [
                'items' => [
                    [
                        'id' => $stripeSubscription->items->data[0]->id,
                        'price' => $newTier->stripe_price_id,
                    ],
                ],
                'proration_behavior' => 'none', // No proration, change applies next billing cycle
                'metadata' => [
                    'tier_id' => $newTier->id,
                ],
            ]
        );

        // Update local subscription
        $subscription->update([
            'tier_id' => $newTier->id,
            'current_monthly_fee' => $newTier->monthly_fee,
            'pending_tier_id' => null,
            'tier_change_scheduled_at' => null,
            'stripe_subscription_status' => $updatedSubscription->status,
        ]);

        return $updatedSubscription;
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription): void
    {
        if ($subscription->stripe_subscription_id) {
            $this->stripe->subscriptions->cancel($subscription->stripe_subscription_id);
        }

        $subscription->update([
            'status' => 'cancelled',
            'stripe_subscription_status' => 'canceled',
        ]);
    }

    /**
     * Reactivate cancelled subscription
     */
    public function reactivateSubscription(Subscription $subscription): \Stripe\Subscription
    {
        // Create new Stripe subscription
        $tier = $subscription->tier;

        $stripeSubscription = $this->stripe->subscriptions->create([
            'customer' => $subscription->stripe_customer_id,
            'items' => [
                ['price' => $tier->stripe_price_id],
            ],
            'metadata' => [
                'organization_id' => $subscription->organization_id,
                'tier_id' => $tier->id,
            ],
        ]);

        $subscription->update([
            'stripe_subscription_id' => $stripeSubscription->id,
            'stripe_subscription_status' => $stripeSubscription->status,
            'status' => 'active',
            'current_period_start' => now()->timestamp($stripeSubscription->current_period_start),
            'current_period_end' => now()->timestamp($stripeSubscription->current_period_end),
            'next_billing_date' => now()->timestamp($stripeSubscription->current_period_end),
        ]);

        return $stripeSubscription;
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(Subscription $subscription, string $paymentMethodId): void
    {
        // Attach new payment method
        $this->stripe->paymentMethods->attach($paymentMethodId, [
            'customer' => $subscription->stripe_customer_id,
        ]);

        // Set as default
        $this->stripe->customers->update($subscription->stripe_customer_id, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId,
            ],
        ]);

        $subscription->update(['stripe_payment_method_id' => $paymentMethodId]);
    }
}
```

#### 3.4 Webhook Handler

**app/Http/Controllers/Api/StripeWebhookController.php:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Organization;
use App\Notifications\PaymentSucceeded;
use App\Notifications\PaymentFailed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhooks
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionCancelled($event->data->object);
                break;

            case 'payment_method.attached':
                $this->handlePaymentMethodAttached($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event: ' . $event->type);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSucceeded($invoice)
    {
        $subscriptionId = $invoice->subscription;

        $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

        if (!$subscription) {
            Log::warning('Subscription not found for Stripe subscription: ' . $subscriptionId);
            return;
        }

        $subscription->update([
            'status' => 'active',
            'stripe_subscription_status' => 'active',
            'grace_period_ends_at' => null,
        ]);

        // Send notification
        $subscription->organization->user->notify(new PaymentSucceeded($invoice));

        Log::info("Payment succeeded for Organization #{$subscription->organization_id}");
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailed($invoice)
    {
        $subscriptionId = $invoice->subscription;

        $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

        if (!$subscription) {
            return;
        }

        // Set grace period (14 days)
        $subscription->update([
            'grace_period_ends_at' => now()->addDays(14),
        ]);

        // Send notification
        $subscription->organization->user->notify(new PaymentFailed($invoice));

        Log::warning("Payment failed for Organization #{$subscription->organization_id}");
    }

    /**
     * Handle subscription updated
     */
    protected function handleSubscriptionUpdated($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (!$subscription) {
            return;
        }

        $subscription->update([
            'stripe_subscription_status' => $stripeSubscription->status,
            'current_period_start' => now()->timestamp($stripeSubscription->current_period_start),
            'current_period_end' => now()->timestamp($stripeSubscription->current_period_end),
            'next_billing_date' => now()->timestamp($stripeSubscription->current_period_end),
        ]);

        Log::info("Subscription updated for Organization #{$subscription->organization_id}");
    }

    /**
     * Handle subscription cancelled
     */
    protected function handleSubscriptionCancelled($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (!$subscription) {
            return;
        }

        $subscription->update([
            'status' => 'cancelled',
            'stripe_subscription_status' => 'canceled',
        ]);

        Log::info("Subscription cancelled for Organization #{$subscription->organization_id}");
    }

    /**
     * Handle payment method attached
     */
    protected function handlePaymentMethodAttached($paymentMethod)
    {
        $customerId = $paymentMethod->customer;

        $subscription = Subscription::where('stripe_customer_id', $customerId)->first();

        if (!$subscription) {
            return;
        }

        $subscription->update(['stripe_payment_method_id' => $paymentMethod->id]);

        Log::info("Payment method attached for Organization #{$subscription->organization_id}");
    }
}
```

**Add to routes/api.php:**
```php
// Stripe webhook (exclude from CSRF)
Route::post('/webhooks/stripe', [Api\StripeWebhookController::class, 'handleWebhook']);
```

**Exclude from CSRF in bootstrap/app.php:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        '/api/webhooks/stripe',
    ]);
})
```

---

### **PHASE 4: Subscription Tier Service** (4-5 days)

#### 4.1 Subscription Tier Service

**app/Services/SubscriptionTierService.php:**
```php
<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionTier;
use App\Models\TierChangeLog;
use App\Models\Donation;
use App\Notifications\TierChangeScheduled;
use App\Notifications\TierUpgradeApplied;
use App\Notifications\TierDowngradeApplied;
use Illuminate\Support\Facades\Log;

class SubscriptionTierService
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Calculate total donations in last 12 months for organization
     */
    public function calculate12MonthTotal(Organization $org): float
    {
        return Donation::where('organization_id', $org->id)
            ->where('payment_status', 'success')
            ->where('created_at', '>=', now()->subMonths(12))
            ->sum('amount');
    }

    /**
     * Get appropriate tier for given donation amount
     */
    public function getTierForAmount(float $amount): SubscriptionTier
    {
        return SubscriptionTier::active()
            ->where('min_amount', '<=', $amount)
            ->where(function ($query) use ($amount) {
                $query->whereNull('max_amount')
                    ->orWhere('max_amount', '>', $amount);
            })
            ->orderBy('min_amount', 'desc')
            ->firstOrFail();
    }

    /**
     * Check if tier should change after a donation
     * This is called automatically by DonationObserver
     */
    public function checkTierAfterDonation(Donation $donation): ?TierChangeLog
    {
        $org = $donation->organization;
        $subscription = $org->subscription;

        if (!$subscription || !$subscription->tier) {
            Log::info("No subscription for Organization #{$org->id}, skipping tier check");
            return null;
        }

        // Recalculate 12-month total
        $total12m = $this->calculate12MonthTotal($org);

        // Update subscription's cached total
        $subscription->update([
            'total_12m_donations' => $total12m,
            'last_tier_check' => now(),
        ]);

        // Get appropriate tier for current total
        $newTier = $this->getTierForAmount($total12m);

        // Record tier at time of donation
        $donation->update(['tier_at_donation' => $subscription->tier_id]);

        // Check if tier changed
        if ($newTier->id === $subscription->tier_id) {
            // No change needed
            return null;
        }

        // Tier has changed - schedule change for next billing date
        return $this->scheduleTierChange($org, $subscription->tier_id, $newTier->id, $total12m, 'donation');
    }

    /**
     * Schedule a tier change for next billing date
     */
    public function scheduleTierChange(
        Organization $org,
        ?int $fromTierId,
        int $toTierId,
        float $total12m,
        string $triggeredBy = 'donation'
    ): TierChangeLog {
        $subscription = $org->subscription;

        // Cancel any existing pending tier changes
        TierChangeLog::where('organization_id', $org->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        // Create new tier change log
        $log = TierChangeLog::create([
            'organization_id' => $org->id,
            'from_tier_id' => $fromTierId,
            'to_tier_id' => $toTierId,
            'triggered_by' => $triggeredBy,
            'triggered_at' => now(),
            'status' => 'pending',
            'donation_total_12m' => $total12m,
        ]);

        // Update subscription with pending tier info
        $subscription->update([
            'pending_tier_id' => $toTierId,
            'tier_change_scheduled_at' => $subscription->next_billing_date,
        ]);

        // Send notification to organization
        $this->notifyTierChange($org, $log);

        Log::info("Tier change scheduled for Organization #{$org->id}: Tier {$fromTierId} → {$toTierId}");

        return $log;
    }

    /**
     * Apply a pending tier change
     * Called on billing date
     */
    public function applyTierChange(TierChangeLog $log): void
    {
        if ($log->status !== 'pending') {
            Log::warning("Attempted to apply non-pending tier change #{$log->id}");
            return;
        }

        $org = $log->organization;
        $subscription = $org->subscription;
        $newTier = $log->toTier;

        try {
            // Update Stripe subscription (if not free tier)
            if (!$newTier->isFree()) {
                $this->stripeService->updateSubscriptionTier($subscription, $newTier);
            } else {
                // Moving to free tier - update locally
                $subscription->update([
                    'tier_id' => $newTier->id,
                    'current_monthly_fee' => 0,
                    'pending_tier_id' => null,
                    'tier_change_scheduled_at' => null,
                ]);
            }

            // Mark log as applied
            $log->markApplied();

            // Send notification
            if ($log->isUpgrade()) {
                $org->user->notify(new TierUpgradeApplied($log));
            } else {
                $org->user->notify(new TierDowngradeApplied($log));
            }

            Log::info("Tier change applied for Organization #{$org->id}: {$log->fromTier->name} → {$newTier->name}");

        } catch (\Exception $e) {
            Log::error("Failed to apply tier change #{$log->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Manually change tier (Super Admin override)
     */
    public function manualTierChange(
        Organization $org,
        int $newTierId,
        string $notes = null,
        bool $applyImmediately = false
    ): TierChangeLog {
        $subscription = $org->subscription;
        $total12m = $this->calculate12MonthTotal($org);

        if ($applyImmediately) {
            // Apply immediately
            $log = TierChangeLog::create([
                'organization_id' => $org->id,
                'from_tier_id' => $subscription->tier_id,
                'to_tier_id' => $newTierId,
                'triggered_by' => 'manual',
                'triggered_at' => now(),
                'status' => 'pending',
                'donation_total_12m' => $total12m,
                'notes' => $notes,
            ]);

            $this->applyTierChange($log);

            return $log;
        } else {
            // Schedule for next billing
            return $this->scheduleTierChange(
                $org,
                $subscription->tier_id,
                $newTierId,
                $total12m,
                'manual'
            );
        }
    }

    /**
     * Recalculate tier for all organizations
     * Run this via scheduled job daily
     */
    public function recalculateAllTiers(): array
    {
        $subscriptions = Subscription::with('organization')
            ->whereNotNull('tier_id')
            ->where('status', 'active')
            ->get();

        $results = [
            'checked' => 0,
            'changes_scheduled' => 0,
            'no_change' => 0,
        ];

        foreach ($subscriptions as $subscription) {
            $org = $subscription->organization;

            if (!$org) {
                continue;
            }

            $total12m = $this->calculate12MonthTotal($org);
            $newTier = $this->getTierForAmount($total12m);

            // Update cached total
            $subscription->update([
                'total_12m_donations' => $total12m,
                'last_tier_check' => now(),
            ]);

            $results['checked']++;

            // Check if tier should change
            if ($newTier->id !== $subscription->tier_id) {
                $this->scheduleTierChange(
                    $org,
                    $subscription->tier_id,
                    $newTier->id,
                    $total12m,
                    'scheduled_job'
                );
                $results['changes_scheduled']++;
            } else {
                $results['no_change']++;
            }
        }

        return $results;
    }

    /**
     * Apply all pending tier changes that are due
     * Run this on billing dates
     */
    public function applyPendingTierChanges(): array
    {
        $pendingLogs = TierChangeLog::with(['organization.subscription'])
            ->where('status', 'pending')
            ->get();

        $results = [
            'processed' => 0,
            'applied' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        foreach ($pendingLogs as $log) {
            $results['processed']++;

            $subscription = $log->organization->subscription;

            if (!$subscription) {
                $results['skipped']++;
                continue;
            }

            // Check if it's billing date
            $nextBilling = $subscription->next_billing_date;

            if (!$nextBilling || !$nextBilling->isToday()) {
                $results['skipped']++;
                continue;
            }

            try {
                $this->applyTierChange($log);
                $results['applied']++;
            } catch (\Exception $e) {
                $results['failed']++;
                Log::error("Failed to apply tier change #{$log->id}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Send notification about tier change
     */
    protected function notifyTierChange(Organization $org, TierChangeLog $log): void
    {
        $org->user->notify(new TierChangeScheduled($log));

        $log->update(['notification_sent' => true]);
    }
}
```

---

### **PHASE 5: Real-time Tier Checking** (2-3 days)

#### 5.1 Donation Observer

**app/Observers/DonationObserver.php:**
```php
<?php

namespace App\Observers;

use App\Models\Donation;
use App\Jobs\CheckTierAfterDonation;

class DonationObserver
{
    /**
     * Handle the Donation "created" event.
     */
    public function created(Donation $donation): void
    {
        // If payment is already successful, trigger tier check
        if ($donation->payment_status === 'success') {
            dispatch(new CheckTierAfterDonation($donation));
        }
    }

    /**
     * Handle the Donation "updated" event.
     */
    public function updated(Donation $donation): void
    {
        // If payment status just changed to success, trigger tier check
        if ($donation->wasChanged('payment_status') && $donation->payment_status === 'success') {
            // Only check if not already checked
            if (!$donation->triggered_tier_check) {
                dispatch(new CheckTierAfterDonation($donation));
            }
        }
    }
}
```

**Register observer in AppServiceProvider:**
```php
// app/Providers/AppServiceProvider.php

use App\Models\Donation;
use App\Observers\DonationObserver;

public function boot(): void
{
    Donation::observe(DonationObserver::class);
}
```

#### 5.2 Check Tier Job

**app/Jobs/CheckTierAfterDonation.php:**
```php
<?php

namespace App\Jobs;

use App\Models\Donation;
use App\Models\ActivityLog;
use App\Services\SubscriptionTierService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckTierAfterDonation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Donation $donation;

    /**
     * Create a new job instance.
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Execute the job.
     */
    public function handle(SubscriptionTierService $tierService): void
    {
        try {
            // Check tier
            $tierChangeLog = $tierService->checkTierAfterDonation($this->donation);

            // Mark donation as having triggered tier check
            $this->donation->update(['triggered_tier_check' => true]);

            if ($tierChangeLog) {
                // Log activity
                ActivityLog::create([
                    'user_id' => null,
                    'organization_id' => $this->donation->organization_id,
                    'action' => 'tier_change_scheduled',
                    'model_type' => TierChangeLog::class,
                    'model_id' => $tierChangeLog->id,
                    'description' => "Tier change scheduled: {$tierChangeLog->fromTier->name} → {$tierChangeLog->toTier->name} (triggered by donation #{$this->donation->id})",
                    'ip_address' => null,
                    'user_agent' => null,
                ]);

                Log::info("Tier check triggered by Donation #{$this->donation->id}: Tier change scheduled");
            } else {
                Log::info("Tier check triggered by Donation #{$this->donation->id}: No tier change needed");
            }

        } catch (\Exception $e) {
            Log::error("Failed to check tier after Donation #{$this->donation->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CheckTierAfterDonation job failed for Donation #{$this->donation->id}: " . $exception->getMessage());
    }
}
```

---

### **PHASE 6: Scheduled Jobs & Commands** (3-4 days)

#### 6.1 Daily Tier Recalculation Command

**app/Console/Commands/RecalculateSubscriptionTiers.php:**
```php
<?php

namespace App\Console\Commands;

use App\Services\SubscriptionTierService;
use Illuminate\Console\Command;

class RecalculateSubscriptionTiers extends Command
{
    protected $signature = 'subscriptions:recalculate-tiers';
    protected $description = 'Recalculate 12-month donation totals and check for tier changes';

    public function handle(SubscriptionTierService $tierService)
    {
        $this->info('Recalculating subscription tiers...');

        $results = $tierService->recalculateAllTiers();

        $this->info("✓ Checked: {$results['checked']} subscriptions");
        $this->info("✓ Changes scheduled: {$results['changes_scheduled']}");
        $this->info("✓ No change: {$results['no_change']}");

        return Command::SUCCESS;
    }
}
```

#### 6.2 Apply Pending Tier Changes Command

**app/Console/Commands/ApplyPendingTierChanges.php:**
```php
<?php

namespace App\Console\Commands;

use App\Services\SubscriptionTierService;
use Illuminate\Console\Command;

class ApplyPendingTierChanges extends Command
{
    protected $signature = 'subscriptions:apply-tier-changes';
    protected $description = 'Apply pending tier changes that are due today';

    public function handle(SubscriptionTierService $tierService)
    {
        $this->info('Applying pending tier changes...');

        $results = $tierService->applyPendingTierChanges();

        $this->info("✓ Processed: {$results['processed']} pending changes");
        $this->info("✓ Applied: {$results['applied']}");
        $this->info("✓ Skipped: {$results['skipped']}");

        if ($results['failed'] > 0) {
            $this->error("✗ Failed: {$results['failed']}");
        }

        return Command::SUCCESS;
    }
}
```

#### 6.3 Schedule Commands

**app/Console/Kernel.php:**
```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Recalculate tiers daily at 2 AM
        $schedule->command('subscriptions:recalculate-tiers')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Apply tier changes daily at 3 AM (after recalculation)
        $schedule->command('subscriptions:apply-tier-changes')
            ->dailyAt('03:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Check for offline devices every hour
        $schedule->command('devices:check-offline')
            ->hourly()
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
```

---

### **PHASE 7: Notifications** (2-3 days)

#### 7.1 Tier Change Scheduled Notification

**app/Notifications/TierChangeScheduled.php:**
```php
<?php

namespace App\Notifications;

use App\Models\TierChangeLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TierChangeScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public TierChangeLog $log;

    public function __construct(TierChangeLog $log)
    {
        $this->log = $log;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $org = $this->log->organization;
        $fromTier = $this->log->fromTier;
        $toTier = $this->log->toTier;
        $isUpgrade = $this->log->isUpgrade();

        $subject = $isUpgrade
            ? '🎉 Your Subscription Tier Will Be Upgraded'
            : 'Your Subscription Tier Will Change';

        $greeting = $isUpgrade
            ? 'Great news!'
            : 'Hi ' . $org->contact_person . ',';

        $message = new MailMessage;
        $message->subject($subject)
            ->greeting($greeting)
            ->line('Based on your fundraising success, your subscription tier will change:');

        if ($fromTier) {
            $message->line("**Current Tier:** {$fromTier->name} (€{$fromTier->monthly_fee}/month)");
        }

        $message->line("**New Tier:** {$toTier->name} (€{$toTier->monthly_fee}/month)")
            ->line("**Based on:** €" . number_format($this->log->donation_total_12m, 2) . " raised in the last 12 months")
            ->line("**Effective Date:** " . $org->subscription->next_billing_date->format('F j, Y'));

        if ($isUpgrade) {
            $message->line('Keep up the excellent work! 🎉');
        }

        $message->action('View Billing Details', url('/organization/billing'));

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'tier_change_scheduled',
            'tier_change_log_id' => $this->log->id,
            'from_tier' => $this->log->fromTier?->name,
            'to_tier' => $this->log->toTier->name,
            'scheduled_at' => $this->log->organization->subscription->next_billing_date->toIso8601String(),
            'is_upgrade' => $this->log->isUpgrade(),
        ];
    }
}
```

#### 7.2 Tier Upgrade Applied Notification

**app/Notifications/TierUpgradeApplied.php:**
```php
<?php

namespace App\Notifications;

use App\Models\TierChangeLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TierUpgradeApplied extends Notification implements ShouldQueue
{
    use Queueable;

    public TierChangeLog $log;

    public function __construct(TierChangeLog $log)
    {
        $this->log = $log;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $toTier = $this->log->toTier;

        return (new MailMessage)
            ->subject('🎉 Tier Upgraded Successfully')
            ->greeting('Congratulations!')
            ->line("Your subscription tier has been upgraded to **{$toTier->name}**.")
            ->line("**New Monthly Fee:** €{$toTier->monthly_fee}")
            ->line("This upgrade reflects your fundraising success. Thank you for making a difference!")
            ->action('View Subscription', url('/organization/billing'));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'tier_upgrade_applied',
            'tier_change_log_id' => $this->log->id,
            'new_tier' => $this->log->toTier->name,
            'new_fee' => $this->log->toTier->monthly_fee,
        ];
    }
}
```

#### 7.3 Tier Downgrade Applied Notification

**app/Notifications/TierDowngradeApplied.php:**
```php
<?php

namespace App\Notifications;

use App\Models\TierChangeLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TierDowngradeApplied extends Notification implements ShouldQueue
{
    use Queueable;

    public TierChangeLog $log;

    public function __construct(TierChangeLog $log)
    {
        $this->log = $log;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $toTier = $this->log->toTier;

        return (new MailMessage)
            ->subject('Subscription Tier Updated')
            ->greeting('Hi there,')
            ->line("Your subscription tier has been updated to **{$toTier->name}**.")
            ->line("**New Monthly Fee:** €{$toTier->monthly_fee}")
            ->line("This change is based on your fundraising volume over the last 12 months.")
            ->line("Your tier will automatically adjust as your fundraising grows.")
            ->action('View Subscription', url('/organization/billing'));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'tier_downgrade_applied',
            'tier_change_log_id' => $this->log->id,
            'new_tier' => $this->log->toTier->name,
            'new_fee' => $this->log->toTier->monthly_fee,
        ];
    }
}
```

#### 7.4 Payment Succeeded Notification

**app/Notifications/PaymentSucceeded.php:**
```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSucceeded extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $amount = $this->invoice->amount_paid / 100; // Convert from cents
        $currency = strtoupper($this->invoice->currency);

        return (new MailMessage)
            ->subject('Payment Received - Invoice #' . $this->invoice->number)
            ->greeting('Thank you!')
            ->line("We have successfully processed your payment of €{$amount}.")
            ->line("**Invoice Number:** {$this->invoice->number}")
            ->line("**Period:** " . date('F j, Y', $this->invoice->period_start) . " - " . date('F j, Y', $this->invoice->period_end))
            ->action('Download Invoice', $this->invoice->invoice_pdf)
            ->line('Thank you for using Dayaa!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'payment_succeeded',
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->amount_paid / 100,
            'invoice_number' => $this->invoice->number,
        ];
    }
}
```

#### 7.5 Payment Failed Notification

**app/Notifications/PaymentFailed.php:**
```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $amount = $this->invoice->amount_due / 100;

        return (new MailMessage)
            ->error()
            ->subject('⚠️ Payment Failed - Action Required')
            ->greeting('Payment Issue')
            ->line("We were unable to process your payment of €{$amount}.")
            ->line("**Invoice Number:** {$this->invoice->number}")
            ->line("Please update your payment method to avoid service interruption.")
            ->line("Your account will remain active for 14 days while we attempt to retry payment.")
            ->action('Update Payment Method', url('/organization/billing'))
            ->line('If you need assistance, please contact our support team.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'payment_failed',
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->amount_due / 100,
            'invoice_number' => $this->invoice->number,
        ];
    }
}
```

---

## **CONTINUED IN NEXT MESSAGE DUE TO LENGTH...**

This is Part 1 of the Implementation Plan. The plan continues with:
- Phase 8: Laravel API Endpoints
- Phase 9: React Native Mobile App
- Phase 10: Web Dashboard Updates
- Phase 11: Testing & QA
- Phase 12: Deployment
- Appendices (Database Schema, API Documentation, etc.)

Should I continue with the rest of the plan?

### **PHASE 8: Laravel API Endpoints** (4-5 days)

#### 8.1 API Routes

**routes/api.php:**
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\StripeWebhookController;

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public endpoints (no authentication)
    Route::post('/devices/pair', [DeviceController::class, 'pair']);
    
    // Device authenticated endpoints (Laravel Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Device Management
        Route::get('/devices/{deviceId}/campaign', [DeviceController::class, 'getCampaign']);
        Route::post('/devices/heartbeat', [DeviceController::class, 'heartbeat']);
        Route::post('/devices/unpair', [DeviceController::class, 'unpair']);
        Route::put('/devices/{deviceId}/update-info', [DeviceController::class, 'updateDeviceInfo']);
        
        // Campaign Data
        Route::get('/campaigns/{campaign}', [CampaignController::class, 'show']);
        
        // Donation Management
        Route::post('/donations', [DonationController::class, 'create']);
        Route::post('/donations/{donation}/confirm', [DonationController::class, 'confirm']);
        Route::post('/donations/{donation}/fail', [DonationController::class, 'fail']);
        Route::get('/donations/{donation}', [DonationController::class, 'show']);
        
        // Receipt
        Route::get('/donations/{donation}/receipt', [DonationController::class, 'receipt']);
    });
});

// Webhooks (exclude from CSRF protection)
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handleWebhook']);
```

#### 8.2 Device API Controller

**app/Http/Controllers/Api/DeviceController.php:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    /**
     * Pair a device with the platform
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function pair(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string|exists:devices,device_id',
            'pin' => 'required|string|size:4',
            'device_type' => 'nullable|string|in:ipad,android_tablet',
            'os_version' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        $device = Device::where('device_id', $validated['device_id'])->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        // Verify PIN (last 4 chars of device_id)
        $correctPin = substr($device->device_id, -4);
        
        if ($validated['pin'] !== $correctPin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN',
            ], 401);
        }

        // Generate API token (Laravel Sanctum)
        $token = $device->createToken('kiosk-app', ['device:access'])->plainTextToken;

        // Update device info
        $device->update([
            'status' => 'online',
            'last_active' => now(),
            'paired_at' => now(),
            'device_type' => $validated['device_type'] ?? 'ipad',
            'os_version' => $validated['os_version'] ?? null,
            'app_version' => $validated['app_version'] ?? null,
        ]);

        Log::info("Device paired: {$device->device_id}");

        return response()->json([
            'success' => true,
            'message' => 'Device paired successfully',
            'data' => [
                'token' => $token,
                'device' => [
                    'id' => $device->id,
                    'device_id' => $device->device_id,
                    'name' => $device->name,
                    'location' => $device->location,
                    'organization' => [
                        'id' => $device->organization->id,
                        'name' => $device->organization->name,
                        'logo' => $device->organization->logo ? asset('storage/' . $device->organization->logo) : null,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Get active campaign for device
     * 
     * @param Request $request
     * @param string $deviceId
     * @return JsonResponse
     */
    public function getCampaign(Request $request, string $deviceId): JsonResponse
    {
        $device = Device::where('device_id', $deviceId)
            ->with(['organization', 'campaigns' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('created_at', 'desc');
            }])
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        // Update last active
        $device->update(['last_active' => now()]);

        // Get first active campaign
        $campaign = $device->campaigns->first();

        if (!$campaign) {
            return response()->json([
                'success' => true,
                'data' => [
                    'campaign' => null,
                    'message' => 'No active campaign assigned',
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'campaign' => [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'description' => $campaign->description,
                    'type' => $campaign->campaign_type,
                    'currency' => $campaign->currency,
                    'status' => $campaign->status,
                    
                    // Design settings
                    'design' => $campaign->design_settings,
                    
                    // Amount settings
                    'amounts' => $campaign->amount_settings,
                    
                    // Organization info
                    'organization' => [
                        'name' => $device->organization->name,
                        'logo' => $device->organization->logo ? asset('storage/' . $device->organization->logo) : null,
                    ],
                ],
                'last_updated' => $campaign->updated_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Heartbeat - keep device connection alive
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string|exists:devices,device_id',
        ]);

        $device = Device::where('device_id', $validated['device_id'])->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        // Update last active
        $device->update(['last_active' => now()]);

        // Check if campaign has been updated
        $campaign = $device->campaigns()->where('status', 'active')->first();
        $campaignUpdated = false;

        if ($campaign && $request->has('last_campaign_update')) {
            $lastUpdate = \Carbon\Carbon::parse($request->last_campaign_update);
            $campaignUpdated = $campaign->updated_at->gt($lastUpdate);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => 'ok',
                'device_status' => $device->status,
                'campaign_updated' => $campaignUpdated,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Unpair device
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function unpair(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string|exists:devices,device_id',
        ]);

        $device = Device::where('device_id', $validated['device_id'])->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        // Revoke all tokens for this device
        $device->tokens()->delete();

        // Update device status
        $device->update([
            'status' => 'offline',
            'unpaired_at' => now(),
        ]);

        Log::info("Device unpaired: {$device->device_id}");

        return response()->json([
            'success' => true,
            'message' => 'Device unpaired successfully',
        ]);
    }

    /**
     * Update device information
     * 
     * @param Request $request
     * @param string $deviceId
     * @return JsonResponse
     */
    public function updateDeviceInfo(Request $request, string $deviceId): JsonResponse
    {
        $device = Device::where('device_id', $deviceId)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        $validated = $request->validate([
            'os_version' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        $device->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Device info updated',
        ]);
    }
}
```

#### 8.3 Donation API Controller

**app/Http/Controllers/Api/DonationController.php:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Device;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    /**
     * Create a new donation (pending payment)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:0.01|max:100000',
            'currency' => 'required|string|size:3',
        ]);

        $device = Device::findOrFail($validated['device_id']);
        $campaign = Campaign::findOrFail($validated['campaign_id']);

        // Verify device belongs to campaign's organization
        if ($device->organization_id !== $campaign->organization_id) {
            return response()->json([
                'success' => false,
                'message' => 'Device does not belong to campaign organization',
            ], 403);
        }

        // Create donation
        $donation = Donation::create([
            'organization_id' => $device->organization_id,
            'campaign_id' => $campaign->id,
            'device_id' => $device->id,
            'amount' => $validated['amount'],
            'currency' => strtoupper($validated['currency']),
            'payment_status' => 'pending',
            'payment_method' => 'sumup',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::info("Donation created: #{$donation->id} - €{$donation->amount}");

        return response()->json([
            'success' => true,
            'message' => 'Donation created successfully',
            'data' => [
                'donation' => [
                    'id' => $donation->id,
                    'receipt_number' => $donation->receipt_number,
                    'amount' => $donation->amount,
                    'currency' => $donation->currency,
                    'status' => $donation->payment_status,
                    'created_at' => $donation->created_at->toIso8601String(),
                ],
            ],
        ], 201);
    }

    /**
     * Confirm donation payment (successful)
     * 
     * @param Request $request
     * @param Donation $donation
     * @return JsonResponse
     */
    public function confirm(Request $request, Donation $donation): JsonResponse
    {
        if ($donation->payment_status === 'success') {
            return response()->json([
                'success' => false,
                'message' => 'Donation already confirmed',
            ], 400);
        }

        $validated = $request->validate([
            'sumup_transaction_id' => 'required|string',
            'sumup_tx_code' => 'nullable|string',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'donor_phone' => 'nullable|string|max:50',
        ]);

        // Update donation
        $donation->update([
            'payment_status' => 'success',
            'sumup_transaction_id' => $validated['sumup_transaction_id'],
            'transaction_id' => $validated['sumup_tx_code'] ?? $validated['sumup_transaction_id'],
            'donor_name' => $validated['donor_name'] ?? null,
            'donor_email' => $validated['donor_email'] ?? null,
            'donor_phone' => $validated['donor_phone'] ?? null,
        ]);

        Log::info("Donation confirmed: #{$donation->id} - {$validated['sumup_transaction_id']}");

        // Tier check will be triggered automatically by DonationObserver

        return response()->json([
            'success' => true,
            'message' => 'Donation confirmed successfully',
            'data' => [
                'donation' => [
                    'id' => $donation->id,
                    'receipt_number' => $donation->receipt_number,
                    'amount' => $donation->amount,
                    'currency' => $donation->currency,
                    'status' => $donation->payment_status,
                    'transaction_id' => $donation->transaction_id,
                ],
            ],
        ]);
    }

    /**
     * Mark donation payment as failed
     * 
     * @param Request $request
     * @param Donation $donation
     * @return JsonResponse
     */
    public function fail(Request $request, Donation $donation): JsonResponse
    {
        $validated = $request->validate([
            'error_message' => 'required|string',
            'error_code' => 'nullable|string',
        ]);

        $donation->update([
            'payment_status' => 'failed',
            'error_message' => $validated['error_message'],
            'error_code' => $validated['error_code'] ?? 'UNKNOWN',
        ]);

        Log::warning("Donation failed: #{$donation->id} - {$validated['error_message']}");

        return response()->json([
            'success' => true,
            'message' => 'Donation marked as failed',
            'data' => [
                'donation' => [
                    'id' => $donation->id,
                    'status' => $donation->payment_status,
                    'error_message' => $donation->error_message,
                ],
            ],
        ]);
    }

    /**
     * Get donation details
     * 
     * @param Donation $donation
     * @return JsonResponse
     */
    public function show(Donation $donation): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'donation' => [
                    'id' => $donation->id,
                    'receipt_number' => $donation->receipt_number,
                    'amount' => $donation->amount,
                    'currency' => $donation->currency,
                    'status' => $donation->payment_status,
                    'transaction_id' => $donation->transaction_id,
                    'created_at' => $donation->created_at->toIso8601String(),
                    'campaign' => [
                        'id' => $donation->campaign->id,
                        'name' => $donation->campaign->name,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Get donation receipt (for email/print)
     * 
     * @param Donation $donation
     * @return JsonResponse
     */
    public function receipt(Donation $donation): JsonResponse
    {
        if ($donation->payment_status !== 'success') {
            return response()->json([
                'success' => false,
                'message' => 'Receipt only available for successful donations',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'receipt' => [
                    'receipt_number' => $donation->receipt_number,
                    'date' => $donation->created_at->format('F j, Y'),
                    'time' => $donation->created_at->format('g:i A'),
                    'amount' => number_format($donation->amount, 2),
                    'currency' => $donation->currency,
                    'organization' => [
                        'name' => $donation->organization->name,
                        'charity_number' => $donation->organization->charity_number,
                        'address' => $donation->organization->address,
                    ],
                    'campaign' => [
                        'name' => $donation->campaign->name,
                    ],
                    'donor' => [
                        'name' => $donation->donor_name,
                        'email' => $donation->donor_email,
                    ],
                ],
            ],
        ]);
    }
}
```

#### 8.4 Campaign API Controller

**app/Http/Controllers/Api/CampaignController.php:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    /**
     * Get campaign details
     * 
     * @param Campaign $campaign
     * @return JsonResponse
     */
    public function show(Campaign $campaign): JsonResponse
    {
        if ($campaign->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Campaign is not active',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'campaign' => [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'description' => $campaign->description,
                    'type' => $campaign->campaign_type,
                    'currency' => $campaign->currency,
                    'design_settings' => $campaign->design_settings,
                    'amount_settings' => $campaign->amount_settings,
                    'organization' => [
                        'name' => $campaign->organization->name,
                        'logo' => $campaign->organization->logo ? asset('storage/' . $campaign->organization->logo) : null,
                    ],
                ],
            ],
        ]);
    }
}
```

---

### **PHASE 9: React Native Mobile App** (14-21 days)

#### 9.1 Project Setup

**Initialize React Native project:**
```bash
npx react-native@latest init DayaaKiosk
cd DayaaKiosk

# Install dependencies
npm install @react-navigation/native @react-navigation/stack
npm install react-native-screens react-native-safe-area-context
npm install axios
npm install react-native-config
npm install @react-native-async-storage/async-storage
npm install react-native-svg
npm install react-native-linear-gradient

# For iOS
cd ios && pod install && cd ..
```

**Install SumUp SDK:**
```bash
# For iOS (via CocoaPods)
# Add to ios/Podfile:
# pod 'SumUpSDK'

# For Android (via Gradle)
# Add to android/app/build.gradle:
# implementation 'com.sumup:merchant-sdk:4.1.0'
```

#### 9.2 Project Structure

```
DayaaKiosk/
├── src/
│   ├── screens/
│   │   ├── PairingScreen.js           # Device pairing with QR/PIN
│   │   ├── CampaignScreen.js          # Main campaign display
│   │   ├── AmountSelectionScreen.js   # Donation amount selection
│   │   ├── PaymentProcessingScreen.js # SumUp payment processing
│   │   ├── ThankYouScreen.js          # Success screen
│   │   ├── AdminScreen.js             # PIN-locked admin settings
│   │   └── NoCampaignScreen.js        # No campaign assigned
│   ├── services/
│   │   ├── ApiService.js              # Laravel API calls
│   │   ├── SumUpService.js            # SumUp SDK wrapper
│   │   ├── StorageService.js          # AsyncStorage management
│   │   ├── KioskService.js            # Kiosk mode utilities
│   │   └── OfflineQueueService.js     # Offline donation queue
│   ├── components/
│   │   ├── DonationButton.js          # Preset amount button
│   │   ├── CustomAmountInput.js       # Numeric keypad
│   │   ├── CampaignHeader.js          # Campaign branding
│   │   ├── LoadingOverlay.js          # Loading spinner
│   │   └── ErrorMessage.js            # Error display
│   ├── hooks/
│   │   ├── useCampaign.js             # Campaign data hook
│   │   ├── useHeartbeat.js            # Keep-alive polling
│   │   ├── useDonation.js             # Donation flow hook
│   │   └── useOfflineQueue.js         # Offline queue hook
│   ├── navigation/
│   │   └── AppNavigator.js            # Navigation stack
│   ├── utils/
│   │   ├── colors.js                  # Brand colors
│   │   ├── validators.js              # Input validation
│   │   └── formatters.js              # Currency/date formatting
│   ├── config/
│   │   └── api.js                     # API configuration
│   └── App.js                          # Main app component
├── android/                            # Android native code
├── ios/                                # iOS native code
├── .env.example                        # Environment variables template
└── package.json
```

#### 9.3 Core Services

**src/services/ApiService.js:**
```javascript
import axios from 'axios';
import Config from 'react-native-config';
import StorageService from './StorageService';

const API_URL = Config.API_URL || 'https://software.dayaatec.de/api/v1';

class ApiService {
  constructor() {
    this.client = axios.create({
      baseURL: API_URL,
      timeout: 30000,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    });

    // Add token to requests
    this.client.interceptors.request.use(async (config) => {
      const token = await StorageService.getToken();
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    });
  }

  /**
   * Pair device with platform
   */
  async pairDevice(deviceId, pin, deviceInfo = {}) {
    try {
      const response = await this.client.post('/devices/pair', {
        device_id: deviceId,
        pin: pin,
        device_type: deviceInfo.type || 'ipad',
        os_version: deviceInfo.osVersion,
        app_version: deviceInfo.appVersion,
      });

      if (response.data.success) {
        // Save token
        await StorageService.setToken(response.data.data.token);
        await StorageService.setDevice(response.data.data.device);
        
        return response.data.data;
      }

      throw new Error(response.data.message || 'Pairing failed');
    } catch (error) {
      console.error('Pairing error:', error);
      throw this.handleError(error);
    }
  }

  /**
   * Get active campaign for device
   */
  async getCampaign(deviceId) {
    try {
      const response = await this.client.get(`/devices/${deviceId}/campaign`);
      
      if (response.data.success) {
        const campaign = response.data.data.campaign;
        
        if (campaign) {
          await StorageService.setCampaign(campaign);
        }
        
        return campaign;
      }

      throw new Error(response.data.message || 'Failed to get campaign');
    } catch (error) {
      console.error('Get campaign error:', error);
      throw this.handleError(error);
    }
  }

  /**
   * Send heartbeat
   */
  async sendHeartbeat(deviceId, lastCampaignUpdate = null) {
    try {
      const payload = { device_id: deviceId };
      
      if (lastCampaignUpdate) {
        payload.last_campaign_update = lastCampaignUpdate;
      }

      const response = await this.client.post('/devices/heartbeat', payload);
      
      return response.data.data;
    } catch (error) {
      console.error('Heartbeat error:', error);
      // Don't throw on heartbeat errors, just log
      return null;
    }
  }

  /**
   * Create donation
   */
  async createDonation(deviceId, campaignId, amount, currency = 'EUR') {
    try {
      const response = await this.client.post('/donations', {
        device_id: deviceId,
        campaign_id: campaignId,
        amount: parseFloat(amount),
        currency: currency,
      });

      if (response.data.success) {
        return response.data.data.donation;
      }

      throw new Error(response.data.message || 'Failed to create donation');
    } catch (error) {
      console.error('Create donation error:', error);
      throw this.handleError(error);
    }
  }

  /**
   * Confirm donation (payment successful)
   */
  async confirmDonation(donationId, sumupData) {
    try {
      const response = await this.client.post(`/donations/${donationId}/confirm`, {
        sumup_transaction_id: sumupData.transactionId,
        sumup_tx_code: sumupData.txCode,
        donor_name: sumupData.donorName,
        donor_email: sumupData.donorEmail,
        donor_phone: sumupData.donorPhone,
      });

      if (response.data.success) {
        return response.data.data.donation;
      }

      throw new Error(response.data.message || 'Failed to confirm donation');
    } catch (error) {
      console.error('Confirm donation error:', error);
      throw this.handleError(error);
    }
  }

  /**
   * Mark donation as failed
   */
  async failDonation(donationId, errorMessage, errorCode = null) {
    try {
      const response = await this.client.post(`/donations/${donationId}/fail`, {
        error_message: errorMessage,
        error_code: errorCode,
      });

      return response.data.success;
    } catch (error) {
      console.error('Fail donation error:', error);
      // Don't throw, just return false
      return false;
    }
  }

  /**
   * Unpair device
   */
  async unpairDevice(deviceId) {
    try {
      const response = await this.client.post('/devices/unpair', {
        device_id: deviceId,
      });

      if (response.data.success) {
        await StorageService.clearAll();
        return true;
      }

      return false;
    } catch (error) {
      console.error('Unpair error:', error);
      return false;
    }
  }

  /**
   * Handle API errors
   */
  handleError(error) {
    if (error.response) {
      // Server responded with error
      const message = error.response.data?.message || 'Server error';
      return new Error(message);
    } else if (error.request) {
      // No response received
      return new Error('No connection to server. Please check your internet.');
    } else {
      // Request setup error
      return new Error(error.message || 'Request failed');
    }
  }
}

export default new ApiService();
```

**src/services/SumUpService.js:**
```javascript
import { NativeModules, Platform } from 'react-native';

const { SumUpSDK } = NativeModules;

class SumUpService {
  /**
   * Login to SumUp (iOS & Android)
   */
  async login(affiliateKey) {
    try {
      if (Platform.OS === 'ios') {
        return await SumUpSDK.login(affiliateKey);
      } else {
        return await SumUpSDK.login(affiliateKey);
      }
    } catch (error) {
      console.error('SumUp login error:', error);
      throw new Error('Failed to login to SumUp: ' + error.message);
    }
  }

  /**
   * Check if logged in
   */
  async isLoggedIn() {
    try {
      return await SumUpSDK.isLoggedIn();
    } catch (error) {
      console.error('SumUp isLoggedIn error:', error);
      return false;
    }
  }

  /**
   * Logout from SumUp
   */
  async logout() {
    try {
      return await SumUpSDK.logout();
    } catch (error) {
      console.error('SumUp logout error:', error);
      return false;
    }
  }

  /**
   * Pair card terminal
   */
  async pairTerminal() {
    try {
      return await SumUpSDK.presentCardReaderPage();
    } catch (error) {
      console.error('SumUp pair terminal error:', error);
      throw new Error('Failed to pair terminal: ' + error.message);
    }
  }

  /**
   * Process payment
   */
  async processPayment(amount, currency = 'EUR', title = 'Donation') {
    try {
      const payment = {
        total: parseFloat(amount),
        currency: currency,
        title: title,
        skipSuccessScreen: true,
      };

      const result = await SumUpSDK.checkout(payment);

      return {
        success: result.success || false,
        transactionId: result.transactionCode,
        txCode: result.txCode,
        amount: result.amount,
        currency: result.currency,
        cardType: result.cardType,
        entryMode: result.entryMode,
      };
    } catch (error) {
      console.error('SumUp payment error:', error);
      
      return {
        success: false,
        error: error.message || 'Payment failed',
        errorCode: error.code || 'UNKNOWN',
      };
    }
  }

  /**
   * Get terminal status
   */
  async getTerminalStatus() {
    try {
      return await SumUpSDK.getTerminalStatus();
    } catch (error) {
      console.error('SumUp terminal status error:', error);
      return null;
    }
  }
}

export default new SumUpService();
```

**src/services/StorageService.js:**
```javascript
import AsyncStorage from '@react-native-async-storage/async-storage';

const KEYS = {
  TOKEN: '@dayaa_token',
  DEVICE: '@dayaa_device',
  CAMPAIGN: '@dayaa_campaign',
  OFFLINE_QUEUE: '@dayaa_offline_queue',
  SETTINGS: '@dayaa_settings',
};

class StorageService {
  /**
   * Save authentication token
   */
  async setToken(token) {
    try {
      await AsyncStorage.setItem(KEYS.TOKEN, token);
    } catch (error) {
      console.error('Error saving token:', error);
    }
  }

  /**
   * Get authentication token
   */
  async getToken() {
    try {
      return await AsyncStorage.getItem(KEYS.TOKEN);
    } catch (error) {
      console.error('Error getting token:', error);
      return null;
    }
  }

  /**
   * Save device info
   */
  async setDevice(device) {
    try {
      await AsyncStorage.setItem(KEYS.DEVICE, JSON.stringify(device));
    } catch (error) {
      console.error('Error saving device:', error);
    }
  }

  /**
   * Get device info
   */
  async getDevice() {
    try {
      const device = await AsyncStorage.getItem(KEYS.DEVICE);
      return device ? JSON.parse(device) : null;
    } catch (error) {
      console.error('Error getting device:', error);
      return null;
    }
  }

  /**
   * Save campaign data
   */
  async setCampaign(campaign) {
    try {
      await AsyncStorage.setItem(KEYS.CAMPAIGN, JSON.stringify(campaign));
    } catch (error) {
      console.error('Error saving campaign:', error);
    }
  }

  /**
   * Get campaign data
   */
  async getCampaign() {
    try {
      const campaign = await AsyncStorage.getItem(KEYS.CAMPAIGN);
      return campaign ? JSON.parse(campaign) : null;
    } catch (error) {
      console.error('Error getting campaign:', error);
      return null;
    }
  }

  /**
   * Save offline queue
   */
  async setOfflineQueue(queue) {
    try {
      await AsyncStorage.setItem(KEYS.OFFLINE_QUEUE, JSON.stringify(queue));
    } catch (error) {
      console.error('Error saving offline queue:', error);
    }
  }

  /**
   * Get offline queue
   */
  async getOfflineQueue() {
    try {
      const queue = await AsyncStorage.getItem(KEYS.OFFLINE_QUEUE);
      return queue ? JSON.parse(queue) : [];
    } catch (error) {
      console.error('Error getting offline queue:', error);
      return [];
    }
  }

  /**
   * Clear all storage
   */
  async clearAll() {
    try {
      await AsyncStorage.multiRemove([
        KEYS.TOKEN,
        KEYS.DEVICE,
        KEYS.CAMPAIGN,
        KEYS.OFFLINE_QUEUE,
      ]);
    } catch (error) {
      console.error('Error clearing storage:', error);
    }
  }
}

export default new StorageService();
```

#### 9.4 Key Screens

**src/screens/PairingScreen.js:**
```javascript
import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
  Platform,
} from 'react-native';
import ApiService from '../services/ApiService';
import { VERSION } from '../config/constants';

const PairingScreen = ({ navigation }) => {
  const [deviceId, setDeviceId] = useState('');
  const [pin, setPin] = useState('');
  const [loading, setLoading] = useState(false);

  const handlePair = async () => {
    if (!deviceId || !pin) {
      Alert.alert('Error', 'Please enter Device ID and PIN');
      return;
    }

    if (pin.length !== 4) {
      Alert.alert('Error', 'PIN must be 4 digits');
      return;
    }

    setLoading(true);

    try {
      const deviceInfo = {
        type: Platform.OS === 'ios' ? 'ipad' : 'android_tablet',
        osVersion: Platform.Version,
        appVersion: VERSION,
      };

      const result = await ApiService.pairDevice(deviceId, pin, deviceInfo);

      Alert.alert('Success', 'Device paired successfully!', [
        {
          text: 'OK',
          onPress: () => navigation.replace('Campaign'),
        },
      ]);
    } catch (error) {
      Alert.alert('Pairing Failed', error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <View style={styles.card}>
        <Text style={styles.title}>Pair Device</Text>
        <Text style={styles.subtitle}>
          Enter your Device ID and PIN to get started
        </Text>

        <TextInput
          style={styles.input}
          placeholder="Device ID (e.g., DEV-XXXX)"
          value={deviceId}
          onChangeText={setDeviceId}
          autoCapitalize="characters"
          autoCorrect={false}
        />

        <TextInput
          style={styles.input}
          placeholder="PIN (4 digits)"
          value={pin}
          onChangeText={setPin}
          keyboardType="number-pad"
          maxLength={4}
          secureTextEntry
        />

        <TouchableOpacity
          style={[styles.button, loading && styles.buttonDisabled]}
          onPress={handlePair}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.buttonText}>Pair Device</Text>
          )}
        </TouchableOpacity>

        <Text style={styles.helpText}>
          Don't have a Device ID? Contact your organization admin.
        </Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F3F4F6',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 20,
    padding: 40,
    width: '100%',
    maxWidth: 500,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.1,
    shadowRadius: 20,
    elevation: 10,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 10,
    textAlign: 'center',
  },
  subtitle: {
    fontSize: 16,
    color: '#6B7280',
    marginBottom: 30,
    textAlign: 'center',
  },
  input: {
    backgroundColor: '#F9FAFB',
    borderWidth: 2,
    borderColor: '#E5E7EB',
    borderRadius: 12,
    padding: 16,
    fontSize: 18,
    marginBottom: 20,
  },
  button: {
    backgroundColor: '#1163F0',
    borderRadius: 12,
    padding: 18,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonDisabled: {
    backgroundColor: '#9CA3AF',
  },
  buttonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: '600',
  },
  helpText: {
    marginTop: 20,
    fontSize: 14,
    color: '#6B7280',
    textAlign: 'center',
  },
});

export default PairingScreen;
```

**Note:** Due to length constraints, I'll continue with the remaining screens and phases in the next response. The implementation plan includes:

- More React Native screens (Campaign, Amount Selection, Payment, Thank You)
- Phase 10: Web Dashboard Updates
- Phase 11: Multilingual Support  
- Phase 12: Testing & QA
- Phase 13: Deployment
- Appendices (Timeline, ER Diagram, API Docs)

Should I continue appending to the file?

### 9.4 React Native Screens (Continued)

#### `src/screens/CampaignScreen.js`
```javascript
import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  Image,
  StyleSheet,
  ActivityIndicator,
  TouchableOpacity,
  SafeAreaView,
} from 'react-native';
import { useCampaign } from '../hooks/useCampaign';
import { useHeartbeat } from '../hooks/useHeartbeat';

const CampaignScreen = ({ navigation }) => {
  const { campaign, loading, error, refreshCampaign } = useCampaign();
  useHeartbeat(); // Send heartbeat every 60 seconds

  useEffect(() => {
    const interval = setInterval(() => {
      refreshCampaign(); // Refresh campaign every 5 minutes
    }, 5 * 60 * 1000);

    return () => clearInterval(interval);
  }, []);

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
        <Text style={styles.loadingText}>Loading Campaign...</Text>
      </View>
    );
  }

  if (error || !campaign) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>
          {error || 'No active campaign found'}
        </Text>
        <TouchableOpacity
          style={styles.retryButton}
          onPress={refreshCampaign}
        >
          <Text style={styles.retryButtonText}>Retry</Text>
        </TouchableOpacity>
      </View>
    );
  }

  const { design_settings } = campaign;
  const backgroundColor = design_settings?.background_color || '#ffffff';
  const primaryColor = design_settings?.primary_color || '#3b82f6';
  const textColor = design_settings?.text_color || '#000000';

  return (
    <SafeAreaView
      style={[styles.container, { backgroundColor }]}
    >
      {/* Logo */}
      {design_settings?.logo_url && (
        <Image
          source={{ uri: design_settings.logo_url }}
          style={styles.logo}
          resizeMode="contain"
        />
      )}

      {/* Campaign Title */}
      <Text style={[styles.title, { color: textColor }]}>
        {campaign.name}
      </Text>

      {/* Campaign Description */}
      {campaign.description && (
        <Text style={[styles.description, { color: textColor }]}>
          {campaign.description}
        </Text>
      )}

      {/* Hero Image */}
      {design_settings?.hero_image_url && (
        <Image
          source={{ uri: design_settings.hero_image_url }}
          style={styles.heroImage}
          resizeMode="cover"
        />
      )}

      {/* Call to Action */}
      <TouchableOpacity
        style={[styles.donateButton, { backgroundColor: primaryColor }]}
        onPress={() => navigation.navigate('AmountSelection')}
        activeOpacity={0.8}
      >
        <Text style={styles.donateButtonText}>
          {design_settings?.cta_text || 'Donate Now'}
        </Text>
      </TouchableOpacity>

      {/* Footer Message */}
      {design_settings?.footer_message && (
        <Text style={[styles.footer, { color: textColor }]}>
          {design_settings.footer_message}
        </Text>
      )}
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
  },
  centerContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#f9fafb',
  },
  loadingText: {
    marginTop: 16,
    fontSize: 18,
    color: '#6b7280',
  },
  errorText: {
    fontSize: 18,
    color: '#ef4444',
    textAlign: 'center',
    marginBottom: 20,
  },
  retryButton: {
    backgroundColor: '#3b82f6',
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 8,
  },
  retryButtonText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: '600',
  },
  logo: {
    width: 200,
    height: 100,
    marginBottom: 32,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 16,
  },
  description: {
    fontSize: 18,
    textAlign: 'center',
    marginBottom: 24,
    paddingHorizontal: 20,
  },
  heroImage: {
    width: '100%',
    height: 300,
    borderRadius: 12,
    marginBottom: 32,
  },
  donateButton: {
    paddingHorizontal: 48,
    paddingVertical: 20,
    borderRadius: 12,
    elevation: 4,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 4,
  },
  donateButtonText: {
    color: '#ffffff',
    fontSize: 24,
    fontWeight: 'bold',
  },
  footer: {
    marginTop: 32,
    fontSize: 14,
    textAlign: 'center',
  },
});

export default CampaignScreen;
```

#### `src/screens/AmountSelectionScreen.js`
```javascript
import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  TextInput,
  StyleSheet,
  SafeAreaView,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { useCampaign } from '../hooks/useCampaign';

const AmountSelectionScreen = ({ navigation }) => {
  const { campaign } = useCampaign();
  const [selectedAmount, setSelectedAmount] = useState(null);
  const [customAmount, setCustomAmount] = useState('');
  const [showCustomInput, setShowCustomInput] = useState(false);

  const { design_settings, amount_settings } = campaign || {};
  const primaryColor = design_settings?.primary_color || '#3b82f6';
  const textColor = design_settings?.text_color || '#000000';
  const backgroundColor = design_settings?.background_color || '#ffffff';

  const presetAmounts = amount_settings?.preset_amounts || [5, 10, 20, 50, 100];
  const minAmount = amount_settings?.min_amount || 1;
  const maxAmount = amount_settings?.max_amount || 10000;

  const handlePresetAmount = (amount) => {
    setSelectedAmount(amount);
    setShowCustomInput(false);
    setCustomAmount('');
  };

  const handleCustomAmount = () => {
    setShowCustomInput(true);
    setSelectedAmount(null);
  };

  const handleCustomAmountChange = (value) => {
    // Only allow numbers and decimals
    const cleaned = value.replace(/[^0-9.]/g, '');
    setCustomAmount(cleaned);
    
    const amount = parseFloat(cleaned);
    if (!isNaN(amount)) {
      setSelectedAmount(amount);
    }
  };

  const handleContinue = () => {
    if (!selectedAmount || selectedAmount < minAmount || selectedAmount > maxAmount) {
      alert(`Please select an amount between €${minAmount} and €${maxAmount}`);
      return;
    }

    navigation.navigate('PaymentProcessing', { amount: selectedAmount });
  };

  return (
    <SafeAreaView style={[styles.container, { backgroundColor }]}>
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={styles.keyboardView}
      >
        <View style={styles.content}>
          {/* Header */}
          <Text style={[styles.title, { color: textColor }]}>
            Select Donation Amount
          </Text>

          {/* Preset Amounts */}
          <View style={styles.amountGrid}>
            {presetAmounts.map((amount) => (
              <TouchableOpacity
                key={amount}
                style={[
                  styles.amountButton,
                  selectedAmount === amount && !showCustomInput && {
                    backgroundColor: primaryColor,
                    borderColor: primaryColor,
                  },
                ]}
                onPress={() => handlePresetAmount(amount)}
                activeOpacity={0.7}
              >
                <Text
                  style={[
                    styles.amountText,
                    selectedAmount === amount && !showCustomInput && styles.amountTextSelected,
                  ]}
                >
                  €{amount}
                </Text>
              </TouchableOpacity>
            ))}

            {/* Custom Amount Button */}
            <TouchableOpacity
              style={[
                styles.amountButton,
                showCustomInput && {
                  backgroundColor: primaryColor,
                  borderColor: primaryColor,
                },
              ]}
              onPress={handleCustomAmount}
              activeOpacity={0.7}
            >
              <Text
                style={[
                  styles.amountText,
                  showCustomInput && styles.amountTextSelected,
                ]}
              >
                Custom
              </Text>
            </TouchableOpacity>
          </View>

          {/* Custom Amount Input */}
          {showCustomInput && (
            <View style={styles.customInputContainer}>
              <Text style={[styles.customLabel, { color: textColor }]}>
                Enter Custom Amount
              </Text>
              <View style={styles.inputWrapper}>
                <Text style={styles.currencySymbol}>€</Text>
                <TextInput
                  style={styles.customInput}
                  value={customAmount}
                  onChangeText={handleCustomAmountChange}
                  keyboardType="decimal-pad"
                  placeholder="0.00"
                  placeholderTextColor="#9ca3af"
                  autoFocus
                />
              </View>
              <Text style={styles.rangeHint}>
                Min: €{minAmount} | Max: €{maxAmount}
              </Text>
            </View>
          )}

          {/* Action Buttons */}
          <View style={styles.actionButtons}>
            <TouchableOpacity
              style={styles.cancelButton}
              onPress={() => navigation.goBack()}
            >
              <Text style={styles.cancelButtonText}>Cancel</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={[
                styles.continueButton,
                { backgroundColor: primaryColor },
                !selectedAmount && styles.continueButtonDisabled,
              ]}
              onPress={handleContinue}
              disabled={!selectedAmount}
            >
              <Text style={styles.continueButtonText}>Continue</Text>
            </TouchableOpacity>
          </View>
        </View>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  keyboardView: {
    flex: 1,
  },
  content: {
    flex: 1,
    padding: 20,
    justifyContent: 'center',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 32,
  },
  amountGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'center',
    marginBottom: 24,
  },
  amountButton: {
    width: '28%',
    aspectRatio: 1.5,
    margin: '2%',
    borderWidth: 2,
    borderColor: '#d1d5db',
    borderRadius: 12,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#ffffff',
  },
  amountText: {
    fontSize: 24,
    fontWeight: '600',
    color: '#374151',
  },
  amountTextSelected: {
    color: '#ffffff',
  },
  customInputContainer: {
    marginBottom: 32,
  },
  customLabel: {
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 12,
    textAlign: 'center',
  },
  inputWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#3b82f6',
    borderRadius: 12,
    paddingHorizontal: 16,
    backgroundColor: '#ffffff',
  },
  currencySymbol: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#374151',
    marginRight: 8,
  },
  customInput: {
    flex: 1,
    fontSize: 32,
    fontWeight: 'bold',
    color: '#374151',
    paddingVertical: 16,
  },
  rangeHint: {
    marginTop: 8,
    fontSize: 14,
    color: '#6b7280',
    textAlign: 'center',
  },
  actionButtons: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 32,
  },
  cancelButton: {
    flex: 1,
    marginRight: 12,
    paddingVertical: 16,
    borderRadius: 12,
    borderWidth: 2,
    borderColor: '#d1d5db',
    backgroundColor: '#ffffff',
    alignItems: 'center',
  },
  cancelButtonText: {
    fontSize: 18,
    fontWeight: '600',
    color: '#374151',
  },
  continueButton: {
    flex: 1,
    marginLeft: 12,
    paddingVertical: 16,
    borderRadius: 12,
    alignItems: 'center',
  },
  continueButtonDisabled: {
    opacity: 0.5,
  },
  continueButtonText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#ffffff',
  },
});

export default AmountSelectionScreen;
```

#### `src/screens/PaymentProcessingScreen.js`
```javascript
import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ActivityIndicator,
  StyleSheet,
  SafeAreaView,
  Alert,
} from 'react-native';
import { useCampaign } from '../hooks/useCampaign';
import { useDonation } from '../hooks/useDonation';
import SumUpService from '../services/SumUpService';

const PaymentProcessingScreen = ({ route, navigation }) => {
  const { amount } = route.params;
  const { campaign } = useCampaign();
  const { createDonation } = useDonation();
  const [status, setStatus] = useState('initiating'); // initiating, processing, success, failed

  useEffect(() => {
    processPayment();
  }, []);

  const processPayment = async () => {
    try {
      // Step 1: Initiate donation on backend
      setStatus('initiating');
      const donation = await createDonation(amount);

      // Step 2: Process payment via SumUp
      setStatus('processing');
      const paymentResult = await SumUpService.processPayment(amount, 'EUR', {
        donationId: donation.id,
        campaignName: campaign.name,
      });

      if (paymentResult.success) {
        // Step 3: Update donation with transaction details
        await updateDonationSuccess(donation.id, paymentResult);
        
        setStatus('success');
        
        // Navigate to Thank You screen after 1 second
        setTimeout(() => {
          navigation.replace('ThankYou', {
            amount,
            receiptNumber: donation.receipt_number,
          });
        }, 1000);
      } else {
        throw new Error(paymentResult.message || 'Payment failed');
      }
    } catch (error) {
      console.error('Payment error:', error);
      setStatus('failed');
      
      Alert.alert(
        'Payment Failed',
        error.message || 'Unable to process payment. Please try again.',
        [
          {
            text: 'Try Again',
            onPress: () => navigation.goBack(),
          },
          {
            text: 'Cancel',
            onPress: () => navigation.navigate('Campaign'),
            style: 'cancel',
          },
        ]
      );
    }
  };

  const updateDonationSuccess = async (donationId, paymentResult) => {
    // This would call your API to update the donation
    // The webhook will also update it, but this provides immediate feedback
    try {
      await ApiService.updateDonation(donationId, {
        payment_status: 'completed',
        sumup_transaction_id: paymentResult.transactionId,
        sumup_transaction_code: paymentResult.transactionCode,
      });
    } catch (error) {
      console.error('Error updating donation:', error);
      // Don't throw - webhook will handle this
    }
  };

  const getStatusMessage = () => {
    switch (status) {
      case 'initiating':
        return 'Preparing payment...';
      case 'processing':
        return 'Processing payment...';
      case 'success':
        return 'Payment successful!';
      case 'failed':
        return 'Payment failed';
      default:
        return 'Please wait...';
    }
  };

  const getStatusColor = () => {
    switch (status) {
      case 'success':
        return '#10b981';
      case 'failed':
        return '#ef4444';
      default:
        return '#3b82f6';
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.content}>
        <ActivityIndicator
          size="large"
          color={getStatusColor()}
          animating={status !== 'failed'}
        />
        
        <Text style={[styles.statusText, { color: getStatusColor() }]}>
          {getStatusMessage()}
        </Text>

        <Text style={styles.amountText}>€{amount.toFixed(2)}</Text>

        {status === 'processing' && (
          <Text style={styles.hintText}>
            Please follow the prompts on the card terminal
          </Text>
        )}
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f9fafb',
  },
  content: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
  },
  statusText: {
    fontSize: 24,
    fontWeight: 'bold',
    marginTop: 24,
    textAlign: 'center',
  },
  amountText: {
    fontSize: 48,
    fontWeight: 'bold',
    color: '#374151',
    marginTop: 16,
  },
  hintText: {
    fontSize: 16,
    color: '#6b7280',
    marginTop: 32,
    textAlign: 'center',
  },
});

export default PaymentProcessingScreen;
```

#### `src/screens/ThankYouScreen.js`
```javascript
import React, { useEffect, useRef } from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  Animated,
} from 'react-native';
import { useCampaign } from '../hooks/useCampaign';

const ThankYouScreen = ({ route, navigation }) => {
  const { amount, receiptNumber } = route.params;
  const { campaign } = useCampaign();
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const scaleAnim = useRef(new Animated.Value(0.5)).current;

  const { design_settings } = campaign || {};
  const primaryColor = design_settings?.primary_color || '#3b82f6';
  const backgroundColor = design_settings?.background_color || '#ffffff';
  const textColor = design_settings?.text_color || '#000000';
  const thankYouMessage = design_settings?.thank_you_message || 'Thank you for your donation!';

  useEffect(() => {
    // Animate checkmark
    Animated.parallel([
      Animated.timing(fadeAnim, {
        toValue: 1,
        duration: 500,
        useNativeDriver: true,
      }),
      Animated.spring(scaleAnim, {
        toValue: 1,
        friction: 3,
        useNativeDriver: true,
      }),
    ]).start();

    // Auto-reset to campaign screen after 10 seconds
    const timer = setTimeout(() => {
      navigation.navigate('Campaign');
    }, 10000);

    return () => clearTimeout(timer);
  }, []);

  return (
    <SafeAreaView style={[styles.container, { backgroundColor }]}>
      <View style={styles.content}>
        {/* Animated Checkmark */}
        <Animated.View
          style={[
            styles.checkmarkContainer,
            { backgroundColor: primaryColor },
            {
              opacity: fadeAnim,
              transform: [{ scale: scaleAnim }],
            },
          ]}
        >
          <Text style={styles.checkmark}>✓</Text>
        </Animated.View>

        {/* Thank You Message */}
        <Text style={[styles.title, { color: textColor }]}>
          {thankYouMessage}
        </Text>

        {/* Amount */}
        <Text style={[styles.amount, { color: primaryColor }]}>
          €{amount.toFixed(2)}
        </Text>

        {/* Receipt Number */}
        {receiptNumber && (
          <Text style={[styles.receipt, { color: textColor }]}>
            Receipt #{receiptNumber}
          </Text>
        )}

        {/* Additional Message */}
        <Text style={[styles.message, { color: textColor }]}>
          Your contribution makes a difference
        </Text>

        {/* Auto-reset countdown */}
        <Text style={styles.countdown}>
          Returning to home screen in 10 seconds...
        </Text>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  content: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
  },
  checkmarkContainer: {
    width: 120,
    height: 120,
    borderRadius: 60,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 32,
  },
  checkmark: {
    fontSize: 72,
    color: '#ffffff',
    fontWeight: 'bold',
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 16,
  },
  amount: {
    fontSize: 56,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  receipt: {
    fontSize: 18,
    marginBottom: 24,
  },
  message: {
    fontSize: 20,
    textAlign: 'center',
    marginBottom: 48,
  },
  countdown: {
    fontSize: 14,
    color: '#9ca3af',
    textAlign: 'center',
  },
});

export default ThankYouScreen;
```

#### `src/screens/AdminScreen.js`
```javascript
import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  Alert,
} from 'react-native';
import StorageService from '../services/StorageService';
import ApiService from '../services/ApiService';

const AdminScreen = ({ navigation }) => {
  const [pin, setPin] = useState('');
  const [authenticated, setAuthenticated] = useState(false);
  const [offlineCount, setOfflineCount] = useState(0);

  const ADMIN_PIN = '9999'; // In production, this should be configurable

  const handlePinSubmit = () => {
    if (pin === ADMIN_PIN) {
      setAuthenticated(true);
      loadOfflineCount();
    } else {
      Alert.alert('Error', 'Invalid PIN');
      setPin('');
    }
  };

  const loadOfflineCount = async () => {
    const queue = await StorageService.getOfflineQueue();
    setOfflineCount(queue.length);
  };

  const handleUnpair = async () => {
    Alert.alert(
      'Unpair Device',
      'Are you sure you want to unpair this device? You will need to pair again to use the kiosk.',
      [
        { text: 'Cancel', style: 'cancel' },
        {
          text: 'Unpair',
          style: 'destructive',
          onPress: async () => {
            try {
              await ApiService.unpairDevice();
              await StorageService.clearAll();
              
              Alert.alert('Success', 'Device unpaired successfully', [
                { text: 'OK', onPress: () => navigation.replace('Pairing') },
              ]);
            } catch (error) {
              Alert.alert('Error', 'Failed to unpair device');
            }
          },
        },
      ]
    );
  };

  const handleSyncOffline = async () => {
    try {
      const queue = await StorageService.getOfflineQueue();
      
      if (queue.length === 0) {
        Alert.alert('Info', 'No offline donations to sync');
        return;
      }

      let successCount = 0;
      const failedDonations = [];

      for (const donation of queue) {
        try {
          await ApiService.createDonation(donation);
          successCount++;
        } catch (error) {
          failedDonations.push(donation);
        }
      }

      // Update queue with only failed donations
      await StorageService.saveOfflineQueue(failedDonations);
      setOfflineCount(failedDonations.length);

      Alert.alert(
        'Sync Complete',
        `Synced ${successCount} donations. ${failedDonations.length} failed.`
      );
    } catch (error) {
      Alert.alert('Error', 'Failed to sync offline donations');
    }
  };

  const handleExit = () => {
    navigation.goBack();
  };

  if (!authenticated) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.pinContainer}>
          <Text style={styles.title}>Admin Access</Text>
          <Text style={styles.subtitle}>Enter PIN to continue</Text>

          <TextInput
            style={styles.pinInput}
            value={pin}
            onChangeText={setPin}
            keyboardType="number-pad"
            secureTextEntry
            maxLength={4}
            autoFocus
          />

          <View style={styles.buttonRow}>
            <TouchableOpacity
              style={[styles.button, styles.cancelButton]}
              onPress={() => navigation.goBack()}
            >
              <Text style={styles.cancelButtonText}>Cancel</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={[styles.button, styles.submitButton]}
              onPress={handlePinSubmit}
            >
              <Text style={styles.submitButtonText}>Submit</Text>
            </TouchableOpacity>
          </View>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.adminContainer}>
        <Text style={styles.title}>Admin Settings</Text>

        {/* Device Info */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Device Information</Text>
          <Text style={styles.infoText}>Device ID: {/* Show device ID */}</Text>
          <Text style={styles.infoText}>Status: Online</Text>
        </View>

        {/* Offline Queue */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Offline Donations</Text>
          <Text style={styles.infoText}>
            Pending Sync: {offlineCount} donations
          </Text>
          
          {offlineCount > 0 && (
            <TouchableOpacity
              style={styles.actionButton}
              onPress={handleSyncOffline}
            >
              <Text style={styles.actionButtonText}>Sync Now</Text>
            </TouchableOpacity>
          )}
        </View>

        {/* Actions */}
        <View style={styles.section}>
          <TouchableOpacity
            style={[styles.actionButton, styles.dangerButton]}
            onPress={handleUnpair}
          >
            <Text style={styles.actionButtonText}>Unpair Device</Text>
          </TouchableOpacity>
        </View>

        {/* Exit */}
        <TouchableOpacity style={styles.exitButton} onPress={handleExit}>
          <Text style={styles.exitButtonText}>Exit Admin</Text>
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f9fafb',
  },
  pinContainer: {
    flex: 1,
    justifyContent: 'center',
    padding: 20,
  },
  adminContainer: {
    flex: 1,
    padding: 20,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 8,
    color: '#111827',
  },
  subtitle: {
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 32,
    color: '#6b7280',
  },
  pinInput: {
    fontSize: 32,
    fontWeight: 'bold',
    textAlign: 'center',
    borderWidth: 2,
    borderColor: '#3b82f6',
    borderRadius: 12,
    padding: 16,
    marginBottom: 24,
    backgroundColor: '#ffffff',
  },
  buttonRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  button: {
    flex: 1,
    paddingVertical: 16,
    borderRadius: 12,
    alignItems: 'center',
  },
  cancelButton: {
    marginRight: 12,
    borderWidth: 2,
    borderColor: '#d1d5db',
    backgroundColor: '#ffffff',
  },
  cancelButtonText: {
    fontSize: 18,
    fontWeight: '600',
    color: '#374151',
  },
  submitButton: {
    marginLeft: 12,
    backgroundColor: '#3b82f6',
  },
  submitButtonText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#ffffff',
  },
  section: {
    backgroundColor: '#ffffff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 16,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 12,
    color: '#111827',
  },
  infoText: {
    fontSize: 16,
    color: '#6b7280',
    marginBottom: 8,
  },
  actionButton: {
    backgroundColor: '#3b82f6',
    paddingVertical: 12,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 12,
  },
  dangerButton: {
    backgroundColor: '#ef4444',
  },
  actionButtonText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#ffffff',
  },
  exitButton: {
    marginTop: 'auto',
    paddingVertical: 16,
    borderRadius: 12,
    borderWidth: 2,
    borderColor: '#d1d5db',
    backgroundColor: '#ffffff',
    alignItems: 'center',
  },
  exitButtonText: {
    fontSize: 18,
    fontWeight: '600',
    color: '#374151',
  },
});

export default AdminScreen;
```

### 9.5 React Native Custom Hooks

#### `src/hooks/useCampaign.js`
```javascript
import { useState, useEffect } from 'react';
import ApiService from '../services/ApiService';
import StorageService from '../services/StorageService';

export const useCampaign = () => {
  const [campaign, setCampaign] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchCampaign = async () => {
    try {
      setLoading(true);
      setError(null);

      const device = await StorageService.getDevice();
      if (!device) {
        throw new Error('Device not paired');
      }

      const response = await ApiService.getActiveCampaign();
      
      if (response.success && response.data) {
        setCampaign(response.data);
        // Cache campaign data
        await StorageService.saveCampaign(response.data);
      } else {
        // Try to load from cache
        const cachedCampaign = await StorageService.getCampaign();
        if (cachedCampaign) {
          setCampaign(cachedCampaign);
        } else {
          throw new Error('No active campaign found');
        }
      }
    } catch (err) {
      console.error('Error fetching campaign:', err);
      setError(err.message);
      
      // Try to load from cache on error
      const cachedCampaign = await StorageService.getCampaign();
      if (cachedCampaign) {
        setCampaign(cachedCampaign);
        setError(null); // Clear error if we have cached data
      }
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCampaign();
  }, []);

  return {
    campaign,
    loading,
    error,
    refreshCampaign: fetchCampaign,
  };
};
```

#### `src/hooks/useHeartbeat.js`
```javascript
import { useEffect } from 'react';
import ApiService from '../services/ApiService';
import NetInfo from '@react-native-community/netinfo';

export const useHeartbeat = (intervalMs = 60000) => {
  useEffect(() => {
    const sendHeartbeat = async () => {
      try {
        const netState = await NetInfo.fetch();
        
        if (netState.isConnected) {
          await ApiService.sendHeartbeat({
            ip_address: netState.details?.ipAddress || 'unknown',
            status: 'online',
          });
        }
      } catch (error) {
        console.error('Heartbeat error:', error);
        // Fail silently - don't disrupt user experience
      }
    };

    // Send initial heartbeat
    sendHeartbeat();

    // Set up interval
    const interval = setInterval(sendHeartbeat, intervalMs);

    return () => clearInterval(interval);
  }, [intervalMs]);
};
```

#### `src/hooks/useDonation.js`
```javascript
import { useState } from 'react';
import ApiService from '../services/ApiService';
import StorageService from '../services/StorageService';
import NetInfo from '@react-native-community/netinfo';

export const useDonation = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const createDonation = async (amount, donorInfo = {}) => {
    try {
      setLoading(true);
      setError(null);

      const device = await StorageService.getDevice();
      const campaign = await StorageService.getCampaign();

      if (!device || !campaign) {
        throw new Error('Device not paired or no active campaign');
      }

      const donationData = {
        campaign_id: campaign.id,
        device_id: device.id,
        amount: parseFloat(amount),
        currency: 'EUR',
        payment_method: 'sumup',
        payment_status: 'pending',
        ...donorInfo,
      };

      // Check network status
      const netState = await NetInfo.fetch();

      if (netState.isConnected) {
        // Online: send to server
        const response = await ApiService.createDonation(donationData);
        return response.data;
      } else {
        // Offline: queue for later
        await StorageService.addToOfflineQueue(donationData);
        
        // Return mock donation object
        return {
          id: `offline_${Date.now()}`,
          ...donationData,
          receipt_number: `OFFLINE-${Date.now()}`,
        };
      }
    } catch (err) {
      console.error('Error creating donation:', err);
      setError(err.message);
      
      // If API fails, queue offline
      try {
        await StorageService.addToOfflineQueue({
          campaign_id: campaign.id,
          device_id: device.id,
          amount: parseFloat(amount),
          currency: 'EUR',
          payment_method: 'sumup',
          payment_status: 'pending',
          ...donorInfo,
        });
      } catch (queueError) {
        console.error('Failed to queue offline donation:', queueError);
      }
      
      throw err;
    } finally {
      setLoading(false);
    }
  };

  return {
    createDonation,
    loading,
    error,
  };
};
```

#### `src/hooks/useOfflineQueue.js`
```javascript
import { useState, useEffect } from 'react';
import NetInfo from '@react-native-community/netinfo';
import ApiService from '../services/ApiService';
import StorageService from '../services/StorageService';

export const useOfflineQueue = () => {
  const [queueSize, setQueueSize] = useState(0);
  const [syncing, setSyncing] = useState(false);

  const checkQueue = async () => {
    const queue = await StorageService.getOfflineQueue();
    setQueueSize(queue.length);
    return queue.length;
  };

  const syncQueue = async () => {
    try {
      setSyncing(true);
      const queue = await StorageService.getOfflineQueue();

      if (queue.length === 0) {
        return { success: true, synced: 0, failed: 0 };
      }

      let syncedCount = 0;
      const failedDonations = [];

      for (const donation of queue) {
        try {
          await ApiService.createDonation(donation);
          syncedCount++;
        } catch (error) {
          console.error('Failed to sync donation:', error);
          failedDonations.push(donation);
        }
      }

      // Update queue with only failed donations
      await StorageService.saveOfflineQueue(failedDonations);
      setQueueSize(failedDonations.length);

      return {
        success: true,
        synced: syncedCount,
        failed: failedDonations.length,
      };
    } catch (error) {
      console.error('Error syncing queue:', error);
      return { success: false, error: error.message };
    } finally {
      setSyncing(false);
    }
  };

  useEffect(() => {
    // Check queue on mount
    checkQueue();

    // Set up network listener for auto-sync
    const unsubscribe = NetInfo.addEventListener((state) => {
      if (state.isConnected && !syncing) {
        syncQueue();
      }
    });

    return () => unsubscribe();
  }, []);

  return {
    queueSize,
    syncing,
    syncQueue,
    checkQueue,
  };
};
```

### 9.6 App Navigation Setup

#### `src/navigation/AppNavigator.js`
```javascript
import React, { useEffect, useState } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import StorageService from '../services/StorageService';

// Screens
import PairingScreen from '../screens/PairingScreen';
import CampaignScreen from '../screens/CampaignScreen';
import AmountSelectionScreen from '../screens/AmountSelectionScreen';
import PaymentProcessingScreen from '../screens/PaymentProcessingScreen';
import ThankYouScreen from '../screens/ThankYouScreen';
import AdminScreen from '../screens/AdminScreen';

const Stack = createStackNavigator();

const AppNavigator = () => {
  const [initialRoute, setInitialRoute] = useState(null);

  useEffect(() => {
    checkPairingStatus();
  }, []);

  const checkPairingStatus = async () => {
    try {
      const token = await StorageService.getToken();
      const device = await StorageService.getDevice();

      if (token && device) {
        setInitialRoute('Campaign');
      } else {
        setInitialRoute('Pairing');
      }
    } catch (error) {
      console.error('Error checking pairing status:', error);
      setInitialRoute('Pairing');
    }
  };

  if (!initialRoute) {
    return null; // Or a splash screen
  }

  return (
    <NavigationContainer>
      <Stack.Navigator
        initialRouteName={initialRoute}
        screenOptions={{
          headerShown: false,
          gestureEnabled: false, // Disable swipe gestures for kiosk mode
        }}
      >
        <Stack.Screen name="Pairing" component={PairingScreen} />
        <Stack.Screen name="Campaign" component={CampaignScreen} />
        <Stack.Screen name="AmountSelection" component={AmountSelectionScreen} />
        <Stack.Screen name="PaymentProcessing" component={PaymentProcessingScreen} />
        <Stack.Screen name="ThankYou" component={ThankYouScreen} />
        <Stack.Screen name="Admin" component={AdminScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default AppNavigator;
```

#### `App.js`
```javascript
import React, { useEffect } from 'react';
import { StatusBar } from 'react-native';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import AppNavigator from './src/navigation/AppNavigator';
import SumUpService from './src/services/SumUpService';

const App = () => {
  useEffect(() => {
    // Initialize SumUp SDK
    initializeSumUp();
  }, []);

  const initializeSumUp = async () => {
    try {
      await SumUpService.initialize();
      console.log('SumUp SDK initialized');
    } catch (error) {
      console.error('SumUp initialization failed:', error);
    }
  };

  return (
    <GestureHandlerRootView style={{ flex: 1 }}>
      <StatusBar hidden />
      <AppNavigator />
    </GestureHandlerRootView>
  );
};

export default App;
```

---

## Phase 10: Web Dashboard Updates (Laravel Blade Views)

### 10.1 Update Organization Layout with Billing Link

**File:** `resources/views/components/organization-layout.blade.php`

Add billing link to navigation:

```blade
<!-- After Reports link -->
<a href="{{ route('organization.billing.index') }}"
   class="block px-4 py-2 text-sm {{ request()->routeIs('organization.billing.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
    {{ __('app.nav.billing') }}
</a>
```

### 10.2 Billing Overview Page

**File:** `resources/views/organization/billing/index.blade.php`

```blade
<x-organization-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Billing & Subscription
                    </h2>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('organization.billing.plans') }}"
                       class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Change Plan
                    </a>
                </div>
            </div>

            <!-- Current Plan Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Current Plan
                    </h3>

                    @if($subscription)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Plan Info -->
                            <div>
                                <p class="text-sm text-gray-500">Plan Name</p>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ ucfirst($subscription->tier->name ?? 'Free') }}
                                </p>
                            </div>

                            <!-- Price -->
                            <div>
                                <p class="text-sm text-gray-500">Monthly Fee</p>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    €{{ number_format($subscription->tier->monthly_fee ?? 0, 2) }}
                                </p>
                            </div>

                            <!-- Status -->
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Billing Period -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Current Period</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $subscription->current_period_start->format('M d, Y') }} -
                                        {{ $subscription->current_period_end->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Next Billing Date</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $subscription->next_billing_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No active subscription</p>
                        <a href="{{ route('organization.billing.plans') }}"
                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                            Choose a Plan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Usage & Tier Progress -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Fundraising Progress (Last 12 Months)
                    </h3>

                    @php
                        $total12m = $organization->donations()
                            ->where('payment_status', 'completed')
                            ->where('created_at', '>=', now()->subYear())
                            ->sum('amount');
                        
                        $currentTier = $subscription?->tier;
                        $nextTier = \App\Models\SubscriptionTier::where('min_amount', '>', $currentTier?->min_amount ?? 0)
                            ->orderBy('min_amount', 'asc')
                            ->first();
                        
                        $progress = 0;
                        if ($nextTier) {
                            $progress = min(100, ($total12m / $nextTier->min_amount) * 100);
                        }
                    @endphp

                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-2xl font-bold text-gray-900">
                                €{{ number_format($total12m, 2) }}
                            </span>
                            @if($nextTier)
                                <span class="text-sm text-gray-500">
                                    Next tier at €{{ number_format($nextTier->min_amount, 0) }}
                                </span>
                            @endif
                        </div>

                        <!-- Progress Bar -->
                        @if($nextTier)
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full transition-all duration-500"
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                €{{ number_format($nextTier->min_amount - $total12m, 2) }} to next tier
                                ({{ $nextTier->name }})
                            </p>
                        @else
                            <p class="text-sm text-green-600 font-medium">
                                You're on the highest tier!
                            </p>
                        @endif
                    </div>

                    <!-- Tier Change Notification -->
                    @php
                        $pendingChange = $organization->tierChangeLogs()
                            ->where('status', 'pending')
                            ->where('scheduled_date', '>', now())
                            ->latest()
                            ->first();
                    @endphp

                    @if($pendingChange)
                        <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Tier change scheduled:</strong>
                                        You will be moved to <strong>{{ $pendingChange->toTier->name }}</strong>
                                        on {{ $pendingChange->scheduled_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Usage Limits
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Campaigns -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Campaigns</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $usedCampaigns }} / {{ $maxCampaigns ?? '∞' }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $campaignProgress = $maxCampaigns ? min(100, ($usedCampaigns / $maxCampaigns) * 100) : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full"
                                     style="width: {{ $campaignProgress }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Devices -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Devices</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $usedDevices }} / {{ $maxDevices ?? '∞' }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $deviceProgress = $maxDevices ? min(100, ($usedDevices / $maxDevices) * 100) : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full"
                                     style="width: {{ $deviceProgress }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-organization-layout>
```

### 10.3 Plan Selection Page

**File:** `resources/views/organization/billing/plans.blade.php`

```blade
<x-organization-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Choose Your Plan
                </h2>
                <p class="text-gray-600">
                    Your tier is automatically determined by your fundraising performance over the last 12 months
                </p>
            </div>

            <!-- Pricing Tiers -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(\App\Models\SubscriptionTier::orderBy('min_amount')->get() as $tier)
                    @php
                        $isCurrentTier = $currentPlan === $tier->id;
                        $features = json_decode($tier->features, true) ?? [];
                    @endphp

                    <div class="bg-white rounded-lg shadow-lg overflow-hidden
                        {{ $isCurrentTier ? 'ring-2 ring-blue-600' : '' }}">
                        
                        @if($isCurrentTier)
                            <div class="bg-blue-600 text-white text-center py-2 text-sm font-semibold">
                                Current Plan
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Tier Name -->
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                {{ $tier->name }}
                            </h3>

                            <!-- Fundraising Range -->
                            <p class="text-sm text-gray-600 mb-4">
                                @if($tier->max_amount)
                                    €{{ number_format($tier->min_amount, 0) }} - €{{ number_format($tier->max_amount, 0) }}
                                @else
                                    €{{ number_format($tier->min_amount, 0) }}+
                                @endif
                                <span class="block">12-month fundraising</span>
                            </p>

                            <!-- Price -->
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-gray-900">
                                    €{{ number_format($tier->monthly_fee, 0) }}
                                </span>
                                <span class="text-gray-600">/month</span>
                            </div>

                            <!-- Features -->
                            <ul class="space-y-3 mb-6">
                                @foreach($features as $feature)
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            @if($isCurrentTier)
                                <button disabled
                                        class="w-full bg-gray-300 text-gray-600 py-2 px-4 rounded-md font-semibold cursor-not-allowed">
                                    Current Plan
                                </button>
                            @else
                                <p class="text-sm text-gray-500 text-center">
                                    Tier changes automatically based on your fundraising
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('organization.billing.index') }}"
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Back to Billing Overview
                </a>
            </div>
        </div>
    </div>
</x-organization-layout>
```

### 10.4 Add Billing Navigation Translation

**File:** `lang/en/app.php`

Add to navigation array:

```php
'nav' => [
    // ... existing entries
    'billing' => 'Billing & Subscription',
],
```

---

## Phase 11: Multilingual Support (German Translations)

### 11.1 German App Translations

**File:** `lang/de/app.php`

```php
<?php

return [
    // Navigation
    'nav' => [
        'dashboard'  => 'Dashboard',
        'profile'    => 'Profil',
        'campaigns'  => 'Kampagnen',
        'devices'    => 'Geräte',
        'donations'  => 'Spenden',
        'reports'    => 'Berichte',
        'billing'    => 'Abrechnung & Abonnement',
        'sign_out'   => 'Abmelden',
        'account_settings' => 'Kontoeinstellungen',
        'org_profile' => 'Organisationsprofil',
    ],

    // Dashboard
    'dashboard' => [
        'title'             => 'Dashboard',
        'welcome'           => 'Willkommen zurück',
        'total_donations'   => 'Gesamtspenden',
        'this_month'        => 'Diesen Monat',
        'active_campaigns'  => 'Aktive Kampagnen',
        'online_devices'    => 'Online-Geräte',
        'recent_donations'  => 'Letzte Spenden',
        'view_all'          => 'Alle anzeigen',
        'no_donations_yet'  => 'Noch keine Spenden',
    ],

    // Campaigns
    'campaigns' => [
        'title'         => 'Kampagnen',
        'create'        => 'Kampagne erstellen',
        'edit'          => 'Kampagne bearbeiten',
        'name'          => 'Kampagnenname',
        'status'        => 'Status',
        'type'          => 'Typ',
        'start_date'    => 'Startdatum',
        'end_date'      => 'Enddatum',
        'active'        => 'Aktiv',
        'inactive'      => 'Inaktiv',
        'scheduled'     => 'Geplant',
        'one_time'      => 'Einmalig',
        'recurring'     => 'Wiederkehrend',
        'no_campaigns'  => 'Noch keine Kampagnen. Erstellen Sie Ihre erste Kampagne!',
        'duplicate'     => 'Duplizieren',
        'delete'        => 'Löschen',
        'preview'       => 'Vorschau',
    ],

    // Devices
    'devices' => [
        'title'         => 'Geräte',
        'add'           => 'Gerät hinzufügen',
        'name'          => 'Gerätename',
        'status'        => 'Status',
        'online'        => 'Online',
        'offline'       => 'Offline',
        'active'        => 'Aktiv',
        'inactive'      => 'Inaktiv',
        'pairing_code'  => 'Kopplungscode',
        'last_seen'     => 'Zuletzt gesehen',
        'no_devices'    => 'Noch keine Geräte registriert.',
        'pair_new'      => 'Neues Gerät koppeln',
    ],

    // Donations
    'donations' => [
        'title'         => 'Spenden',
        'amount'        => 'Betrag',
        'date'          => 'Datum',
        'campaign'      => 'Kampagne',
        'device'        => 'Gerät',
        'status'        => 'Status',
        'receipt'       => 'Quittung #',
        'transaction'   => 'Transaktions-ID',
        'success'       => 'Erfolg',
        'pending'       => 'Ausstehend',
        'failed'        => 'Fehlgeschlagen',
        'no_donations'  => 'Keine Spenden gefunden.',
    ],

    // Reports
    'reports' => [
        'title'         => 'Berichte & Analysen',
        'subtitle'      => 'Spendeneinblicke und Leistungsmetriken',
        'export_csv'    => 'CSV exportieren',
        'today'         => 'Heute',
        'this_week'     => 'Diese Woche',
        'this_month'    => 'Diesen Monat',
        'all_time'      => 'Alle Zeit',
        'top_campaign'  => 'Top-Kampagne',
        'top_device'    => 'Top-Gerät',
        'active_devices' => 'Aktive Geräte',
        'trend_chart'   => 'Spendentrend (letzte 30 Tage)',
        'campaign_perf' => 'Kampagnenleistung',
        'hourly_chart'  => 'Stündliche Aktivität (letzte 30 Tage)',
        'dow_chart'     => 'Wochentagsanalyse (letzte 90 Tage)',
        'device_perf'   => 'Geräteleistung',
        'filter'        => 'Spenden filtern',
        'apply_filters' => 'Filter anwenden',
        'reset'         => 'Zurücksetzen',
        'date_range'    => 'Datumsbereich',
        'yesterday'     => 'Gestern',
        'last_7_days'   => 'Letzte 7 Tage',
        'last_30_days'  => 'Letzte 30 Tage',
        'last_month'    => 'Letzter Monat',
        'custom_range'  => 'Benutzerdefinierter Bereich',
        'no_data'       => 'Keine Spenden gefunden',
        'adjust_filters' => 'Versuchen Sie, Ihre Filter anzupassen',
    ],

    // Kiosk
    'kiosk' => [
        'tap_to_donate'   => 'Tippen Sie auf einen Betrag, um fortzufahren',
        'enter_amount'    => 'Betrag eingeben',
        'custom_amount'   => 'Benutzerdefinierten Betrag eingeben',
        'cancel'          => 'Abbrechen',
        'continue'        => 'Weiter',
        'clear'           => 'Löschen',
        'thank_you'       => 'Vielen Dank!',
        'donation_received' => 'Ihre Spende wurde empfangen.',
        'processing'      => 'Verarbeitung...',
    ],

    // Common
    'common' => [
        'save'     => 'Speichern',
        'cancel'   => 'Abbrechen',
        'delete'   => 'Löschen',
        'edit'     => 'Bearbeiten',
        'view'     => 'Ansehen',
        'back'     => 'Zurück',
        'search'   => 'Suchen',
        'loading'  => 'Laden...',
        'yes'      => 'Ja',
        'no'       => 'Nein',
        'actions'  => 'Aktionen',
        'status'   => 'Status',
        'name'     => 'Name',
        'email'    => 'E-Mail',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
    ],
];
```

---

## Phase 12: Testing & Quality Assurance

### 12.1 Unit Testing

#### Laravel Tests (PHPUnit)

**File:** `tests/Unit/SubscriptionTierServiceTest.php`

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\SubscriptionTier;
use App\Models\Subscription;
use App\Models\Donation;
use App\Services\SubscriptionTierService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTierServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SubscriptionTierService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SubscriptionTierService();
        $this->seed(\Database\Seeders\SubscriptionTierSeeder::class);
    }

    /** @test */
    public function it_calculates_12_month_total_correctly()
    {
        $org = Organization::factory()->create();
        
        // Create donations
        Donation::factory()->create(['organization_id' => $org->id, 'amount' => 500, 'created_at' => now()->subMonths(6)]);
        Donation::factory()->create(['organization_id' => $org->id, 'amount' => 300, 'created_at' => now()->subMonths(3)]);
        Donation::factory()->create(['organization_id' => $org->id, 'amount' => 200, 'created_at' => now()->subMonths(14)]); // Outside range
        
        $total = $this->service->calculate12MonthTotal($org);
        
        $this->assertEquals(800, $total);
    }

    /** @test */
    public function it_determines_correct_tier_for_amount()
    {
        $tier = $this->service->getTierForAmount(5000);
        
        $this->assertEquals('Tier 2', $tier->name);
    }

    /** @test */
    public function it_schedules_tier_change_correctly()
    {
        $org = Organization::factory()->create();
        $tier1 = SubscriptionTier::where('name', 'Free')->first();
        $tier2 = SubscriptionTier::where('name', 'Tier 2')->first();
        
        $subscription = Subscription::factory()->create([
            'organization_id' => $org->id,
            'tier_id' => $tier1->id,
            'next_billing_date' => now()->addMonth(),
        ]);
        
        $log = $this->service->scheduleTierChange($org, $tier1->id, $tier2->id, 5000, 'donation');
        
        $this->assertNotNull($log);
        $this->assertEquals('pending', $log->status);
        $this->assertEquals($tier2->id, $log->to_tier_id);
    }
}
```

### 12.2 Feature Testing

**File:** `tests/Feature/BillingTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\SubscriptionTier;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function organization_admin_can_view_billing_page()
    {
        $user = User::factory()->create(['role' => 'org_admin']);
        $org = Organization::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get(route('organization.billing.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Billing & Subscription');
    }

    /** @test */
    public function it_displays_current_tier_correctly()
    {
        $this->seed(\Database\Seeders\SubscriptionTierSeeder::class);
        
        $user = User::factory()->create(['role' => 'org_admin']);
        $org = Organization::factory()->create(['user_id' => $user->id]);
        $tier = SubscriptionTier::where('name', 'Tier 2')->first();
        
        Subscription::factory()->create([
            'organization_id' => $org->id,
            'tier_id' => $tier->id,
            'status' => 'active',
        ]);
        
        $response = $this->actingAs($user)->get(route('organization.billing.index'));
        
        $response->assertSee('Tier 2');
        $response->assertSee('€' . number_format($tier->monthly_fee, 2));
    }
}
```

### 12.3 React Native Testing (Jest)

**File:** `__tests__/services/ApiService.test.js`

```javascript
import ApiService from '../../src/services/ApiService';
import StorageService from '../../src/services/StorageService';

jest.mock('../../src/services/StorageService');

describe('ApiService', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  test('pairDevice returns token on success', async () => {
    global.fetch = jest.fn(() =>
      Promise.resolve({
        ok: true,
        json: () => Promise.resolve({
          success: true,
          data: {
            token: 'test-token-123',
            device: { id: 1, name: 'Test Device' }
          }
        }),
      })
    );

    const result = await ApiService.pairDevice('DEV123', '1234');

    expect(result.token).toBe('test-token-123');
    expect(StorageService.setToken).toHaveBeenCalledWith('test-token-123');
  });

  test('createDonation sends correct payload', async () => {
    StorageService.getToken.mockResolvedValue('test-token');

    global.fetch = jest.fn(() =>
      Promise.resolve({
        ok: true,
        json: () => Promise.resolve({
          success: true,
          data: { id: 1, amount: 50, receipt_number: 'RCP-001' }
        }),
      })
    );

    const result = await ApiService.createDonation({
      campaign_id: 1,
      amount: 50,
      currency: 'EUR',
    });

    expect(result.data.amount).toBe(50);
  });
});
```

### 12.4 End-to-End Testing Checklist

**Manual Testing Checklist:**

- [ ] **Pairing Flow**
  - [ ] Device can pair with valid device_id and PIN
  - [ ] Invalid PIN shows error
  - [ ] Token is stored and persists after app restart

- [ ] **Campaign Display**
  - [ ] Active campaign loads correctly
  - [ ] Branding (colors, logo, images) displays properly
  - [ ] Campaign updates reflect in kiosk within 5 minutes

- [ ] **Donation Flow**
  - [ ] Preset amounts are selectable
  - [ ] Custom amount input works correctly
  - [ ] SumUp payment terminal pairs successfully
  - [ ] Payment processes and shows success screen
  - [ ] Receipt number is generated

- [ ] **Offline Mode**
  - [ ] Donations are queued when offline
  - [ ] Queue syncs automatically when connection restored
  - [ ] No duplicate donations after sync

- [ ] **Tier Changes**
  - [ ] Tier changes after sufficient donations
  - [ ] Notification email sent 7 days before change
  - [ ] Change applies on billing date
  - [ ] Stripe subscription updates correctly

- [ ] **Web Dashboard**
  - [ ] Billing page shows correct tier
  - [ ] Fundraising progress bar accurate
  - [ ] Usage limits display correctly
  - [ ] Language switcher works (EN/DE)

---

## Phase 13: Deployment

### 13.1 Laravel Deployment (Production Server)

#### Environment Setup

**File:** `.env.production`

```env
APP_NAME="Dayaa"
APP_ENV=production
APP_KEY=<generated>
APP_DEBUG=false
APP_URL=https://dayaa.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dayaa_production
DB_USERNAME=dayaa_user
DB_PASSWORD=<secure-password>

STRIPE_KEY=<production-key>
STRIPE_SECRET=<production-secret>
STRIPE_WEBHOOK_SECRET=<webhook-secret>

SUMUP_APP_ID=<production-app-id>
SUMUP_APP_SECRET=<production-secret>
SUMUP_MERCHANT_CODE=<merchant-code>

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=<email>
MAIL_PASSWORD=<password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dayaa.com
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_DRIVER=file
```

#### Deployment Commands

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Run migrations
php artisan migrate --force

# 4. Seed subscription tiers (first deploy only)
php artisan db:seed --class=SubscriptionTierSeeder

# 5. Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue workers
php artisan queue:restart

# 7. Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Supervisor Configuration (Queue Workers)

**File:** `/etc/supervisor/conf.d/dayaa-worker.conf`

```ini
[program:dayaa-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dayaa/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasflags=TERM
numprocs=4
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/dayaa/storage/logs/worker.log
stopwaitsecs=3600
```

#### Scheduled Tasks (Cron)

```bash
* * * * * cd /var/www/dayaa && php artisan schedule:run >> /dev/null 2>&1
```

### 13.2 React Native Deployment

#### iOS Deployment (App Store)

**Steps:**

1. **Update Version Numbers**
   - Edit `ios/DayaaKiosk/Info.plist`
   - Increment `CFBundleShortVersionString` and `CFBundleVersion`

2. **Build for Release**
   ```bash
   cd ios
   pod install
   xcodebuild -workspace DayaaKiosk.xcworkspace \
              -scheme DayaaKiosk \
              -configuration Release \
              -archivePath $PWD/build/DayaaKiosk.xcarchive \
              archive
   ```

3. **Submit to App Store**
   - Open Xcode
   - Product → Archive
   - Distribute App → App Store Connect
   - Upload and submit for review

4. **Configure Kiosk Mode**
   - Enable "Guided Access" in iOS Settings
   - Triple-click home button to activate
   - Lock to Dayaa Kiosk app

#### Android Deployment (Google Play)

**Steps:**

1. **Update Version Numbers**
   - Edit `android/app/build.gradle`
   - Increment `versionCode` and `versionName`

2. **Generate Signed APK**
   ```bash
   cd android
   ./gradlew bundleRelease
   ```

3. **Upload to Google Play Console**
   - Navigate to Google Play Console
   - Upload AAB file from `android/app/build/outputs/bundle/release/`
   - Fill out store listing and submit for review

4. **Configure Kiosk Mode**
   - Enable "Lock Task Mode" in app
   - Set Dayaa Kiosk as Device Owner using ADB:
   ```bash
   adb shell dpm set-device-owner com.dayaakiosk/.DeviceAdminReceiver
   ```

#### Over-the-Air Updates (CodePush)

**Setup:**

```bash
# Install CodePush CLI
npm install -g code-push-cli

# Register app
code-push app add DayaaKiosk-iOS ios react-native
code-push app add DayaaKiosk-Android android react-native

# Deploy updates
code-push release-react DayaaKiosk-iOS ios
code-push release-react DayaaKiosk-Android android
```

### 13.3 Production Monitoring

#### Laravel Monitoring (Laravel Telescope)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

#### Error Tracking (Sentry)

**File:** `config/sentry.php`

```php
'dsn' => env('SENTRY_LARAVEL_DSN'),
'traces_sample_rate' => 0.2,
'profiles_sample_rate' => 0.2,
```

#### React Native Monitoring (Sentry)

```javascript
// src/services/SentryService.js
import * as Sentry from '@sentry/react-native';

Sentry.init({
  dsn: 'https://xxx@sentry.io/xxx',
  environment: 'production',
  tracesSampleRate: 0.2,
});

export default Sentry;
```

---

## Appendices

### Appendix A: Project Timeline Summary

| Phase | Description | Estimated Duration |
|-------|-------------|-------------------|
| 1 | Database Architecture Updates | 2-3 days |
| 2 | Models & Relationships | 1-2 days |
| 3 | Stripe Integration | 3-4 days |
| 4 | Subscription Tier Service | 2-3 days |
| 5 | Real-time Tier Checking | 1-2 days |
| 6 | Scheduled Jobs | 1-2 days |
| 7 | Notifications | 1-2 days |
| 8 | Laravel API Endpoints | 3-4 days |
| 9 | React Native Mobile App | 10-14 days |
| 10 | Web Dashboard Updates | 2-3 days |
| 11 | Multilingual Support | 1-2 days |
| 12 | Testing & QA | 5-7 days |
| 13 | Deployment | 2-3 days |
| **Total** | **End-to-End Implementation** | **35-50 days** |

### Appendix B: Entity-Relationship Diagram

```
┌─────────────────┐         ┌──────────────────┐
│  organizations  │────┬────│  subscriptions   │
│                 │    │    │                  │
│ - id            │    │    │ - id             │
│ - name          │    │    │ - organization_id│
│ - status        │    │    │ - tier_id        │
│ - tax_id        │    │    │ - stripe_sub_id  │
│ - bank_account  │    │    │ - status         │
└─────────────────┘    │    │ - price          │
        │              │    └──────────────────┘
        │              │             │
        ├──────────────┼─────────────┘
        │              │
        │              │    ┌──────────────────────┐
        │              └────│ subscription_tiers   │
        │                   │                      │
        │                   │ - id                 │
        │                   │ - name               │
        │                   │ - min_amount         │
        │                   │ - max_amount         │
        │                   │ - monthly_fee        │
        │                   │ - stripe_price_id    │
        │                   │ - features (JSON)    │
        │                   └──────────────────────┘
        │
        ├───────────────┐
        │               │
        ▼               ▼
┌─────────────┐   ┌──────────────┐
│  campaigns  │   │   devices    │
│             │   │              │
│ - id        │   │ - id         │
│ - org_id    │   │ - org_id     │
│ - name      │   │ - device_id  │
│ - status    │   │ - name       │
│ - design    │   │ - status     │
│ - amounts   │   │ - last_seen  │
└─────────────┘   └──────────────┘
        │                 │
        └────────┬────────┘
                 │
                 ▼
         ┌──────────────┐
         │  donations   │
         │              │
         │ - id         │
         │ - org_id     │
         │ - campaign_id│
         │ - device_id  │
         │ - amount     │
         │ - status     │
         │ - sumup_id   │
         │ - receipt_no │
         └──────────────┘
```

### Appendix C: API Endpoint Reference

#### Device Endpoints

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/devices/pair` | Pair device with PIN | None |
| POST | `/api/devices/heartbeat` | Send device heartbeat | Token |
| POST | `/api/devices/unpair` | Unpair device | Token |
| GET | `/api/devices/campaign` | Get active campaign | Token |

#### Donation Endpoints

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/donations` | Create donation | Token |
| GET | `/api/donations/{id}` | Get donation details | Token |
| POST | `/api/donations/{id}/complete` | Mark donation complete | Token |
| POST | `/api/donations/{id}/fail` | Mark donation failed | Token |

#### Webhook Endpoints

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/stripe/webhook` | Stripe webhook handler | Signature |
| POST | `/api/sumup/webhook` | SumUp webhook handler | None |

### Appendix D: SumUp SDK Integration Notes

**iOS Implementation:**

```swift
// ios/DayaaKiosk/SumUpModule.m
#import <React/RCTBridgeModule.h>

@interface RCT_EXTERN_MODULE(SumUpModule, NSObject)

RCT_EXTERN_METHOD(initialize:(NSString *)apiKey
                  resolver:(RCTPromiseResolveBlock)resolve
                  rejecter:(RCTPromiseRejectBlock)reject)

RCT_EXTERN_METHOD(processPayment:(double)amount
                  currency:(NSString *)currency
                  resolver:(RCTPromiseResolveBlock)resolve
                  rejecter:(RCTPromiseRejectBlock)reject)

@end
```

**Android Implementation:**

```java
// android/app/src/main/java/com/dayaakiosk/SumUpModule.java
package com.dayaakiosk;

import com.sumup.merchant.api.SumUpAPI;
import com.sumup.merchant.api.SumUpPayment;

public class SumUpModule extends ReactContextBaseJavaModule {
    @ReactMethod
    public void processPayment(double amount, String currency, Promise promise) {
        SumUpPayment payment = SumUpPayment.builder()
            .total(new BigDecimal(amount))
            .currency(SumUpPayment.Currency.EUR)
            .build();
            
        SumUpAPI.checkout(getCurrentActivity(), payment, REQUEST_CODE);
    }
}
```

---

## End of Implementation Plan V2.0

**Total Lines:** ~5,500+  
**Phases:** 13  
**Technologies:** Laravel 12, React Native, Stripe, SumUp, MySQL  
**Deployment Targets:** Web (Laravel), iOS (App Store), Android (Google Play)

**Next Steps:**
1. Review this implementation plan with your development team
2. Set up development environment (Laravel + React Native)
3. Begin Phase 1 (Database Architecture Updates)
4. Follow phases sequentially, testing after each phase
5. Deploy to production after Phase 12 (Testing & QA)

**Support Resources:**
- Laravel Documentation: https://laravel.com/docs
- React Native Documentation: https://reactnative.dev
- Stripe API Docs: https://stripe.com/docs/api
- SumUp SDK Docs: https://developer.sumup.com

---
