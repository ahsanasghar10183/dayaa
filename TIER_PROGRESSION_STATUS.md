# Tier Progression Status — Your Existing Organizations

Read-only note. Nothing in the codebase was changed. Full technical detail already
lives in `SUBSCRIPTION_TIER_TESTING_GUIDE.md` (uncommitted, in repo root) — this file
is the short answer to "will Tier 1 → Tier 2 → … happen automatically for the orgs I
already set up and paid for via Stripe?"

## Your current state

- Multiple organizations already exist.
- Each has already gone through Stripe checkout (subscription created, payment method
  attached) — that part of the flow works correctly.
- Every one of those organizations currently has **€0 in donations**.

## What that means right now

- Tier assignment is based on the **trailing 12-month sum of completed donations**
  (`SubscriptionTierService::calculate12MonthDonations()`), not on signup date or
  anything else.
- With €0 in donations, `determineTierByAmount(0)` always resolves to **Tier 1
  ("Basic", €0–€83)** — which is also the tier every org starts on at checkout.
- So: correct, expected state — nothing should move, and nothing needs to.

## What's supposed to happen once a donation pushes a tier over the line

1. A donation is marked `payment_status = completed`.
2. `DonationObserver` fires → `SubscriptionTierService::checkAndScheduleTierChange()`
   recalculates the 12-month total and finds the matching tier band.
3. If the org's total now falls in a higher band (e.g. crosses €84 → Tier 2), it
   creates a `TierChangeLog` row (`status = pending`) and is supposed to schedule the
   change for the next Stripe billing date, then email the org.
4. A nightly job (`routes/console.php`, midnight) is supposed to pick up any due
   pending changes and actually flip `subscriptions.tier_id` + update the Stripe
   subscription price.

## The catch — step 4 currently never runs

Confirmed in the code (see `SUBSCRIPTION_TIER_TESTING_GUIDE.md` Part 2 for the exact
lines):

- The scheduling code writes a field (`scheduled_for`) that **isn't in
  `TierChangeLog`'s `$fillable`** and doesn't match the real DB column
  (`scheduled_date`). It's silently dropped, so every pending row ends up with a NULL
  schedule date.
- The nightly job filters on `scheduled_for <= today`, which never matches anything
  (NULL never satisfies `<=`), so it always processes 0 rows.
- The manual fallback, `php artisan tiers:apply-pending`, calls a service method
  (`processPendingTierChanges()`) that doesn't exist — it fatal-errors every time.
- `php artisan tiers:recalculate` has the same problem, calling
  `recalculateAllOrganizationTiers()`, which also doesn't exist.

**Net effect:** the system correctly *detects* that a tier change is due and logs it
as pending, but nothing ever *applies* it — the org stays on Tier 1 forever from the
system's point of view, no matter how much it raises, until someone fixes the
column-name mismatch (and the two broken artisan commands).

## Bottom line for your orgs today

- Zero donations → correctly on Tier 1. No action needed, nothing to test yet.
- Once real donations start coming in and one crosses a tier boundary, you'll see it
  logged correctly in `tier_change_logs`, but the org's subscription will **not**
  actually move to the new Stripe price/tier on its own — that part is broken and
  needs a fix before automatic progression will work end-to-end.
