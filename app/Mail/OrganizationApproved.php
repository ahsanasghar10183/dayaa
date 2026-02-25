<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Organization $organization
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Organization Has Been Approved - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.organization-approved',
            with: [
                'orgName'      => $this->organization->name,
                'dashboardUrl' => url('/organization/dashboard'),
                'appName'      => config('app.name', 'Dayaa'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
