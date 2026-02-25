<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Organization $organization,
        public ?string $reason = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Organization Application Update - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.organization-rejected',
            with: [
                'orgName'    => $this->organization->name,
                'reason'     => $this->reason,
                'supportUrl' => url('/'),
                'appName'    => config('app.name', 'Dayaa'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
