<x-mail::message>
# Device Offline Alert

Hello,

We noticed that one of your donation kiosk devices has gone offline and may need your attention.

<x-mail::panel>
**Device:** {{ $deviceName }}
**Last Seen:** {{ $lastSeenAt }}
</x-mail::panel>

## Possible Causes

- The device may have lost its internet connection
- The device may have been powered off
- A network or configuration issue may have occurred

## What To Do

1. Check that the device is powered on and connected to the internet
2. Verify the Wi-Fi or ethernet connection on the device
3. Restart the device if needed
4. If the issue persists, re-pair the device in your dashboard

<x-mail::button :url="$devicesUrl" color="primary">
View Devices
</x-mail::button>

If you believe this is an error or need technical assistance, please contact our support team.

Best regards,
**The {{ $appName }} Team**
</x-mail::message>
