<x-mail::message>
# New Contact Inquiry

You have received a new message through the Dayaa website.

## Customer Details

- **Name:** {{ $contactData['name'] }}
- **Email:** {{ $contactData['email'] }}
- **Phone:** {{ $contactData['phone'] ?? 'Not provided' }}
- **Subject:** {{ $contactData['subject'] ?? 'General Inquiry' }}

## Message

{{ $contactData['message'] }}

---

**Reply directly to this email to respond to the customer.**

Thank you,<br>
**Dayaa Team**
</x-mail::message>
