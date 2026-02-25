<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierChangeLog extends Model
{
    protected $fillable = [
        'organization_id',
        'from_tier_id',
        'to_tier_id',
        'triggered_by',
        'status',
        'donation_total_12m',
        'scheduled_date',
        'applied_at',
        'notes',
    ];

    protected $casts = [
        'donation_total_12m' => 'decimal:2',
        'scheduled_date' => 'datetime',
        'applied_at' => 'datetime',
    ];

    /**
     * Get the organization that owns this log
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the tier we're changing from
     */
    public function fromTier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'from_tier_id');
    }

    /**
     * Get the tier we're changing to
     */
    public function toTier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'to_tier_id');
    }

    /**
     * Mark this tier change as applied
     */
    public function markAsApplied(): bool
    {
        return $this->update([
            'status' => 'applied',
            'applied_at' => now(),
        ]);
    }

    /**
     * Cancel this tier change
     */
    public function cancel(string $reason = null): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    /**
     * Check if this change is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Scope for pending changes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for changes scheduled for a specific date
     */
    public function scopeScheduledFor($query, $date)
    {
        return $query->whereDate('scheduled_date', '<=', $date);
    }
}
