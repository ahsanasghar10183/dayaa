<x-mail::message>
# Application Status Update

Hello,

Thank you for submitting your organization application to {{ $appName }}.

After reviewing your application for **{{ $orgName }}**, we were unable to approve it at this time.

@if($reason)
**Reason:**
{{ $reason }}
@endif

## What Can You Do?

If you believe this decision was made in error, or if you have additional information to provide, please contact our support team. We'd be happy to review your application again.

<x-mail::button :url="$supportUrl" color="primary">
Contact Support
</x-mail::button>

We appreciate your interest in {{ $appName }} and encourage you to reach out if you have any questions.

Best regards,
**The {{ $appName }} Team**
</x-mail::message>
