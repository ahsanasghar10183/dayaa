@component('mail::message')
# Subscription Tier Upgrade Scheduled

Hello {{ $organization->name }},

Great news! Based on your fundraising success, your subscription tier is being upgraded.

@component('mail::panel')
**Your subscription will be upgraded on {{ $scheduledDate }}**

- Current Tier: **{{ $fromTier->name }}** (€{{ number_format($fromTier->monthly_fee, 2) }}/month)
- New Tier: **{{ $toTier->name }}** (€{{ number_format($toTier->monthly_fee, 2) }}/month)
- 12-Month Fundraising Total: **€{{ number_format($donationTotal, 2) }}**
@endcomponent

## What This Means

Your organization has reached **€{{ number_format($donationTotal, 2) }}** in total donations over the last 12 months, qualifying you for the **{{ $toTier->name }}** tier.

### New Tier Benefits
@foreach($toTier->features as $feature)
- {{ $feature }}
@endforeach

## Billing Changes

- The new tier fee of **€{{ number_format($toTier->monthly_fee, 2) }}/month** will be charged on your next billing date: **{{ $nextBillingDate }}**
- This change is automatic - no action required from you
- You'll receive a confirmation email once the upgrade is applied

@component('mail::button', ['url' => $billingUrl])
View Billing Details
@endcomponent

## Questions?

If you have any questions about this tier change or your subscription, please don't hesitate to contact our support team.

Thank you for using {{ config('app.name') }} to make a difference!

Best regards,<br>
The {{ config('app.name') }} Team

---

<small>This is an automated notification. The tier change will occur automatically on {{ $scheduledDate }}.</small>
@endcomponent
