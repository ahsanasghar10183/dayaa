<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'organization_id',
        'tier_id',
        'plan',
        'price',
        'status',
        'current_period_start',
        'current_period_end',
        'next_billing_date',
        'stripe_subscription_id',
        'stripe_customer_id',
        'stripe_payment_method_id',
        'cancelled_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'next_billing_date' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if subscription is premium
     */
    public function isPremium(): bool
    {
        return $this->plan === 'premium';
    }

    /**
     * Check if subscription is basic
     */
    public function isBasic(): bool
    {
        return $this->plan === 'basic';
    }

    /**
     * Get the organization that owns this subscription
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the subscription tier
     */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(SubscriptionTier::class, 'tier_id');
    }
}
