# Subscription & Tier Auto-Upgrade — How It Works, and How to Test It

Read-only investigation, nothing in the codebase was changed to produce this file.
Verified against the actual code as of commit `e61d1b6` (2026-09-12), superseding the
commit message on `9fff37c` ("Fixed the subscriptionm issue, everythung is working"),
which is inaccurate for the tier auto-upgrade path — see Part 2.

---

## Part 1 — How the subscription system works end to end

**Files involved:**
- `app/Http/Controllers/Organization/SubscriptionController.php` — plan selection, checkout, billing page
- `app/Services/StripeService.php` — thin wrapper around the Stripe SDK
- `app/Services/SubscriptionTierService.php` — tier detection + scheduling + applying
- `app/Observers/DonationObserver.php` — triggers tier checks on every completed donation
- `app/Jobs/ApplyPendingTierChanges.php` — daily cron job that's supposed to apply due tier changes
- `app/Models/{Subscription,SubscriptionTier,TierChangeLog}.php`
- `database/seeders/SubscriptionTierSeeder.php` — seeds 9 tiers, "Basic" (Tier 1) + 8 tiers all literally named "Premium"

**Flow:**

1. An organization admin goes to `organization.billing.create` → `SubscriptionController::create()`. Every org always starts on **Tier 1 ("Basic")** — this is hardcoded (`SubscriptionTier::active()->ordered()->first()`), not user-selectable.
2. Submitting the form hits `SubscriptionController::store()`, which:
   - Creates (or reuses) a Stripe Customer
   - Attaches the payment method
   - Creates a Stripe Subscription with a 30-day trial, using Tier 1's `stripe_price_id`
   - Writes a local `Subscription` row (`tier_id`, `stripe_subscription_id`, `stripe_customer_id`, period dates, etc.)
3. From then on, **every completed donation** fires `DonationObserver::created()/updated()`, which calls `SubscriptionTierService::checkAndScheduleTierChange($organization)`:
   - Sums `Donation.amount` for that org over the trailing 12 months (`payment_status = 'completed'` only)
   - Finds the tier whose `[min_amount, max_amount)` range contains that total (`determineTierByAmount()`)
   - If it differs from the org's current tier, calls `scheduleTierChange()`, which creates a `TierChangeLog` row with `status = 'pending'` and is *supposed to* set `scheduled_for` to the next Stripe billing date, and sends a "tier change scheduled" email
4. A daily scheduled job (`Schedule::job(new ApplyPendingTierChanges)->daily()->at('00:00')` in `routes/console.php`) is *supposed to* pick up any `TierChangeLog` rows whose `scheduled_for` date has arrived and actually apply them — updating the Stripe subscription's price and the local `subscriptions.tier_id`, then sending a "tier applied" confirmation email.

This is a reasonable design. Steps 1–3 work correctly. **Step 4 never fires — see Part 2.**

---

## Part 2 — The tier auto-upgrade bug (confirmed still present)

**Root cause: a column-name mismatch between the model's `$fillable` array and the code that writes to it.**

`app/Models/TierChangeLog.php`:
```php
protected $fillable = [
    'organization_id', 'from_tier_id', 'to_tier_id', 'triggered_by',
    'status', 'donation_total_12m', 'scheduled_date', 'applied_at', 'notes',
];
```

`app/Services/SubscriptionTierService.php::scheduleTierChange()` writes:
```php
TierChangeLog::create([
    ...
    'triggered_at'      => now(),            // not in $fillable → silently dropped
    'scheduled_for'     => $nextBillingDate, // not in $fillable → silently dropped
    'notification_sent' => false,            // not in $fillable → silently dropped
]);
```

Laravel's mass assignment doesn't throw an error for a field that isn't fillable — it just quietly doesn't write it. So every `TierChangeLog` row ends up with `scheduled_for = NULL`.

Then `app/Jobs/ApplyPendingTierChanges.php` (the daily cron job) does:
```php
TierChangeLog::where('status', 'pending')
    ->whereDate('scheduled_for', '<=', $today)
    ->get();
```
Comparing a `NULL` column with `<=` never matches in SQL, so this query **always returns zero rows**. The job runs every night, logs "Processing pending tier changes, count: 0", and does nothing.

