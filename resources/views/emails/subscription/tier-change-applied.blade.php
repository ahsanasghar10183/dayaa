@component('mail::message')
# Subscription Tier Upgraded Successfully

Hello {{ $organization->name }},

Your subscription tier has been successfully upgraded! 🎉

@component('mail::panel')
**Tier Upgrade Completed**

- Previous Tier: **{{ $fromTier->name }}** (€{{ number_format($fromTier->monthly_fee, 2) }}/month)
- Current Tier: **{{ $toTier->name }}** (€{{ number_format($toTier->monthly_fee, 2) }}/month)
- Effective Date: **{{ $appliedDate }}**
@endcomponent

## Your New Benefits

Congratulations! You now have access to all **{{ $toTier->name }}** features:

@foreach($toTier->features as $feature)
- ✅ {{ $feature }}
@endforeach

## Billing Information

- **New Monthly Fee:** €{{ number_format($toTier->monthly_fee, 2) }}
- **Next Billing Date:** {{ $nextBillingDate }}
- **12-Month Fundraising Total:** €{{ number_format($donationTotal, 2) }}

Your new tier pricing will be reflected on your next invoice. The upgrade was prorated based on your current billing cycle.

@component('mail::button', ['url' => $billingUrl])
View Subscription Details
@endcomponent

## Keep Growing!

Your tier is automatically adjusted based on your rolling 12-month fundraising total. As you continue to grow your donations, you may qualify for even higher tiers with more features.

### Progress to Next Tier
@if($nextTier)
You need **€{{ number_format($nextTier->min_amount - $donationTotal, 2) }}** more in 12-month fundraising to reach **{{ $nextTier->name }}** (€{{ number_format($nextTier->monthly_fee, 2) }}/month).
@else
You're at our highest tier! Keep up the excellent fundraising work. 🌟
@endif

## Questions?

If you have any questions about your new tier or subscription, our support team is here to help.

Thank you for your continued trust in {{ config('app.name') }}!

Best regards,<br>
The {{ config('app.name') }} Team

---

<small>This tier change was applied automatically based on your fundraising performance. View your complete billing history in your dashboard.</small>
@endcomponent
