<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionTier extends Model
{
    protected $fillable = [
        'name',
        'min_amount',
        'max_amount',
        'monthly_fee',
        'stripe_price_id',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Always return features as an array, regardless of how it was stored
     * (valid JSON, newline-separated string, comma-separated, or null).
     */
    public function getFeaturesAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        $separator = str_contains($value, "\n") ? "\n" : ',';
        return array_values(array_filter(array_map('trim', explode($separator, $value))));
    }

    public function setFeaturesAttribute($value): void
    {
        $this->attributes['features'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Check if an amount falls within this tier's range
     */
    public function isInRange(float $amount): bool
    {
        if ($amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount === null) {
            return true; // Unlimited upper range
        }

        return $amount <= $this->max_amount;
    }

    /**
     * Get all subscriptions using this tier
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tier_id');
    }

    /**
     * Get all tier change logs where this tier is the target
     */
    public function tierChangesTo(): HasMany
    {
        return $this->hasMany(TierChangeLog::class, 'to_tier_id');
    }

    /**
     * Get all tier change logs where this tier is the origin
     */
    public function tierChangesFrom(): HasMany
    {
        return $this->hasMany(TierChangeLog::class, 'from_tier_id');
    }

    /**
     * Scope to get only active tiers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order tiers by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('min_amount');
    }
}
