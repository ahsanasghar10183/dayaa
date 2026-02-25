<x-mail::message>
# Welcome to {{ $appName }}!

Hello **{{ $userName }}**,

Thank you for registering with {{ $appName }}! We're excited to have you on board.

{{ $appName }} is a powerful donation management platform designed to help organizations collect and manage donations seamlessly through digital kiosk devices.

## Getting Started

Here's how to get up and running quickly:

1. **Complete your organization profile** - Add your organization's details
2. **Submit for approval** - Our team will review your application
3. **Create campaigns** - Set up your donation campaigns using our wizard
4. **Pair devices** - Connect your donation kiosk devices
5. **Start collecting** - Accept donations and track your analytics

<x-mail::button :url="$dashboardUrl" color="success">
Go to Dashboard
</x-mail::button>

If you have any questions, please don't hesitate to reach out.

Welcome aboard!

**The {{ $appName }} Team**
</x-mail::message>