**Net effect:** the tier change is correctly detected and logged as "pending" (you'll see it in the `tier_change_logs` table with the right `from_tier_id`/`to_tier_id`), but it is **never actually applied**. `subscriptions.tier_id` never changes, no matter how much time passes or how many more donations come in. The org is stuck on whatever tier it started on (Tier 1), forever, from the system's point of view — even though behind the scenes it keeps calculating the correct new tier and re-logging it (or not re-logging, since after the first pending log, `currentTierId` from `organization->subscription?->tier_id` is still the old tier, so it may create a *new* pending log for every single subsequent donation once the total moves into a different band).

**The manual escape hatch is also broken.** `php artisan tiers:apply-pending` (`app/Console/Commands/ApplyPendingTierChanges.php`) calls:
```php
$this->tierService->processPendingTierChanges();
```
`SubscriptionTierService` has no method by this name (only `applyTierChange(TierChangeLog $log)`, which takes one specific log, not a batch). Running this command throws `Error: Call to undefined method App\Services\SubscriptionTierService::processPendingTierChanges()` every time.

**Secondary bug, boundary gap in tier bands.** `determineTierByAmount()`:
```php
SubscriptionTier::where('min_amount', '<=', $amount)
    ->where(fn($q) => $q->whereNull('max_amount')->orWhere('max_amount', '>', $amount))
    ->orderBy('min_amount')->first();
```
uses strict `>` against `max_amount`. The seeded bands are contiguous and closed at both ends (Tier 1: €0–€83, Tier 2: €84–€667, …). If a 12-month total lands on *exactly* a boundary value like €83.00 or €667.00, **no tier satisfies both conditions** — the method returns `null`, and `checkAndScheduleTierChange()` just does nothing (no log, no error, nothing). Unlikely to hit exactly on a real donation total, but worth knowing if you test with round numbers.

**Cosmetic issue that will confuse you while testing:** 7 of the 9 seeded tiers are all literally named `"Premium"` (only Tier 1 is "Basic"). The plans page (`organization/billing/plans.blade.php`) and the tier-progress widget both display `$tier->name`, so you'll see multiple identically-labeled "Premium" cards/labels and have to distinguish them by the price/range text instead. This doesn't affect the upgrade logic, just makes it harder to visually confirm which tier an org is actually on.

---

## Part 3 — How to test the parts that DO work (Stripe subscription checkout)

Requires Stripe **test-mode** keys in `.env` (`STRIPE_KEY`/`STRIPE_SECRET` starting with `pk_test_`/`sk_test_`) and real Stripe test Price IDs on your seeded tiers (the seeder currently has placeholder `stripe_price_id` values — `store()` will fail with "Subscription configuration error" until these point at real test-mode Prices in your Stripe dashboard).

1. Log in as an organization admin whose org has no active subscription.
2. Go to Billing → Subscribe (`organization.billing.create`). Confirm it always shows Tier 1 as the starting plan.
3. Submit the form using a Stripe test card, e.g. `4242 4242 4242 4242`, any future expiry, any CVC.
4. Confirm:
   - Redirect to dashboard with a success message
   - In Stripe's test dashboard, a new Customer + Subscription (trialing) exists
   - Locally, a row exists in `subscriptions` with `tier_id` = Tier 1's id, `status = 'active'`, `stripe_subscription_id` populated
5. Try declined-card test numbers (e.g. `4000 0000 0000 0002`) and confirm the `CardException` branch shows a friendly error and rolls back (no orphaned local `Subscription` row — check `DB::rollBack()` actually leaves no row).
6. Try submitting again while already subscribed — should redirect with "You already have an active subscription."

This exercises the one part of the subscription flow that's live and correct today.

---

## Part 4 — How to test the tier-detection logic directly (bypassing the broken job)

Since the auto-apply job is non-functional, you can't test the "tier actually changes on the dashboard" behavior end-to-end without a manual fix. But you *can* verify the detection math is correct using `php artisan tinker`, which is useful to confirm Part 2's diagnosis and to know exactly what will start working the moment the `$fillable`/job bug is fixed:

```php
php artisan tinker

$org = App\Models\Organization::find(1); // pick a real org with a subscription
$service = app(App\Services\SubscriptionTierService::class);

// 1. Check current 12-month total
$service->calculate12MonthDonations($org);

// 2. Check what tier that total maps to
$tier = $service->determineTierByAmount($service->calculate12MonthDonations($org));
$tier?->name . ' (min: ' . $tier?->min_amount . ', max: ' . $tier?->max_amount . ')';

// 3. Create a completed donation that pushes the org over a threshold
// (Tier 1 -> Tier 2 boundary is €84)
App\Models\Donation::create([
    'organization_id' => $org->id,
    'campaign_id' => $org->campaigns()->first()->id, // needs a real campaign
    'amount' => 100,
    'payment_status' => 'completed',
    // fill any other required fields your Donation model needs
]);

// 4. Confirm a TierChangeLog row was created
App\Models\TierChangeLog::where('organization_id', $org->id)->latest()->first();
// -> you'll see status=pending, correct from/to tier ids, but scheduled_for is NULL

// 5. Confirm the subscription tier did NOT change (this is the bug)
$org->fresh()->subscription->tier_id; // still the old tier

// 6. Confirm the daily job finds nothing to do
(new App\Jobs\ApplyPendingTierChanges)->handle(app(App\Services\SubscriptionTierService::class));
// check storage/logs/laravel.log for "Processing pending tier changes ... count: 0"

// 7. Confirm the broken manual command fatal-errors
php artisan tiers:apply-pending
// -> Error: Call to undefined method ...processPendingTierChanges()
```

You can also manually apply one specific pending log to confirm the *apply* logic itself (as opposed to the scheduling/cron trigger) works correctly in isolation:

```php
$log = App\Models\TierChangeLog::where('status', 'pending')->latest()->first();
app(App\Services\SubscriptionTierService::class)->applyTierChange($log);
$log->fresh()->status; // should now be 'applied'
$org->fresh()->subscription->tier_id; // should now be the new tier
```

If step 7 (calling `applyTierChange()` directly) works but nothing ever gets there on its own — that confirms the break is specifically in the scheduling/column-mismatch/job-filter chain (Part 2), not in the tier-change-application logic itself.

---

## Summary checklist

| Piece | Status | Test method |
|---|---|---|
| Org always starts on Tier 1 | Works | UI walkthrough |
| Stripe checkout (customer/payment method/subscription creation) | Works (needs real test Price IDs) | UI walkthrough with Stripe test cards |
| Donation completion → tier re-calculation | Works | Tinker: `calculate12MonthDonations` / `determineTierByAmount` |
| Tier change gets logged as pending | Works (log row created, but `scheduled_for` always NULL) | Tinker / inspect `tier_change_logs` table |
| Tier change auto-applies on schedule | **Broken** | Nightly job always processes 0 rows |
| Manual `tiers:apply-pending` command | **Broken** (fatal error) | `php artisan tiers:apply-pending` |
| Applying one pending change directly via `applyTierChange()` | Works in isolation | Tinker, as shown above |
| Exact-boundary donation totals (e.g. €83.00, €667.00) | **Broken** (no tier matches, silently) | Tinker with a donation total exactly on a boundary |
