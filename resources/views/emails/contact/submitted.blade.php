<x-mail::message>
# New Contact Form Submission

You have received a new contact form submission from the Dayaa website.

## Contact Information

- **Name:** {{ $contactData['name'] }}
- **Email:** {{ $contactData['email'] }}
- **Phone:** {{ $contactData['phone'] ?? 'Not provided' }}
@if(isset($contactData['company']))
- **Company:** {{ $contactData['company'] }}
@endif

## Subject

{{ $contactData['subject'] ?? 'General Inquiry' }}

## Message

{{ $contactData['message'] }}

---

**Submitted on:** {{ now()->format('M d, Y \a\t g:i A') }}

You can reply directly to this email to respond to {{ $contactData['name'] }}.

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>
