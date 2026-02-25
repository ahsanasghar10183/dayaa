<?php

namespace App\Mail;

use App\Models\TierChangeLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TierChangeScheduled extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public TierChangeLog $tierChangeLog;

    /**
     * Create a new message instance.
     */
    public function __construct(TierChangeLog $tierChangeLog)
    {
        $this->tierChangeLog = $tierChangeLog;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $isUpgrade = $this->tierChangeLog->toTier->monthly_fee > ($this->tierChangeLog->fromTier->monthly_fee ?? 0);

        return new Envelope(
            subject: $isUpgrade ? 'Your Subscription is Being Upgraded!' : 'Subscription Tier Change Scheduled',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tier-change-scheduled',
            with: [
                'organization' => $this->tierChangeLog->organization,
                'fromTier' => $this->tierChangeLog->fromTier,
                'toTier' => $this->tierChangeLog->toTier,
                'scheduledDate' => $this->tierChangeLog->scheduled_date,
                'donationTotal' => $this->tierChangeLog->donation_total_12m,
                'isUpgrade' => $this->tierChangeLog->toTier->monthly_fee > ($this->tierChangeLog->fromTier->monthly_fee ?? 0),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
