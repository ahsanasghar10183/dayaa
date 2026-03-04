<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'campaign_type',
        'reference_code',
        'design_settings',
        'content_settings',
        'amount_settings',
        'status',
        'start_date',
        'end_date',
        'language',
        'currency',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'design_settings' => 'array',
            'content_settings' => 'array',
            'amount_settings' => 'array',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Check if campaign is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if campaign is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Get the organization that owns this campaign
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get all donations for this campaign
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get all devices assigned to this campaign
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'campaign_device')
            ->withTimestamps();
    }

    /**
     * Get total donations amount for this campaign
     */
    public function getTotalDonationsAttribute(): float
    {
        return $this->donations()->where('payment_status', 'completed')->sum('amount');
    }

    /**
     * Get total donations count for this campaign
     */
    public function getDonationsCountAttribute(): int
    {
        return $this->donations()->where('payment_status', 'completed')->count();
    }
}
