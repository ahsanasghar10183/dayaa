<x-mail::message>
# Subscription Renewal Reminder

Hello **{{ $orgName }}**,

This is a friendly reminder that your {{ $appName }} subscription is coming up for renewal.

<x-mail::panel>
**Plan:** {{ $planName }}
**Renewal Date:** {{ $renewalDate }}
**Amount:** €{{ number_format($amount, 2) }}
</x-mail::panel>

Your subscription will be automatically renewed on the date above. No action is required if you wish to continue using {{ $appName }}.

## Need to Make Changes?

If you'd like to upgrade, downgrade, or cancel your subscription before the renewal date, you can do so from your account settings.

<x-mail::button :url="$billingUrl" color="primary">
Manage Subscription
</x-mail::button>

If you have any questions about your subscription or billing, please contact our support team.

Thank you for continuing to use {{ $appName }}!

Best regards,
**The {{ $appName }} Team**
</x-mail::message>
