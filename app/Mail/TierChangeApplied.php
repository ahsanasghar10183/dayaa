<?php

namespace App\Mail;

use App\Models\TierChangeLog;
use App\Services\SubscriptionTierService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TierChangeApplied extends Mailable implements ShouldQueue
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
            subject: $isUpgrade ? 'Subscription Upgraded Successfully!' : 'Subscription Tier Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $subscription = $this->tierChangeLog->organization->subscription;
        $tierService = app(SubscriptionTierService::class);

        // Get next tier for progress display
        $nextTier = \App\Models\SubscriptionTier::where('min_amount', '>', $this->tierChangeLog->donation_total_12m)
            ->orderBy('min_amount', 'asc')
            ->first();

        return new Content(
            markdown: 'emails.subscription.tier-change-applied',
            with: [
                'organization' => $this->tierChangeLog->organization,
                'fromTier' => $this->tierChangeLog->fromTier,
                'toTier' => $this->tierChangeLog->toTier,
                'appliedDate' => $this->tierChangeLog->updated_at->format('F j, Y'),
                'nextBillingDate' => $subscription?->current_period_end?->format('F j, Y') ?? 'your next billing date',
                'donationTotal' => $this->tierChangeLog->donation_total_12m,
                'nextTier' => $nextTier,
                'billingUrl' => route('organization.billing.index'),
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
