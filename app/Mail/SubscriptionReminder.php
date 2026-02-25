<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Organization $organization,
        public string $planName,
        public string $renewalDate,
        public float $amount
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Renewal Reminder - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscription-reminder',
            with: [
                'orgName'      => $this->organization->name,
                'planName'     => $this->planName,
                'renewalDate'  => $this->renewalDate,
                'amount'       => $this->amount,
                'billingUrl'   => url('/organization/dashboard'),
                'appName'      => config('app.name', 'Dayaa'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
