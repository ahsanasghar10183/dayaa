<x-mail::message>
# Great News! Your Organization Is Approved

Hello,

We are pleased to inform you that **{{ $orgName }}** has been reviewed and **approved** on {{ $appName }}!

Your organization account is now active. You can start creating campaigns, pairing devices, and accepting donations right away.

<x-mail::button :url="$dashboardUrl" color="success">
Go to Your Dashboard
</x-mail::button>

## What's Next?

- **Create your first campaign** using the Campaign Wizard
- **Pair your donation devices** in the Devices section
- **Track your donations** in the Reports section

If you have any questions or need assistance getting started, feel free to contact our support team.

Thank you for choosing {{ $appName }} to power your fundraising efforts!

Warm regards,
**The {{ $appName }} Team**
</x-mail::message>
